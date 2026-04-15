@extends('layouts.app')

@section('title', 'Purchase Records')
@section('page-title', 'Purchase History')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Purchase Records</h2>
                <p class="text-gray-500 text-sm mt-1">Overview of inventory inflow and supplier transactions.</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- New Supplier Button -->
                <a href="{{ route('suppliers.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-user-plus"></i>
                    <span>Register Supplier</span>
                </a>

                <!-- New Purchase Button -->
                <a href="{{ route('purchases.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i>
                    <span>New Purchase</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Spending Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center text-teal-600 text-xl">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Spending</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Ksh {{ number_format($totalSpending ?? 0, 2) }}</p>
                </div>
            </div>

            <!-- Suppliers Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active Suppliers</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $activeSuppliers ?? 0 }}</p>
                </div>
            </div>

            <!-- Transactions Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 text-xl">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $purchases->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-lg">Recent Transactions</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Invoice #</th>
                            <th class="px-6 py-3 text-left">Supplier</th>
                            <th class="px-6 py-3 text-center">Items</th>
                            <th class="px-6 py-3 text-right">Total Amount</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Date (Fixed to handle string/null safely) -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $purchase->created_at->diffForHumans() }}</div>
                                </td>

                                <!-- Invoice -->
                                <td class="px-6 py-4 font-mono text-gray-600 text-xs">
                                    {{ $purchase->invoice_number ?? 'N/A' }}
                                </td>

                                <!-- Supplier -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xs font-bold">
                                            {{ Str::substr($purchase->supplier->name ?? 'N', 0, 1) }}
                                        </div>
                                        <span
                                            class="font-medium text-gray-700">{{ $purchase->supplier->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>

                                <!-- Items Count -->
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold text-gray-600">
                                        {{ $purchase->items->count() }} items
                                    </span>
                                </td>

                                <!-- Total Amount -->
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    Ksh {{ number_format($purchase->total_amount, 2) }}
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $purchase->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ Str::title($purchase->status ?? 'Received') }}
                                    </span>
                                </td>

                                <!-- Actions (Updated with styled buttons) -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('purchases.show', $purchase->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition border border-blue-100">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition border border-teal-100">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-100">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-inbox text-4xl text-gray-200"></i>
                                        <p class="text-gray-500 font-medium">No purchase records found.</p>
                                        <a href="{{ route('purchases.create') }}"
                                            class="text-teal-600 font-semibold text-sm hover:underline">Record your first
                                            purchase</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($purchases->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
