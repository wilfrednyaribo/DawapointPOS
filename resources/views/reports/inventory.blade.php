@extends('layouts.app')

@section('title', 'Inventory Report')
@section('page-title', 'Inventory Report')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Inventory Report</h2>
                <p class="text-gray-500 text-sm mt-1">Comprehensive stock valuation and status analysis.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm no-print">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock Status</label>
                <select name="stock_status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                    <option value="">All Statuses</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('reports.inventory') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Total Items -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Items</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format($summary['total_items']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Quantity -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fas fa-cubes"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Qty</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format($summary['total_quantity']) }}</p>
                </div>
            </div>
        </div>

        <!-- Cost Value -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Cost Value</p>
                    <p class="text-lg font-bold text-gray-800">Ksh {{ number_format($summary['total_value'], 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Retail Value -->
        <div class="bg-white rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-money-bill-trend-up"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Retail Value</p>
                    <p class="text-lg font-bold text-green-600">Ksh {{ number_format($summary['retail_value'], 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-xl p-4 border-l-4 border-l-amber-400 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-battery-quarter"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Low Stock</p>
                    <p class="text-lg font-bold text-amber-600">{{ number_format($summary['low_stock_count']) }}</p>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white rounded-xl p-4 border-l-4 border-l-red-400 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Out of Stock</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($summary['out_of_stock_count']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <!-- Changed text-sm to text-xs for smaller font size -->
            <table class="w-full text-xs">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <!-- Reduced padding py-3 -->
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Drug Details</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-[10px] font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Stock Status</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Reorder Lvl</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Cost Price</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Sell Price</th>
                        <th class="px-4 py-3 text-right text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Stock Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($drugs as $drug)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Reduced padding py-2.5 -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-md bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-[10px] flex-shrink-0">
                                    {{ substr($drug->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate">{{ $drug->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate">{{ $drug->generic_name ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-gray-600 whitespace-nowrap">{{ $drug->category->name ?? '-' }}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-500 whitespace-nowrap">{{ $drug->sku }}</td>
                        <td class="px-4 py-2.5">
                            <div class="flex flex-col items-center gap-0.5">
                                <span class="font-bold text-gray-800">{{ $drug->quantity_in_stock }} <span class="font-normal text-gray-400">{{ $drug->unit }}</span></span>
                                @if($drug->quantity_in_stock <= 0)
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold bg-red-50 text-red-600 border border-red-100">OUT</span>
                                @elseif($drug->quantity_in_stock <= $drug->reorder_level)
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold bg-amber-50 text-amber-600 border border-amber-100">LOW</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-semibold bg-green-50 text-green-600 border border-green-100">OK</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="font-bold text-gray-700">{{ $drug->reorder_level }}</span>
                        </td>
                        <td class="px-4 py-2.5 text-right text-gray-600 whitespace-nowrap">Ksh {{ number_format($drug->cost_price, 2) }}</td>
                        <td class="px-4 py-2.5 text-right font-medium text-gray-800 whitespace-nowrap">Ksh {{ number_format($drug->selling_price, 2) }}</td>
                        <td class="px-4 py-2.5 text-right font-bold text-gray-800 whitespace-nowrap">Ksh {{ number_format($drug->quantity_in_stock * $drug->cost_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-200"></i>
                                <p class="font-medium text-gray-500">No inventory data found.</p>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection