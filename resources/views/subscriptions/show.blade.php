@extends('layouts.app')

@section('title', 'My Subscription')
@section('page-title', 'Subscription Status')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    @php 
        $endDate = $pharmacy->subscription_ends_at ? \Carbon\Carbon::parse($pharmacy->subscription_ends_at) : null;
        $isExpired = $endDate ? $endDate->isPast() : true;
        $daysLeft = $endDate ? now()->diffInDays($endDate, false) : 0;
    @endphp

    <!-- Main Status Header -->
    <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Decorative Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, currentColor 1px, transparent 0); background-size: 24px 24px;"></div>
        </div>

        <div class="relative p-8 {{ $isExpired ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-indigo-500 to-purple-600' }}">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        @if($isExpired)
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        @else
                            <i class="fas fa-crown text-white text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $isExpired ? 'bg-white/20 text-white' : 'bg-white/20 text-white' }} mb-2">
                            {{ $pharmacy->name }}
                        </span>
                        @if($isExpired)
                            <h2 class="text-2xl font-bold text-white tracking-tight">Subscription Expired</h2>
                            <p class="text-white/80 text-sm mt-0.5">Access to the system has been restricted.</p>
                        @else
                            <h2 class="text-2xl font-bold text-white tracking-tight">Plan is Active</h2>
                            <p class="text-white/80 text-sm mt-0.5">You have full access to all features.</p>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0 text-center md:text-right">
                    <p class="text-xs uppercase tracking-wider text-white/60 font-semibold mb-1">Valid Until</p>
                    <div class="bg-white/10 backdrop-blur-sm px-5 py-2 rounded-xl inline-block border border-white/10">
                        <span class="text-xl font-bold text-white font-mono">
                            {{ $endDate ? $endDate->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Time Remaining Card -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center {{ $isExpired ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Time Left</p>
                @if($isExpired)
                    <p class="text-lg font-bold text-red-600">Expired</p>
                @else
                    <p class="text-lg font-bold text-gray-800">{{ $daysLeft }} Days</p>
                @endif
            </div>
        </div>

        <!-- Total Renewals Card -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                <i class="fas fa-history text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Renewals</p>
                <p class="text-lg font-bold text-gray-800">{{ $pharmacy->subscriptionPayments->count() }} Times</p>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg {{ $isExpired ? 'bg-gray-100 text-gray-400' : 'bg-green-100 text-green-600' }} flex items-center justify-center">
                <i class="fas fa-signal text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</p>
                <p class="text-lg font-bold {{ $isExpired ? 'text-red-500' : 'text-green-600' }}">
                    {{ $isExpired ? 'Offline' : 'Online' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Warning Message -->
    @if($isExpired || $daysLeft < 7)
        <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-xl p-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 pt-0.5">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-bell text-amber-600 text-sm"></i>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-amber-800">Action Required</h4>
                    <p class="text-sm text-amber-700 mt-1">
                        @if($isExpired)
                            Your subscription has expired. Please contact the administrator to restore access.
                        @else
                            Your subscription is expiring soon (Less than 7 days left). Please arrange for renewal to avoid interruption.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Renewal History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Payment & Renewal History</h3>
                <p class="text-sm text-gray-500 mt-0.5">A record of all your past subscription extensions.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Processed</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Plan Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">New Expiry</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Processed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pharmacy->subscriptionPayments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                        <i class="fas fa-calendar-check text-xs"></i>
                                    </div>
                                    <span class="text-gray-800 font-medium">{{ $payment->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $payment->days_purchased >= 365 ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100' }}">
                                    +{{ $payment->days_purchased }} Days
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs tracking-wide">
                                {{ $payment->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <i class="fas fa-user-shield text-gray-300 mr-2"></i>Admin
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 bg-gray-50/50">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">No History Found</p>
                                        <p class="text-xs text-gray-400 mt-1">Renewal records will appear here.</p>
                                    </div>
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