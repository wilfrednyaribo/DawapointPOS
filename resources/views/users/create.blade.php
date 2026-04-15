@extends('layouts.app')

@section('title', 'Create User')
@section('page-title', 'Add New User')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New User</h2>
            <p class="text-gray-500 text-sm">
                @if(auth()->user()->isSuperAdmin())
                    Assign an Admin to a specific pharmacy, or add staff.
                @else
                    Add a new Cashier or Pharmacist to your pharmacy.
                @endif
            </p>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white rounded-xl text-sm font-semibold transition">
            <i class="fas fa-arrow-left text-xs"></i> Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- ERROR FEEDBACK BLOCK -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                    <div class="flex items-center gap-2 font-bold mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Validation Error</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SUPER ADMIN ONLY: Pharmacy Selection -->
            @if(auth()->user()->isSuperAdmin())
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                    <label class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">
                        <i class="fas fa-hospital mr-1"></i> Assign to Pharmacy *
                    </label>
                    <p class="text-xs text-indigo-600 mb-2">Super Admins must assign users to a specific pharmacy.</p>
                    <select name="pharmacy_id" class="w-full px-4 py-2.5 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 bg-white @error('pharmacy_id') border-red-500 @enderror" required>
                        <option value="">-- Select Pharmacy --</option>
                        @foreach($pharmacies as $pharmacy)
                            <option value="{{ $pharmacy->id }}" {{ old('pharmacy_id') == $pharmacy->id ? 'selected' : '' }}>
                                {{ $pharmacy->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pharmacy_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assign Role *</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 bg-white @error('role') border-red-500 @enderror" required>
                    <option value="">Select Role</option>
                    {{-- Super Admin can create Admins. Pharmacy Admins usually create staff. --}}
                    @if(auth()->user()->isSuperAdmin())
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Pharmacy Admin</option>
                    @endif
                    <option value="pharmacist" {{ old('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password *</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition shadow-lg">
                    <i class="fas fa-check-circle"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection