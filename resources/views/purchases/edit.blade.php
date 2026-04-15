@extends('layouts.app')

@section('title', 'Edit Purchase')
@section('page-title', 'Update Purchase')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Purchase</h2>
            <p class="text-gray-500 text-sm">Update purchase details for Invoice: {{ $purchase->invoice_number ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm group">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Supplier & Date -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="font-bold text-gray-800">Purchase Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Supplier -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Supplier *</label>
                    <div class="relative">
                        <select name="supplier_id" class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 appearance-none bg-white" required>
                            <option value="">Select Supplier</option>
                            @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Invoice Number -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Invoice Number</label>
                    <input type="text" name="invoice_number" value="{{ $purchase->invoice_number }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-mono focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Purchase Date *</label>
                    <input type="date" name="purchase_date" value="{{ $purchase->purchase_date }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500" required>
                </div>
            </div>
        </div>

        <!-- Summary of Items (Read Only for context) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <h3 class="font-bold text-gray-800">Items Summary</h3>
            </div>
            <div class="p-6 text-sm text-gray-600">
                <p>This purchase contains <strong>{{ $purchase->items->count() }} items</strong>.</p>
                <p class="text-xs text-gray-400 mt-1">To modify specific items, please delete this purchase and create a new one, or use a dedicated inventory adjustment tool.</p>
            </div>
        </div>

        <!-- Notes & Total -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500">{{ $purchase->notes }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Amount</label>
                    <input type="number" name="total_amount" step="0.01" value="{{ $purchase->total_amount }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-right font-bold focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                <a href="{{ route('purchases.show', $purchase->id) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-sm font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg">
                    <i class="fas fa-check-circle"></i>
                    Update Purchase
                </button>
            </div>
        </div>
    </form>
</div>
@endsection