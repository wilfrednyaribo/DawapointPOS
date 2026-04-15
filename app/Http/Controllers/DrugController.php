<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Carbon\Carbon; // Ensure this is at the top of your Controller file
use App\Models\Category;
use App\Models\Supplier;
use App\Models\DrugBatch;
use App\Models\InventoryMovement;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Required for import slug generation
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DrugController extends Controller
{
    public function index(Request $request)
    {
        $query = Drug::with(['category', 'batches' => function($q) {
            $q->where('quantity_remaining', '>', 0)
              ->where('expiry_date', '>', now())
              ->orderBy('expiry_date');
        }]);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('generic_name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
                  ->orWhere('barcode', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->stock_filter === 'low') {
            $query->whereColumn('quantity_in_stock', '<=', 'reorder_level');
        }

        if ($request->stock_filter === 'out') {
            $query->where('quantity_in_stock', '<=', 0);
        }

        if ($request->prescription === 'required') {
            $query->where('requires_prescription', true);
        }

        $drugs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $categories = Category::orderBy('name')->get();

        return view('drugs.index', compact('drugs', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('drugs.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:drugs,sku',
            'barcode' => 'nullable|string|unique:drugs,barcode',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'dosage_form' => 'nullable|string|max:100',
            'strength' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'integer|min:0',
            'reorder_level' => 'integer|min:0',
            'requires_prescription' => 'boolean',
            'is_controlled' => 'boolean',
            'storage_condition' => 'nullable|string|max:100',
        ]);

        $drug = Drug::create($validated);

        if ($request->quantity_in_stock > 0) {
            DrugBatch::create([
                'drug_id' => $drug->id,
                'batch_number' => 'INIT-' . $drug->sku,
                'expiry_date' => $request->expiry_date ?? now()->addYears(2),
                'quantity_received' => $request->quantity_in_stock,
                'quantity_remaining' => $request->quantity_in_stock,
                'purchase_price' => $drug->cost_price,
                'received_date' => now(),
            ]);

            InventoryMovement::create([
                'drug_id' => $drug->id,
                'type' => 'purchase',
                'quantity' => $request->quantity_in_stock,
                'quantity_before' => 0,
                'quantity_after' => $request->quantity_in_stock,
                'user_id' => auth()->id(),
                'notes' => 'Initial stock',
            ]);
        }

        return redirect()->route('drugs.index')->with('success', 'Drug added successfully.');
    }

    /**
     * NEW: Import Drugs from CSV/Excel
     */
public function import(Request $request)
{
    // 1. FIX: Enable auto detection of line endings
    ini_set('auto_detect_line_endings', 1);

    // 2. Validate
    $request->validate([
        'drug_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('drug_file');
    $path = $file->getRealPath();
    $successCount = 0;
    $failCount = 0;

    // 3. Native PHP CSV Reader
    if (($handle = fopen($path, 'r')) !== FALSE) {
        $header = fgetcsv($handle, 1000, ','); // Skip headers row
        
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            
            // Skip if row is empty
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                DB::beginTransaction();

                // --- CORRECT MAPPING BASED ON YOUR IMAGE ---
                
                $name = $row[0] ?? null;           // Col A
                $genericName = $row[1] ?? null;    // Col B
                $sku = $row[2] ?? null;            // Col C
                
                if (empty($name) || empty($sku)) {
                    $failCount++;
                    DB::rollBack();
                    continue;
                }

                $categoryName = $row[3] ?? '';     // Col D
                $supplierName = $row[4] ?? '';     // Col E
                $costPrice = $row[5] ?? 0;         // Col F
                $sellingPrice = $row[6] ?? 0;      // Col G
                
                // Col H (Index 7) is empty in your file, so we skip it.
                
                // Stock is in Col I (Index 8)
                $quantity = isset($row[8]) ? (int)$row[8] : 0; 
                
                // --- SMART DATE PARSING ---
                
                // Helper function to parse mixed dates (Handles both 1/21/2025 and 30/12/2030)
                $parseDate = function($dateString) {
                    if (empty($dateString)) return null;
                    
                    $dateString = trim($dateString);
                    
                    try {
                        // Try standard parse (Handles Y-m-d and m/d/Y often)
                        return Carbon::parse($dateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        try {
                            // Try d/m/Y (e.g., 30/12/2030) which caused your 1970 error
                            return Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                        } catch (\Exception $e2) {
                            return null;
                        }
                    }
                };

                // Manufacturing Date is Col J (Index 9)
                $manufacturingDate = $parseDate($row[9] ?? null);

                // Expiry Date is Col K (Index 10)
                $expiryDate = $parseDate($row[10] ?? null);

                // --- LOOKUP LOGIC ---
                
                $categoryId = Category::where('name', trim($categoryName))->value('id');
                if (!$categoryId) {
                    $categoryId = 1; // Default
                }

                $supplierId = Supplier::where('name', trim($supplierName))->value('id');

                // --- SAVE TO DATABASE ---

                $drug = Drug::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $name,
                        'generic_name' => $genericName,
                        'category_id' => $categoryId,
                        'supplier_id' => $supplierId,
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice,
                        'quantity_in_stock' => $quantity,
                        'reorder_level' => 10,
                        'slug' => Str::slug($name . '-' . $sku),
                    ]
                );

                // Create or Update Batch
                // We use updateOrCreate to fix the batch if the drug already exists
                DrugBatch::updateOrCreate(
                    [
                        'drug_id' => $drug->id,
                        'batch_number' => 'INIT-' . $drug->sku
                    ],
                    [
                        'manufacturing_date' => $manufacturingDate,
                        'expiry_date' => $expiryDate ?? now()->addYears(2),
                        'quantity_received' => $quantity,
                        'quantity_remaining' => $quantity,
                        'purchase_price' => $drug->cost_price,
                        'received_date' => now(),
                    ]
                );

                DB::commit();
                $successCount++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $failCount++;
            }
        }
        fclose($handle);
    }

    return redirect()->route('drugs.index')
        ->with('success', "Import complete. Added/Updated: {$successCount}, Skipped: {$failCount}.");
}

    public function show(Drug $drug)
    {
        $drug->load(['category', 'supplier', 'batches', 'inventoryMovements.user']);
        return view('drugs.show', compact('drug'));
    }

    public function edit(Drug $drug)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('drugs.edit', compact('drug', 'categories', 'suppliers'));
    }

    public function update(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'sku' => [
                'required', 
                'string', 
                Rule::unique('drugs')->ignore($drug->id),
            ],
            'barcode' => [
                'nullable', 
                'string', 
                Rule::unique('drugs')->ignore($drug->id),
            ],
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'dosage_form' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'storage_condition' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $drug, $validated) {
            $validated['requires_prescription'] = $request->has('requires_prescription');
            $validated['is_controlled'] = $request->has('is_controlled');

            if (empty($validated['supplier_id'])) {
                $validated['supplier_id'] = null;
            }

            $drug->update($validated);
        });

        return redirect()->route('drugs.index')
            ->with('success', 'Drug "' . $drug->name . '" updated successfully.');
    }

    public function destroy(Drug $drug)
    {
        $drug->delete();
        return redirect()->route('drugs.index')->with('success', 'Drug deleted successfully.');
    }



public function downloadTemplate()
{
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=drug_import_template.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    // UPDATED Columns
    $columns = [
        'Name', 'Generic Name', 'SKU', 
        'Category Name', 'Supplier Name', // CHANGED from ID to Name
        'Cost Price', 'Selling Price', 'Stock Qty', 
        'Manufacturing Date', 'Expiry Date'
    ];

    $callback = function() use ($columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        
        // Sample Row with Names
        fputcsv($file, [
            'Paracetamol', 'Acetaminophen', 'PARA-001', 
            'Tablets', 'Health Suppliers Ltd', // Use real names here
            '50.00', '120.00', '100', 
            '2024-01-01', '2026-12-31'
        ]);
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    /**
     * UPDATED: Add Stock Method
     * 1. Creates/Updates Batch.
     * 2. Updates Drug Stock.
     * 3. Records Inventory Movement.
     * 4. NEW: Creates Purchase Record for Dashboard.
     */
    public function addStock(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'batch_number' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date|after:today',
            'purchase_price' => 'required|numeric|min:0',
            'manufacturing_date' => 'nullable|date|before:expiry_date',
        ]);

        // Auto-generate batch number if missing
        $batchNumber = $request->batch_number;
        if (!$batchNumber) {
            $batchNumber = 'BTN-' . str_pad(DrugBatch::max('id') + 1, 5, '0', STR_PAD_LEFT);
        }

        $quantityBefore = $drug->quantity_in_stock;

        DB::beginTransaction();
        try {
            // 1. Batch Handling
            $batch = DrugBatch::where('drug_id', $drug->id)
                ->where('batch_number', $batchNumber)
                ->first();

            if ($batch) {
                $batch->update([
                    'quantity_received' => $batch->quantity_received + $validated['quantity'],
                    'quantity_remaining' => $batch->quantity_remaining + $validated['quantity'],
                ]);
            } else {
                $batch = DrugBatch::create([
                    'drug_id' => $drug->id,
                    'batch_number' => $batchNumber,
                    'expiry_date' => $validated['expiry_date'],
                    'manufacturing_date' => $validated['manufacturing_date'] ?? null,
                    'quantity_received' => $validated['quantity'],
                    'quantity_remaining' => $validated['quantity'],
                    'purchase_price' => $validated['purchase_price'],
                    'received_date' => now(),
                ]);
            }

            // 2. Update Drug Stock
            $drug->increment('quantity_in_stock', $validated['quantity']);

            // 3. Update Supplier
            if ($request->filled('supplier_id')) {
                $drug->update(['supplier_id' => $request->supplier_id]);
            }

            // 4. Inventory Movement
            InventoryMovement::create([
                'drug_id' => $drug->id,
                'drug_batch_id' => $batch->id,
                'type' => 'purchase',
                'quantity' => $validated['quantity'],
                'quantity_before' => $quantityBefore,
                'quantity_after' => $drug->quantity_in_stock,
                'user_id' => auth()->id(),
                'notes' => 'Stock added - Batch: ' . $batchNumber,
            ]);

            // 5. Create Purchase Record
            $totalAmount = $validated['quantity'] * $validated['purchase_price'];
            $supplierId = $validated['supplier_id'] ?? $drug->supplier_id;

            $purchase = Purchase::create([
                'supplier_id' => $supplierId,
                'invoice_number' => 'INV-' . now()->format('Ymd-His'),
                'purchase_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'user_id' => auth()->id(),
            ]);

            // 6. Create Purchase Item (CORRECTED)
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'drug_id' => $drug->id,
                'batch_number' => $batchNumber,       // Required by DB
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['purchase_price'], // CORRECTED: Changed from unit_price to unit_cost
                'total' => $totalAmount,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Stock added successfully. New quantity: ' . $drug->quantity_in_stock);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding stock: ' . $e->getMessage());
        }
    }
}