<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    
    <div class="max-w-md mx-auto">
        
        <!-- Print Button -->
        <div class="mb-4 flex items-center justify-between no-print">
            <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Sales
            </a>
            <button onclick="window.print()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-print mr-2"></i>Print Receipt
            </button>
        </div>
        
        <!-- Receipt -->
        <div class="bg-white rounded-lg shadow-lg p-6" id="receipt">
            
            <!-- Header -->
            <div class="text-center border-b border-dashed border-gray-300 pb-4 mb-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-prescription-bottle-medical text-primary-600 text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Medprime-Pharmacy</h1>
                <p class="text-sm text-gray-500">Your Trusted Pharmacy</p>
                <p class="text-xs text-gray-400 mt-1">Moi Avenue, Nairobi</p>
                <p class="text-xs text-gray-400">Tel: +254 741 473 024</p>
            </div>
            
            <!-- Invoice Info -->
            <div class="mb-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Invoice:</span>
                    <span class="font-semibold">{{ $sale->invoice_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date:</span>
                    <span>{{ $sale->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Cashier:</span>
                    <span>{{ $sale->user->name }}</span>
                </div>

                <!-- UPDATED: Customer Logic -->
                @if($sale->customer_name || $sale->customer)
                <div class="flex justify-between">
                    <span class="text-gray-500">Customer:</span>
                    <span class="font-medium text-gray-800">
                        {{-- Prefer the walk-in name, fallback to registered customer --}}
                        {{ $sale->customer_name ?? $sale->customer->name }}
                    </span>
                </div>
                @endif

                @if($sale->prescription_number)
                <div class="flex justify-between">
                    <span class="text-gray-500">Rx No:</span>
                    <span>{{ $sale->prescription_number }}</span>
                </div>
                @endif
            </div>
            
            <!-- Items -->
            <div class="border-t border-b border-dashed border-gray-300 py-4 mb-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="text-left font-medium">Item</th>
                            <th class="text-center font-medium">Qty</th>
                            <th class="text-right font-medium">Price</th>
                            <th class="text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="py-2">
                                <span class="block font-medium">{{ $item->drug_name }}</span>
                                @if($item->batch_number)
                                <span class="text-xs text-gray-400">Batch: {{ $item->batch_number }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</span></td>
                            <td class="text-right font-medium">{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Totals -->
            <div class="space-y-1 text-sm mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal:</span>
                    <span>Ksh {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->discount > 0)
                <div class="flex justify-between text-red-600">
                    <span>Discount:</span>
                    <span>-Ksh {{ number_format($sale->discount, 2) }}</span>
                </div>
                @endif
                @if($sale->tax > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Tax:</span>
                    <span>Ksh {{ number_format($sale->tax, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2 mt-2">
                    <span>Total:</span>
                    <span>Ksh {{ number_format($sale->total, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Paid ({{ ucfirst($sale->payment_method) }}):</span>
                    <span>Ksh {{ number_format($sale->amount_paid, 2) }}</span>
                </div>
                @if($sale->change_given > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Change:</span>
                    <!-- FIXED: Changed TZS to Ksh -->
                    <span>Ksh {{ number_format($sale->change_given, 2) }}</span>
                </div>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="text-center text-xs text-gray-400 border-t border-dashed border-gray-300 pt-4">
                <p class="mb-2">Thank you for your purchase!</p>
                <p>Keep medicines out of reach of children</p>
                <p>Store in a cool, dry place</p>
                <p class="mt-2">For any queries, please retain this receipt</p>
            </div>
            
            <!-- Barcode simulation -->
            <div class="mt-4 flex justify-center">
                <div class="bg-gray-800 text-white text-xs px-4 py-1 rounded">
                    {{ $sale->invoice_number }}
                </div>
            </div>
            
        </div>
        
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</body>
</html>