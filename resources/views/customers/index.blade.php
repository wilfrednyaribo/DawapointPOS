@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customer Visits')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header Section (Light Grey) -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Customer Visits</h2>
                <p class="text-gray-500 text-sm mt-1">Monitor walk-ins and registered customer activity.</p>
            </div>
            <!-- Grouped Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('pos.create') }}" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <i class="fas fa-cash-register"></i>
                    <span>Make New Sale</span>
                </a>
                <a href="{{ route('sales.index') }}" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <i class="fas fa-eye"></i>
                    <span>View All Sales</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Profiles -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Profiles</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $customers->count() }}</p>
                </div>
            </div>
        </div>
        
        <!-- Today's Walk-ins -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Today's Walk-ins</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todaysWalkins ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Returning Customers -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fas fa-redo"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Returning Customers</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $customers->filter(function($c) { 
                            return $c->sales && $c->sales->count() > 1; 
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Customer Table Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Table Header -->
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list-ul text-indigo-500"></i>
                    Customer List
                </h3>
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" placeholder="Search name..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm bg-white"
                        id="searchInput" onkeyup="filterTable()">
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-sm" id="customerTable">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] tracking-widest sticky top-0 border-b border-gray-100 z-10">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Customer Name</th>
                            <th class="px-6 py-4 text-left font-semibold">Type</th>
                            <th class="px-6 py-4 text-left font-semibold">Last Visit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-xs">
                                        {{ $customer->name ? $customer->name[0] : 'W' }}
                                    </div>
                                    <p class="font-medium text-gray-800">{{ $customer->name ?? 'Unknown' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($customer->type == 'Walk-in')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                        Walk-in
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        Registered
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-gray-500 text-xs font-medium">
                                    <i class="fas fa-clock text-gray-300"></i>
                                    <span>{{ $customer->last_visit ? $customer->last_visit->diffForHumans() : 'N/A' }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users-slash text-5xl mb-4 text-gray-200"></i>
                                    <p class="font-medium text-gray-500">No customers found</p>
                                    <p class="text-xs text-gray-400 mt-1">New sales will automatically add walk-ins here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Feed Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit sticky top-24">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-stream text-indigo-500"></i>
                    Recent Activity
                </h3>
                <!-- Moved Link Here -->
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline whitespace-nowrap">
                    View All Sales <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-4 space-y-1 max-h-[480px] overflow-y-auto">
                @php 
                    $recentSales = \App\Models\Sale::latest()->take(6)->get(); 
                @endphp
                
                @foreach($recentSales as $sale)
                <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer relative group">
                    <!-- Timeline Dot -->
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center flex-shrink-0 text-lg text-gray-500">
                        🧾
                    </div>
                    
                    <div class="flex-1 border-l-2 border-gray-100 pl-4 ml-[-22px] py-1 group-hover:border-indigo-200 transition-colors">
                        <p class="text-sm text-gray-800 font-semibold">
                            {{ $sale->customer_name ?? 'Walk-in Customer' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                            Spent <span class="font-bold text-green-600">Ksh {{ number_format($sale->total, 0) }}</span>
                        </p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide font-medium">
                            {{ $sale->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('customerTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            if (cells[0] && cells[0].textContent.toLowerCase().includes(input)) {
                found = true;
            }
            rows[i].style.display = found ? '' : 'none';
        }
    }
</script>
@endpush