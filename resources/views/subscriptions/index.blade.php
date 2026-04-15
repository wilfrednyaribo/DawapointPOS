@extends('layouts.app')

@section('title', 'Subscriptions')
@section('page-title', 'Pharmacy Subscriptions')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Subscription Management</h2>
            <p class="text-gray-500 text-sm">Manage pharmacy renewals and active status.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Pharmacy Name</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Expires On</th>
                    <th class="px-6 py-3 text-left">Days Left</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($pharmacies as $pharmacy)
                    @php 
                        $isExpired = $pharmacy->subscription_ends_at ? $pharmacy->subscription_ends_at->isPast() : true;
                        $daysLeft = $pharmacy->subscription_ends_at ? now()->diffInDays($pharmacy->subscription_ends_at, false) : -1;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $pharmacy->name }}
                            <span class="block text-xs font-normal text-gray-500">{{ $pharmacy->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($pharmacy->is_active && !$isExpired)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactive / Expired</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $pharmacy->subscription_ends_at ? $pharmacy->subscription_ends_at->format('M d, Y') : 'Not Set' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($isExpired)
                                <span class="text-red-600 font-bold">Expired</span>
                            @else
                                <span class="text-gray-800">{{ $daysLeft }} days</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('subscriptions.edit', $pharmacy->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                                <i class="fas fa-calendar-plus"></i> Renew
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection