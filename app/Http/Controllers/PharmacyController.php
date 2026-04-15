<?php

namespace App\Http\Controllers;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pharmacies = Pharmacy::latest()->paginate(10);
        return view('pharmacies.index', compact('pharmacies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmacies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:5|unique:pharmacies,code', // Validate Code (e.g., SHA)
        'email' => 'required|string|email|max:255|unique:pharmacies,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    // 1. Generate a URL-friendly slug from the name
    $validated['slug'] = Str::slug($validated['name']);

    // 2. Convert Code to Uppercase (SHA instead of sha)
    $validated['code'] = strtoupper($validated['code']);

    // 3. Create the Pharmacy
    Pharmacy::create($validated);

    return redirect()->route('pharmacies.index')
        ->with('success', 'Pharmacy created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Pharmacy $pharmacy)
    {
        $pharmacy->load('users');
        return view('pharmacies.show', compact('pharmacy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmacy $pharmacy)
    {
        return view('pharmacies.edit', compact('pharmacy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pharmacy $pharmacy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pharmacies,email,' . $pharmacy->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $pharmacy->update($validated);

        return redirect()->route('pharmacies.index')
            ->with('success', 'Pharmacy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pharmacy $pharmacy)
    {
        if ($pharmacy->users()->count() > 0) {
            return back()->with('error', 'Cannot delete this pharmacy. It has associated user accounts.');
        }

        if ($pharmacy->drugs()->count() > 0) {
            return back()->with('error', 'Cannot delete this pharmacy. It has associated drug inventory.');
        }

        $pharmacy->delete();

        return redirect()->route('pharmacies.index')
            ->with('success', 'Pharmacy deleted successfully.');
    }


    public function activate(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_active' => true]);
        return back()->with('success', "{$pharmacy->name} has been activated.");
    }

    public function deactivate(Pharmacy $pharmacy)
    {
        $pharmacy->update(['is_active' => false]);
        return back()->with('success', "{$pharmacy->name} has been deactivated. Users cannot login.");
    }
}
