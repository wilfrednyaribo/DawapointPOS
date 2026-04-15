@extends('layouts.app')

@section('title', 'Renew Subscription')
@section('page-title', 'Renew Subscription')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Renew: {{ $pharmacy->name }}</h3>
        <p class="text-sm text-gray-500 mb-6">
            Current Expiry: <strong>{{ $pharmacy->subscription_ends_at ? $pharmacy->subscription_ends_at->format('M d, Y') : 'N/A' }}</strong>
        </p>

        <form method="POST" action="{{ route('subscriptions.update', $pharmacy->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Quick Duration Buttons -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Select Duration</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" onclick="document.getElementById('duration').value=30" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition">1 Month</button>
                        <button type="button" onclick="document.getElementById('duration').value=90" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition">3 Months</button>
                        <button type="button" onclick="document.getElementById('duration').value=365" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition">1 Year</button>
                    </div>
                </div>

                <!-- Custom Duration Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Custom Days</label>
                    <input type="number" id="duration" name="duration_days" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="e.g., 30" required>
                </div>

                <!-- Status Toggle -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pharmacy Status</label>
                    <select name="is_active" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm">
                        <option value="1" {{ $pharmacy->is_active ? 'selected' : '' }}>Active (Can Login)</option>
                        <option value="0" {{ !$pharmacy->is_active ? 'selected' : '' }}>Deactivated (Cannot Login)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition">
                    Save Subscription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection