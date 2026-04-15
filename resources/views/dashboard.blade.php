@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <!-- Modern Styling & Animations -->
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            height: 6px;
            border-radius: 999px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 1s ease-in-out;
        }

        /* Animated background blob */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.3;
            animation: blob 7s infinite;
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        /* Custom Dashboard Button Styles */
        .dash-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dash-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Clickable Card Style */
        .clickable-card {
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .clickable-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Quote Animation Styles */
        .quote-container {
            min-height: 24px;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .quote-text {
            display: inline-block;
            white-space: nowrap;
            will-change: transform;
        }

        @keyframes slideLoop {
            0% { transform: translateX(100%); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateX(-100%); opacity: 0; }
        }

        .slide-active {
            animation: slideLoop 10s linear forwards;
        }
    </style>

    <div class="relative space-y-8 overflow-hidden">
        <!-- Decorative Blobs -->
        <div class="blob top-0 -left-20 w-96 h-96 bg-primary-200 z-0"></div>
        <div class="blob top-40 -right-20 w-80 h-80 bg-blue-200 z-0" style="animation-delay: 2s;"></div>

        <!-- Welcome Header -->
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Hi {{ auth()->user()->name }}, welcome back!
                </h1>
                <!-- Dynamic Quote Display -->
                <div class="quote-container mt-1 flex items-center">
                    <p id="quoteText" class="quote-text text-sm font-medium"></p>
                </div>
            </div>

            <!-- Protected Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                
                @can('access_pos')
                <a href="{{ route('pos.create') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-orange-100 text-orange-700 hover:bg-orange-200 shadow-orange-200/50 border border-orange-200">
                    <i class="fas fa-cash-register mr-1"></i> New Sale
                </a>
                @endcan

                @can('view_purchases')
                <a href="{{ route('purchases.create') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 shadow-blue-600/20">
                    <i class="fas fa-truck-ramp-box mr-1"></i> New Purchase
                </a>

                <a href="{{ route('purchases.index') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-sky-100 text-sky-700 hover:bg-sky-200 shadow-sky-200/50 border border-sky-200">
                    <i class="fas fa-history mr-1"></i> Purchases
                </a>
                @endcan

                @can('view_drugs')
                <a href="{{ route('drugs.create') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">
                    <i class="fas fa-plus mr-1"></i> Add Drug
                </a>
                @endcan

                @can('view_sales')
                <a href="{{ route('sales.index') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">
                    <i class="fas fa-calendar-day mr-1"></i> Today's Sales
                </a>
                @endcan

                @can('view_reports')
                <a href="{{ route('reports.index') }}"
                    class="dash-btn text-xs px-3 py-1.5 bg-purple-600 text-white hover:bg-purple-700 shadow-purple-600/20">
                    <i class="fas fa-chart-line mr-1"></i> Reports
                </a>
                @endcan

            </div>
        </div>

        <!-- Stats Cards -->
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @can('view_reports')
            <!-- Revenue Card -->
            <div class="glass-card rounded-2xl p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Today's Revenue</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2 tracking-tight">Ksh {{ number_format($todaySales, 0) }}</p>
                        <div class="flex items-center gap-1 mt-2 text-sm text-primary-600">
                            <i class="fas fa-arrow-up text-xs"></i>
                            <span>{{ $todayTransactions }} transactions</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-primary-400 to-primary-600 text-white">
                        <i class="fas fa-sack-dollar text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Card -->
            <div class="glass-card rounded-2xl p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Monthly Revenue</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2 tracking-tight">Ksh {{ number_format($monthlySales, 0) }}</p>
                        <div class="flex items-center gap-1 mt-2 text-sm text-blue-600">
                            <i class="fas fa-calendar-alt text-xs"></i>
                            <span>{{ $monthlyTransactions }} this month</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="glass-card rounded-2xl p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Today's Profit</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2 tracking-tight">Ksh {{ number_format($todayProfit, 0) }}</p>
                        <div class="flex items-center gap-1 mt-2 text-sm text-emerald-600">
                            <i class="fas fa-trending-up text-xs"></i>
                            <span>Net Income</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-emerald-400 to-emerald-600 text-white">
                        <i class="fas fa-hand-holding-dollar text-2xl"></i>
                    </div>
                </div>
            </div>
            @endcan

            @can('view_drugs')
            <!-- Low Stock Card -->
            <a href="{{ route('drugs.index', ['stock_filter' => 'low']) }}"
                class="glass-card rounded-2xl p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 border-l-4 border-amber-400 clickable-card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Low Stock</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2 tracking-tight">{{ $lowStockDrugs->count() }}</p>
                        <div class="flex items-center gap-1 mt-2 text-sm text-amber-600">
                            <i class="fas fa-boxes-stacked text-xs"></i>
                            <span>Click to view items</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-amber-400 to-orange-500 text-white animate-pulse">
                        <i class="fas fa-triangle-exclamation text-2xl"></i>
                    </div>
                </div>
            </a>
            @endcan

        </div>

        <!-- Main Content Grid -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8">

            @can('view_reports')
            <!-- Sales Chart -->
            <div class="lg:col-span-2 glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Revenue Analytics</h3>
                        <p class="text-sm text-gray-500">Sales performance over the last 7 days</p>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg text-sm font-medium text-gray-600">
                        <span class="w-2 h-2 rounded-full bg-primary-500"></span> Sales
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="salesChart" height="140"></canvas>
                </div>
            </div>
            @endcan

            @can('view_reports')
            <!-- Top Products -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Top Performing Drugs</h3>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($topDrugs as $index => $drug)
                        @php $maxSold = $topDrugs->max('total_sold'); @endphp
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center 
                                @if ($index == 0) bg-amber-100 text-amber-600 @elseif($index == 1) bg-gray-200 text-gray-600 @elseif($index == 2) bg-orange-100 text-orange-600 @else bg-gray-100 text-gray-500 @endif">
                                <span class="font-extrabold">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ $drug->name }}</p>
                                <div class="progress-bar mt-1">
                                    <div class="progress-fill bg-gradient-to-r from-primary-400 to-primary-600"
                                        style="width: {{ ($drug->total_sold / $maxSold) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $drug->total_sold ?? 0 }}</p>
                                <p class="text-xs text-gray-400">units</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <i class="fas fa-chart-simple text-4xl mb-2"></i>
                            <p>No sales data yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endcan

        </div>

        <!-- Bottom Section -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8">

            @can('view_drugs')
            <!-- Expiring Soon -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden border-t-4 border-orange-400">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Expiry Alerts</h3>
                    </div>
                    <span class="badge bg-orange-100 text-orange-700">{{ $expiringBatches->count() }} Batches</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                    @forelse($expiringBatches as $batch)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-orange-50 flex items-center justify-center text-orange-400">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $batch->drug->name }}</p>
                                    <p class="text-xs text-gray-400">Batch #{{ $batch->batch_number }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-orange-600">{{ $batch->expiry_date->format('M d') }}</p>
                                <p class="text-xs text-gray-400">{{ $batch->days_until_expiry }}d left</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400">
                            <i class="fas fa-shield-check text-4xl mb-2 text-green-400"></i>
                            <p>No items expiring soon</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock List -->
            <div class="glass-card rounded-2xl shadow-xl overflow-hidden border-t-4 border-red-400">
                <a href="{{ route('drugs.index', ['stock_filter' => 'low']) }}"
                    class="block p-5 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Inventory Alerts</h3>
                                <p class="text-xs text-gray-400">Click to view all low stock items</p>
                            </div>
                        </div>
                        <span class="badge bg-red-100 text-red-700">{{ $lowStockDrugs->count() }} Items</span>
                    </div>
                </a>
                <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                    @forelse($lowStockDrugs as $drug)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-red-50 flex items-center justify-center text-red-400">
                                    <i class="fas fa-capsules"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $drug->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $drug->category->name ?? 'General' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold @if ($drug->quantity_in_stock <= 0) text-red-600 @else text-amber-600 @endif">
                                    {{ $drug->quantity_in_stock }} {{ $drug->unit }}
                                </p>
                                <p class="text-xs text-gray-400">Limit: {{ $drug->reorder_level }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400">
                            <i class="fas fa-check-double text-4xl mb-2 text-green-400"></i>
                            <p>Stock levels healthy</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endcan

        </div>

        @can('view_sales')
        <!-- Recent Sales Table -->
        <div class="relative z-10 glass-card rounded-2xl shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-900 text-white hover:bg-blue-950 rounded-lg text-sm font-bold transition-all duration-300 shadow-sm hover:shadow-md group">
                    <span>View All</span>
                    <i class="fas fa-arrow-right text-xs transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">Invoice</th>
                            <th class="px-6 py-4 text-left">Customer</th>
                            <th class="px-6 py-4 text-center">Items</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-center">Payment</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-gray-800">{{ $sale->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs font-bold">
                                            {{ ($sale->customer_name ?? ($sale->customer->name ?? 'W'))[0] }}
                                        </div>
                                        <span>
                                            {{ $sale->customer_name ?? ($sale->customer->name ?? 'Walk-in') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium">{{ $sale->items->count() }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-gray-800">Ksh {{ number_format($sale->total, 0) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="badge bg-gray-100 text-gray-700">{{ ucfirst($sale->payment_method) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($sale->status === 'completed')
                                        <span class="inline-flex items-center gap-1 badge bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle text-xs"></i> Paid
                                        </span>
                                    @else
                                        <span class="badge bg-yellow-100 text-yellow-700">{{ ucfirst($sale->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $sale->created_at->format('M d, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <i class="fas fa-receipt text-4xl mb-2"></i><br>No recent sales
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan

    </div>
@endsection

@push('scripts')
    @can('view_reports')
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Gradient for chart background
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(22, 163, 74, 0.2)');
        gradient.addColorStop(1, 'rgba(22, 163, 74, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('D')) !!},
                datasets: [{
                    label: 'Sales',
                    data: {!! $salesChart->pluck('total_sales') !!},
                    borderColor: '#16a34a',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#16a34a',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return 'Ksh ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                        ticks: {
                            callback: function(value) { return 'Ksh ' + value.toLocaleString(); }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endcan

    <script>
        // 5 Medical Quotes
        const quotes = [
            "The greatest wealth is health. - Virgil",
            "Let food be thy medicine. - Hippocrates",
            "Cure sometimes, treat often, comfort always. - Hippocrates",
            "The best doctor gives the least medicines. - Benjamin Franklin",
            "Medicine heals doubts as well as diseases. - John Ray"
        ];

        const colors = [
            'text-teal-600', 'text-blue-600', 'text-purple-600', 'text-indigo-600',
            'text-pink-600', 'text-red-500', 'text-orange-600', 'text-green-600'
        ];

        let currentIndex = 0;
        const quoteElement = document.getElementById('quoteText');

        function colorizeText(text) {
            return text.split('').map(char => {
                if (char === ' ') return ' ';
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                return `<span class="${randomColor}">${char}</span>`;
            }).join('');
        }

        function updateQuote() {
            quoteElement.style.opacity = 0;
            setTimeout(() => {
                quoteElement.innerHTML = colorizeText(quotes[currentIndex]);
                quoteElement.classList.remove('slide-active');
                void quoteElement.offsetWidth; // Force reflow
                quoteElement.classList.add('slide-active');
                quoteElement.style.opacity = 1;
                currentIndex = (currentIndex + 1) % quotes.length;
            }, 500);
        }

        updateQuote();
        setInterval(updateQuote, 15000);
    </script>
@endpush