<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    // 1. Registered Customers
    $registeredCustomers = Customer::all()->map(function($c){
        $c->type = 'Registered';
        $c->last_visit = $c->updated_at;
        return $c;
    });

    // 2. Walk-in Customers
    $walkInCustomers = Sale::whereNotNull('customer_name')
        ->where('customer_id', null)
        ->where('customer_name', '!=', '') 
        ->select('customer_name')
        ->distinct()
        ->get()
        ->map(function ($sale) {
            $customer = new Customer();
            $customer->id = -(rand(1000, 9999));
            $customer->name = $sale->customer_name;
            $customer->type = 'Walk-in';
            
            $lastSale = Sale::where('customer_name', $sale->customer_name)
                            ->orderBy('created_at', 'desc')
                            ->first();
            
            $customer->last_visit = $lastSale->created_at ?? now();
            return $customer;
        });

    // 3. Merge and Sort
    $customers = $registeredCustomers->merge($walkInCustomers);
    $customers = $customers->sortByDesc('last_visit');

    // 4. Calculate Today's Walk-ins (For the stat card)
    $todaysWalkins = Sale::whereNotNull('customer_name')
        ->whereDate('created_at', today())
        ->count();

    return view('customers.index', compact('customers', 'todaysWalkins'));
}  /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}