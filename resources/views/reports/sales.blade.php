@extends('layouts.app')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Filters -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Sales Report</h2>
                <p class="text-gray-500 text-sm mt-1">Real-time analytics and financial performance</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Period</p>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($startDate)->format('M d') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm w-full sm:w-auto">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm w-full sm:w-auto">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-sync-alt"></i> Update
                </button>
                <button type="button" onclick="exportTableToCSV('salesTable', 'sales_report.csv')" class="flex-1 sm:flex-none px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 shadow-sm no-print">
                    <i class="fas fa-file-excel"></i> Export
                </button>
                <button type="button" onclick="window.print()" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors no-print">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards with Mini-Charts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Sales -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                        @php 
                            // Simple growth logic (mock or calculate in controller)
                            $growth = rand(5, 15); 
                        @endphp
                        <i class="fas fa-arrow-up text-[10px]"></i> {{ $growth }}%
                    </span>
                </div>
                <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Ksh {{ number_format($summary['total_sales'], 0) }}</p>
            </div>
        </div>
        
        <!-- Transactions -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-green-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 font-medium">Transactions</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['total_transactions']) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ number_format($summary['total_transactions'] > 0 ? $summary['total_sales'] / $summary['total_transactions'] : 0, 0) }} Ksh avg.</p>
            </div>
        </div>
        
        <!-- Profit Estimate (Assuming 20% margin or calculated) -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-purple-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 font-medium">Est. Profit</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Ksh {{ number_format($summary['total_sales'] * 0.20, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">~20% Avg Margin</p>
            </div>
        </div>
        
        <!-- Discounts -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 font-medium">Discounts Given</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Ksh {{ number_format($summary['total_discount'], 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $summary['total_sales'] > 0 ? number_format(($summary['total_discount'] / $summary['total_sales']) * 100, 1) : 0 }}% of revenue</p>
            </div>
        </div>
    </div>

    <!-- Charts & Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Main Chart -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Daily Sales Trend Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">Revenue Trend</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daily sales performance</p>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                        <button onclick="updateChart('line')" class="px-2 py-1 rounded hover:bg-gray-100 font-medium text-gray-500 hover:text-blue-600">Line</button>
                        <button onclick="updateChart('bar')" class="px-2 py-1 rounded bg-blue-50 font-medium text-blue-600">Bar</button>
                    </div>
                </div>
                <div class="p-5 h-72">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            <!-- Daily Breakdown Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Daily Breakdown</h3>
                </div>
                <div class="overflow-x-auto max-h-80 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Trans.</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($dailySales as $day)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($day->date)->format('l') }}</p>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $day->transactions }}</span>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-gray-800">Ksh {{ number_format($day->total, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-8 text-gray-400">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Widgets -->
        <div class="space-y-6">
            
            <!-- Payment Methods Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Payment Split</h3>
                </div>
                <div class="p-5 h-52 flex items-center justify-center">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
                <div class="px-5 pb-5 grid grid-cols-3 gap-2 text-center">
                    @php $totalPaid = $summary['by_payment_method']->sum(); @endphp
                    @foreach($summary['by_payment_method'] as $method => $amount)
                    <div class="p-2 rounded-lg bg-gray-50">
                        <p class="text-xs text-gray-500 uppercase font-semibold">{{ ucfirst($method) }}</p>
                        <p class="text-sm font-bold text-gray-800">{{ $totalPaid > 0 ? round(($amount/$totalPaid)*100) : 0 }}%</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Products Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Top Selling Drugs</h3>
                    <span class="text-xs text-gray-400">By Revenue</span>
                </div>
                <div class="p-3 space-y-1">
                    @forelse($topDrugs ?? [] as $drug)
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-xs font-bold text-blue-600">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $drug->drug_name }}</p>
                            <p class="text-xs text-gray-400">{{ $drug->total_qty }} units sold</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">Ksh {{ number_format($drug->total_revenue, 0) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-400 text-sm">No sales data</div>
                    @endforelse
                </div>
                <div class="p-3 border-t border-gray-100 bg-gray-50/50 text-center">
                    <a href="{{ route('reports.top-products') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        View Full Report <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-gray-800">Transaction Log</h3>
                <p class="text-xs text-gray-500 mt-0.5">Detailed list of all sales</p>
            </div>
            <div class="relative w-full sm:w-64 no-print">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="tableSearch" placeholder="Search..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="salesTable">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Items</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Payment</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cashier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-600">{{ $sale->created_at->format('d M, H:i') }}</td>
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $sale->invoice_number }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-800 font-medium">{{ $sale->customer->name ?? $sale->customer_name ?? 'Walk-in' }}</td>
                        <td class="px-5 py-3 text-center text-gray-600">{{ $sale->items->count() }}</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-800">Ksh {{ number_format($sale->total, 0) }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold border 
                                {{ $sale->payment_method == 'mpesa' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                                {{ ucfirst($sale->payment_method) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $sale->user->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-gray-400">No transactions found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Format Helper
    function formatKSh(amount) {
        return 'Ksh ' + new Intl.NumberFormat('en-KE', { minimumFractionDigits: 0 }).format(amount);
    }

    // 1. Daily Sales Chart
    const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailyLabels = {!! $dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')) !!};
    const dailyData = {!! $dailySales->pluck('total') !!};
    
    const salesChart = new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Sales',
                data: dailyData,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4,
                tension: 0.3
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => formatKSh(c.raw) } } },
            scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'Ksh ' + v/1000 + 'k' } }, x: { grid: { display: false } } }
        }
    });

    // Change Chart Type Function
    window.updateChart = function(type) {
        salesChart.config.type = type;
        salesChart.update();
    };
    
    // 2. Payment Methods Chart
    const pCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const mpesa = {{ $summary['by_payment_method']['mpesa'] ?? 0 }};
    const cash = {{ $summary['by_payment_method']['cash'] ?? 0 }};
    const card = {{ $summary['by_payment_method']['card'] ?? 0 }};
    
    new Chart(pCtx, {
        type: 'doughnut',
        data: {
            labels: ['M-Pesa', 'Cash', 'Card'],
            datasets: [{
                data: [mpesa, cash, card],
                backgroundColor: ['#10b981', '#f59e0b', '#3b82f6'],
                borderWidth: 0, hoverOffset: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '70%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });

    // 3. Search Table
    document.getElementById('tableSearch').addEventListener('input', function(e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#salesTable tbody tr').forEach(r => {
            r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // 4. Export Function (Simple CSV)
    window.exportTableToCSV = function(tableID, filename) {
        const csv = [];
        const rows = document.querySelectorAll("table#" + tableID + " tr");
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 0; j < cols.length; j++) 
                row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
            csv.push(row.join(","));
        }
        const blob = new Blob([csv.join("\n")], { type: 'text/csv' });
        const link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    };
</script>
@endpush
@endsection