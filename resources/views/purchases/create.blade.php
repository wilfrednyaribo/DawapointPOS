@extends('layouts.app')

@section('title', 'New Purchase')
@section('page-title', 'Record Purchase')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Record New Purchase</h2>
            <p class="text-gray-500 text-sm">Add stock from suppliers and generate batch numbers.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm hover:shadow-md group">
            <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform text-xs"></i>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Supplier & Date -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="font-bold text-gray-800">Supplier Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Supplier -->
                <div class="md:col-span-1">
                    <label class="label">Supplier *</label>
                    <div class="relative">
                        <select name="supplier_id" class="input pl-10 appearance-none" required>
                            <option value="">Select Supplier</option>
                            @foreach(\App\Models\Supplier::orderBy('name')->get() as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- Invoice Number (Auto Generated) -->
                <div>
                    <label class="label">Invoice Number</label>
                    <div class="flex gap-2">
                        <input type="text" name="invoice_number" id="invoiceNumberInput" class="input font-mono bg-gray-50 flex-1" readonly>
                        <button type="button" onclick="generateInvoiceNumber()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-600 transition" title="Generate New">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Date -->
                <div>
                    <label class="label">Purchase Date *</label>
                    <input type="date" name="purchase_date" class="input" value="{{ now()->format('Y-m-d') }}" required>
                </div>
            </div>
        </div>

        <!-- Section 2: Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Purchase Items</h3>
                </div>
                <button type="button" id="addItemBtn" class="text-sm font-semibold text-teal-600 hover:text-teal-700 transition flex items-center gap-1">
                    <i class="fas fa-plus-circle"></i> Add Item
                </button>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="itemsTable">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 text-left w-1/3">Drug</th>
                                <th class="pb-3 text-left w-24">Batch #</th>
                                <th class="pb-3 text-left w-28">Expiry</th>
                                <th class="pb-3 text-right w-24">Qty</th>
                                <th class="pb-3 text-right w-32">Cost Price</th>
                                <th class="pb-3 text-right w-32">Total</th>
                                <th class="pb-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsList">
                            <!-- Items will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-100 p-6 bg-gray-50 flex justify-end">
                <div class="w-full md:w-1/3 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span id="subtotalDisplay" class="font-semibold">Ksh 0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                        <span>Grand Total</span>
                        <span id="grandTotalDisplay" class="text-teal-600">Ksh 0.00</span>
                    </div>
                    <input type="hidden" name="total_amount" id="totalAmountInput">
                </div>
            </div>
        </div>

        <!-- Section 3: Notes & Submit -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <label class="label">Notes</label>
            <textarea name="notes" rows="2" class="input" placeholder="Optional notes about this purchase..."></textarea>
            
            <div class="flex items-center justify-end gap-3 mt-6">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-sm hover:shadow-md border border-red-100 hover:border-red-600">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-check-circle"></i>
                    Save Purchase
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // --- 1. Auto Generate Invoice Number ---
    function generateInvoiceNumber() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        const invoice = `INV-${y}${m}${d}-${random}`;
        
        document.getElementById('invoiceNumberInput').value = invoice;
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', generateInvoiceNumber);


    // --- 2. Dynamic Items Logic ---
    let rowCount = 0;
    const drugsData = @json(\App\Models\Drug::orderBy('name')->get(['id', 'name', 'cost_price']));

    function addItem() {
        rowCount++;
        const tbody = document.getElementById('itemsList');
        
        const drugOptions = drugsData.map(d => `<option value="${d.id}" data-cost="${d.cost_price}">${d.name}</option>`).join('');
        
        const row = `
        <tr class="item-row border-b border-gray-50 hover:bg-gray-50/50">
            <td class="py-3 pr-2">
                <select name="items[${rowCount}][drug_id]" class="input text-sm drug-select" required>
                    <option value="">Select Drug</option>
                    ${drugOptions}
                </select>
            </td>
            <td class="py-3 pr-2">
                <input type="text" name="items[${rowCount}][batch_number]" class="input text-sm font-mono" placeholder="BTN-001" required>
            </td>
            <td class="py-3 pr-2">
                <input type="date" name="items[${rowCount}][expiry_date]" class="input text-sm" required>
            </td>
            <td class="py-3 pr-2">
                <input type="number" name="items[${rowCount}][quantity]" class="input text-sm text-right qty-input" min="1" required>
            </td>
            <td class="py-3 pr-2">
                <input type="number" name="items[${rowCount}][cost_price]" class="input text-sm text-right cost-input" step="0.01" min="0" required>
            </td>
            <td class="py-3 pr-2 text-right font-bold total-cell">0.00</td>
            <td class="py-3 pl-2">
                <button type="button" class="text-red-400 hover:text-red-600 remove-btn">
                    <i class="fas fa-times-circle"></i>
                </button>
            </td>
        </tr>`;
        
        tbody.insertAdjacentHTML('beforeend', row);
    }

    // Event Listeners
    document.getElementById('addItemBtn').addEventListener('click', addItem);
    
    document.getElementById('itemsList').addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

    document.getElementById('itemsList').addEventListener('input', function(e) {
        if (e.target.classList.contains('drug-select')) {
            const cost = e.target.options[e.target.selectedIndex].dataset.cost;
            const row = e.target.closest('tr');
            if (cost) row.querySelector('.cost-input').value = cost;
            calculateTotals();
        }
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('cost-input')) {
            calculateTotals();
        }
    });

    function calculateTotals() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            const lineTotal = qty * cost;
            row.querySelector('.total-cell').textContent = lineTotal.toFixed(2);
            total += lineTotal;
        });
        
        document.getElementById('subtotalDisplay').textContent = 'Ksh ' + total.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = 'Ksh ' + total.toFixed(2);
        document.getElementById('totalAmountInput').value = total;
    }

    // Add first row by default
    addItem();
</script>
@endpush