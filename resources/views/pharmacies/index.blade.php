@extends('layouts.app')

@section('title', 'Pharmacy Management')
@section('page-title', 'All Pharmacies')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pharmacy Management</h2>
            <p class="text-gray-500 text-sm">Manage all registered pharmacies on the platform.</p>
        </div>
        <a href="{{ route('pharmacies.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 text-white hover:bg-brand-700 rounded-xl text-sm font-bold transition-all shadow-lg">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Pharmacy</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left">Pharmacy Name</th>
                        <th class="px-6 py-4 text-left">Contact Email</th>
                        <th class="px-6 py-4 text-left">Phone</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pharmacies as $pharmacy)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$pharmacy->is_active ? 'bg-red-50/30' : '' }}">
                        <!-- Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $pharmacy->is_active ? 'bg-brand-100 text-brand-600' : 'bg-gray-100 text-gray-400' }} rounded-lg flex items-center justify-center font-bold">
                                    {{ Str::substr($pharmacy->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $pharmacy->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $pharmacy->address ?? 'No address' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Email -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $pharmacy->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $pharmacy->phone ?? '-' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            @if($pharmacy->is_active)
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">
                                    Deactivated
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                <!-- Toggle Status Button -->
                                @if($pharmacy->is_active)
                                    <form action="{{ route('pharmacies.deactivate', $pharmacy->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will prevent all users of this pharmacy from logging in.');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-lg transition border border-orange-100">
                                            <i class="fas fa-power-off"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('pharmacies.activate', $pharmacy->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition border border-green-100">
                                            <i class="fas fa-power-off"></i> Activate
                                        </button>
                                    </form>
                                @endif

                                <!-- Edit Button -->
                                <a href="{{ route('pharmacies.edit', $pharmacy->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                                    <i class="fas fa-pen"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('pharmacies.destroy', $pharmacy->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pharmacy? All associated data will be lost.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-100">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-hospital text-4xl text-gray-200"></i>
                                <span>No pharmacies found.</span>
                                <a href="{{ route('pharmacies.create') }}" class="text-brand-600 font-semibold hover:underline">Add the first pharmacy</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pharmacies->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $pharmacies->links() }}
        </div>
        @endif
    </div>
</div>
@endsection