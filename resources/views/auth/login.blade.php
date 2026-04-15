<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MediPrime</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
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
        :root {
            --bg: #0a0f14;
            --fg: #f8fafc;
            --muted: #64748b;
            --accent: #06c4a2;
            --accent-glow: rgba(6, 196, 162, 0.4);
            --card: rgba(15, 23, 32, 0.8);
            --border: rgba(100, 116, 139, 0.2);
            --glass: rgba(255, 255, 255, 0.03);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            min-height: 100vh;
        }
        
        /* Animated Background */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: 
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(6, 196, 162, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 80% 60%, rgba(14, 165, 233, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse 50% 30% at 50% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
                linear-gradient(180deg, #0a0f14 0%, #0f172a 100%);
        }
        
        /* Grid Pattern Overlay */
        .grid-pattern {
            position: fixed;
            inset: 0;
            z-index: 1;
            background-image: 
                linear-gradient(rgba(100, 116, 139, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100, 116, 139, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent);
        }
        
        /* Floating Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(1px);
            pointer-events: none;
        }
        
        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6, 196, 162, 0.3) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            animation: floatOrb 25s ease-in-out infinite;
        }
        
        .orb-2 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 70%);
            bottom: -50px;
            right: -50px;
            animation: floatOrb 20s ease-in-out infinite reverse;
        }
        
        .orb-3 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: floatOrb 15s ease-in-out infinite;
            animation-delay: -7s;
        }
        
        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 30px) scale(1.02); }
        }
        
        /* Molecular Structure Animation */
        .molecule {
            position: absolute;
            opacity: 0.6;
            animation: moleculeFloat 30s linear infinite;
        }
        
        .molecule-dot {
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent-glow);
        }
        
        .molecule-line {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            transform-origin: left center;
        }
        
        @keyframes moleculeFloat {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }
        
        /* Main Container */
        .login-container {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        
        .login-card {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 680px;
            background: rgba(15, 23, 32, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        /* Left Panel - Branding */
        .brand-panel {
            position: relative;
            width: 50%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            background: 
                linear-gradient(135deg, rgba(6, 196, 162, 0.1) 0%, transparent 50%),
                linear-gradient(225deg, rgba(14, 165, 233, 0.08) 0%, transparent 50%);
        }
        
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
        }
        
        /* Orbital Rings */
        .orbital-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            pointer-events: none;
        }
        
        .orbital-ring {
            position: absolute;
            border: 1px solid rgba(6, 196, 162, 0.2);
            border-radius: 50%;
            animation: orbitalSpin 20s linear infinite;
        }
        
        .orbital-ring:nth-child(1) {
            inset: 0;
            animation-duration: 25s;
        }
        
        .orbital-ring:nth-child(2) {
            inset: 30px;
            animation-duration: 20s;
            animation-direction: reverse;
        }
        
        .orbital-ring:nth-child(3) {
            inset: 60px;
            animation-duration: 15s;
        }
        
        .orbital-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 20px var(--accent-glow), 0 0 40px var(--accent-glow);
            animation: orbitalPulse 2s ease-in-out infinite;
        }
        
        @keyframes orbitalSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes orbitalPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.7; }
        }
        
        /* Logo */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            position: relative;
            z-index: 10;
        }
        
        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent), #0ea5e9);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(6, 196, 162, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .logo-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        
        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Brand Content */
        .brand-content {
            position: relative;
            z-index: 10;
        }
        
        .brand-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 700;
            line-height: 1.2;
            color: #f8fafc;
            margin-bottom: 1rem;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        /* Feature List */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 1rem;
            background: rgba(6, 196, 162, 0.05);
            border: 1px solid rgba(6, 196, 162, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            background: rgba(6, 196, 162, 0.1);
            border-color: rgba(6, 196, 162, 0.2);
            transform: translateX(8px);
        }
        
        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(6, 196, 162, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .feature-icon svg {
            width: 18px;
            height: 18px;
            color: var(--accent);
        }
        
        .feature-text {
            font-size: 0.9rem;
            color: #cbd5e1;
        }
        
        /* Brand Footer */
        .brand-footer {
            position: relative;
            z-index: 10;
            font-size: 0.8rem;
            color: #64748b;
        }
        
        /* Right Panel - Form */
        .form-panel {
            width: 50%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: rgba(255, 255, 255, 0.02);
        }
        
        .form-panel::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10%;
            bottom: 10%;
            width: 1px;
            background: linear-gradient(180deg, transparent, var(--border), transparent);
        }
        
        .form-container {
            max-width: 380px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Form Header */
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .form-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.875rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 0.5rem;
        }
        
        .form-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        /* Mobile Logo */
        .mobile-logo {
            display: none;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: color 0.3s ease;
            pointer-events: none;
        }
        
        .form-input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.75rem;
            background: rgba(15, 23, 32, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-input::placeholder {
            color: #475569;
        }
        
        .form-input:hover {
            border-color: rgba(100, 116, 139, 0.4);
        }
        
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6, 196, 162, 0.15);
        }
        
        .form-input:focus + .input-icon,
        .form-input:focus ~ .input-icon {
            color: var(--accent);
        }
        
        .form-input.has-error {
            border-color: #f43f5e;
        }
        
        .form-input.has-error:focus {
            box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.15);
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #94a3b8;
        }
        
        /* Error Message */
        .error-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #fb7185;
        }
        
        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox-input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        
        .checkbox-label {
            font-size: 0.85rem;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .checkbox-label:hover {
            color: #cbd5e1;
        }
        
        .forgot-link {
            font-size: 0.85rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #34d399;
            text-decoration: underline;
        }
        
        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, var(--accent) 0%, #02a385 100%);
            border: none;
            border-radius: 12px;
            color: #0a0f14;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(6, 196, 162, 0.4);
        }
        
        .submit-btn:hover::before {
            transform: translateX(100%);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .submit-btn svg {
            transition: transform 0.3s ease;
        }
        
        .submit-btn:hover svg {
            transform: translateX(4px);
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.75rem 0;
        }
        
        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        
        .divider-text {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        /* Social Buttons */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            background: rgba(15, 23, 32, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: #cbd5e1;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(100, 116, 139, 0.4);
            transform: translateY(-2px);
        }
        
        .social-btn svg {
            width: 18px;
            height: 18px;
        }
        
        /* Demo Box */
        .demo-box {
            margin-top: 2rem;
            padding: 1rem 1.25rem;
            background: rgba(6, 196, 162, 0.05);
            border: 1px solid rgba(6, 196, 162, 0.15);
            border-radius: 12px;
        }
        
        .demo-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.625rem;
        }
        
        .demo-content {
            font-size: 0.85rem;
            color: #94a3b8;
            line-height: 1.7;
        }
        
        .demo-code {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            background: rgba(6, 196, 162, 0.1);
            border-radius: 4px;
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 0.75rem;
            color: #34d399;
        }
        
        /* Status Message */
        .status-message {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            background: rgba(6, 196, 162, 0.1);
            border: 1px solid rgba(6, 196, 162, 0.2);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: #34d399;
        }
        
        /* Entrance Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        
        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .login-card {
                max-width: 480px;
                min-height: auto;
            }
            
            .brand-panel {
                display: none;
            }
            
            .form-panel {
                width: 100%;
                padding: 2rem;
            }
            
            .form-panel::before {
                display: none;
            }
            
            .mobile-logo {
                display: flex;
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                border-radius: 1.5rem;
            }
            
            .form-panel {
                padding: 1.5rem;
            }
            
            .social-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="bg-mesh"></div>
    <div class="grid-pattern"></div>
    
    <!-- Floating Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    
    <!-- Main Container -->
    <div class="login-container">
        <div class="login-card">
            
            <!-- Left Panel - Branding -->
            <div class="brand-panel">
                <!-- Orbital Animation -->
                <div class="orbital-container">
                    <div class="orbital-ring">
                        <div class="orbital-dot" style="top: 0; left: 50%; transform: translate(-50%, -50%);"></div>
                    </div>
                    <div class="orbital-ring">
                        <div class="orbital-dot" style="bottom: 20%; right: 0; transform: translate(50%, 50%);"></div>
                    </div>
                    <div class="orbital-ring">
                        <div class="orbital-dot" style="top: 30%; left: 0; transform: translate(-50%, -50%);"></div>
                    </div>
                </div>
                
                <!-- Logo -->
                <div class="brand-logo animate-in">
                    <div class="logo-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0a0f14;">
                            <path d="M10.5 20.5H6.5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h4"></path>
                            <path d="M13.5 20.5h4a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4"></path>
                            <path d="M10 7V4a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v3"></path>
                            <path d="M8 14h8"></path>
                            <path d="M8 11h8"></path>
                        </svg>
                    </div>
                    <span class="logo-text">DawaPoint</span>
                </div>
                
                <!-- Content -->
                <div class="brand-content">
                    <h1 class="brand-title animate-in delay-1">Next-Gen Pharmacy Management</h1>
                    <p class="brand-subtitle animate-in delay-2">Transform your pharmacy operations with intelligent inventory tracking, automated alerts, and comprehensive analytics.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item animate-in delay-3">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18 17V9"></path>
                                    <path d="M13 17V5"></path>
                                    <path d="M8 17v-3"></path>
                                </svg>
                            </div>
                            <span class="feature-text">Real-time Inventory Analytics</span>
                        </div>
                        <div class="feature-item animate-in delay-4">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <span class="feature-text">Smart Expiry Alerts (FEFO)</span>
                        </div>
                        <div class="feature-item animate-in delay-5">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <line x1="10" y1="9" x2="8" y2="9"></line>
                                </svg>
                            </div>
                            <span class="feature-text">Comprehensive Sales Reports</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="brand-footer animate-in delay-6">
                    2026 DawaPoint. Crafted for modern pharmacies.
                </div>
            </div>
            
            <!-- Right Panel - Form -->
            <div class="form-panel">
                <div class="form-container">
                    
                    <!-- Mobile Logo -->
                    <div class="mobile-logo brand-logo">
                        <div class="logo-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0a0f14;">
                                <path d="M10.5 20.5H6.5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h4"></path>
                                <path d="M13.5 20.5h4a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4"></path>
                                <path d="M10 7V4a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v3"></path>
                                <path d="M8 14h8"></path>
                                <path d="M8 11h8"></path>
                            </svg>
                        </div>
                        <span class="logo-text">DawaPoint</span>
                    </div>
                    
                    <!-- Header -->
                    <div class="form-header animate-in">
                        <h2 class="form-title">Welcome back</h2>
                        <p class="form-subtitle">Enter your credentials to access your account</p>
                    </div>
                    
                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="status-message animate-in delay-1">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    @endif
                    
                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <!-- Email -->
                        <div class="form-group animate-in delay-1">
                            <label class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="form-input @error('email') has-error @enderror" 
                                       placeholder="admin@pharmapos.com"
                                       required
                                       autofocus>
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                </span>
                            </div>
                            @error('email')
                            <p class="error-message">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="form-group animate-in delay-2" x-data="{ showPassword: false }">
                            <div class="form-options" style="margin-bottom: 0.5rem;">
                                <label class="form-label" style="margin-bottom: 0;">Password</label>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                                @endif
                            </div>
                            <div class="input-wrapper">
                                <input :type="showPassword ? 'text' : 'password'" 
                                       name="password" 
                                       class="form-input @error('password') has-error @enderror" 
                                       placeholder="Enter your password"
                                       required>
                                <span class="input-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="password-toggle"
                                        :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                    <svg x-show="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg x-show="showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                        <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                        <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                        <line x1="2" y1="2" x2="22" y2="22"></line>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <p class="error-message">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="form-options animate-in delay-3">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       class="checkbox-input">
                                <label for="remember" class="checkbox-label">Remember for 30 days</label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn animate-in delay-4">
                            <span>Sign In</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                        
                    </form>
                    
                    <!-- Divider -->
                    {{-- <div class="divider animate-in delay-5">
                        <div class="divider-line"></div>
                        <span class="divider-text">or continue with</span>
                        <div class="divider-line"></div>
                    </div>
                    
                    <!-- Social Login -->
                    <div class="social-grid animate-in delay-5">
                        <button class="social-btn" type="button">
                            <svg viewBox="0 0 24 24">
                                <path fill="#ea4335" d="M5.26620003,9.76452941 C6.19878754,6.93863203 8.85444915,4.90909091 12,4.90909091 C13.6909091,4.90909091 15.2181818,5.50909091 16.4181818,6.49090909 L19.9090909,3 C17.7818182,1.14545455 15.0545455,0 12,0 C7.27006974,0 3.1977497,2.69829785 1.23999023,6.65002441 L5.26620003,9.76452941 Z"/>
                                <path fill="#34a853" d="M16.0407269,18.0125889 C14.9509167,18.7163016 13.5660892,19.0909091 12,19.0909091 C8.86648613,19.0909091 6.21911939,17.076871 5.27698177,14.2678769 L1.23746264,17.3349879 C3.19279051,21.2936293 7.26500293,24 12,24 C14.9328362,24 17.7353462,22.9573905 19.834192,20.9995801 L16.0407269,18.0125889 Z"/>
                                <path fill="#4a90e2" d="M19.834192,20.9995801 C22.0291676,18.9520994 23.4545455,15.903663 23.4545455,12 C23.4545455,11.2909091 23.3454545,10.5272727 23.1818182,9.81818182 L12,9.81818182 L12,14.4545455 L18.4363636,14.4545455 C18.1187732,16.013626 17.2662994,17.2212117 16.0407269,18.0125889 L19.834192,20.9995801 L19.834192,20.9995801 Z"/>
                                <path fill="#fbbc05" d="M5.27698177,14.2678769 C5.03832634,13.556323 4.90909091,12.7937589 4.90909091,12 C4.90909091,11.2182781 5.03443647,10.4668121 5.26620003,9.76452941 L1.23999023,6.65002441 C0.43658717,8.26043162 0,10.0753848 0,12 C0,13.9195484 0.444780743,15.7## L1.23746264,17.3349879 L5.27698177,14.2678769 L5.27698177,14.2678769 Z"/>
                            </svg>
                            <span>Google</span>
                        </button>
                        <button class="social-btn" type="button">
                            <svg viewBox="0 0 24 24" fill="#00a4ef">
                                <path d="M11.4,24H0V12.6h11.4V24z M12.6,0H24v11.4H12.6V0z M0,11.4h11.4V0H0V11.4z M12.6,12.6H24V24H12.6V12.6z"/>
                            </svg>
                            <span>Microsoft</span>
                        </button>
                    </div> --}}
                    
                    <!-- Demo Credentials -->
                    {{-- <div class="demo-box animate-in delay-6">
                        <div class="demo-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Demo Credentials
                        </div>
                        <div class="demo-content">
                            Email: <span class="demo-code">admin@pharmapos.com</span><br>
                            Password: <span class="demo-code">password</span>
                        </div>
                    </div> --}}
                    
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        // Initialize Alpine.js visibility toggle fix
        document.addEventListener('alpine:init', () => {
            Alpine.directive('show', (el, { expression }, { effect, evaluateLater }) => {
                let getValue = evaluateLater(expression);
                effect(() => {
                    getValue(value => {
                        el.style.display = value ? 'block' : 'none';
                    });
                });
            });
        });
        
        // Focus ring enhancement
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
        
        // Intersection Observer for scroll animations (if page scrolls)
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.animate-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>