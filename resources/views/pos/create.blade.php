
@extends('layouts.app')

@section('title', 'Point of Sale')
@section('page-title', 'New Sale')

@section('content')
<div class="pos-system-wrapper bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="mb-6 animate-slide-up" style="animation-delay: 0.05s;">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-11 h-11 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white shadow-lg shadow-teal-200">
                        <i class="fas fa-cash-register text-lg"></i>
                    </span>
                    <div>
                        Point of Sale
                        <p class="text-sm font-normal text-gray-500 mt-0.5">Process sales and manage inventory</p>
                    </div>
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-white px-5 py-3 rounded-xl border border-gray-100 shadow-sm text-sm font-medium text-gray-600">
                    <i class="fas fa-calendar-day text-teal-500"></i>
                    <span>{{ now()->format('d M, Y') }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_450px] gap-6 items-start">
        
        <!-- Left Column: Products -->
        <div class="products-section space-y-4">
            <!-- Search Card -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search by name or scan barcode..." 
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition focus:bg-white">
                    </div>
                    <select id="categoryFilter" class="w-full sm:w-48 px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium text-gray-600 focus:ring-2 focus:ring-teal-500 appearance-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Product List -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-slide-up" style="animation-delay: 0.15s;">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left p-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Product</th>
                                <th class="text-center p-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-32">Stock</th>
                                <th class="text-right p-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-32">Price</th>
                                <th class="p-4 w-28 text-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50" id="productsList">
                            @foreach($drugs as $drug)
                                @php
                                    $reorderLevel = $drug->reorder_level ?? 20;
                                    $isLowStock = $drug->quantity_in_stock > 0 && $drug->quantity_in_stock <= $reorderLevel;
                                    $isOutOfStock = $drug->quantity_in_stock <= 0;
                                    $isAlert = $isLowStock || $isOutOfStock;
                                @endphp
                                
                                <tr class="product-row group transition-all {{ $isAlert ? 'alert-row' : '' }} {{ $isOutOfStock ? 'out-of-stock-row' : '' }}" 
                                    data-id="{{ $drug->id }}"
                                    data-name="{{ $drug->name }}"
                                    data-price="{{ $drug->selling_price }}"
                                    data-stock="{{ $drug->quantity_in_stock }}"
                                    data-category="{{ $drug->category_id }}">
                                    
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl {{ $isAlert ? 'bg-red-50 text-red-400' : 'bg-teal-50 text-teal-500' }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                                                <i class="fas fa-pills"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $drug->name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $drug->strength ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-4 text-center">
                                        @if($isOutOfStock)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full border border-red-200">
                                                <i class="fas fa-ban text-[10px]"></i> Out
                                            </span>
                                        @elseif($isLowStock)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-100">
                                                <i class="fas fa-arrow-down text-[10px]"></i> {{ $drug->quantity_in_stock }}
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                                {{ $drug->quantity_in_stock }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right">
                                        <span class="font-bold text-gray-900">Ksh {{ number_format($drug->selling_price, 0) }}</span>
                                    </td>

                                    <td class="p-4 text-center">
                                        <button class="add-btn {{ $isOutOfStock ? 'disabled-btn' : '' }}" 
                                                {{ $isOutOfStock ? 'disabled' : '' }}>
                                            <span class="add-icon">&#43;</span>
                                            <span class="add-text">Add</span>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Cart -->
        <div class="cart-container bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden flex flex-col animate-slide-up" style="animation-delay: 0.2s;">
            
            <!-- Cart Header -->
            <div class="p-5 border-b border-gray-50 bg-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white">
                             <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-800 text-lg">Current Sale</h2>
                            <p class="text-xs text-gray-400"><span id="cartCount">0</span> items selected</p>
                        </div>
                    </div>
                    <button id="clearCartBtn" class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <!-- NEW: Customer Info Section -->
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Customer</label>
                    <button type="button" id="toggleCustomerBtn" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition">
                        <i class="fas fa-user-plus mr-1"></i> Add Customer
                    </button>
                </div>
                
                <!-- Default: Walk-in Display -->
                <div id="customerDisplay" class="flex items-center gap-2 text-sm font-medium text-gray-700 bg-white p-3 rounded-lg border border-gray-100">
                    <i class="fas fa-user text-gray-400"></i>
                    <span>Walk-in Customer</span>
                </div>

                <!-- Toggled: Customer Input -->
                <div id="customerInputWrapper" class="hidden">
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <input type="text" id="customerNameInput" placeholder="Enter customer name..." 
                               class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="p-4 bg-white flex-1 overflow-y-auto" id="cartItems">
                <div class="empty-cart flex flex-col items-center justify-center py-8 text-center" id="emptyCart">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 ring-4 ring-gray-100">
                        <i class="fas fa-receipt text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-400">No items selected</p>
                    <p class="text-xs text-gray-300 mt-1">Click products to add</p>
                </div>
                <div id="cartItemsList" class="space-y-1"></div>
            </div>

            <!-- Cart Footer -->
            <div class="border-t border-gray-100 p-5 bg-gray-50/50">
                <div class="space-y-3 mb-5">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium" id="subtotal">Ksh 0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-400">
                        <span>Discount</span>
                        <span class="font-medium">- <span id="discount">Ksh 0.00</span></span>
                    </div>
                    <div class="pt-4 border-t border-dashed border-gray-200 flex justify-between items-end">
                        <span class="text-gray-500 font-medium">Total</span>
                        <span class="text-3xl font-bold text-gray-900" id="grandTotal">Ksh 0.00</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Payment Method</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" class="payment-btn active" data-method="cash">
                            <i class="fas fa-money-bill mr-1"></i> Cash
                        </button>
                        <button type="button" class="payment-btn" data-method="mpesa">
                            <i class="fas fa-mobile-alt mr-1"></i> M-Pesa
                        </button>
                        <button type="button" class="payment-btn" data-method="card">
                            <i class="fas fa-credit-card mr-1"></i> Card
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Tendered</label>
                    <input type="number" id="amountTendered" class="amount-input" placeholder="0.00">
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        <button type="button" class="quick-cash" data-amount="500">+500</button>
                        <button type="button" class="quick-cash" data-amount="1000">+1K</button>
                        <button type="button" class="quick-cash" data-amount="2000">+2K</button>
                        <button type="button" class="quick-cash" id="exactBtn">Exact</button>
                    </div>
                </div>

                <div class="change-display mb-5 flex justify-between items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100" id="changeDisplay">
                    <span class="text-sm font-bold text-gray-600">Change</span>
                    <span class="text-2xl font-bold text-teal-600" id="changeDue">Ksh 0.00</span>
                </div>

                <form id="posForm" action="{{ route('pos.store') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="cash">
                    <input type="hidden" name="customer_name" id="customerNameInputHidden" value="">
                    <input type="hidden" name="items" id="itemsInput">
                    <input type="hidden" name="total" id="totalInput">
                    <input type="hidden" name="amount_tendered" id="amountTenderedInput">
                </form>

                <button type="button" id="completeSaleBtn" class="complete-btn w-full py-4 rounded-xl text-white font-bold text-base disabled:opacity-40 shadow-lg transition transform hover:scale-[1.02] active:scale-[0.98]" disabled>
                    <i class="fas fa-check-circle mr-2"></i> Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --accent: #0d9488; --accent-dark: #0f766e; --danger: #ef4444;
    }

    /* Animations */
    @keyframes slideInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }
    .animate-slide-up { animation: slideInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-right { animation: slideInRight 0.3s ease-out forwards; }

    /* Product Rows */
    .product-row { transition: all 0.2s ease; position: relative; }
    .product-row:hover { background-color: #f8fafc; }

    /* Red Banner Styles */
    .alert-row {
        background: linear-gradient(90deg, rgba(254, 226, 226, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
        border-left: 4px solid #fca5a5;
    }
    .out-of-stock-row {
        background: linear-gradient(90deg, rgba(254, 226, 226, 0.4) 0%, rgba(255, 255, 255, 0) 100%);
        border-left: 4px solid #ef4444;
    }
    .alert-row:hover, .out-of-stock-row:hover { background-color: #fef2f2; }

    /* --- Button Styles --- */
    .add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px; 
        border-radius: 9999px; 
        background: linear-gradient(135deg, var(--accent), var(--accent-dark));
        color: #ffffff !important; 
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(13, 148, 136, 0.2);
        white-space: nowrap;
    }

    .add-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 148, 136, 0.3);
        filter: brightness(1.1);
    }

    .add-btn:active { transform: translateY(0); }

    .add-btn .add-icon {
        font-size: 18px; 
        font-weight: 700;
        line-height: 1;
        margin-top: -1px; 
    }

    .add-btn .add-text { font-family: system-ui, -apple-system, sans-serif; }

    /* Disabled State */
    .add-btn:disabled, .add-btn.disabled-btn { 
        background: #e5e7eb !important; 
        color: #a1a1aa !important; 
        cursor: not-allowed; 
        transform: none !important; 
        box-shadow: none; 
        opacity: 0.8;
    }

    /* Cart Fluid Container */
    .cart-container {
        height: auto; 
        position: relative; 
    }

    /* Compact Cart Items */
    .cart-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px; border-radius: 8px; gap: 8px; transition: all 0.2s;
    }
    .cart-item:hover { background: #f9fafb; }

    .qty-btn {
        width: 22px; height: 22px; border-radius: 6px; border: 1px solid #e5e7eb; background: white;
        display: flex; align-items: center; justify-content: center; cursor: pointer; color: #9ca3af;
        transition: all 0.15s; font-size: 9px;
    }
    .qty-btn:hover { background: var(--accent); border-color: var(--accent); color: white; }
    .qty-btn.minus:hover { background: var(--danger); border-color: var(--danger); }

    /* Forms */
    .payment-btn { 
        padding: 10px; border: 2px solid #f3f4f6; background: white; border-radius: 12px; 
        font-size: 12px; font-weight: 600; color: #9ca3af; cursor: pointer; transition: all 0.2s; 
    }
    .payment-btn.active { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25); }
    
    .quick-cash { 
        padding: 10px; background: #f3f4f6; border-radius: 10px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; 
        color: #6b7280; transition: all 0.2s;
    }
    .quick-cash:hover { background: var(--accent); color: white; }
    
    .amount-input { 
        width: 100%; padding: 14px; border: 2px solid #e5e7eb; border-radius: 12px; 
        font-weight: 600; font-size: 18px; text-align: right; transition: all 0.2s; background: #fafafa;
    }
    .amount-input:focus { outline: none; border-color: var(--accent); background: white; }
    
    .complete-btn { background: linear-gradient(135deg, var(--accent), var(--accent-dark)); box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3); }

    /* Responsive */
    @media (max-width: 1024px) {
        .cart-container { position: relative; top: auto; }
    }
