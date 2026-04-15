@extends('layouts.app')

@section('title', 'Purchase Details')
@section('page-title', 'Purchase Record')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Purchase Details</h2>
            <p class="text-gray-500 text-sm">Invoice: {{ $purchase->invoice_number ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm group">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to List</span>
        </a>
    </div>

    <!-- Supplier & Meta Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Supplier Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:col-span-2">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold">Supplier</p>
                    <h3 class="text-xl font-bold text-gray-800">{{ $purchase->supplier->name ?? 'Unknown' }}</h3>
                    <p class="text-sm text-gray-500">{{ $purchase->supplier->phone ?? 'No contact' }}</p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-400 uppercase font-semibold">Status</p>
                <span class="px-3 py-1 text-xs font-bold rounded-full 
                    {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ Str::title($purchase->status ?? 'Received') }}
                </span>
            </div>
            <div class="border-t border-gray-100 pt-2 mt-2">
                <p class="text-xs text-gray-400">Date</p>
                <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <h3 class="font-bold text-gray-800">Purchased Items</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Drug Name</th>
                        <th class="px-6 py-3 text-left">Batch #</th>
                        <th class="px-6 py-3 text-left">Expiry</th>
                        <th class="px-6 py-3 text-right">Qty</th>
                        <th class="px-6 py-3 text-right">Cost Price</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($purchase->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->drug->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-mono text-gray-600 text-xs">{{ $item->batch_number }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($item->expiry_date)->format('M Y') }}</td>
                        <td class="px-6 py-4 text-right text-gray-800">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right text-gray-600">Ksh {{ number_format($item->cost_price, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">Ksh {{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-t border-gray-100 p-6 bg-gray-50 flex justify-end">
            <div class="w-full md:w-1/3 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-semibold">Ksh {{ number_format($purchase->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2 mt-2">
                    <span>Grand Total</span>
                    <span class="text-teal-600">Ksh {{ number_format($purchase->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($purchase->notes)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="font-bold text-gray-800 mb-2 text-sm">Notes</h4>
        <p class="text-gray-600 text-sm whitespace-pre-line">{{ $purchase->notes }}</p>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex items-center gap-3 justify-end">
        <a href="{{ route('purchases.edit', $purchase->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition shadow-lg">
            <i class="fas fa-pen"></i> Edit Purchase
        </a>
    </div>
</div>
@endsection