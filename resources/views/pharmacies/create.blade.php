@extends('layouts.app')

@section('title', 'Create Pharmacy')
@section('page-title', 'Add New Pharmacy')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Pharmacy</h2>
            <p class="text-gray-500 text-sm">Register a new pharmacy branch on the platform.</p>
        </div>
        <a href="{{ route('pharmacies.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white rounded-xl text-sm font-semibold transition">
            <i class="fas fa-arrow-left text-xs"></i> Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('pharmacies.store') }}" class="p-6 space-y-6">
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

            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pharmacy Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" placeholder="e.g., MediPrime Downtown" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NEW: Pharmacy Code (Short Code for Invoices) -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Pharmacy Code <span class="text-gray-400 font-normal">(3-5 Letters)</span>
                </label>
                <input type="text" name="code" value="{{ old('code') }}" maxlength="5" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 uppercase @error('code') border-red-500 @enderror" placeholder="e.g., SHA (for Shahsan)" style="text-transform: uppercase;">
                <p class="text-gray-400 text-xs mt-1">
                    <i class="fas fa-info-circle mr-1"></i> Used in invoice numbers (e.g., SHA-0001).
                </p>
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Contact Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror" placeholder="pharmacy@example.com" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="e.g., 0712345678">
            </div>

            <!-- Address -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Physical Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Street, Building, City">{{ old('address') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('pharmacies.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 text-white hover:bg-brand-700 rounded-xl text-sm font-bold transition shadow-lg">
                    <i class="fas fa-check-circle"></i> Create Pharmacy
                </button>
            </div>
        </form>
    </div>
</div>
@endsection