<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\DrugBatch;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load 'supplier' and 'items' to prevent N+1 query issues
        $purchases = Purchase::with('supplier', 'items')
                    ->latest()
                    ->paginate(10); 

        // Calculate Stats
        $totalSpending = Purchase::sum('total_amount');
        
        // FIXED: Count only suppliers who actually have purchases
        $activeSuppliers = Supplier::has('purchases')->count(); 

        return view('purchases.index', compact('purchases', 'totalSpending', 'activeSuppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $drugs = Drug::orderBy('name')->get(); 
        
        return view('purchases.create', compact('suppliers', 'drugs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'required|exists:drugs,id',
            'items.*.batch_number' => 'required|string',
            'items.*.expiry_date' => 'required|date|after:today',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 2. Create the Purchase Header
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $validated['total_amount'],
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
                'status' => 'completed',
            ]);

            // 3. Process Each Item
            foreach ($validated['items'] as $item) {
                $drug = Drug::find($item['drug_id']);

                // A. Create Purchase Item Record
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'drug_id' => $item['drug_id'],
                    'batch_number' => $item['batch_number'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['cost_price'],
                    'total' => $item['quantity'] * $item['cost_price'],
                ]);

                // B. Create or Update Drug Batch
                $batch = DrugBatch::where('drug_id', $item['drug_id'])
                    ->where('batch_number', $item['batch_number'])
                    ->first();

                if ($batch) {
                    // Update existing batch
                    $batch->increment('quantity_received', $item['quantity']);
                    $batch->increment('quantity_remaining', $item['quantity']);
                } else {
                    // Create new batch
                    $batch = DrugBatch::create([
                        'drug_id' => $item['drug_id'],
                        'batch_number' => $item['batch_number'],
                        'expiry_date' => $item['expiry_date'],
                        'quantity_received' => $item['quantity'],
                        'quantity_remaining' => $item['quantity'],
                        'purchase_price' => $item['cost_price'],
                        'received_date' => $validated['purchase_date'],
                    ]);
                }

                // C. Update Drug Total Stock
                $quantityBefore = $drug->quantity_in_stock;
                $drug->increment('quantity_in_stock', $item['quantity']);

                // D. Record Inventory Movement
                InventoryMovement::create([
                    'drug_id' => $drug->id,
                    'drug_batch_id' => $batch->id,
                    'type' => 'purchase',
                    'quantity' => $item['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $drug->quantity_in_stock,
                    'user_id' => auth()->id(),
                    'notes' => 'Purchase #' . $purchase->id,
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully! Stock has been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving purchase: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.drug');
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('items');
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $purchase->update($validated);

        return redirect()->route('purchases.show', $purchase->id)
            ->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        // Optional: Add logic to reverse stock if a purchase is deleted
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }
}