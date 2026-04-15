@extends('layouts.app')

@section('title', 'Expiry Report')
@section('page-title', 'Expiry Report')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Expiry Report</h2>
                <p class="text-gray-500 text-sm mt-1">Track expiring and expired drug batches to minimize loss.</p>
            </div>
            
            <div class="flex items-center gap-3">
                 <!-- Filter -->
                <form method="GET" class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Days Threshold</label>
                        <input type="number" name="days" value="{{ $days }}" min="1" 
                            class="px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white w-24">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                        Apply
                    </button>
                    <button type="button" onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm no-print">
                        <i class="fas fa-print"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="flex flex-wrap gap-2 no-print">
            <span class="text-xs text-gray-400 font-medium self-center mr-2">Jump to:</span>
            <a href="{{ route('reports.sales') }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 rounded-lg text-xs font-semibold transition-all">
                <i class="fas fa-chart-line mr-1"></i> Sales
            </a>
            <a href="{{ route('reports.inventory') }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-teal-50 hover:text-teal-600 hover:border-teal-200 rounded-lg text-xs font-semibold transition-all">
                <i class="fas fa-boxes-stacked mr-1"></i> Inventory
            </a>
            <a href="{{ route('reports.top-products') }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-purple-50 hover:text-purple-600 hover:border-purple-200 rounded-lg text-xs font-semibold transition-all">
                <i class="fas fa-star mr-1"></i> Top Products
            </a>
            <a href="{{ route('reports.profit') }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-green-200 rounded-lg text-xs font-semibold transition-all">
                <i class="fas fa-dollar-sign mr-1"></i> Profit
            </a>
        </div>
    </div>

    <!-- Risk Summary Cards -->
    {{-- UPDATED: Added Forecasted Loss Card --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <!-- Forecasted Loss (New) -->
        <div class="bg-white rounded-xl p-5 border border-red-200 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                <i class="fas fa-fire"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Projected Loss ({{ $days }} Days)</p>
                <p class="text-xl font-bold text-rose-600">
                    Ksh {{ number_format($projectedLossValue ?? 0, 0) }}
                </p>
                <p class="text-xs text-gray-400">Value at risk in this period</p>
            </div>
        </div>

        <!-- Total at Risk Value (Expired Only) -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Current Loss</p>
                <p class="text-xl font-bold text-red-600">
                    Ksh {{ number_format($totalLossValue ?? 0, 0) }}
                </p>
                <p class="text-xs text-gray-400">From Already Expired Stock</p>
            </div>
        </div>

        <!-- Expiring Soon Count -->
        <div class="bg-white rounded-xl p-5 border-l-4 border-l-amber-400 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Expiring Soon</p>
                <p class="text-xl font-bold text-gray-800">{{ $expiringSoon->count() }} <span class="text-sm font-normal text-gray-400">Batches</span></p>
                <p class="text-xs text-gray-400">Within {{ $days }} days</p>
            </div>
        </div>

        <!-- Expired Count -->
        <div class="bg-white rounded-xl p-5 border-l-4 border-l-red-400 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xl">
                <i class="fas fa-ban"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Already Expired</p>
                <p class="text-xl font-bold text-gray-800">{{ $expired->count() }} <span class="text-sm font-normal text-gray-400">Batches</span></p>
                <p class="text-xs text-gray-400">Do not dispense</p>
            </div>
        </div>
    </div>

    <!-- Main Grid: Split View -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        
        <!-- Left Column: Expiring Soon -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit">
            <div class="p-5 border-b border-gray-100 bg-amber-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Expiring Soon</h3>
                        <p class="text-xs text-gray-500">Next {{ $days }} days</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                    {{ $expiringSoon->sum('quantity_remaining') }} Units
                </span>
            </div>

            <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
                @forelse($expiringSoon as $batch)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $batch->drug->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 font-mono">Batch: {{ $batch->batch_number }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-[10px] font-bold">
                            {{ $batch->expiry_date->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span><i class="fas fa-cubes mr-1"></i> {{ $batch->quantity_remaining }} units left</span>
                        {{-- ADDED: Value of expiring stock --}}
                        <span class="font-semibold text-amber-700">
                            Ksh {{ number_format($batch->quantity_remaining * ($batch->purchase_price ?? $batch->drug->cost_price ?? 0), 0) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-check-circle text-3xl mb-2 text-green-400"></i>
                    <p>No batches expiring soon.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Expired List -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit">
            <div class="p-5 border-b border-gray-100 bg-red-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Expired Stock</h3>
                        <p class="text-xs text-gray-500">Requires immediate disposal</p>
                    </div>
                </div>
                 <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                    {{ $expired->sum('quantity_remaining') }} Units
                </span>
            </div>

            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                @if($expired->count() > 0)
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-500 font-semibold">Drug</th>
                            <th class="px-4 py-2 text-left text-gray-500 font-semibold">Batch</th>
                            <th class="px-4 py-2 text-center text-gray-500 font-semibold">Qty</th>
                            <th class="px-4 py-2 text-right text-gray-500 font-semibold">Loss Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($expired as $batch)
                        <tr class="hover:bg-red-50/30">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $batch->drug->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-mono text-gray-500">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-3 text-center font-bold text-red-600">{{ $batch->quantity_remaining }}</td>
                            <td class="px-4 py-3 text-right text-red-500 font-semibold">
                                Ksh {{ number_format($batch->quantity_remaining * ($batch->purchase_price ?? $batch->drug->cost_price ?? 0), 0) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-thumbs-up text-3xl mb-2 text-green-400"></i>
                    <p>No expired batches found.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Detailed View (All Batches)</h3>
            <span class="text-xs text-gray-400 font-medium">
                Sorted by Expiry Date
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-500 uppercase tracking-wider">Drug Name</th>
                        <th class="px-6 py-3 text-left text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-gray-500 uppercase tracking-wider">Batch No.</th>
                        <th class="px-6 py-3 text-center text-gray-500 uppercase tracking-wider">Remaining Qty</th>
                        <th class="px-6 py-3 text-center text-gray-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-center text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($expired->concat($expiringSoon)->sortBy('expiry_date') as $batch)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-semibold text-gray-800">{{ $batch->drug->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $batch->drug->category->name ?? '-' }}</td>
                        <td class="px-6 py-3 font-mono text-gray-500">{{ $batch->batch_number }}</td>
                        <td class="px-6 py-3 text-center font-bold">{{ $batch->quantity_remaining }}</td>
                        <td class="px-6 py-3 text-center text-gray-600">{{ $batch->expiry_date->format('d M, Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($batch->isExpired())
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">EXPIRED</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">EXPIRING SOON</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection