@extends('layouts.app')

@section('title', 'Drug Inventory')
@section('page-title', 'Drug Inventory')

@section('content')
    <div class="space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Inventory Management</h2>
                <p class="text-gray-500 mt-1">Manage your pharmacy stock, batches, and pricing</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('drugs.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i>
                    <span>Add New Drug</span>
                </a>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form method="GET" class="flex flex-col md:flex-row flex-wrap gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, SKU, or barcode..." class="input pl-10 w-full">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <select name="category" class="input w-full md:w-48">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="stock_filter" class="input w-full md:w-44">
                    <option value="">All Status</option>
                    <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>⚠️ Low Stock</option>
                    <option value="out" {{ request('stock_filter') === 'out' ? 'selected' : '' }}>🚫 Out of Stock
                    </option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="btn bg-gray-800 text-white hover:bg-gray-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('drugs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Modern Table View -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-gray-800">Drug List</h3>
                <span class="text-sm text-gray-500 font-medium">
                    Showing {{ $drugs->firstItem() ?? 0 }} - {{ $drugs->lastItem() ?? 0 }} of {{ $drugs->total() }} results
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-[10px] text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">Product Info</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3 text-center">Stock Status</th>
                            <th class="px-4 py-3 text-center">Reorder Level</th>
                            <th class="px-4 py-3 text-right">Cost Price</th>
                            <th class="px-4 py-3 text-right">Selling Price</th>
                            <th class="px-4 py-3 text-center">Margin</th>
                            <!-- Increased width slightly for buttons -->
                            <th class="px-4 py-3 text-center w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($drugs as $drug)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <!-- Product Info -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-pills text-teal-500"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $drug->name }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $drug->sku }}</span>
                                                @if ($drug->requires_prescription)
                                                    <span class="badge bg-blue-100 text-blue-700 text-[9px] px-1.5 py-0.5">Rx</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="px-4 py-3">
                                    <span class="text-gray-600">{{ $drug->category->name ?? '-' }}</span>
                                    <span class="block text-[10px] text-gray-400">{{ $drug->dosage_form }}</span>
                                </td>

                                <!-- Stock Status -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center gap-0.5">
                                        @if ($drug->quantity_in_stock <= 0)
                                            <span class="badge badge-danger w-full justify-center text-[10px]">Out of Stock</span>
                                        @elseif($drug->is_low_stock)
                                            <span class="badge badge-warning w-full justify-center text-[10px]">Low Stock</span>
                                        @else
                                            <span class="badge badge-success w-full justify-center text-[10px]">In Stock</span>
                                        @endif
                                        <span class="font-bold text-base {{ $drug->quantity_in_stock <= 0 ? 'text-red-600' : ($drug->is_low_stock ? 'text-amber-600' : 'text-gray-800') }}">
                                            {{ $drug->quantity_in_stock }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Reorder Level -->
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold text-gray-700 text-sm">{{ $drug->reorder_level }}</span>
                                </td>

                                <!-- Cost Price -->
                                <td class="px-4 py-3 text-right text-gray-500 font-mono">
                                    Ksh {{ number_format($drug->cost_price, 2) }}
                                </td>

                                <!-- Selling Price -->
                                <td class="px-4 py-3 text-right font-semibold text-gray-800 font-mono">
                                    Ksh {{ number_format($drug->selling_price, 2) }}
                                </td>

                                <!-- Margin -->
                                <td class="px-4 py-3 text-center">
                                    <span class="text-green-600 font-bold text-xs">{{ $drug->profit_margin }}%</span>
                                </td>

                                <!-- UPDATED: Action Buttons with Icons AND Text -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('drugs.show', $drug) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-[10px] font-semibold transition-all duration-200 shadow-sm"
                                            title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('drugs.edit', $drug) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white rounded-lg text-[10px] font-semibold transition-all duration-200 shadow-sm"
                                            title="Edit Drug">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('drugs.destroy', $drug) }}" class="inline"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg text-[10px] font-semibold transition-all duration-200 shadow-sm"
                                                title="Delete Drug">
                                                <i class="fas fa-trash-alt"></i> Del
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-box-open text-3xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-600">No drugs found</p>
                                        <p class="text-xs">Try adjusting your search or filter criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if ($drugs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $drugs->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection