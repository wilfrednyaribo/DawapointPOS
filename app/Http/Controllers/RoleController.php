<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return the view with the form
        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used for this UI
        return redirect()->route('roles.index');
    }

    /**
     * Store a newly created resource in storage.
     * NOTE: We are using this method to SAVE the permissions for all roles at once.
     */
    public function store(Request $request)
    {
        // Define the mapping between Form Input Names and Database Role Names
        $rolesMap = [
            'cashier_permissions'   => 'Cashier',
            'pharmacist_permissions' => 'Pharmacist',
            'admin_permissions'     => 'Admin',
        ];

        foreach ($rolesMap as $inputKey => $roleName) {
            // 1. Find the role in the database
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                // 2. Get the array of permission names from the request (e.g., ['pos_access', 'view_sales'])
                // If no boxes are checked, default to an empty array
                $permissions = $request->input($inputKey, []);

                // 3. Sync permissions
                // This automatically handles adding new permissions and removing unchecked ones
                foreach ($permissions as $permName) {
                    // Ensure the permission exists in the database before assigning
                    Permission::firstOrCreate(['name' => $permName]);
                }
                
                // Sync the permissions to the role
                $role->syncPermissions($permissions);
            }
        }

        // 4. Redirect back to the index page with a success message
        return redirect()->route('roles.index')->with('status', 'Roles & Permissions updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('roles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic handled in 'store' for this specific UI
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}