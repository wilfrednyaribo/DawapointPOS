@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Categories</h2>
                <p class="text-gray-500 text-sm mt-1">Organize your inventory with classification tags.</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Quick Action: New Drug -->
                <a href="{{ route('drugs.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-pills"></i>
                    <span>Add New Drug</span>
                </a>

                <!-- Add Category Button -->
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Category</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Categories -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 text-xl">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $categories->count() }}</p>
                </div>
            </div>

            <!-- Active Categories -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active Categories</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $categories->count() }}</p>
                </div>
            </div>

            <!-- Quick Link Card: View Drugs by Category -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-sm p-6 flex items-center justify-between text-white">
                <div>
                    <p class="text-xs font-semibold text-purple-100 uppercase tracking-wider">Inventory</p>
                    <p class="text-lg font-bold mt-1">View Drugs by Category</p>
                </div>
                <a href="{{ route('drugs.index') }}" class="p-3 bg-white/20 hover:bg-white/30 rounded-lg transition">
                    <i class="fas fa-boxes-stacked"></i>
                </a>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg">Category Directory</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Category Name</th>
                            <th class="px-6 py-3 text-left hidden md:table-cell">Slug</th>
                            <th class="px-6 py-3 text-center">Drugs</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Category Name -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ Str::substr($category->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $category->name }}</span>
                                    </div>
                                </td>

                                <!-- Slug -->
                                <td class="px-6 py-4 text-gray-500 font-mono text-xs hidden md:table-cell">
                                    {{ $category->slug }}
                                </td>

                                <!-- Drugs Count -->
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-600">
                                        {{ $category->drugs_count ?? 0 }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition border border-teal-100">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-100">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-tags text-4xl text-gray-200"></i>
                                        <p class="text-gray-500 font-medium">No categories found.</p>
                                        <a href="{{ route('categories.create') }}"
                                           class="text-indigo-600 font-semibold text-sm hover:underline">Create your first category</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection