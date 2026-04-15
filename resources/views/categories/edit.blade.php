@extends('layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Update Category')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Category</h2>
            <p class="text-gray-500 text-sm">Modify details for <strong>{{ $category->name }}</strong>.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-800 hover:text-white hover:border-gray-800 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm group">
            <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform text-xs"></i>
            <span>Back to List</span>
        </a>
    </div>

    <form method="POST" action="{{ route('categories.update', $category->id) }}" class="space-y-6">
        @csrf
        @method('PUT') <!-- Important: Use PUT for updates -->

        <!-- Section: Category Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600">
                    <i class="fas fa-pen-to-square"></i>
                </div>
                <h3 class="font-bold text-gray-800">Category Information</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Validation Error Summary -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-red-600 font-semibold text-sm mb-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            Please fix the following errors:
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Pharmaceuticals" required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" placeholder="Brief description...">{{ old('description', $category->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-end gap-3">
            <a href="{{ route('categories.index') }}" class="px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-red-100 hover:border-red-600">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <i class="fas fa-floppy-disk"></i>
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection