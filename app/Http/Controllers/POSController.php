<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function create()
    {
        // Load drugs with active batches
        $drugs = Drug::where('quantity_in_stock', '>', 0)
            ->with('activeBatches')
            ->orderBy('name')
            ->get();

        // Load customers
        $customers = Customer::orderBy('name')->get();

        // Load categories
        $categories = Category::orderBy('name')->get();

        // Pass all variables to the view
        return view('pos.create', compact('drugs', 'customers', 'categories'));
    }

    public function search(Request $request)
    {
        return response()->json(
            Drug::where('quantity_in_stock', '>', 0)
                ->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->q}%")
                      ->orWhere('barcode', 'like', "%{$request->q}%");
                })
                ->limit(10)
                ->get()
        );
    }

    public function store(Request $request)
    {
        // Redirect store logic to SaleController to avoid duplication
        return app(SaleController::class)->store($request);
    }
}