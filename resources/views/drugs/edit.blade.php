@extends('layouts.app')

@section('title', 'Edit Drug')
@section('page-title', 'Update Drug Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Update Drug Details</h2>
            <p class="text-gray-500 text-sm mt-1">
                Editing: <span class="font-semibold text-gray-700">{{ $drug->name }}</span> 
                @if($drug->strength) <span class="text-gray-400">({{ $drug->strength }})</span> @endif
            </p>
        </div>
        <a href="{{ route('drugs.index') }}" 
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-all shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Inventory</span>
        </a>
    </div>

    <form action="{{ route('drugs.update', $drug->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: General Information -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- General Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">General Information</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Drug Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Drug Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $drug->name) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm" required>
                            </div>

                            <!-- Generic Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Generic Name</label>
                                <input type="text" name="generic_name" value="{{ old('generic_name', $drug->generic_name) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm">
                            </div>

                            <!-- SKU -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="sku" value="{{ old('sku', $drug->sku) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm font-mono" required>
                            </div>

                            <!-- Barcode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                                <input type="text" name="barcode" value="{{ old('barcode', $drug->barcode) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm font-mono">
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $drug->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                                <select name="supplier_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $drug->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm"
                                placeholder="Brief description of the drug...">{{ old('description', $drug->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Attributes & Storage Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Attributes & Storage</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <!-- Dosage Form -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dosage Form</label>
                                <select name="dosage_form" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm bg-white">
                                    <option value="tablet" {{ $drug->dosage_form == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="capsule" {{ $drug->dosage_form == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="syrup" {{ $drug->dosage_form == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="injection" {{ $drug->dosage_form == 'injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="cream" {{ $drug->dosage_form == 'cream' ? 'selected' : '' }}>Cream/Ointment</option>
                                    <option value="drops" {{ $drug->dosage_form == 'drops' ? 'selected' : '' }}>Drops</option>
                                    <option value="inhaler" {{ $drug->dosage_form == 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                </select>
                            </div>
                            <!-- Strength -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Strength</label>
                                <input type="text" name="strength" value="{{ old('strength', $drug->strength) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm" placeholder="e.g. 500mg">
                            </div>
                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <input type="text" name="unit" value="{{ old('unit', $drug->unit) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm" placeholder="e.g. Strip, Bottle">
                            </div>
                        </div>
                        <!-- Storage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Storage Condition</label>
                            <input type="text" name="storage_condition" value="{{ old('storage_condition', $drug->storage_condition) }}" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm" placeholder="e.g. Store below 25°C">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                
                <!-- Pricing Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Pricing</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price (Ksh)</label>
                            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $drug->cost_price) }}" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm text-right font-mono" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price (Ksh)</label>
                            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $drug->selling_price) }}" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm text-right font-mono" required>
                        </div>
                        
                        <!-- Margin Display -->
                        <div class="pt-3 border-t border-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Profit Margin</span>
                                <span class="text-sm font-bold text-green-600">
                                    {{ $drug->profit_margin ?? 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Inventory</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity In Stock</label>
                            <input type="number" name="quantity_in_stock" value="{{ old('quantity_in_stock', $drug->quantity_in_stock) }}" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm text-center font-bold text-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                            <input type="number" name="reorder_level" value="{{ old('reorder_level', $drug->reorder_level) }}" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm text-center" required>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 space-y-5">
                        <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wider">Status Flags</h4>
                        
                        <!-- Prescription Toggle -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-prescription text-blue-500"></i>
                                <span class="text-sm text-gray-700 font-medium">Prescription</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="requires_prescription" class="sr-only peer" {{ $drug->requires_prescription ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Controlled Toggle -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-ban text-red-500"></i>
                                <span class="text-sm text-gray-700 font-medium">Controlled</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_controlled" class="sr-only peer" {{ $drug->is_controlled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>

                        <button type="submit" 
                            class="w-full px-5 py-3 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection