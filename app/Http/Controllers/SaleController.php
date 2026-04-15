<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\DrugBatch;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Multitenancy: Only get sales for the current pharmacy
        $query = Sale::with(['customer', 'user', 'items.drug'])
                     ->where('pharmacy_id', auth()->user()->pharmacy_id);

        // Filters
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhere('prescription_number', 'like', "%{$request->search}%")
                    ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Multitenancy: Only get drugs for the current pharmacy
        $drugs = Drug::where('pharmacy_id', auth()->user()->pharmacy_id)
            ->where('quantity_in_stock', '>', 0)
            ->with(['activeBatches' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->orderBy('expiry_date'); // FEFO Logic
            }])
            ->orderBy('name')
            ->get();

        $customers = Customer::where('pharmacy_id', auth()->user()->pharmacy_id)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('drugs', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
            'amount_tendered' => 'required|numeric',
        ]);

        $user = auth()->user();
        $pharmacy = $user->pharmacy;

        DB::beginTransaction();
        try {
            // Decode items
            $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;

            if (empty($items)) {
                throw new \Exception("No items in cart.");
            }

            $subtotal = 0;
            $saleItems = [];

            foreach ($items as $item) {
                // 2. Fetch Drug scoped to pharmacy
                $drugId = $item['id'] ?? $item['drug_id'];
                $drug = Drug::where('id', $drugId)
                            ->where('pharmacy_id', $pharmacy->id)
                            ->first();

                if (!$drug) {
                    throw new \Exception("Drug not found or access denied.");
                }

                // Check Stock
                if ($drug->quantity_in_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$drug->name}. Available: {$drug->quantity_in_stock}");
                }

                // Assign Batch (FEFO - First Expiry First Out)
                $batch = $drug->activeBatches->first();
                if (!$batch || $batch->quantity_remaining < $item['quantity']) {
                    throw new \Exception("No valid batch with sufficient stock for {$drug->name}");
                }

                $itemTotal = $drug->selling_price * $item['quantity'];
                $subtotal += $itemTotal;

                $saleItems[] = [
                    'drug_id' => $drug->id,
                    'drug_batch_id' => $batch->id,
                    'drug_name' => $drug->name,
                    'batch_number' => $batch->batch_number,
                    'quantity' => $item['quantity'],
                    'unit_price' => $drug->selling_price,
                    'discount' => 0,
                    'total' => $itemTotal,
                ];

                // Inventory Logic
                $quantityBefore = $drug->quantity_in_stock;
                $drug->decrement('quantity_in_stock', $item['quantity']);
                $batch->decrement('quantity_remaining', $item['quantity']);

                // Record Movement
                InventoryMovement::create([
                    'drug_id' => $drug->id,
                    'drug_batch_id' => $batch->id,
                    'pharmacy_id' => $pharmacy->id,
                    'type' => 'sale',
                    'quantity' => $item['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $drug->quantity_in_stock,
                    'user_id' => $user->id,
                    'notes' => 'Sale transaction',
                ]);
            }

            // Calculate totals
            $total = $request->total;
            $amountPaid = $request->amount_tendered;
            $changeGiven = max(0, $amountPaid - $total);
            $tax = 0;
            $discount = 0;

            // --- INVOICE GENERATION LOGIC (UPDATED) ---
            
            // 1. Get Pharmacy Code (e.g., SHA, LOP) or fallback to ID
            $pharmacyCode = $pharmacy->code ?? $pharmacy->id;
            
            // 2. Count sales for THIS pharmacy
            $saleCount = Sale::where('pharmacy_id', $pharmacy->id)->count();
            $nextNumber = $saleCount + 1;

            // 3. Generate Invoice: SHA-0001
            $invoiceNumber = $pharmacyCode . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Create Sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'pharmacy_id'    => $pharmacy->id,
                'customer_id'    => null,
                'customer_name'  => $request->customer_name ?? 'Walk-in Customer',
                'user_id'        => $user->id,
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'total'          => $total,
                'amount_paid'    => $amountPaid,
                'change_given'   => $changeGiven,
                'payment_method' => $validated['payment_method'],
                'status'         => 'completed',
            ]);

            // Create Sale Items
            foreach ($saleItems as $item) {
                $sale->items()->create($item);
            }

            DB::commit();

            return redirect()->route('sales.receipt', $sale)->with('success', 'Sale completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        // Authorization: Ensure user owns this sale
        if ($sale->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        $sale->load(['customer', 'user', 'items.drug', 'items.batch']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the receipt for the sale.
     */
    public function receipt(Sale $sale)
    {
        // Authorization: Ensure user owns this sale
        if ($sale->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        $sale->load(['customer', 'user', 'items.drug']);
        return view('sales.receipt', compact('sale'));
    }

    /**
     * Search for drugs (POS Search).
     */
    public function searchDrug(Request $request)
    {
        $query = Drug::where('pharmacy_id', auth()->user()->pharmacy_id)
            ->where('quantity_in_stock', '>', 0)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('generic_name', 'like', "%{$request->q}%")
                    ->orWhere('barcode', 'like', "%{$request->q}%")
                    ->orWhere('sku', 'like', "%{$request->q}%");
            })
            ->with(['activeBatches' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->orderBy('expiry_date');
            }])
            ->limit(10)
            ->get();

        return response()->json($query);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Authorization
        if ($sale->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403);
        }

        // Optional: Reverse stock logic if a sale is deleted
        // (Implement if you want stock to return when sale is voided)

        $sale->items()->delete();
        $sale->delete();

        return redirect()->route('sales.index')
                         ->with('success', 'Sale record deleted.');
    }
}