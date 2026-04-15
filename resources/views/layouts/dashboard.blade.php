@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6" x-data="{ animated: false }" x-init="setTimeout(() => animated = true, 100)">
    
    <!-- Quick Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-medium text-slate-700">Live Dashboard</span>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <!-- New Sale Button -->
            <a href="{{ route('pos.create') }}" 
               class="group relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-brand-500 to-teal-500 text-white font-semibold rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-brand-500/25 hover:-translate-y-0.5 active:translate-y-0">
                <span class="absolute inset-0 bg-gradient-to-r from-brand-400 to-teal-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <svg class="w-4 h-4 relative z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span class="relative z-10">New Sale</span>
            </a>
            
            <!-- Add Drug Button -->
            <a href="{{ route('drugs.create') }}" 
               class="group inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl transition-all duration-300 hover:border-brand-300 hover:text-brand-600 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.5 2.5a9 9 0 0 0-7.5 9c0 3.5 2.5 6.5 5.5 7.5"/>
                    <path d="M13.5 2.5a9 9 0 0 1 7.5 9c0 3.5-2.5 6.5-5.5 7.5"/>
                    <path d="M12 2v6"/>
                    <circle cx="12" cy="14" r="4"/>
                </svg>
                <span>Add Drug</span>
            </a>
            
            <!-- Reports Button -->
            <a href="{{ route('reports.index') }}" 
               class="group inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl transition-all duration-300 hover:border-brand-300 hover:text-brand-600 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
                    <path d="M22 12A10 10 0 0 0 12 2v10z"/>
                </svg>
                <span>Reports</span>
            </a>
            
            <!-- Refresh Button -->
            <button onclick="location.reload()" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl transition-all duration-300 hover:border-brand-300 hover:text-brand-600 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"/>
                    <polyline points="1 20 1 14 7 14"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Today's Sales -->
        <div class="group relative bg-white rounded-2xl border border-slate-200/50 p-5 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-brand-500/5 hover:-translate-y-1"
             :class="{ 'opacity-0 translate-y-4': !animated }"
             style="transition-delay: 0ms;">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Decorative Circle -->
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-brand-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-brand-400 to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-brand-50 rounded-lg">
                        <div class="w-1.5 h-1.5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-brand-600">Today</span>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Sales</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">KES {{ number_format($todaySales, 0) }}</p>
                </div>
                
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-1 text-emerald-600">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ $todayTransactions }}</span>
                    </div>
                    <span class="text-xs text-slate-400">transactions</span>
                </div>
            </div>
        </div>
        
        <!-- Monthly Sales -->
        <div class="group relative bg-white rounded-2xl border border-slate-200/50 p-5 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-1"
             :class="{ 'opacity-0 translate-y-4': !animated }"
             style="transition-delay: 50ms;">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 20V10"/>
                            <path d="M12 20V4"/>
                            <path d="M6 20v-6"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-blue-50 rounded-lg">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-blue-600">Month</span>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">KES {{ number_format($monthlySales, 0) }}</p>
                </div>
                
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-1 text-blue-600">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M6 8h.01M10 8h.01M14 8h.01"/>
                        </svg>
                        <span class="text-xs font-semibold">{{ $monthlyTransactions }}</span>
                    </div>
                    <span class="text-xs text-slate-400">transactions</span>
                </div>
            </div>
        </div>
        
        <!-- Today's Profit -->
        <div class="group relative bg-white rounded-2xl border border-slate-200/50 p-5 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-emerald-500/5 hover:-translate-y-1"
             :class="{ 'opacity-0 translate-y-4': !animated }"
             style="transition-delay: 100ms;">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-emerald-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                            <path d="M12 18V6"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-emerald-50 rounded-lg">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-emerald-600">Profit</span>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Today's Profit</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">KES {{ number_format($todayProfit, 0) }}</p>
                </div>
                
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-1 text-emerald-600">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        </svg>
                        <span class="text-xs font-semibold">
                            @php
                                $profitMargin = $todaySales > 0 ? round(($todayProfit / $todaySales) * 100, 1) : 0;
                            @endphp
                            {{ $profitMargin }}%
                        </span>
                    </div>
                    <span class="text-xs text-slate-400">margin</span>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="group relative bg-white rounded-2xl border border-slate-200/50 p-5 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-amber-500/5 hover:-translate-y-1"
             :class="{ 'opacity-0 translate-y-4': !animated }"
             style="transition-delay: 150ms;">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-amber-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-amber-50 rounded-lg border border-amber-200">
                        <div class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-amber-600">Alert</span>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Low Stock Items</p>
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">{{ $lowStockDrugs->count() }}</p>
                </div>
                
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('drugs.index', ['filter' => 'low_stock']) }}" class="flex items-center gap-1 text-amber-600 hover:text-amber-700 transition-colors">
                        <span class="text-xs font-semibold">View Items</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Sales Overview</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Revenue performance over the last 7 days</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-gradient-to-r from-brand-400 to-brand-500 rounded-full"></span>
                        <span class="text-sm text-slate-500">Sales</span>
                    </div>
                    <select class="text-sm border-slate-200 rounded-lg px-3 py-1.5 bg-slate-50 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>This Year</option>
                    </select>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        <!-- Top Selling Drugs -->
        <div class="bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold text-slate-800">Top Selling</h3>
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">This Week</span>
            </div>
            <div class="space-y-3">
                @forelse($topDrugs as $index => $drug)
                @php
                    $colors = ['from-brand-400 to-brand-500', 'from-blue-400 to-blue-500', 'from-violet-400 to-violet-500', 'from-amber-400 to-amber-500', 'from-rose-400 to-rose-500'];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div class="flex items-center gap-3 p-2 -mx-2 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-9 h-9 bg-gradient-to-br {{ $color }} rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate group-hover:text-brand-600 transition-colors">{{ $drug->name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-slate-400">{{ $drug->total_sold ?? 0 }} sold</span>
                            @if($drug->total_sold > 50)
                            <span class="text-[10px] px-1.5 py-0.5 bg-emerald-100 text-emerald-600 rounded font-medium">Hot</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">KES {{ number_format(($drug->total_revenue ?? 0), 0) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M10.5 2.5a9 9 0 0 0-7.5 9c0 3.5 2.5 6.5 5.5 7.5"/>
                            <path d="M13.5 2.5a9 9 0 0 1 7.5 9c0 3.5-2.5 6.5-5.5 7.5"/>
                            <circle cx="12" cy="14" r="4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">No sales data yet</p>
                </div>
                @endforelse
            </div>
        </div>
        
    </div>
    
    <!-- Alerts & Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Expiring Drugs -->
        <div class="bg-white rounded-2xl border border-slate-200/50 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-orange-50/50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800">Expiring Soon</h3>
                        <p class="text-xs text-slate-500">Items requiring attention</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200">
                    {{ $expiringBatches->count() }} items
                </span>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($expiringBatches as $batch)
                <div class="px-6 py-3 hover:bg-slate-50/50 transition-colors group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                <svg class="w-4 h-4 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.5 2.5a9 9 0 0 0-7.5 9c0 3.5 2.5 6.5 5.5 7.5"/>
                                    <circle cx="12" cy="14" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $batch->drug->name }}</p>
                                <p class="text-xs text-slate-400">Batch: {{ $batch->batch_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-orange-600">{{ $batch->expiry_date->format('M d, Y') }}</p>
                            <p class="text-xs @if($batch->days_until_expiry <= 7) text-red-500 font-medium @else text-slate-400 @endif">
                                {{ $batch->days_until_expiry }} days left
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-600">All Clear!</p>
                    <p class="text-xs text-slate-400 mt-1">No drugs expiring soon</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Low Stock -->
        <div class="bg-white rounded-2xl border border-slate-200/50 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-red-50/50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-800">Low Stock Alert</h3>
                        <p class="text-xs text-slate-500">Items below reorder level</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200">
                    {{ $lowStockDrugs->count() }} items
                </span>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($lowStockDrugs as $drug)
                <div class="px-6 py-3 hover:bg-slate-50/50 transition-colors group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.5 2.5a9 9 0 0 0-7.5 9c0 3.5 2.5 6.5 5.5 7.5"/>
                                    <circle cx="12" cy="14" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $drug->name }}</p>
                                <p class="text-xs text-slate-400">{{ $drug->category->name ?? 'Uncategorized' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold @if($drug->quantity_in_stock <= 0) text-red-600 @else text-amber-600 @endif">
                                {{ $drug->quantity_in_stock }} {{ $drug->unit }}
                            </p>
                            <p class="text-xs text-slate-400">Min: {{ $drug->reorder_level }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-600">All Stocked Up!</p>
                    <p class="text-xs text-slate-400 mt-1">All items adequately stocked</p>
                </div>
                @endforelse
            </div>
        </div>
        
    </div>
    
    <!-- Recent Sales -->
    <div class="bg-white rounded-2xl border border-slate-200/50 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-brand-400 to-teal-500 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Recent Sales</h3>
                    <p class="text-xs text-slate-500">Latest transactions</p>
                </div>
            </div>
            <a href="{{ route('sales.index') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-brand-600 hover:text-brand-700 hover:bg-brand-50 rounded-lg transition-colors">
                <span>View All</span>
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSales as $sale)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-semibold text-brand-600 bg-brand-50 px-2 py-1 rounded">{{ $sale->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-slate-600">{{ ($sale->customer->name ?? 'W')[0] }}</span>
                                </div>
                                <span class="text-sm text-slate-700">{{ $sale->customer->name ?? 'Walk-in Customer' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600">{{ $sale->items->count() }} items</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-800">KES {{ number_format($sale->total, 0) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $paymentColors = [
                                    'cash' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'mpesa' => 'bg-violet-100 text-violet-700 border-violet-200',
                                    'card' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'insurance' => 'bg-amber-100 text-amber-700 border-amber-200',
                                ];
                                $paymentClass = $paymentColors[$sale->payment_method] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $paymentClass }}">
                                {{ ucfirst($sale->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($sale->status === 'completed')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Completed
                            </span>
                            @elseif($sale->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg border border-amber-200">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Pending
                            </span>
                            @elseif($sale->status === 'cancelled')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                Cancelled
                            </span>
                            @else
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200">
                                {{ ucfirst($sale->status) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500">{{ $sale->created_at->format('M d, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('sales.receipt', $sale) }}" 
                                   class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors" 
                                   title="Print Receipt">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"/>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                        <rect x="6" y="14" width="12" height="8"/>
                                    </svg>
                                </a>
                                <a href="{{ route('sales.show', $sale) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="View Details">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600">No Recent Sales</p>
                            <p class="text-xs text-slate-400 mt-1">Sales will appear here once recorded</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    // Sales Chart with Modern Styling
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    
    // Create gradient
    const gradient = salesCtx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(6, 196, 162, 0.25)');
    gradient.addColorStop(1, 'rgba(6, 196, 162, 0.0)');
    
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! $salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D')) !!},
            datasets: [{
                label: 'Sales (KES)',
                data: {!! $salesChart->pluck('total_sales') !!},
                borderColor: '#06c4a2',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#06c4a2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#06c4a2',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'KES ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.04)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        padding: 10,
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        },
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        padding: 10,
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                }
            }
        }
    });
</script>
@endpush