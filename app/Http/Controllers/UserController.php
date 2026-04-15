<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pharmacy; // Import Pharmacy model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
{
    $currentUser = auth()->user();

    // 1. Base Query with relationships
    $query = User::with(['roles', 'pharmacy']);

    // 2. Multi-tenancy Scope
    // If NOT Super Admin, strictly limit to their own pharmacy
    if ($currentUser->pharmacy_id !== null) {
        $query->where('pharmacy_id', $currentUser->pharmacy_id);
    }

    // 3. Execute Query
    $users = $query->latest()->get();

    // 4. Group Users by Pharmacy Name
    // This creates a collection like: ['Main Pharmacy' => [...users], 'Uptown' => [...users]]
    $pharmacies = $users->groupBy(function ($user) {
        // Handle cases where pharmacy might be null (e.g., Super Admins or Unassigned)
        return $user->pharmacy->name ?? 'System Administrators';
    });

    return view('users.index', compact('pharmacies'));
}

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Pass pharmacies list only if user is Super Admin
        $pharmacies = null;
        if (auth()->user()->isSuperAdmin()) {
            $pharmacies = Pharmacy::all();
        }

        return view('users.create', compact('pharmacies'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required|string|in:admin,cashier,pharmacist',
        'password' => 'required|string|min:8|confirmed',
    ];

    // Super Admin must select a pharmacy
    if (auth()->user()->isSuperAdmin()) {
        $rules['pharmacy_id'] = 'required|exists:pharmacies,id';
    }

    $validated = $request->validate($rules);

    // Determine pharmacy_id
    $pharmacyId = auth()->user()->isSuperAdmin() 
        ? $request->pharmacy_id 
        : auth()->user()->pharmacy_id;

    // Create user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'], // keep if still using column
        'pharmacy_id' => $pharmacyId,
        'password' => Hash::make($validated['password']),
    ]);

    // Assign Spatie role
    if ($request->has('role')) {
        $user->assignRole($validated['role']);
    }

    return redirect()->route('users.index')
        ->with('success', 'User created successfully.');
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // SECURITY CHECK: Prevent editing users from other pharmacies
        if (!auth()->user()->isSuperAdmin() && $user->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403, 'Unauthorized action.');
        }

        $pharmacies = null;
        if (auth()->user()->isSuperAdmin()) {
            $pharmacies = Pharmacy::all();
        }

        return view('users.edit', compact('user', 'pharmacies'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // SECURITY CHECK
        if (!auth()->user()->isSuperAdmin() && $user->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,cashier,pharmacist',
        ];

        // Allow password update only if filled
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        // Only Super Admin can change pharmacy
        if (auth()->user()->isSuperAdmin()) {
            $rules['pharmacy_id'] = 'required|exists:pharmacies,id';
        }

        $validated = $request->validate($rules);

        // Prepare update data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update pharmacy_id only if Super Admin
        if (auth()->user()->isSuperAdmin()) {
            $updateData['pharmacy_id'] = $request->pharmacy_id;
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // SECURITY CHECK: Prevent deleting users from other pharmacies
        if (!auth()->user()->isSuperAdmin() && $user->pharmacy_id !== auth()->user()->pharmacy_id) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }


    public function toggleStatus(User $user)
{
    // Security: Only Super Admin can toggle status
    if (!auth()->user()->isSuperAdmin()) {
        abort(403);
    }

    $user->update([
        'is_active' => !$user->is_active
    ]);

    return back()->with('success', 'User status updated successfully.');
}
}