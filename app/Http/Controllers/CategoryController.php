<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = auth()->user();

    // Base query with count of drugs
    $query = Category::withCount('drugs')->latest();

    // Multi-tenancy logic
    if ($user->pharmacy_id === null) {
        // Super Admin sees all
        $categories = $query->get();
    } else {
        // Pharmacy User sees only theirs
        $categories = $query->where('pharmacy_id', $user->pharmacy_id)->get();
    }

    return view('categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $user = auth()->user();
    $pharmacyId = $user->pharmacy_id; // Will be null for Super Admin

    // 1. Generate the base slug
    $slug = Str::slug($validated['name']);

    // 2. Check if this slug already exists FOR THIS PHARMACY
    $count = Category::where('pharmacy_id', $pharmacyId)
                      ->where('slug', $slug)
                      ->count();

    // 3. If it exists, append a suffix to make it unique (e.g., antibiotics-1)
    if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
    }

    // 4. Create the category
    Category::create([
        'name' => $validated['name'],
        'slug' => $slug,
        'description' => $validated['description'],
        'pharmacy_id' => $pharmacyId,
    ]);

    return redirect()->route('categories.index')
                     ->with('success', 'Category created successfully.');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();

        // LOGIC: Super Admin can edit ANY category.
        // Pharmacy Users can only edit their own.
        if ($user->pharmacy_id === null) {
            $category = Category::findOrFail($id);
        } else {
            $category = Category::where('pharmacy_id', $user->pharmacy_id)
                                ->findOrFail($id);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();

        // Access Control
        if ($user->pharmacy_id === null) {
            $category = Category::findOrFail($id);
        } else {
            $category = Category::where('pharmacy_id', $user->pharmacy_id)
                                ->findOrFail($id);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();

        // LOGIC: Super Admin can delete ANY category.
        // Pharmacy Users can only delete their own.
        if ($user->pharmacy_id === null) {
            $category = Category::findOrFail($id);
        } else {
            $category = Category::where('pharmacy_id', $user->pharmacy_id)
                                ->findOrFail($id);
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
}