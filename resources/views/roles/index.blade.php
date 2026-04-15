@extends('layouts.app')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Roles & Permissions</h2>
            <p class="text-gray-500 text-sm">Check the boxes to grant access. Uncheck to deny.</p>
        </div>
        <button type="submit" form="rolesForm" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all shadow-lg">
            <i class="fas fa-save"></i>
            <span>Save Changes</span>
        </button>
    </div>

    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Content Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <form id="rolesForm" action="{{ route('roles.store') }}" method="POST">
            @csrf
            
            <div class="divide-y divide-gray-100">

                <!-- 1. CASHIER ROLE -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-cash-register text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Cashier</h3>
                                <p class="text-xs text-gray-500">Access to POS, billing, and daily sales reports.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ml-13 pl-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php 
                            $cashierRole = Spatie\Permission\Models\Role::where('name', 'Cashier')->first();
                            $cashierPermissions = $cashierRole ? $cashierRole->permissions : collect([]);
                        @endphp
                        
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_dashboard" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm" 
                                {{ $cashierPermissions->contains('name', 'view_dashboard') ? 'checked' : '' }}>
                            Dashboard
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="access_pos" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'access_pos') ? 'checked' : '' }}>
                            Point of Sale
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_sales" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_sales') ? 'checked' : '' }}>
                            Sales History
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_customers" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_customers') ? 'checked' : '' }}>
                            Customers
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_drugs" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_drugs') ? 'checked' : '' }}>
                            Drug Inventory
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_categories" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_categories') ? 'checked' : '' }}>
                            Categories
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_suppliers" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_suppliers') ? 'checked' : '' }}>
                            Suppliers
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_purchases" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_purchases') ? 'checked' : '' }}>
                            Purchases
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_reports" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_reports') ? 'checked' : '' }}>
                            Reports
                        </label>
                    </div>

                    <!-- Subscription Access Section -->
                    <div class="ml-13 pl-12 mt-4 pt-4 border-t border-dashed border-gray-200">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Subscription Access</h4>
                        <label class="flex items-center gap-2 text-sm text-indigo-600 font-semibold cursor-pointer">
                            <input type="checkbox" name="cashier_permissions[]" value="view_subscription" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $cashierPermissions->contains('name', 'view_subscription') ? 'checked' : '' }}>
                            <i class="fas fa-calendar-check text-xs"></i>
                            View Subscription Status
                        </label>
                        <p class="text-xs text-gray-400 mt-1 ml-6">Allows this role to see when the pharmacy subscription expires.</p>
                    </div>
                </div>

                <!-- 2. PHARMACIST ROLE -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                <i class="fas fa-pills text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Pharmacist</h3>
                                <p class="text-xs text-gray-500">Manage inventory, prescriptions, and stock alerts.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ml-13 pl-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php 
                            $pharmacistRole = Spatie\Permission\Models\Role::where('name', 'Pharmacist')->first();
                            $pharmacistPermissions = $pharmacistRole ? $pharmacistRole->permissions : collect([]);
                        @endphp

                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_dashboard" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_dashboard') ? 'checked' : '' }}>
                            Dashboard
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="access_pos" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'access_pos') ? 'checked' : '' }}>
                            Point of Sale
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_sales" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_sales') ? 'checked' : '' }}>
                            Sales History
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_customers" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_customers') ? 'checked' : '' }}>
                            Customers
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_drugs" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_drugs') ? 'checked' : '' }}>
                            Drug Inventory
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_categories" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_categories') ? 'checked' : '' }}>
                            Categories
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_suppliers" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_suppliers') ? 'checked' : '' }}>
                            Suppliers
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_purchases" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_purchases') ? 'checked' : '' }}>
                            Purchases
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_reports" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_reports') ? 'checked' : '' }}>
                            Reports
                        </label>
                    </div>

                    <!-- Subscription Access Section -->
                    <div class="ml-13 pl-12 mt-4 pt-4 border-t border-dashed border-gray-200">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Subscription Access</h4>
                        <label class="flex items-center gap-2 text-sm text-indigo-600 font-semibold cursor-pointer">
                            <input type="checkbox" name="pharmacist_permissions[]" value="view_subscription" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                {{ $pharmacistPermissions->contains('name', 'view_subscription') ? 'checked' : '' }}>
                            <i class="fas fa-calendar-check text-xs"></i>
                            View Subscription Status
                        </label>
                        <p class="text-xs text-gray-400 mt-1 ml-6">Allows this role to see when the pharmacy subscription expires.</p>
                    </div>
                </div>

                <!-- 3. ADMIN ROLE (Super Admin Only) -->
                @if(auth()->user()->pharmacy_id === null)
                    <div class="p-6 hover:bg-gray-50 transition-colors bg-red-50/30">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                    <i class="fas fa-user-shield text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">Administrator</h3>
                                    <p class="text-xs text-gray-500">Full system access including users, reports, and settings.</p>
                                </div>
                            </div>
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-semibold">Global Role</span>
                        </div>
                        
                        <div class="ml-13 pl-12 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php 
                                $adminRole = Spatie\Permission\Models\Role::where('name', 'Admin')->first();
                                $adminPermissions = $adminRole ? $adminRole->permissions : collect([]);
                            @endphp

                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_dashboard" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_dashboard') ? 'checked' : '' }}>
                                Dashboard
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="access_pos" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'access_pos') ? 'checked' : '' }}>
                                Point of Sale
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_sales" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_sales') ? 'checked' : '' }}>
                                Sales History
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_customers" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_customers') ? 'checked' : '' }}>
                                Customers
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_drugs" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_drugs') ? 'checked' : '' }}>
                                Drug Inventory
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_categories" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_categories') ? 'checked' : '' }}>
                                Categories
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_suppliers" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_suppliers') ? 'checked' : '' }}>
                                Suppliers
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_purchases" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_purchases') ? 'checked' : '' }}>
                                Purchases
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="admin_permissions[]" value="view_reports" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_reports') ? 'checked' : '' }}>
                                Reports
                            </label>

                            <!-- Divider for Admin Specific Permissions -->
                            <div class="col-span-4 border-t border-gray-200 my-2"></div>

                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer font-semibold">
                                <input type="checkbox" name="admin_permissions[]" value="view_users" 
                                    class="rounded border-gray-300 text-red-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_users') ? 'checked' : '' }}>
                                All Users
                            </label>
                             <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer font-semibold">
                                <input type="checkbox" name="admin_permissions[]" value="view_roles" 
                                    class="rounded border-gray-300 text-red-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_roles') ? 'checked' : '' }}>
                                Roles & Permissions
                            </label>
                            
                            <!-- Subscription Management for Super Admin -->
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer font-semibold">
                                <input type="checkbox" name="admin_permissions[]" value="view_subscription" 
                                    class="rounded border-gray-300 text-red-600 shadow-sm"
                                    {{ $adminPermissions->contains('name', 'view_subscription') ? 'checked' : '' }}>
                                Manage Subscriptions
                            </label>
                        </div>
                    </div>
                @endif

            </div>
        </form>

    </div>

</div>
@endsection