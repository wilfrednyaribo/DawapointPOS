@extends('layouts.app')

@section('title', 'Register Supplier')
@section('page-title', 'New Supplier')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Register New Supplier</h2>
            <p class="text-gray-500 text-sm">Add a new vendor or distributor to your system.</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm group">
            <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform text-xs"></i>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Company Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="font-bold text-gray-800">Company Details</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Supplier Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Supplier Name *</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., MediSupply Ltd" required>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Address</label>
                    <input type="text" name="address" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Street, City, Building">
                </div>
            </div>
        </div>

        <!-- Section 2: Contact Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600">
                    <i class="fas fa-address-card"></i>
                </div>
                <h3 class="font-bold text-gray-800">Contact Information</h3>
            </div>
            
            <div class="p-6 space-y-6">
                
                <!-- Contact Person -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Contact Person *</label>
                    <div class="relative">
                        <input type="text" name="contact_person" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., John Smith" required>
                        <i class="fas fa-user-tie absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number *</label>
                        <div class="relative">
                            <input type="text" name="phone" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="+254 7XX XXX XXX" required>
                            <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="contact@supplier.com">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-end gap-3">
            <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-red-100 hover:border-red-600">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <i class="fas fa-check-circle"></i>
                Save Supplier
            </button>
        </div>
    </form>
</div>
@endsection