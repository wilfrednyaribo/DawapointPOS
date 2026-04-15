
@extends('layouts.app')

@section('title', $drug->name)
@section('page-title', 'Drug Details')

@section('content')
    <div class="space-y-6">

        <!-- Top Navigation -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <!-- UPDATED: Modern Ghost Style Back Button -->
            <a href="{{ route('drugs.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm hover:shadow-md group">
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform text-xs"></i>
                <span>Back to Inventory</span>
            </a>

            <!-- UPDATED: Modern Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Edit Details Button -->
                <a href="{{ route('drugs.edit', $drug) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-sm hover:shadow-md border border-blue-100 hover:border-blue-600">
                    <i class="fas fa-pen text-xs"></i>
                    <span>Edit Details</span>
                </a>

                <!-- Add Stock Button -->
                <button onclick="document.getElementById('add-stock-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i>
                    <span>Add Stock</span>
                </button>
            </div>
        </div>

        <!-- Main Drug Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-slate-50 to-white p-6 border-b border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-pills text-primary-600 text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $drug->name }}</h1>
                            <p class="text-gray-500 text-lg mt-1">{{ $drug->generic_name ?: 'No generic name specified' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                @if ($drug->requires_prescription)
                                    <span class="badge bg-blue-100 text-blue-700 border border-blue-200"><i
                                            class="fas fa-prescription mr-1"></i> Rx Required</span>
                                @endif
                                @if ($drug->is_controlled)
                                    <span class="badge bg-red-100 text-red-700 border border-red-200"><i
                                            class="fas fa-ban mr-1"></i> Controlled</span>
                                @endif
                                @if ($drug->quantity_in_stock <= 0)
                                    <span class="badge bg-red-50 text-red-600 border border-red-200">Out of Stock</span>
                                @elseif($drug->is_low_stock)
                                    <span class="badge bg-amber-50 text-amber-600 border border-amber-200">Low Stock</span>
                                @else
                                    <span class="badge bg-green-50 text-green-600 border border-green-200">In Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-gray-100">
                <!-- Stock -->
                <div class="p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Current Stock</p>
                    <p
                        class="text-3xl font-bold @if ($drug->quantity_in_stock <= 0) text-red-600 @elseif($drug->is_low_stock) text-amber-600 @else text-gray-900 @endif">
                        {{ $drug->quantity_in_stock }}
                    </p>
                    <p class="text-sm text-gray-400">{{ $drug->unit }}</p>
                </div>
                <!-- Selling Price -->
                <div class="p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Selling Price</p>
                    <p class="text-3xl font-bold text-primary-600">Ksh {{ number_format($drug->selling_price) }}</p>
                    <p class="text-sm text-gray-400">Per unit</p>
                </div>
                <!-- Cost Price -->
                <div class="p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Cost Price</p>
                    <p class="text-3xl font-bold text-gray-700">Ksh {{ number_format($drug->cost_price) }}</p>
                    <p class="text-sm text-gray-400">Purchase cost</p>
                </div>
                <!-- Margin -->
                <div class="p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Profit Margin</p>
                    <p class="text-3xl font-bold text-green-600">{{ $drug->profit_margin }}%</p>
                    <p class="text-sm text-gray-400">Markup</p>
                </div>
            </div>
        </div>

        <!-- Detailed Info & Batches Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column: Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-gray-400"></i> Product Details
                </h3>
                <dl class="space-y-4">
                    <div class="flex justify-between items-start">
                        <dt class="text-sm text-gray-500">SKU</dt>
                        <dd class="text-sm font-mono font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded">
                            {{ $drug->sku }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Category</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $drug->category->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Dosage Form</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $drug->dosage_form ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Strength</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $drug->strength ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Supplier</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $drug->supplier->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Storage</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $drug->storage_condition ?? '-' }}</dd>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <dt class="text-sm text-gray-500 mb-1">Description</dt>
                        <dd class="text-sm text-gray-600 italic">{{ $drug->description ?? 'No description available.' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Right Column: Batches -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Batch Tracking</h3>
                    <span class="text-xs text-gray-400 font-medium">{{ $drug->batches->count() }} Batches Found</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Batch No.</th>
                                <th class="px-6 py-3 text-center">Quantity</th>
                                <th class="px-6 py-3 text-center">Expiry</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($drug->batches as $batch)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-800">{{ $batch->batch_number }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-bold text-lg">{{ $batch->quantity_remaining }}</span>
                                        <span class="text-gray-400 text-xs block">{{ $batch->quantity_received }}
                                            received</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600">
                                        {{ $batch->expiry_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($batch->isExpired())
                                            <span class="badge bg-red-100 text-red-700 border-red-200">Expired</span>
                                        @elseif($batch->isExpiringSoon())
                                            <span
                                                class="badge bg-orange-100 text-orange-700 border-orange-200 whitespace-nowrap">
                                                {{ $batch->days_until_expiry }} days left
                                            </span>
                                        @else
                                            <span class="badge bg-green-100 text-green-700 border-green-200">Valid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                        No batch information available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inventory Movements -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Movement History</h3>
            </div>

            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Type</th>
                            <th class="px-6 py-3 text-center">Change</th>
                            <th class="px-6 py-3 text-center">Balance</th>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($drug->inventoryMovements as $movement)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-gray-500 whitespace-nowrap">
                                    {{ $movement->created_at->format('M d, H:i') }}
                                </td>
                                <td class="px-6 py-3">
                                    @if ($movement->type === 'purchase')
                                        <span class="badge bg-green-100 text-green-700">Purchase</span>
                                    @elseif($movement->type === 'sale')
                                        <span class="badge bg-blue-100 text-blue-700">Sale</span>
                                    @elseif($movement->type === 'adjustment')
                                        <span class="badge bg-yellow-100 text-yellow-700">Adjustment</span>
                                    @elseif($movement->type === 'expired')
                                        <span class="badge bg-red-100 text-red-700">Expired</span>
                                    @else
                                        <span class="badge bg-gray-100">{{ ucfirst($movement->type) }}</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-3 text-center font-bold font-mono {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                </td>
                                <td class="px-6 py-3 text-center font-mono text-gray-600">
                                    <span class="text-gray-400 text-xs mr-1">was {{ $movement->quantity_before }}</span>
                                    → {{ $movement->quantity_after }}
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $movement->user->name }}</td>
                                <td class="px-6 py-3 text-gray-400 italic text-xs max-w-xs truncate">
                                    {{ $movement->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    No movements recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Add Stock Modal -->
    <div id="add-stock-modal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden transform transition-all">
            <!-- Updated Modal Header -->
            <div class="relative bg-gradient-to-r from-primary-600 to-emerald-500 p-6">
                <!-- Decorative Background Blob -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl">
                </div>

                <div class="relative flex items-start justify-between gap-4">
                    <!-- Left Side: Title Group -->
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0 border border-white/20">
                            <i class="fas fa-boxes-stacked text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">Add New Stock</h3>
                            <p class="text-sm text-white/70 font-medium mt-0.5">Enter batch details to update inventory</p>
                        </div>
                    </div>

                    <!-- Right Side: Improved Close Button -->
                    <button onclick="document.getElementById('add-stock-modal').classList.add('hidden')"
                        class="w-10 h-10 rounded-full bg-white/10 hover:bg-white hover:text-red-500 text-white flex items-center justify-center transition-all duration-300 border border-white/20 hover:border-white backdrop-blur flex-shrink-0 shadow-sm hover:shadow-lg hover:-translate-y-0.5"
                        title="Close">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('drugs.add-stock', $drug) }}" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="label">Batch Number</label>
                        <input type="text" name="batch_number" class="input font-mono"
                            placeholder="e.g., BTN-2024-001 (Optional)">
                    </div>
                    
                    <!-- NEW: Supplier Dropdown -->
                    <div class="col-span-2 sm:col-span-1">
                        <label class="label">Supplier</label>
                        <select name="supplier_id" class="input">
                            <option value="">Select Supplier</option>
                            @foreach(App\Models\Supplier::orderBy('name')->get() as $supplier)
                                <option value="{{ $supplier->id }}" {{ $drug->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Quantity *</label>
                        <input type="number" name="quantity" class="input font-mono" min="1" required
                            placeholder="0">
                    </div>

                    <div>
                        <label class="label">Purchase Price (Ksh) *</label>
                        <input type="number" name="purchase_price" value="{{ $drug->cost_price }}"
                            class="input font-mono" step="0.01" min="0" required>
                    </div>

                    <div>
                        <label class="label">Expiry Date *</label>
                        <input type="date" name="expiry_date" class="input" required>
                    </div>

                    <div>
                        <label class="label">Manufacturing Date</label>
                        <input type="date" name="manufacturing_date" class="input">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 bg-gray-50 px-6 py-4 -mx-6 -mb-6 mt-6">
                    <button type="button" onclick="document.getElementById('add-stock-modal').classList.add('hidden')"
                        class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-boxes-stacked mr-1"></i> Add to Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
