@extends('layouts.app')

@section('title', 'Sales')
@section('page-title', 'Sales Transactions')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Sales Transactions</h2>
            <p class="text-gray-500 text-sm mt-1">Overview of sales and customer transactions.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- New Sale Button -->
            <a href="{{ route('pos.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <i class="fas fa-plus"></i>
                <span>New Sale</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice..." 
                       class="w-48 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <select name="status" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition w-36">
                <option value="">All Status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="payment_method" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition w-36">
                <option value="">All Payment</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                <option value="mobile_money" {{ request('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                <option value="insurance" {{ request('payment_method') === 'insurance' ? 'selected' : '' }}>Insurance</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition w-36">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition w-36">

            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-bold transition-all">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-lg">Recent Transactions</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Invoice</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-center">Items</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-right">Paid</th>
                        <th class="px-6 py-3 text-center">Payment</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Date</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Invoice -->
                        <td class="px-6 py-4">
                            <div class="font-medium text-teal-600">{{ $sale->invoice_number }}</div>
                            @if($sale->prescription_number)
                            <div class="text-xs text-gray-400">Rx: {{ $sale->prescription_number }}</div>
                            @endif
                        </td>
                        
                        <!-- Customer -->
                        <td class="px-6 py-4">
                            @if($sale->customer_name)
                                <span class="font-medium text-gray-700">{{ $sale->customer_name }}</span>
                            @elseif($sale->customer)
                                <span class="font-medium text-gray-700">{{ $sale->customer->name }}</span>
                            @else
                                <span class="text-gray-400 italic">Walk-in</span>
                            @endif
                        </td>
                        
                        <!-- Items -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold text-gray-600">
                                {{ $sale->items->count() }} items
                            </span>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Ksh {{ number_format($sale->total, 2) }}
                        </td>

                        <!-- Paid -->
                        <td class="px-6 py-4 text-right text-gray-600">
                            Ksh {{ number_format($sale->amount_paid, 2) }}
                        </td>

                        <!-- Payment Method -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-100">
                                {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            @if($sale->status === 'completed')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                Completed
                            </span>
                            @elseif($sale->status === 'pending')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                            @elseif($sale->status === 'cancelled')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                Cancelled
                            </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-center">
                            <div class="font-medium text-gray-800">
                                {{ $sale->created_at->format('d M, Y') }}
                            </div>
                            <div class="text-xs text-gray-400">{{ $sale->created_at->format('H:i') }}</div>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- View Button -->
                                <a href="{{ route('sales.show', $sale) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition border border-blue-100">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <!-- Receipt Button -->
                                <a href="{{ route('sales.receipt', $sale) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition border border-teal-100">
                                    <i class="fas fa-receipt"></i> Receipt
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this sale?')">
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
                        <td colspan="9" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-receipt text-4xl text-gray-200"></i>
                                <p class="text-gray-500 font-medium">No sales found</p>
                                <a href="{{ route('pos.create') }}" class="text-teal-600 font-semibold text-sm hover:underline">
                                    Record your first sale
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $sales->links() }}
        </div>
        @endif
    </div>

</div>
@endsection