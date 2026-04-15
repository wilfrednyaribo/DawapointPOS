
@extends('layouts.app')

@section('title', 'Sale Details')
@section('page-title', 'Sale Details')

@section('content')
<div class="space-y-6">
    
    <!-- Light Grey Header Section -->
    <div class="bg-gray-50 rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8 relative bg-gradient-to-br from-gray-50 to-gray-100">
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Left Side: Title & Invoice -->
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center justify-center">
                            <i class="fas fa-file-invoice-dollar text-2xl text-teal-600"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Sale Detail</h1>
                            <p class="text-gray-500 text-sm">Review transaction details and item breakdown</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Invoice & Actions -->
                <div class="flex flex-col items-start md:items-end gap-3">
                    <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                        <span class="text-xs text-gray-400 block">Invoice Number</span>
                        <span class="text-xl font-mono font-bold tracking-wider text-gray-800">{{ $sale->invoice_number }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-bold transition-all duration-300 shadow-sm hover:shadow-lg">
                            <i class="fas fa-print"></i>
                            <span>Print Receipt</span>
                        </a>
                        <a href="{{ route('sales.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 rounded-lg text-sm font-bold transition-all duration-300 border border-gray-200 text-gray-700">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Meta Grid -->
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-gray-200">
                <div>
                    <span class="text-xs text-gray-400 block mb-1">Date</span>
                    <span class="font-semibold text-gray-800">{{ $sale->created_at->format('M d, Y') }}</span>
                    <span class="text-gray-400 text-sm block">{{ $sale->created_at->format('H:i A') }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block mb-1">Customer</span>
                    <span class="font-semibold text-gray-800">{{ $sale->customer_name ?? ($sale->customer->name ?? 'Walk-in') }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block mb-1">Cashier</span>
                    <span class="font-semibold text-gray-800">{{ $sale->user->name }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block mb-1">Status</span>
                    @if($sale->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Completed
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> {{ ucfirst($sale->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content: Items Table -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Items Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-pills text-sm"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Transaction Items</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full ml-auto">{{ $sale->items->count() }} items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left p-4 text-xs font-bold text-gray-400 uppercase">Product</th>
                                <th class="text-center p-4 text-xs font-bold text-gray-400 uppercase">Batch Info</th>
                                <th class="text-center p-4 text-xs font-bold text-gray-400 uppercase">Qty</th>
                                <th class="text-right p-4 text-xs font-bold text-gray-400 uppercase">Unit Price</th>
                                <th class="text-right p-4 text-xs font-bold text-gray-400 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($sale->items as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-capsules text-xs"></i>
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $item->drug_name }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="inline-flex flex-col items-start">
                                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $item->batch_number }}</span>
                                        @if($item->batch && $item->batch->expiry_date)
                                            @php $isExpired = $item->batch->expiry_date->isPast(); @endphp
                                            <span class="text-xs mt-1 {{ $isExpired ? 'text-red-500' : 'text-gray-400' }}">
                                                Exp: {{ $item->batch->expiry_date->format('M y') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 font-bold text-gray-700">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="p-4 text-right text-gray-500">
                                    Ksh {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="p-4 text-right font-semibold text-gray-800">
                                    Ksh {{ number_format($item->total, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Totals Section -->
                <div class="border-t border-gray-200 p-6 bg-gradient-to-b from-gray-50 to-white space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-700">Ksh {{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    @if($sale->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-500">Discount Applied</span>
                        <span class="text-red-600 font-medium">- Ksh {{ number_format($sale->discount, 2) }}</span>
                    </div>
                    @endif
                    @if($sale->tax > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tax</span>
                        <span class="text-gray-700">Ksh {{ number_format($sale->tax, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold border-t-2 border-gray-200 pt-3 mt-2">
                        <span class="text-gray-800">Grand Total</span>
                        <span class="text-teal-600">Ksh {{ number_format($sale->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Payment Summary Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Payment Summary</h3>
                </div>
                
                <div class="space-y-4">
                    <!-- Payment Method -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            @if($sale->payment_method === 'cash')
                                <i class="fas fa-money-bill-wave text-green-500"></i>
                            @elseif($sale->payment_method === 'mpesa')
                                <i class="fas fa-mobile-alt text-purple-500"></i>
                            @else
                                <i class="fas fa-credit-card text-blue-500"></i>
                            @endif
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($sale->payment_method) }}</span>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Paid</span>
                    </div>
                    
                    <!-- Amounts -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Amount Tendered</span>
                            <span class="font-semibold text-gray-700">Ksh {{ number_format($sale->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Change Given</span>
                            <span class="font-semibold text-teal-600">Ksh {{ number_format($sale->change_given, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Activity Log</h3>
                </div>
                
                <div class="relative border-l-2 border-gray-200 ml-2 space-y-6 pl-6">
                    <!-- Event 1 -->
                    <div class="relative">
                        <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-teal-500 border-4 border-white shadow"></div>
                        <p class="text-xs text-gray-400">{{ $sale->created_at->format('d M, H:i') }}</p>
                        <p class="text-sm font-medium text-gray-700">Sale Created</p>
                        <p class="text-xs text-gray-400 mt-0.5">Recorded by {{ $sale->user->name }}</p>
                    </div>
                    
                    @if($sale->status === 'completed')
                    <!-- Event 2 -->
                    <div class="relative">
                        <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-green-500 border-4 border-white shadow"></div>
                        <p class="text-xs text-gray-400">{{ $sale->created_at->format('d M, H:i') }}</p>
                        <p class="text-sm font-medium text-gray-700">Payment Received</p>
                        <p class="text-xs text-gray-400 mt-0.5">via {{ ucfirst($sale->payment_method) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes Section -->
            @if($sale->notes || $sale->prescription_number)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Additional Info</h3>
                </div>
                <div class="space-y-3 text-sm">
                    @if($sale->prescription_number)
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider mb-1">Prescription No.</span>
                        <span class="text-gray-800 font-mono">{{ $sale->prescription_number }}</span>
                    </div>
                    @endif
                    @if($sale->notes)
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider mb-1">Notes</span>
                        <span class="text-gray-700">{{ $sale->notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
