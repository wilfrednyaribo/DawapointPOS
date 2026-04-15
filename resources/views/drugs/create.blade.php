@extends('layouts.app')

@section('title', 'Add New Drug')
@section('page-title', 'Add New Drug')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">New Drug Entry</h2>
                <p class="text-sm text-gray-500 mt-1">Fill in the details below to add a new product to inventory</p>
            </div>
            
            <!-- Back to List Button -->
            <a href="{{ route('drugs.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm hover:shadow-md group">
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform text-xs"></i>
                <span>Back to List</span>
            </a>
        </div>

        <!-- NEW: Quick Add & Import Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Card 1: Quick Add Templates -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Quick Add Templates</h3>
                </div>
                <p class="text-xs text-gray-600 mb-4">Auto-fill the form with standard drug data.</p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="fillForm('paracetamol')" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-300 text-blue-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 rounded-lg text-sm font-semibold transition-all shadow-sm">
                        <i class="fas fa-tablets"></i>
                        <span>Paracetamol 500mg</span>
                    </button>
                    <button type="button" onclick="fillForm('amoxicillin')" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-300 text-blue-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 rounded-lg text-sm font-semibold transition-all shadow-sm">
                        <i class="fas fa-capsules"></i>
                        <span>Amoxicillin 250mg</span>
                    </button>
                </div>
            </div>

            <!-- Card 2: Excel Import -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-amber-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Bulk Import via Excel</h3>
                        <p class="text-xs text-gray-500">Upload a spreadsheet to add multiple drugs</p>
                    </div>
                </div>
                <div class="p-5">
                    
                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display Error Messages -->
                    @if ($errors->has('drug_file'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                            <div class="flex items-center gap-2 font-bold">
                                <i class="fas fa-exclamation-triangle"></i>
                                Upload Failed
                            </div>
                            <p class="mt-1">{{ $errors->first('drug_file') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('drugs.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="file" name="drug_file" id="drug_file" accept=".csv" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer border border-dashed border-gray-300 rounded-lg p-2">
                                <button type="submit" 
                                        class="flex-shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-500 text-white hover:bg-amber-600 rounded-lg text-sm font-bold transition-all shadow-sm hover:shadow-md">
                                    <i class="fas fa-upload"></i>
                                    <span>Import</span>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Currently supported: <span class="font-medium text-gray-600">.csv files only</span>.
                                <a href="{{ route('drugs.template') }}" class="text-teal-600 hover:underline font-medium ml-1">Download Sample Template</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Manual Entry Form -->
        <form method="POST" action="{{ route('drugs.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Product Details</h3>
                        <p class="text-xs text-gray-500">General information about the drug</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Drug Name -->
                    <div class="lg:col-span-2">
                        <label class="label">Drug Name *</label>
                        <div class="relative">
                            <input type="text" name="name" id="drug_name" value="{{ old('name') }}" class="input pl-10"
                                placeholder="e.g., Paracetamol" required>
                            <i class="fas fa-capsules absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Generic Name -->
                    <div>
                        <label class="label">Generic Name</label>
                        <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name') }}" class="input"
                            placeholder="e.g., Acetaminophen">
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="label">SKU (Stock Unit) *</label>
                        <div class="relative">
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="input pl-10 font-mono"
                                placeholder="DRUG-001" required>
                            <i class="fas fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label class="label">Barcode (Optional)</label>
                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" class="input font-mono"
                            placeholder="Scan or enter barcode">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="label">Category *</label>
                        <div class="relative">
                            <select name="category_id" id="category_id" class="input pl-10 appearance-none" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label class="label">Supplier</label>
                        <div class="relative">
                            <select name="supplier_id" id="supplier_id" class="input pl-10 appearance-none">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-truck absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Dosage Form -->
                    <div>
                        <label class="label">Dosage Form</label>
                        <div class="relative">
                            <select name="dosage_form" id="dosage_form" class="input pl-10 appearance-none">
                                <option value="">Select Form</option>
                                <option value="tablet" {{ old('dosage_form') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="capsule" {{ old('dosage_form') === 'capsule' ? 'selected' : '' }}>Capsule</option>
                                <option value="syrup" {{ old('dosage_form') === 'syrup' ? 'selected' : '' }}>Syrup</option>
                                <option value="injection" {{ old('dosage_form') === 'injection' ? 'selected' : '' }}>Injection</option>
                                <option value="cream" {{ old('dosage_form') === 'cream' ? 'selected' : '' }}>Cream/Ointment</option>
                                <option value="drops" {{ old('dosage_form') === 'drops' ? 'selected' : '' }}>Drops</option>
                                <option value="inhaler" {{ old('dosage_form') === 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                <option value="suppository" {{ old('dosage_form') === 'suppository' ? 'selected' : '' }}>Suppository</option>
                                <option value="other" {{ old('dosage_form') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <i class="fas fa-prescription-bottle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Strength -->
                    <div>
                        <label class="label">Strength</label>
                        <div class="relative">
                            <select name="strength" id="strength" class="input pl-10 appearance-none">
                                <option value="">Select Strength</option>
                                <optgroup label="Common Strengths">
                                    <option value="50mg" {{ old('strength') === '50mg' ? 'selected' : '' }}>50 mg</option>
                                    <option value="100mg" {{ old('strength') === '100mg' ? 'selected' : '' }}>100 mg</option>
                                    <option value="125mg" {{ old('strength') === '125mg' ? 'selected' : '' }}>125 mg</option>
                                    <option value="200mg" {{ old('strength') === '200mg' ? 'selected' : '' }}>200 mg</option>
                                    <option value="250mg" {{ old('strength') === '250mg' ? 'selected' : '' }}>250 mg</option>
                                    <option value="400mg" {{ old('strength') === '400mg' ? 'selected' : '' }}>400 mg</option>
                                    <option value="500mg" {{ old('strength') === '500mg' ? 'selected' : '' }}>500 mg</option>
                                    <option value="600mg" {{ old('strength') === '600mg' ? 'selected' : '' }}>600 mg</option>
                                    <option value="800mg" {{ old('strength') === '800mg' ? 'selected' : '' }}>800 mg</option>
                                    <option value="1g" {{ old('strength') === '1g' ? 'selected' : '' }}>1 g (1000 mg)</option>
                                </optgroup>
                                <optgroup label="Antibiotics / Specific">
                                    <option value="125mg/5ml" {{ old('strength') === '125mg/5ml' ? 'selected' : '' }}>125mg/5ml</option>
                                    <option value="250mg/5ml" {{ old('strength') === '250mg/5ml' ? 'selected' : '' }}>250mg/5ml</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="other" {{ old('strength') === 'other' ? 'selected' : '' }}>Other</option>
                                </optgroup>
                            </select>
                            <i class="fas fa-weight-hanging absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="label">Unit of Measure</label>
                        <div class="relative">
                            <select name="unit" id="unit" class="input pl-10 appearance-none">
                                <option value="">Select Unit</option>
                                <option value="Tablet" {{ old('unit') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Capsule" {{ old('unit') === 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                <option value="Bottle" {{ old('unit') === 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                <option value="Vial" {{ old('unit') === 'Vial' ? 'selected' : '' }}>Vial</option>
                                <option value="Ampoule" {{ old('unit') === 'Ampoule' ? 'selected' : '' }}>Ampoule</option>
                                <option value="Tube" {{ old('unit') === 'Tube' ? 'selected' : '' }}>Tube</option>
                                <option value="Sachet" {{ old('unit') === 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="Box" {{ old('unit') === 'Box' ? 'selected' : '' }}>Box</option>
                                <option value="Piece" {{ old('unit') === 'Piece' ? 'selected' : '' }}>Piece</option>
                            </select>
                            <i class="fas fa-balance-scale absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Pricing Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-green-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Pricing</h3>
                            <p class="text-xs text-gray-500">Cost and selling price</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="label">Cost Price (Ksh) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Ksh</span>
                                <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}"
                                    class="input pl-14 font-mono" step="0.01" min="0" required
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="label">Selling Price (Ksh) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Ksh</span>
                                <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}"
                                    class="input pl-14 font-mono" step="0.01" min="0" required
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-purple-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Inventory & Batch</h3>
                            <p class="text-xs text-gray-500">Stock and expiry details</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="label">Initial Stock Quantity</label>
                            <input type="number" name="quantity_in_stock" id="quantity_in_stock" value="{{ old('quantity_in_stock', 0) }}"
                                class="input font-mono" min="0">
                        </div>
                        
                        <!-- Manufacturing Date -->
                        <div>
                            <label class="label">Manufacturing Date</label>
                            <input type="date" name="manufacturing_date" value="{{ old('manufacturing_date') }}"
                                class="input font-mono">
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label class="label">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="input font-mono">
                            <p class="text-xs text-gray-400 mt-1">Required if adding initial stock.</p>
                        </div>

                        <div>
                            <label class="label">Reorder Level (Alert)</label>
                            <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}"
                                class="input font-mono" min="0">
                            <p class="text-xs text-gray-400 mt-1">System will alert when stock falls below this number.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Additional Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600">
                        <i class="fas fa-sliders"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Additional Settings</h3>
                        <p class="text-xs text-gray-500">Storage and prescription requirements</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Storage -->
                    <div>
                        <label class="label">Storage Condition</label>
                        <select name="storage_condition" class="input">
                            <option value="">Select Condition</option>
                            <option value="room_temp" {{ old('storage_condition') === 'room_temp' ? 'selected' : '' }}>Room Temperature</option>
                            <option value="refrigerated" {{ old('storage_condition') === 'refrigerated' ? 'selected' : '' }}>Refrigerated (2-8°C)</option>
                            <option value="frozen" {{ old('storage_condition') === 'frozen' ? 'selected' : '' }}>Frozen</option>
                            <option value="protected_light" {{ old('storage_condition') === 'protected_light' ? 'selected' : '' }}>Protected from Light</option>
                        </select>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-4 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="requires_prescription" value="1"
                                {{ old('requires_prescription') ? 'checked' : '' }}
                                class="w-5 h-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600">Requires Prescription</span>
                                <p class="text-xs text-gray-400">Mark if this drug needs an Rx to sell.</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="is_controlled" value="1"
                                {{ old('is_controlled') ? 'checked' : '' }}
                                class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-red-600">Controlled Substance</span>
                                <p class="text-xs text-gray-400">Track usage and require special authorization.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="label">Description / Notes</label>
                        <textarea name="description" rows="3" class="input" placeholder="Any additional notes about this product...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pb-6 pt-2">
                <a href="{{ route('drugs.index') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-sm hover:shadow-md border border-red-100 hover:border-red-600">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancel</span>
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Drug to Inventory</span>
                </button>
            </div>

        </form>
    </div>

    <!-- JavaScript for Quick Add Templates -->
    <script>
        function fillForm(type) {
            const data = {
                'paracetamol': {
                    name: 'Paracetamol',
                    generic_name: 'Acetaminophen',
                    sku: 'PARA-500-' + Math.floor(Math.random() * 1000),
                    dosage_form: 'tablet',
                    strength: '500mg',
                    unit: 'Tablet',
                    cost_price: 0.50,
                    selling_price: 1.00,
                    quantity_in_stock: 100,
                    storage_condition: 'room_temp'
                },
                'amoxicillin': {
                    name: 'Amoxicillin',
                    generic_name: 'Amoxicillin Trihydrate',
                    sku: 'AMOX-250-' + Math.floor(Math.random() * 1000),
                    dosage_form: 'capsule',
                    strength: '250mg',
                    unit: 'Capsule',
                    cost_price: 1.20,
                    selling_price: 2.50,
                    quantity_in_stock: 50,
                    storage_condition: 'room_temp',
                    requires_prescription: true
                }
            };

            const selected = data[type];
            if (!selected) return;

            // Fill text/number inputs
            document.getElementById('drug_name').value = selected.name;
            document.getElementById('generic_name').value = selected.generic_name;
            document.getElementById('sku').value = selected.sku;
            
            // Fill Pricing & Inventory inputs (Added IDs to HTML for this to work)
            document.getElementById('cost_price').value = selected.cost_price;
            document.getElementById('selling_price').value = selected.selling_price;
            document.getElementById('quantity_in_stock').value = selected.quantity_in_stock;

            // Select dropdowns
            selectDropdown('dosage_form', selected.dosage_form);
            selectDropdown('strength', selected.strength);
            selectDropdown('unit', selected.unit);
            selectDropdown('storage_condition', selected.storage_condition);

            // Checkboxes
            if(selected.requires_prescription) {
                document.querySelector('input[name="requires_prescription"]').checked = true;
            } else {
                document.querySelector('input[name="requires_prescription"]').checked = false;
            }

            // Scroll to top to see filled data
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function selectDropdown(name, value) {
            const select = document.querySelector(`select[name="${name}"]`);
            if(select) {
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === value) {
                        select.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    </script>
@endsection