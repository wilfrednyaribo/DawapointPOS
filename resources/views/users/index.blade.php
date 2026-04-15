@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'All Users')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
            <p class="text-gray-500 text-sm">Manage Cashiers, Pharmacists, and Admins.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl text-sm font-bold transition-all shadow-lg">
            <i class="fas fa-user-plus"></i>
            <span>Add New User</span>
        </a>
    </div>

    <!-- Loop through pharmacies -->
    @foreach($pharmacies as $pharmacyName => $users)
    
        <!-- Pharmacy Group Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $pharmacyName }}</h3>
                        <p class="text-xs text-gray-500">{{ $users->count() }} user(s)</p>
                    </div>
                </div>
                <!-- Optional: Add link to view pharmacy details -->
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Name -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ Str::substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>

                            <!-- Role -->
                            <td class="px-6 py-4">
                                @php 
                                    $role = $user->roles->first()->name ?? 'User';
                                    $colorClasses = [
                                        'Admin' => 'bg-purple-100 text-purple-700',
                                        'Pharmacist' => 'bg-blue-100 text-blue-700',
                                        'Cashier' => 'bg-green-100 text-green-700',
                                    ];
                                    $defaultClass = 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colorClasses[$role] ?? $defaultClass }}">
                                    {{ Str::title($role) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200">Active</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">Inactive</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    <a href="{{ route('users.edit', $user->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition border border-teal-100">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>

                                    <!-- SUPER ADMIN ONLY: Activation Toggle -->
                                    @if(auth()->user()->pharmacy_id === null)
                                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                                            @csrf
                                            @if($user->is_active)
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-lg transition border border-orange-100">
                                                    <i class="fas fa-ban"></i> Deactivate
                                                </button>
                                            @else
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition border border-green-100">
                                                    <i class="fas fa-check-circle"></i> Activate
                                                </button>
                                            @endif
                                        </form>
                                    @endif

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-100">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>
@endsection