@extends('layouts.app')

@section('title', 'Top Products Report')
@section('page-title', 'Top Performing Products')

@section('content')
<div class="space-y-6" x-data="{ showChart: true }">
    
    <!-- Header -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Product Leaderboard</h2>
                <p class="text-gray-500 text-sm mt-1">Best-selling drugs ranked by revenue and quantity.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a href="{{ route('reports.sales') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back To Sales Report</span>
                </a>

                <!-- Period Filter -->
                <form method="GET" class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Period</label>
                        <select name="period" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                            <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('period') === 'month' || !request('period') ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ request('period') === 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                        Apply
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Items Sold -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Items Sold</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($query->sum('total_quantity')) }}</p>
                </div>
            </div>
        </div>
        <!-- Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Ksh {{ number_format($query->sum('total_revenue'), 0) }}</p>
                </div>
            </div>
        </div>
        <!-- Products -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Unique Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $query->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800">Revenue Distribution</h3>
                <p class="text-xs text-gray-500 mt-0.5">Top 5 products by revenue</p>
            </div>
            <button @click="showChart = !showChart" class="text-xs font-medium text-gray-500 hover:text-blue-600 no-print">
                <i class="fas fa-sync-alt mr-1"></i> Toggle Chart
            </button>
        </div>
        <div class="p-6 h-72" x-show="showChart" x-transition>
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>

    <!-- Product List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Times Sold</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty Sold</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider no-print">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($query as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            @if ($index < 3)
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm
                                {{ $index == 0 ? 'bg-gradient-to-br from-amber-400 to-yellow-500' : ($index == 1 ? 'bg-gradient-to-br from-slate-300 to-slate-400' : 'bg-gradient-to-br from-orange-300 to-orange-500') }}">
                                @if ($index == 0)<i class="fas fa-crown text-white"></i> @else {{ $index + 1 }} @endif
                            </div>
                            @else
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 font-bold text-sm bg-gray-100 border border-gray-200">
                                {{ $index + 1 }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $item->drug_name }}</p>
                            <p class="text-xs text-gray-400">ID: #{{ $item->drug_id }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $item->times_sold }} sales
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-gray-700">{{ $item->total_quantity }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-800">Ksh {{ number_format($item->total_revenue, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 no-print">
                            <div class="w-32">
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    @php $maxRevenue = $query->max('total_revenue'); @endphp
                                    <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-500 group-hover:opacity-100 opacity-60" 
                                         style="width: {{ $maxRevenue > 0 ? ($item->total_revenue / $maxRevenue) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-chart-simple text-5xl mb-4 text-gray-200"></i>
                                <p class="font-medium text-gray-600">No sales data found</p>
                                <p class="text-sm text-gray-400 mt-1">Try selecting a different period.</p>
                            </div>
                        </td>
                    </tr>
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

    // Top Products Chart
    const ctx = document.getElementById('topProductsChart');
    if (ctx) {
        // Prepare data from Laravel
        const labels = {!! $query->take(5)->pluck('drug_name') !!};
        const data = {!! $query->take(5)->pluck('total_revenue') !!};

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: data,
                    backgroundColor: [
                        'rgba(251, 191, 36, 0.7)',  // Amber for #1
                        'rgba(148, 163, 184, 0.7)', // Slate for #2
                        'rgba(251, 146, 60, 0.7)',  // Orange for #3
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(59, 130, 246, 0.3)'
                    ],
                    borderColor: [
                        'rgba(251, 191, 36, 1)',
                        'rgba(148, 163, 184, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8,
                    indexAxis: 'y', // Horizontal Bar Chart
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatKSh(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.03)' },
                        ticks: {
                            callback: function(value) {
                                return 'Ksh ' + value/1000 + 'k';
                            }
                        }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection