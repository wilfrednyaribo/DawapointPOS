@extends('layouts.app')

@section('title', 'Profit Report')
@section('page-title', 'Profit Report')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Profit Report</h2>
                <p class="text-gray-500 text-sm mt-1">Analyze revenue, costs, and profit margins.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Date Filter -->
                <form method="GET" class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" 
                            class="px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" 
                            class="px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                        Filter
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
            <a href="{{ route('reports.expiry') }}" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200 rounded-lg text-xs font-semibold transition-all">
                <i class="fas fa-calendar-times mr-1"></i> Expiry
            </a>
        </div>
    </div>

    <!-- Insightful Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Revenue</p>
                <p class="text-xl font-bold text-blue-600">
                    Ksh {{ number_format($totalRevenue ?? 0, 0) }}
                </p>
            </div>
        </div>

        <!-- Total Cost -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-xl">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Cost</p>
                <p class="text-xl font-bold text-orange-600">
                    Ksh {{ number_format($totalCost ?? 0, 0) }}
                </p>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-xl">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Net Profit</p>
                <p class="text-xl font-bold text-green-600">
                    Ksh {{ number_format($totalProfit ?? 0, 0) }}
                </p>
            </div>
        </div>

        <!-- NEW: Avg Profit/Day -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Avg Profit / Day</p>
                <p class="text-xl font-bold text-purple-600">
                    Ksh {{ number_format(($profit->count() > 0 ? $totalProfit / $profit->count() : 0), 0) }}
                </p>
            </div>
        </div>

        <!-- NEW: Margin % -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-xl">
                <i class="fas fa-percent"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Avg Margin</p>
                <p class="text-xl font-bold text-teal-600">
                    {{ $totalRevenue > 0 ? number_format(($totalProfit / $totalRevenue) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Detailed List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800">Daily Breakdown</h3>
                <p class="text-xs text-gray-400 mt-0.5">Visual representation of Revenue vs Profit</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                {{ $profit->count() }} Days Analyzed
            </span>
        </div>
        <div class="overflow-x-auto">
            @if($profit->count() > 0)
            <table class="w-full text-xs">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-500 uppercase tracking-wider w-40">Date</th>
                        <th class="px-6 py-3 text-center text-gray-500 uppercase tracking-wider">Performance</th>
                        <th class="px-6 py-3 text-right text-gray-500 uppercase tracking-wider w-28">Revenue</th>
                        <th class="px-6 py-3 text-right text-gray-500 uppercase tracking-wider w-28">Cost</th>
                        <th class="px-6 py-3 text-right text-gray-500 uppercase tracking-wider w-28">Profit</th>
                        <th class="px-6 py-3 text-right text-gray-500 uppercase tracking-wider w-20">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($profit as $row)
                    @php
                        $margin = $row->revenue > 0 ? ($row->profit / $row->revenue) * 100 : 0;
                        // Calculate widths for visual bars (relative logic)
                        $maxRev = $profit->max('revenue');
                        $revWidth = $maxRev > 0 ? ($row->revenue / $maxRev) * 100 : 0;
                        $profWidth = $row->revenue > 0 ? ($row->profit / $row->revenue) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3">
                            <div class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($row->date)->format('D, d M') }}
                            </div>
                            <div class="text-[10px] text-gray-400">
                                {{ \Carbon\Carbon::parse($row->date)->format('Y') }}
                            </div>
                        </td>
                        
                        <!-- Visual Performance Bar -->
                        <td class="px-6 py-3">
                            <div class="w-full bg-gray-100 rounded-full h-2.5 relative">
                                <!-- Revenue Bar (Background) -->
                                <div class="bg-blue-200 h-2.5 rounded-full" style="width: {{ $revWidth }}%"></div>
                                <!-- Profit Bar (Foreground) -->
                                <div class="absolute top-0 left-0 h-2.5 rounded-full {{ $margin > 20 ? 'bg-green-500' : ($margin > 0 ? 'bg-amber-500' : 'bg-red-400') }}" style="width: {{ $revWidth * ($profWidth/100) }}%"></div>
                            </div>
                        </td>

                        <td class="px-6 py-3 text-right text-blue-700 font-medium">
                            {{ number_format($row->revenue, 0) }}
                        </td>
                        <td class="px-6 py-3 text-right text-orange-600 font-medium">
                            {{ number_format($row->cost, 0) }}
                        </td>
                        <td class="px-6 py-3 text-right font-bold {{ $row->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($row->profit, 0) }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                {{ $margin >= 20 ? 'bg-green-100 text-green-700' : ($margin > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ number_format($margin, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <!-- Summary Footer -->
                <tfoot class="bg-gray-50 border-t-2 border-gray-200 print:bg-white">
                    <tr>
                        <td class="px-6 py-4 text-left font-bold text-gray-700 uppercase text-xs">Totals</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4 text-right font-bold text-blue-700 text-sm">
                            Ksh {{ number_format($totalRevenue, 0) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-orange-600 text-sm">
                            Ksh {{ number_format($totalCost, 0) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600 text-sm">
                            Ksh {{ number_format($totalProfit, 0) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-600 text-sm">
                            {{ $totalRevenue > 0 ? number_format(($totalProfit / $totalRevenue) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-folder-open text-4xl mb-3"></i>
                <p>No profit data found for this period.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection