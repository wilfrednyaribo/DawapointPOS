<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - DawaPoint</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#effefa',
                            100: '#c8fff2',
                            200: '#91ffe6',
                            300: '#52f5d4',
                            400: '#1ee0ba',
                            500: '#06c4a2',
                            600: '#02a385',
                            700: '#06846c',
                            800: '#0a6a58',
                            900: '#0d5748',
                            950: '#00332e',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --bg-deep: #07090d;
            --bg-surface: #0d1117;
            --bg-elevated: #161b22;
            --accent: #06c4a2;
            --accent-glow: rgba(6, 196, 162, 0.4);
            --border-subtle: rgba(255, 255, 255, 0.06);
            --text-primary: #f0f6fc;
            --text-secondary: #8b949e;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f0f2f5;
        }

        /* Premium Sidebar */
        .sidebar {
            background: var(--bg-deep);
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 10%, rgba(6, 196, 162, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 90% 90%, rgba(56, 189, 248, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Grid Pattern */
        .sidebar-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse 100% 80% at 0% 0%, black 0%, transparent 70%);
            pointer-events: none;
        }

        /* Logo */
        .logo-mark {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent) 0%, #0ea5e9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 8px 32px rgba(6, 196, 162, 0.25);
        }

        .logo-mark::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, var(--accent), #0ea5e9, var(--accent));
            border-radius: 14px;
            z-index: -1;
            opacity: 0.5;
            filter: blur(8px);
        }

        /* Navigation Section */
        .nav-section {
            margin-bottom: 0.5rem;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #484f58;
            padding: 0.75rem 1.25rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.06), transparent);
        }

        /* Navigation Items */
        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.75rem;
            border-radius: 12px;
            color: #8b949e;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.06);
        }

        .nav-item.active {
            color: var(--text-primary);
            background: rgba(6, 196, 162, 0.08);
            border-color: rgba(6, 196, 162, 0.2);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 12px var(--accent-glow);
        }

        /* Navigation Icon */
        .nav-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-item:hover .nav-icon {
            background: rgba(6, 196, 162, 0.1);
            border-color: rgba(6, 196, 162, 0.2);
        }

        .nav-item.active .nav-icon {
            background: var(--accent);
            border-color: transparent;
            box-shadow: 0 4px 16px rgba(6, 196, 162, 0.3);
        }

        .nav-item.active .nav-icon svg {
            color: #0a0f14;
        }

        .nav-label {
            font-size: 0.875rem;
            font-weight: 500;
            flex: 1;
        }

        /* Status Indicators */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            animation: statusPulse 2s infinite;
        }

        @keyframes statusPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(0.9);
            }
        }

        .badge {
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            letter-spacing: 0.02em;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Time Widget */
        .time-widget {
            margin: 0.75rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            position: relative;
            overflow: hidden;
        }

        .time-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(6, 196, 162, 0.3), transparent);
        }

        .time-display {
            font-family: 'Space Grotesk', monospace;
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f0f6fc 0%, #8b949e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        /* Collapsible */
        .collapsible-content {
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .collapsible-toggle {
            display: flex;
            align-items: center;
            padding: 0.5rem 1.25rem;
            color: #484f58;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .collapsible-toggle:hover {
            color: #8b949e;
        }

        .collapsible-toggle svg {
            transition: transform 0.2s ease;
        }

        .collapsible-toggle.open svg {
            transform: rotate(180deg);
        }

        /* Quick Action Card */
        .quick-action {
            margin: 0.75rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, rgba(6, 196, 162, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
            border: 1px solid rgba(6, 196, 162, 0.2);
            border-radius: 14px;
            position: relative;
            overflow: hidden;
        }

        .quick-action-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            border: none;
            border-radius: 10px;
            color: #0a0f14;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(6, 196, 162, 0.4);
        }

        /* User Profile */
        .user-profile {
            padding: 1rem;
            margin: 0.5rem 0.75rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, var(--accent) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            color: white;
            position: relative;
            flex-shrink: 0;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: #22c55e;
            border: 2px solid var(--bg-deep);
            border-radius: 50%;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.7rem;
            color: #484f58;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.15rem;
        }

        .user-role-badge {
            padding: 0.15rem 0.4rem;
            background: rgba(6, 196, 162, 0.15);
            border-radius: 4px;
            color: var(--accent);
            font-weight: 500;
            font-size: 0.6rem;
            text-transform: uppercase;
        }

        .logout-btn {
            padding: 0.5rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            color: #f87171;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* FAB */
        .fab {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent) 0%, #0ea5e9 100%);
            border: none;
            border-radius: 16px;
            color: #0a0f14;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(6, 196, 162, 0.4), 0 0 0 4px rgba(6, 196, 162, 0.1);
            transition: all 0.3s ease;
            z-index: 50;
        }

        .fab:hover {
            transform: scale(1.08) rotate(-5deg);
        }

        /* Scrollbar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp 0.5s ease forwards;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Header */
        .header-search {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }

        .header-search:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6, 196, 162, 0.1);
        }

        .header-search input {
            background: transparent;
            border: none;
            outline: none;
            font-size: 0.875rem;
            width: 200px;
        }

        .header-btn {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.06);
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .header-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .fab {
                bottom: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="flex min-h-screen" x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        mobileMenuOpen: false,
        collapsed: false,
        activeSection: 'menu'
    }" @resize.window="sidebarOpen = window.innerWidth >= 1024">

        <!-- Sidebar Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity:0" x-transition:enter-end="opacity:100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity:100"
            x-transition:leave-end="opacity:0" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
            @click="mobileMenuOpen = false" x-cloak></div>

        <!-- Sidebar -->
        <aside class="sidebar fixed inset-y-0 left-0 z-50 w-72 flex flex-col" :class="{ 'open': mobileMenuOpen }"
            :style="sidebarOpen ? 'transform: translateX(0)' : ''" x-show="mobileMenuOpen || sidebarOpen">

            <!-- Background Grid -->
            <div class="sidebar-grid"></div>

            <!-- Logo Section -->
            <div class="relative z-10 px-5 pt-5 pb-3 flex-shrink-0">
                <div class="flex items-center gap-3 animate-slide-in">
                    <div class="logo-mark">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0a0f14"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.5 20.5H6.5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h4" />
                            <path d="M13.5 20.5h4a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4" />
                            <path d="M10 7V4a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v3" />
                            <path d="M8 14h8" />
                            <path d="M8 11h8" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display text-lg font-bold text-white tracking-tight">DawaPoint</h1>
                        <span class="text-[10px] font-semibold text-brand-500 tracking-widest uppercase">Pharmacy
                            POS</span>
                    </div>
                </div>
            </div>

            <!-- Time Widget -->
            <div class="time-widget animate-fade-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-[#484f58] mb-1">Today</p>
                        <p class="text-sm font-medium text-white">
                            {{ \Carbon\Carbon::now('Africa/Nairobi')->format('l, F j') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="time-display">{{ \Carbon\Carbon::now('Africa/Nairobi')->format('H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="relative z-10 flex-1 px-2 py-2 overflow-y-auto sidebar-scroll">

                <!-- Main Menu -->
                <div class="nav-section animate-fade-up" style="animation-delay: 0.15s;">
                    <div class="nav-section-title"><span>Main Menu</span></div>

                    @can('view_dashboard')
                        <a href="{{ route('dashboard') }}"
                            class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="14" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                </svg>
                            </div>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    @endcan

                    @can('access_pos')
                        <a href="{{ route('pos.create') }}"
                            class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <path d="M6 8h.01M10 8h.01M14 8h.01M18 8h.01M8 12h.01M12 12h.01M16 12h.01M18 12h.01" />
                                    <path d="M6 16h12" />
                                </svg>
                            </div>
                            <span class="nav-label">Point of Sale</span>
                            <div class="status-dot"></div>
                        </a>
                    @endcan

                    @can('view_drugs')
                        <a href="{{ route('drugs.index') }}"
                            class="nav-item {{ request()->routeIs('drugs.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 2.5a9 9 0 0 0-7.5 9c0 3.5 2.5 6.5 5.5 7.5" />
                                    <path d="M13.5 2.5a9 9 0 0 1 7.5 9c0 3.5-2.5 6.5-5.5 7.5" />
                                    <path d="M12 2v6" />
                                    <circle cx="12" cy="14" r="4" />
                                </svg>
                            </div>
                            <span class="nav-label">Drug Inventory</span>
                        </a>
                    @endcan

                    @can('view_sales')
                        <a href="{{ route('sales.index') }}"
                            class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <path d="M14 2v6h6" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                    <path d="M10 9H8" />
                                </svg>
                            </div>
                            <span class="nav-label">Sales History</span>
                        </a>
                    @endcan

                    @can('view_reports')
                        <a href="{{ route('reports.index') }}"
                            class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                                    <path d="M22 12A10 10 0 0 0 12 2v10z" />
                                </svg>
                            </div>
                            <span class="nav-label">Reports</span>
                        </a>
                    @endcan
                </div>

                <!-- Management Section (Collapsible) -->
                @canany(['view_categories', 'view_suppliers', 'view_customers', 'view_purchases'])
                    <div class="nav-section animate-fade-up" style="animation-delay: 0.2s;" x-data="{ open: {{ request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'true' : 'true' }} }">
                        <div class="collapsible-toggle" :class="{ 'open': open }" @click="open = !open">
                            <span class="flex-1">Management</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </div>

                        <div class="collapsible-content" :style="open ? 'max-height: 1000px' : 'max-height: 0'">

                            @can('view_categories')
                                <a href="{{ route('categories.index') }}"
                                    class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                                            <line x1="7" y1="7" x2="7.01" y2="7" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">Categories</span>
                                </a>
                            @endcan

                            @can('view_suppliers')
                                <a href="{{ route('suppliers.index') }}"
                                    class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="1" y="3" width="15" height="13" />
                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                            <circle cx="5.5" cy="18.5" r="2.5" />
                                            <circle cx="18.5" cy="18.5" r="2.5" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">Suppliers</span>
                                </a>
                            @endcan

                            @can('view_customers')
                                <a href="{{ route('customers.index') }}"
                                    class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">Customers</span>
                                </a>
                            @endcan

                            @can('view_purchases')
                                <a href="{{ route('purchases.index') }}"
                                    class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <path d="M16 10a4 4 0 0 1-8 0" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">Purchases</span>
                                </a>
                            @endcan

                            @if (auth()->user()->pharmacy_id === null)
                                <a href="{{ route('pharmacies.index') }}"
                                    class="nav-item {{ request()->routeIs('pharmacies.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg>
                                    </div>
                                    <span class="nav-label">Manage Pharmacies</span>
                                </a>
                            @endif

                        </div>
                    </div>
                @endcanany

                <!-- User Management Section -->
                @canany(['view_users', 'view_roles'])
                    <div class="nav-section animate-fade-up" style="animation-delay: 0.25s;" x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }">
                        <div class="collapsible-toggle" :class="{ 'open': open }" @click="open = !open">
                            <span class="flex-1">User Management</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </div>

                        <div class="collapsible-content" :style="open ? 'max-height: 500px' : 'max-height: 0'">
                            @can('view_users')
                                <a href="{{ route('users.index') }}"
                                    class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">All Users</span>
                                </a>
                            @endcan

                            @can('view_roles')
                                <a href="{{ route('roles.index') }}"
                                    class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <div class="nav-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2"
                                                ry="2" />
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                        </svg>
                                    </div>
                                    <span class="nav-label">Roles & Permissions</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcanany

                 <!-- ADMIN INBOX SECTION (Start) -->
                @can('view_contacts')
                    <div class="nav-section animate-fade-up" style="animation-delay: 0.3s;">
                        <div class="nav-section-title"><span>Communication</span></div>

                        <a href="{{ route('admin.contacts.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <span class="nav-label">Inbox</span>
                            <!-- Optional: Red badge for visual emphasis -->
                            <div class="w-2 h-2 rounded-full bg-brand-500 shadow-[0_0_8px_rgba(6,196,162,0.6)]"></div>
                        </a>
                    </div>
                @endcan
                <!-- ADMIN INBOX SECTION (End) -->
                
                <!-- Subscription Section -->
                @canany(['view_subscription', 'manage_pharmacies'])
                    <div class="nav-section animate-fade-up" style="animation-delay: 0.25s;">
                        <div class="nav-section-title"><span>Subscription</span></div>

                        @if(auth()->user()->pharmacy_id === null)
                            <!-- Super Admin Link -->
                            <a href="{{ route('subscriptions.index') }}"
                                class="nav-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                        </rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </div>
                                <span class="nav-label">Manage Plans</span>
                            </a>
                        @else
                            <!-- Pharmacy User Link -->
                            <a href="{{ route('subscriptions.show', auth()->user()->pharmacy_id) }}"
                                class="nav-item {{ request()->routeIs('subscriptions.show') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <span class="nav-label">My Subscription</span>
                                @if (auth()->user()->pharmacy &&
                                        auth()->user()->pharmacy->subscription_ends_at &&
                                        auth()->user()->pharmacy->subscription_ends_at->isPast())
                                    <span class="badge badge-danger">Expired</span>
                                @endif
                            </a>
                        @endif
                    </div>
                @endcanany

                <!-- Quick Action -->
                @can('access_pos')
                    <div class="quick-action animate-fade-up" style="animation-delay: 0.3s;">
                        <p class="text-xs font-semibold text-white/80 mb-1">Quick Sale</p>
                        <p class="text-[11px] text-white/50 mb-3">Fast checkout for walk-in customers</p>
                        <a href="{{ route('pos.create') }}" class="quick-action-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            <span>New Transaction</span>
                        </a>
                    </div>
                @endcan

            </nav>

            <!-- User Profile -->
            <div class="relative z-10 p-3 flex-shrink-0 border-t border-white/5">
                <div class="user-profile">
                    <div class="user-avatar">{{ auth()->user()->name[0] }}</div>
                    <div class="user-info">
                        <p class="user-name">{{ auth()->user()->name }}</p>
                        <div class="user-role">
                            <span
                                class="user-role-badge">{{ auth()->user()->roles->first()->name ?? (auth()->user()->role ?? 'User') }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-72 flex flex-col min-h-screen transition-all duration-300">

            <!-- Top Header -->
            <header class="bg-white/90 backdrop-blur-xl border-b border-slate-200/50 px-6 py-4 sticky top-0 z-30">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden p-2.5 text-slate-600 hover:text-brand-600 hover:bg-slate-100 rounded-xl transition-colors">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="3" y1="12" x2="21" y2="12" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <line x1="3" y1="18" x2="21" y2="18" />
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex p-2.5 text-slate-600 hover:text-brand-600 hover:bg-slate-100 rounded-xl transition-colors">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="9" y1="3" x2="9" y2="21" />
                            </svg>
                        </button>

                        <div>
                            <h2 class="text-xl font-display font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-xs text-slate-500 hidden sm:block">Overview of your pharmacy operations</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Search -->
                        <div class="header-search hidden md:flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            <input type="text" placeholder="Search drugs, sales...">
                            <kbd
                                class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded hidden lg:block">⌘K</kbd>
                        </div>

                        <!-- Notifications -->
                        <button class="header-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                            <span class="notification-badge"></span>
                        </button>

                        <!-- Date Badge -->
                        <div
                            class="hidden lg:flex items-center gap-2 px-4 py-2.5 bg-brand-50 rounded-xl text-sm font-medium text-brand-700 border border-brand-100">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ now()->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-6">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity:0 transform -translate-y-2"
                        x-transition:enter-end="opacity:100 transform translate-y-0"
                        class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-200 rounded-full flex items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </div>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 p-1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                        class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-200 rounded-full flex items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                            </div>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800 p-1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- FAB for Quick POS -->
        @can('access_pos')
            <a href="{{ route('pos.create') }}" class="fab lg:hidden" title="New Sale">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <path d="M6 8h.01M10 8h.01M14 8h.01" />
                    <path d="M6 12h.01M10 12h.01M14 12h.01M18 12h.01" />
                    <path d="M6 16h12" />
                </svg>
            </a>
        @endcan
    </div>

    @stack('scripts')
</body>

</html>