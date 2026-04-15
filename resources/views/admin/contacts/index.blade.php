@extends('layouts.app')

@section('title', 'Contact Messages')

@section('page-title', 'Contact Messages')

@section('content')
<!-- Include Alpine.js if not already in your layout, or ensure it's loaded -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<div x-data="{ 
    expandedRows: {}, 
    toggleRow(id) { 
        this.$set(this.expandedRows, id, !this.expandedRows[id]); 
    } 
}" class="max-w-7xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <div>
                <h3 class="font-display font-bold text-slate-800 text-2xl">Inbox</h3>
                <p class="text-sm text-slate-500 mt-1">Messages received from Potential Clients</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-brand-50 text-brand-600 rounded-full border border-brand-100 shadow-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <span class="text-sm font-bold">Total: {{ $messages->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Messages Container (Grid Layout) -->
    <div class="grid grid-cols-1 gap-4">
        @forelse ($messages as $msg)
            <!-- Individual Message Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5 hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col lg:flex-row gap-5 items-start">
                    
                    <!-- Left: Sender Info -->
                    <div class="w-full lg:w-64 flex-shrink-0 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-sm border border-teal-100">
                                {{ substr($msg->full_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $msg->full_name }}</h4>
                                <div class="text-xs text-slate-400 font-mono">#{{ $msg->id }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center gap-2 text-slate-500">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>{{ $msg->phone_number }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-500 truncate">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span>{{ $msg->email }}</span>
                            </div>
                            @if($msg->pharmacy_name)
                            <div class="flex items-center gap-2 text-slate-600 font-medium bg-slate-50 px-2 py-1 rounded w-fit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M17 21v-8.5c0-.8-.7-1.5-1.5-1.5h-7c-.8 0-1.5.7-1.5 1.5V21"/></svg>
                                <span>{{ $msg->pharmacy_name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Message Content -->
                    <div class="flex-1 w-full border-l border-slate-100 lg:pl-5 pl-0">
                        <div class="mb-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Message</span>
                        </div>
                        
                        <!-- Truncated / Expandable Text -->
                        <div class="bg-slate-50 rounded-lg p-4 text-sm text-slate-700 leading-relaxed relative transition-all duration-300" 
                             :class="expandedRows.{{ $msg->id }} ? '' : 'line-clamp-2'">
                            {{ $msg->message }}
                        </div>

                        <!-- Read More / Read Less Button -->
                        <div class="mt-3 flex justify-between items-center">
                            <button @click="toggleRow({{ $msg->id }})" 
                                    class="text-xs font-bold text-brand-600 hover:text-brand-800 flex items-center gap-1 transition-colors focus:outline-none">
                                <template x-if="!expandedRows.{{ $msg->id }}">
                                    <span>Read More</span>
                                </template>
                                <template x-if="expandedRows.{{ $msg->id }}">
                                    <span>Show Less</span>
                                </template>
                                <svg x-transition:rotate="expandedRows.{{ $msg->id }} ? '180deg' : '0deg'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>

                            <div class="text-xs text-slate-400 font-medium bg-white px-2 py-1 rounded border border-slate-100">
                                {{ $msg->created_at->format('M d, Y • h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center p-12 bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700">No messages found</h3>
                <p class="text-slate-500 text-sm mt-1">Messages from the contact form will appear here.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Utility class to truncate text to 2 lines */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection