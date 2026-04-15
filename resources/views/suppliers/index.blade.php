
@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Supplier Management')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Suppliers</h2>
                <p class="text-gray-500 text-sm mt-1">Manage your vendors and distribution partners.</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- New Purchase Button -->
                <a href="{{ route('purchases.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 text-white hover:bg-teal-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-truck-ramp-box"></i>
                    <span>New Purchase</span>
                </a>

                <!-- Add Supplier Button -->
                <a href="{{ route('suppliers.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-user-plus"></i>
                    <span>Add New Supplier</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Suppliers -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 text-xl">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Suppliers</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $suppliers->total() }}</p>
                </div>
            </div>

            <!-- With Purchases -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 text-xl">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active Partners</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $withPurchases ?? '-' }}</p>
                </div>
            </div>

            <!-- Quick Action Card (Optional Placeholder) -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-sm p-6 flex items-center justify-between text-white">
                <div>
                    <p class="text-xs font-semibold text-purple-100 uppercase tracking-wider">Reports</p>
                    <p class="text-lg font-bold mt-1">View Insights</p>
                </div>
                <a href="{{ route('reports.index') }}" class="p-3 bg-white/20 hover:bg-white/30 rounded-lg transition">
                    <i class="fas fa-chart-pie"></i>
                </a>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg">Supplier Directory</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Company Name</th>
                            <th class="px-6 py-3 text-left">Contact Person</th>
                            <th class="px-6 py-3 text-left">Phone</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Company Name -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ Str::substr($supplier->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $supplier->name }}</span>
                                    </div>
                                </td>

                                <!-- Contact Person -->
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $supplier->contact_person }}
                                </td>

                                <!-- Phone -->
                                <td class="px-6 py-4">
                                    <a href="tel:{{ $supplier->phone }}" class="text-teal-600 hover:underline">
                                        {{ $supplier->phone }}
                                    </a>
                                </td>

                                <!-- Email -->
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $supplier->email ?? 'N/A' }}
                                </td>

                                <!-- Actions (Styled Buttons) -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('suppliers.show', $supplier->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition border border-blue-100">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition border border-teal-100">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this supplier?')">
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
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-users text-4xl text-gray-200"></i>
                                        <p class="text-gray-500 font-medium">No suppliers found.</p>
                                        <a href="{{ route('suppliers.create') }}"
                                           class="text-indigo-600 font-semibold text-sm hover:underline">Register your
                                            first supplier</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($suppliers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
