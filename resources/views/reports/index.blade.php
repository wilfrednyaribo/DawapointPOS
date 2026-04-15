@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports Center')

@section('content')
<!-- Modal Container (Hidden by default) -->
<div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>

        <!-- Modal Panel -->
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="modal-title">Export Custom Report</h3>
                    <p class="text-sm text-gray-500">Select date range and format to download your report.</p>
                </div>
                <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Export Form -->
            <form action="{{ route('reports.export') }}" method="GET" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Report Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 text-sm">
                        <option value="sales">Sales Transactions</option>
                        <option value="inventory">Current Inventory</option>
                        <option value="profit">Profit & Loss</option>
                        <option value="expiry">Expiry Analysis</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Format</label>
                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="format" value="csv" class="sr-only peer" checked>
                            <div class="px-4 py-3 border-2 rounded-xl text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <i class="fas fa-file-csv text-green-600 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-gray-700">CSV</p>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="format" value="pdf" class="sr-only peer">
                            <div class="px-4 py-3 border-2 rounded-xl text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <i class="fas fa-file-pdf text-red-600 text-lg mb-1"></i>
                                <p class="text-xs font-semibold text-gray-700">PDF</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i>
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="space-y-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    
    <!-- Hero Section (Light Grey) -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6"
         :class="{ 'opacity-0 translate-y-4': !loaded }"
         style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Reports Center</h2>
                <p class="text-gray-500 text-sm mt-1">Comprehensive analytics and insights to drive smarter business decisions.</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex gap-4 flex-shrink-0">
                <div class="text-center px-6 py-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <p class="text-2xl font-bold text-gray-800">6</p>
                    <p class="text-xs text-gray-500 mt-1">Report Types</p>
                </div>
                <div class="text-center px-6 py-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <p class="text-2xl font-bold text-blue-600">Live</p>
                    <p class="text-xs text-gray-500 mt-1">Data Sync</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        
        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" 
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 hover:border-blue-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 100ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-blue-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <span class="text-xs font-semibold text-blue-600">View</span>
                        <svg class="w-3 h-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-blue-700 transition-colors">Sales Report</h3>
                <p class="text-sm text-slate-500 leading-relaxed">View daily, weekly, and monthly sales analytics with detailed breakdowns.</p>
                
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex -space-x-1">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400">Charts & Tables</span>
                </div>
            </div>
        </a>
        
        <!-- Inventory Report -->
        <a href="{{ route('reports.inventory') }}" 
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1 hover:border-emerald-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 150ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-emerald-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-emerald-700 transition-colors">Inventory Report</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Complete stock overview with valuations, quantities, and category breakdowns.</p>
                
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Stock Valuation</span>
                </div>
            </div>
        </a>
        
        <!-- Expiry Report -->
        <a href="{{ route('reports.expiry') }}" 
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1 hover:border-orange-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 200ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-orange-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-orange-700 transition-colors">Expiry Report</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Track expiring and expired medications for proactive inventory management.</p>
                
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <div class="px-2 py-0.5 bg-orange-100 rounded text-[10px] font-semibold text-orange-600">FEFO</div>
                    <span class="text-xs text-slate-400">First Expiry First Out</span>
                </div>
            </div>
        </a>
        
        <!-- Top Products -->
        <a href="{{ route('reports.top-products') }}" 
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-violet-500/10 hover:-translate-y-1 hover:border-violet-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 250ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-violet-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-violet-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-violet-700 transition-colors">Top Products</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Best-selling medications and products ranked by revenue and quantity sold.</p>
                
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Best Performers</span>
                </div>
            </div>
        </a>
        
        <!-- Profit Report -->
        <a href="{{ route('reports.profit') }}" 
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-amber-500/10 hover:-translate-y-1 hover:border-amber-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 300ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-amber-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-amber-700 transition-colors">Profit Analysis</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Detailed profit margins, costs, and revenue analysis trends over time.</p>
                
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Margin Insights</span>
                </div>
            </div>
        </a>
        
        <!-- Custom Export (Updated) -->
        <a href="javascript:void(0)" 
           onclick="openModal()"
           class="group relative bg-white rounded-2xl border border-slate-200/50 p-6 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 hover:border-indigo-200"
           :class="{ 'opacity-0 translate-y-4': !loaded }"
           style="transition-delay: 350ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-indigo-100/50 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 px-2 py-1 bg-indigo-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <span class="text-xs font-semibold text-indigo-600">Export</span>
                        <svg class="w-3 h-3 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-indigo-700 transition-colors">Custom Reports</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Build and export personalized reports tailored to your specific business needs.</p>
                
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <span class="text-xs">CSV</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span class="text-xs">PDF</span>
                    </div>
                </div>
            </div>
        </a>
        
    </div>
    
    <!-- Quick Actions Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/50"
         :class="{ 'opacity-0 translate-y-4': !loaded }"
         style="transition-delay: 400ms;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-slate-200">
                <svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-700">Scheduled Reports</p>
                <p class="text-xs text-slate-500">Auto-generate reports daily, weekly, or monthly</p>
            </div>
        </div>
        <button onclick="openModal()" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:border-indigo-300 hover:text-indigo-600 hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Quick Export</span>
        </button>
    </div>
    
</div>

<script>
    function openModal() {
        document.getElementById('exportModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeModal() {
        document.getElementById('exportModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Allow scrolling again
    }
</script>
@endsection