</style>
@endsection

@push('scripts')
<script>
    // State
    let cart = [];
    let paymentMethod = 'cash';

    // DOM
    const cartItemsContainer = document.getElementById('cartItemsList');
    const emptyCart = document.getElementById('emptyCart');
    const subtotalEl = document.getElementById('subtotal');
    const grandTotalEl = document.getElementById('grandTotal');
    const amountTenderedInput = document.getElementById('amountTendered');
    const changeDueEl = document.getElementById('changeDue');
    const completeSaleBtn = document.getElementById('completeSaleBtn');
    const posForm = document.getElementById('posForm');

    // Customer Logic
    const toggleCustomerBtn = document.getElementById('toggleCustomerBtn');
    const customerDisplay = document.getElementById('customerDisplay');
    const customerInputWrapper = document.getElementById('customerInputWrapper');
    const customerNameInput = document.getElementById('customerNameInput');
    const customerNameInputHidden = document.getElementById('customerNameInputHidden');

    toggleCustomerBtn.addEventListener('click', function() {
        const isHidden = customerInputWrapper.classList.contains('hidden');
        if (isHidden) {
            customerInputWrapper.classList.remove('hidden');
            customerDisplay.classList.add('hidden');
            customerNameInput.focus();
            toggleCustomerBtn.innerHTML = '<i class="fas fa-user-slash mr-1"></i> Cancel';
        } else {
            customerInputWrapper.classList.add('hidden');
            customerDisplay.classList.remove('hidden');
            customerNameInput.value = '';
            toggleCustomerBtn.innerHTML = '<i class="fas fa-user-plus mr-1"></i> Add Customer';
        }
    });

    // Add to Cart
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.product-row');
            const id = row.dataset.id;
            const name = row.dataset.name;
            const price = parseFloat(row.dataset.price);
            const stock = parseInt(row.dataset.stock);

            if (stock <= 0) return;

            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    this.classList.add('shake');
                    setTimeout(() => this.classList.remove('shake'), 500);
                    return;
                }
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
            }
            
            renderCart();
        });
    });

    // Render
    function renderCart() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cartCount').textContent = totalItems;

        if (cart.length === 0) {
            emptyCart.style.display = 'flex';
            cartItemsContainer.innerHTML = '';
            completeSaleBtn.disabled = true;
            updateTotals();
            return;
        }

        emptyCart.style.display = 'none';
        completeSaleBtn.disabled = false;

        cartItemsContainer.innerHTML = cart.map((item, index) => `
            <div class="cart-item animate-slide-right">
                <div class="flex-1 min-w-0 pr-2">
                    <p class="font-semibold text-sm text-gray-800 truncate">${item.name}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button onclick="updateQty(${index}, -1)" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                    <span class="font-bold text-sm w-4 text-center text-gray-800">${item.quantity}</span>
                    <button onclick="updateQty(${index}, 1)" class="qty-btn"><i class="fas fa-plus"></i></button>
                </div>
                <div class="w-24 text-right flex-shrink-0">
                    <p class="font-bold text-sm text-teal-600">Ksh ${(item.price * item.quantity).toLocaleString()}</p>
                </div>
                <button onclick="removeItem(${index})" class="text-gray-300 hover:text-red-500 p-1 flex-shrink-0 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `).join('');

        updateTotals();
    }

    // Helpers
    window.updateQty = function(index, change) {
        let newQty = cart[index].quantity + change;
        if (newQty > cart[index].stock) { return; }
        if (newQty < 1) { cart.splice(index, 1); } else { cart[index].quantity = newQty; }
        renderCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        subtotalEl.textContent = 'Ksh ' + subtotal.toLocaleString() + '.00';
        grandTotalEl.textContent = 'Ksh ' + subtotal.toLocaleString() + '.00';
        calculateChange();
    }

    function calculateChange() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tendered = parseFloat(amountTenderedInput.value) || 0;
        const change = tendered - subtotal;
        const display = document.getElementById('changeDisplay');

        if (change >= 0) {
            changeDueEl.textContent = 'Ksh ' + change.toLocaleString(undefined, { minimumFractionDigits: 2 });
            display.classList.remove('bg-red-50'); display.classList.add('bg-white');
            changeDueEl.classList.remove('text-red-600'); changeDueEl.classList.add('text-teal-600');
        } else {
            changeDueEl.textContent = 'Ksh ' + Math.abs(change).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' Due';
            display.classList.remove('bg-white'); display.classList.add('bg-red-50');
            changeDueEl.classList.remove('text-teal-600'); changeDueEl.classList.add('text-red-600');
        }
    }

    // Events
    amountTenderedInput.addEventListener('input', calculateChange);

    document.querySelectorAll('.quick-cash[data-amount]').forEach(btn => {
        btn.addEventListener('click', function() {
            const current = parseFloat(amountTenderedInput.value) || 0;
            amountTenderedInput.value = (current + parseFloat(this.dataset.amount)).toFixed(2);
            calculateChange();
        });
    });

    document.getElementById('exactBtn').addEventListener('click', function() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        amountTenderedInput.value = subtotal.toFixed(2);
        calculateChange();
    });

    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            paymentMethod = this.dataset.method;
            document.getElementById('paymentMethodInput').value = paymentMethod;
        });
    });

    document.getElementById('clearCartBtn').addEventListener('click', function() {
        if(confirm('Clear all items?')) { cart = []; renderCart(); amountTenderedInput.value = ''; }
    });

    // Search
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterProducts() {
        const term = searchInput.value.toLowerCase();
        const cat = categoryFilter.value;
        document.querySelectorAll('.product-row').forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const category = row.dataset.category;
            row.style.display = (name.includes(term) && (!cat || category === cat)) ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);

    // Submit
    completeSaleBtn.addEventListener('click', function() {
        if(cart.length === 0) return;
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tendered = parseFloat(amountTenderedInput.value) || 0;
        if(tendered < subtotal) { alert('Insufficient amount'); return; }

        // Set customer name
        const customerName = customerNameInput.value.trim();
        customerNameInputHidden.value = customerName;

        document.getElementById('itemsInput').value = JSON.stringify(cart);
        document.getElementById('totalInput').value = subtotal;
        document.getElementById('amountTenderedInput').value = tendered;
        posForm.submit();
    });

    renderCart();
</script>
@endpush
