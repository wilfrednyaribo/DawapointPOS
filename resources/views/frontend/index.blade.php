<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DawaPoint — Pharmacy SaaS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- CSRF Token (Preserved from your snippet) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


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
        /* ── LIGHT THEME VARIABLES (Updated for White Background) ── */
        :root {
            /* Background is now pure white */
            --bg: #ffffff;
            /* Text is dark slate */
            --fg: #0f172a;
            /* Muted text */
            --muted: #64748b;
            /* Accent remains the vibrant teal */
            --accent: #06c4a2;
            /* Subtle glow for light mode */
            --accent-glow: rgba(6, 196, 162, 0.25);
            /* Card backgrounds are very light grey/white */
            --card: rgba(255, 255, 255, 0.9);
            /* Borders are subtle grey */
            --border: rgba(15, 23, 32, 0.08);
            /* Glass effect is frosted white */
            --glass: rgba(255, 255, 255, 0.7);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--fg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Background (Subtle Mesh for Light Mode) ── */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% 30%, rgba(6, 196, 162, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(14, 165, 233, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse 50% 30% at 50% 90%, rgba(139, 92, 246, 0.03) 0%, transparent 60%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .grid-pattern {
            position: fixed;
            inset: 0;
            z-index: 1;
            background-image: linear-gradient(rgba(148, 163, 184, 0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent);
            -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent);
            pointer-events: none;
        }

        /* ── Orbs (Light Mode: Soft colored blobs) ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px)
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: rgba(6, 196, 162, 0.08);
            top: -120px;
            left: -120px;
            animation: floatOrb 25s ease-in-out infinite
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: rgba(14, 165, 233, 0.06);
            bottom: -80px;
            right: -80px;
            animation: floatOrb 20s ease-in-out infinite reverse
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: rgba(139, 92, 246, 0.05);
            top: 40%;
            left: 60%;
            animation: floatOrb 18s ease-in-out infinite;
            animation-delay: -8s
        }

        @keyframes floatOrb {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            25% {
                transform: translate(30px, -30px) scale(1.05)
            }

            50% {
                transform: translate(-20px, 20px) scale(0.95)
            }

            75% {
                transform: translate(20px, 30px) scale(1.02)
            }
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1rem 2rem;
            transition: all .4s ease
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            padding: .75rem 2rem
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent), #0ea5e9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(6, 196, 162, 0.25);
            position: relative;
            overflow: hidden;
            flex-shrink: 0
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.4) 50%, transparent 100%);
            transform: translateX(-100%);
            animation: shimmer 3s infinite
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%)
            }
        }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0f172a 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none
        }

        .nav-links a {
            font-size: .9rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: color .3s;
            position: relative
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width .3s
        }

        .nav-links a:hover {
            color: #0f172a
        }

        .nav-links a:hover::after {
            width: 100%
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: .75rem
        }

        /* ── Buttons ── */
        .btn-ghost {
            padding: .6rem 1.25rem;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            color: #475569;
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }

        .btn-ghost:hover {
            border-color: var(--accent);
            background: rgba(6, 196, 162, 0.05);
            color: #0f172a;
            transform: translateY(-1px)
        }

        .btn-primary {
            padding: .6rem 1.5rem;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, var(--accent), #02a385);
            border: none;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(6, 196, 162, 0.3)
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.2) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform .5s
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 196, 162, 0.4)
        }

        .btn-primary:hover::before {
            transform: translateX(100%)
        }

        .btn-large {
            padding: 1rem 2.25rem;
            font-size: 1rem;
            border-radius: 14px
        }

        .btn-large svg {
            transition: transform .3s
        }

        .btn-large:hover svg {
            transform: translateX(4px)
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 2rem 4rem
        }

        .hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem 1rem;
            background: #f0fdfa;
            border: 1px solid rgba(6, 196, 162, 0.2);
            border-radius: 100px;
            font-size: .78rem;
            font-weight: 600;
            color: #0f766e;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02)
        }

        .hero-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: pulse 2s ease-in-out infinite
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .5;
                transform: scale(1.3)
            }
        }

        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.25rem, 4.5vw, 3.5rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em
        }

        .hero-title .accent {
            background: linear-gradient(135deg, var(--accent), #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .hero-desc {
            font-size: 1.1rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 520px
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border)
        }

        .hero-stat-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a
        }

        .hero-stat-value span {
            color: var(--accent)
        }

        .hero-stat-label {
            font-size: .8rem;
            color: #64748b;
            margin-top: .2rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Hero Visual (Light Mode Dashboard) ── */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center
        }

        .hero-visual-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6, 196, 162, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            animation: floatOrb 10s ease-in-out infinite
        }

        .dashboard-preview {
            position: relative;
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 20px 50px -12px rgba(15, 23, 32, 0.15);
        }

        .dash-topbar {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc
        }

        .dash-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%
        }

        .dash-body {
            padding: 1.5rem
        }

        .dash-row {
            display: flex;
            gap: .75rem;
            margin-bottom: 1.5rem
        }

        .dash-card {
            flex: 1;
            padding: 1rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02)
        }

        .dash-card-label {
            font-size: .7rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .25rem;
            font-weight: 600
        }

        .dash-card-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a
        }

        .dash-card-change {
            font-size: .7rem;
            color: var(--accent);
            margin-top: .15rem;
            font-weight: 600
        }

        .dash-chart {
            height: 120px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02)
        }

        .dash-chart-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%
        }

        .dash-table {
            width: 100%;
            margin-top: 1.5rem
        }

        .dash-table-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .75rem .5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background .2s
        }

        .dash-table-row:last-child {
            border-bottom: none
        }

        .dash-table-row:hover {
            background: #f8fafc
        }

        .dash-table-name {
            font-size: .85rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 500
        }

        .dash-table-pill {
            padding: .15rem .6rem;
            border-radius: 100px;
            font-size: .65rem;
            font-weight: 600
        }

        .pill-green {
            background: #dcfce7;
            color: #15803d
        }

        .pill-yellow {
            background: #fef9c3;
            color: #a16207
        }

        .pill-red {
            background: #fee2e2;
            color: #b91c1c
        }

        .dash-table-qty {
            font-size: .85rem;
            color: #64748b;
            font-family: 'Space Grotesk', sans-serif
        }

        /* ── Floating Cards (Light Mode) ── */
        .float-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: .75rem 1rem;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
            animation: floatCard 6s ease-in-out infinite
        }

        @keyframes floatCard {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
            }
        }

        .float-card-1 {
            top: -10px;
            right: -20px;
            animation-delay: 0s
        }

        .float-card-2 {
            bottom: 40px;
            left: -30px;
            animation-delay: -2s
        }

        .float-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .4rem
        }

        .float-card-label {
            font-size: .7rem;
            color: #94a3b8;
            font-weight: 600
        }

        .float-card-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            color: #0f172a
        }

        /* ── Section Shared ── */
        section {
            position: relative;
            z-index: 10
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem 1rem;
            background: #f0fdfa;
            border: 1px solid rgba(6, 196, 162, 0.15);
            border-radius: 100px;
            font-size: .75rem;
            font-weight: 600;
            color: #0d9488;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 1rem
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 700;
            line-height: 1.15;
            color: #0f172a;
            margin-bottom: 1rem
        }

        .section-desc {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.7;
            max-width: 600px
        }

        /* Glass Cards (Light Mode: White cards with soft shadow) */
        .glass-card {
            background: var(--card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            transition: all .4s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }

        .glass-card:hover {
            border-color: var(--accent);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -8px rgba(6, 196, 162, 0.12);
        }

        /* ── Features Grid ── */
        .features {
            padding: 6rem 2rem
        }

        .features-inner {
            max-width: 1280px;
            margin: 0 auto
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 3rem
        }

        .feature-card {
            padding: 2rem;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), #0ea5e9);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .4s ease
        }

        .feature-card:hover::before {
            transform: scaleX(1)
        }

        .feature-icon-lg {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #f0fdfa;
            border: 1px solid rgba(6, 196, 162, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: all .3s
        }

        .feature-card:hover .feature-icon-lg {
            background: var(--accent);
            box-shadow: 0 4px 12px rgba(6, 196, 162, 0.3)
        }

        .feature-card:hover .feature-icon-lg svg {
            stroke: #fff
        }

        .feature-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .6rem
        }

        .feature-card p {
            font-size: .875rem;
            color: #64748b;
            line-height: 1.6
        }

        .feature-card ul {
            list-style: none;
            margin-top: .75rem;
            display: flex;
            flex-direction: column;
            gap: .4rem
        }

        .feature-card ul li {
            font-size: .8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: .4rem
        }

        .feature-card ul li svg {
            color: var(--accent);
            flex-shrink: 0
        }

        /* ── Stats ── */
        .stats {
            padding: 4rem 2rem
        }

        .stats-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem
        }

        .stat-card {
            padding: 2rem;
            text-align: center;
            background: #fff;
        }

        .stat-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0f172a, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .stat-label {
            font-size: .85rem;
            color: #64748b;
            margin-top: .25rem
        }

        /* ── Pricing Section ── */
        .pricing-section {
            padding: 6rem 2rem;
            background: linear-gradient(to bottom, transparent, rgba(6, 196, 162, 0.03), transparent);
        }

        .pricing-grid {
            max-width: 1200px;
            margin: 3rem auto 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .pricing-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent);
            box-shadow: 0 20px 40px -10px rgba(6, 196, 162, 0.15);
        }

        .pricing-card.popular::before {
            content: 'MOST POPULAR';
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--accent);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 2.5rem;
            transform: rotate(45deg);
        }

        .price-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .plan-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .price-amount {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .price-period {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .registration-fee {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .price-features {
            flex-grow: 1;
            margin-bottom: 2rem;
        }

        .price-features ul {
            list-style: none;
        }

        .price-features li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #475569;
            font-size: 0.95rem;
        }

        .price-features li svg {
            color: var(--accent);
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        /* ── CTA ── */
        .cta {
            padding: 6rem 2rem
        }

        .cta-inner {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            position: relative
        }

        .cta-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(6, 196, 162, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none
        }

        .cta-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 1rem;
            color: #0f172a
        }

        .cta-desc {
            font-size: 1.05rem;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto
        }

        .cta-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap
        }

        /* ── Footer ── */
        .footer {
            position: relative;
            z-index: 10;
            border-top: 1px solid var(--border);
            padding: 3rem 2rem 2rem;
            background: #f8fafc
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem
        }

        .footer-brand p {
            font-size: .85rem;
            color: #64748b;
            line-height: 1.7;
            margin-top: 1rem;
            max-width: 280px
        }

        .footer-col h4 {
            font-size: .8rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 1rem
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .6rem
        }

        .footer-col ul a {
            font-size: .85rem;
            color: #64748b;
            text-decoration: none;
            transition: color .3s
        }

        .footer-col ul a:hover {
            color: var(--accent)
        }

        .footer-bottom {
            max-width: 1280px;
            margin: 2rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .8rem;
            color: #94a3b8
        }

        /* ── Scroll Animations ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all .7s cubic-bezier(.22, 1, .36, 1)
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0)
        }

        .reveal-delay-1 {
            transition-delay: .1s
        }

        .reveal-delay-2 {
            transition-delay: .2s
        }

        .reveal-delay-3 {
            transition-delay: .3s
        }

        .reveal-delay-4 {
            transition-delay: .4s
        }

        /* ── Mobile Nav ── */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #0f172a;
            cursor: pointer;
            padding: .5rem
        }

        .mobile-menu {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem
        }

        .mobile-menu.open {
            display: flex
        }

        .mobile-menu a {
            font-size: 1.25rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: color .3s
        }

        .mobile-menu a:hover {
            color: var(--accent)
        }

        .mobile-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            color: #0f172a;
            cursor: pointer
        }

        /* ── Responsive ── */
        @media(max-width:1024px) {
            .hero-inner {
                grid-template-columns: 1fr;
                text-align: center
            }

            .hero-desc {
                margin-left: auto;
                margin-right: auto
            }

            .hero-actions {
                justify-content: center
            }

            .hero-stats {
                justify-content: center
            }

            .hero-visual {
                margin-top: 3rem;
                order: -1
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .stats-inner {
                grid-template-columns: repeat(2, 1fr)
            }

            .footer-inner {
                grid-template-columns: 1fr 1fr
            }

            .float-card {
                display: none
            }

            .nav-links {
                display: none
            }

            .mobile-toggle {
                display: block
            }
        }

        @media(max-width:640px) {
            .features-grid {
                grid-template-columns: 1fr
            }

            .stats-inner {
                grid-template-columns: 1fr
            }

            .footer-inner {
                grid-template-columns: 1fr
            }

            .hero-stats {
                flex-direction: column;
                gap: 1rem
            }

            .footer-bottom {
                flex-direction: column;
                gap: .5rem;
                text-align: center
            }

            .cta-actions {
                flex-direction: column
            }

            .hero-actions {
                flex-direction: column;
                align-items: center
            }
        }

        @media(prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important
            }
        }
    </style>
</head>

<body>

    <!-- Background -->
    <div class="bg-mesh"></div>
    <div class="grid-pattern"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-inner">
            <!-- Logo links to Home route -->
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:.75rem;text-decoration:none">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.5 20.5H6.5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h4" />
                        <path d="M13.5 20.5h4a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4" />
                        <path d="M10 7V4a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v3" />
                        <path d="M8 14h8" />
                        <path d="M8 11h8" />
                    </svg>
                </div>
                <span class="logo-text">DawaPoint</span>
            </a>
            <ul class="nav-links">
                <!-- Anchor links for the same page -->
                <li><a href="#hero">Home</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#features">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li> <a href="tel:+254741473024" class="btn-ghost d-flex align-items-center gap-2"
                        style="padding: 0.5rem 1.25rem; font-size: 0.9rem; color: #28a745; border-color: #28a745;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        +254 741 473 024
                    </a></li>

            </ul>
            <div class="nav-actions">
                <!-- Login Route -->
                <a href="{{ route('login') }}" class="btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Login
                </a>
                <!-- CTA Anchor -->
                <a href="#pricing" class="btn-primary">Get Started</a>
            </div>
            <button class="mobile-toggle" onclick="document.getElementById('mobileMenu').classList.add('open')"
                aria-label="Open menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-close" onclick="document.getElementById('mobileMenu').classList.remove('open')"
            aria-label="Close menu">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
        <a href="#hero" onclick="document.getElementById('mobileMenu').classList.remove('open')">Home</a>
        <a href="#pricing" onclick="document.getElementById('mobileMenu').classList.remove('open')">Pricing</a>
        <a href="#features" onclick="document.getElementById('mobileMenu').classList.remove('open')">Services</a>
        <a href="#stats" onclick="document.getElementById('mobileMenu').classList.remove('open')">Contact</a>


        <!-- Login Route -->
        <a href="{{ route('login') }}" class="btn-primary btn-large"
            onclick="document.getElementById('mobileMenu').classList.remove('open')">Login to Dashboard</a>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-inner">
            <div>
                <div class="hero-badge reveal">
                    <span class="dot"></span>
                    Trusted by 15+ pharmacies

                </div>
                {{-- <a href="tel:+254741473024" class="btn-ghost d-flex align-items-center gap-2" style="padding: 0.5rem 1.25rem; font-size: 0.9rem; color: #28a745; border-color: #28a745;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
    </svg>
    +254 741 473 024
</a> --}}
                <h1 class="hero-title reveal reveal-delay-1">
                    A SaaS Solution Making Pharmacy Operation <span class="accent">Effortless</span>
                </h1>
                <p class="hero-desc reveal reveal-delay-2">
                    DawaPoint is an all-in-one pharmacy POS management platform — handle sales, inventory, expiry
                    tracking, wholesale ordering, and financial reporting from a single dashboard.
                </p>
                <div class="hero-actions reveal reveal-delay-3">
                    <!-- Start Free Trial Button -->
                    <a href="#pricing" class="btn-primary btn-large">
                        Start Free Trial
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>

                    <!-- Watch Demo Button -->
                    <a href="#features" class="btn-ghost" style="padding:1rem 1.75rem">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polygon points="10 8 16 12 10 16 10 8" />
                        </svg>
                        Watch Demo
                    </a>

                    <!-- Call Us Button (Added) -->
                    <a href="tel:+254741473024" class="btn-ghost d-flex align-items-center gap-2"
                        style="padding: 0.5rem 1.25rem; font-size: 0.9rem; color: #28a745; border-color: #28a745;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        +254 741 473 024
                    </a>


                </div>
                <div class="hero-stats reveal reveal-delay-4">
                    <div>
                        <div class="hero-stat-value">15<span>+</span></div>
                        <div class="hero-stat-label">Active Pharmacies</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">10K<span>+</span></div>
                        <div class="hero-stat-label">Prescriptions</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">99.9<span>%</span></div>
                        <div class="hero-stat-label">Uptime</div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="hero-visual reveal reveal-delay-2">
                <div class="hero-visual-glow"></div>
                <div class="dashboard-preview">
                    <!-- Top Bar -->
                    <div class="dash-topbar">
                        <div class="dash-dot" style="background:#f43f5e"></div>
                        <div class="dash-dot" style="background:#facc15"></div>
                        <div class="dash-dot" style="background:#22c55e"></div>
                        <span style="margin-left:auto;font-size:.7rem;color:#94a3b8">DawaPoint Dashboard</span>
                    </div>
                    <div class="dash-body">
                        <!-- Stat Cards -->
                        <div class="dash-row">
                            <div class="dash-card">
                                <div class="dash-card-label">Today's Sales</div>
                                <div class="dash-card-value" id="salesCounter">Ksh 0</div>
                                <div class="dash-card-change">+12.5% vs yesterday</div>
                            </div>
                            <div class="dash-card">
                                <div class="dash-card-label">Orders</div>
                                <div class="dash-card-value" id="ordersCounter">0</div>
                                <div class="dash-card-change">+8.3% vs yesterday</div>
                            </div>
                            <div class="dash-card">
                                <div class="dash-card-label">Low Stock</div>
                                <div class="dash-card-value" style="color:#ef4444">7</div>
                                <div class="dash-card-change" style="color:#ef4444">Needs restock</div>
                            </div>
                        </div>
                        <!-- Chart Area -->
                        <div class="dash-chart">
                            <canvas id="miniChart" width="460" height="120"></canvas>
                        </div>
                        <!-- Table -->
                        <div class="dash-table">
                            <div class="dash-table-row">
                                <span class="dash-table-name">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:var(--accent);display:inline-block;flex-shrink:0"></span>
                                    Amoxicillin 500mg
                                </span>
                                <span class="dash-table-pill pill-green">In Stock</span>
                                <span class="dash-table-qty">340 units</span>
                            </div>
                            <div class="dash-table-row">
                                <span class="dash-table-name">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:#eab308;display:inline-block;flex-shrink:0"></span>
                                    Paracetamol 1g
                                </span>
                                <span class="dash-table-pill pill-yellow">Low Stock</span>
                                <span class="dash-table-qty">12 units</span>
                            </div>
                            <div class="dash-table-row">
                                <span class="dash-table-name">
                                    <span
                                        style="width:8px;height:8px;border-radius:50%;background:#ef4444;display:inline-block;flex-shrink:0"></span>
                                    Metformin 850mg
                                </span>
                                <span class="dash-table-pill pill-red">Expiring</span>
                                <span class="dash-table-qty">28 units</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Cards -->
                <div class="float-card float-card-1">
                    <div class="float-card-icon" style="background:#f0fdfa">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#06c4a2"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                            <polyline points="16 7 22 7 22 13" />
                        </svg>
                    </div>
                    <div class="float-card-label">Revenue Growth</div>
                    <div class="float-card-value" style="color:#059669">+23.4%</div>
                </div>
                <div class="float-card float-card-2">
                    <div class="float-card-icon" style="background:#f0f9ff">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="float-card-label">Avg. Processing</div>
                    <div class="float-card-value" style="color:#0284c7">1.2s</div>
                </div>
            </div>
        </div>
    </section>


    <!-- Hero Section (Image Left, Text Right) -->

    <!-- Core Features -->
    <!-- Core Features -->
    <section class="features" id="features">
        <div class="features-inner">
            <!-- Section Header -->
            <div style="text-align:center; max-width: 700px; margin: 0 auto 4rem;">
                <div class="section-badge reveal">Platform Modules</div>
                <h2 class="section-title reveal reveal-delay-1">Everything Your Pharmacy Needs</h2>
                <p class="section-desc reveal reveal-delay-2" style="margin:0 auto">
                    Four powerful modules designed to cover every aspect of modern pharmacy operations — from
                    point-of-sale to wholesale distribution.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="features-grid">

                <!-- 1. POS Module -->
                <div class="glass-card feature-card reveal"
                    style="background: linear-gradient(180deg, #ffffff 0%, #f0fdfa 100%); border: 1px solid rgba(6, 196, 162, 0.2);">
                    <div style="margin-bottom: 1.5rem; position: relative; z-index: 1;">
                        <!-- Pill Label -->
                        <div
                            style="font-size: 0.75rem; font-weight: 700; color: #0f766e; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; display: inline-block; background: rgba(6, 196, 162, 0.1); padding: 0.25rem 0.75rem; border-radius: 100px;">
                            Module 01
                        </div>
                        <div class="feature-icon-lg"
                            style="background: #fff; box-shadow: 0 4px 6px -1px rgba(6, 196, 162, 0.1);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#06c4a2"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                                <path d="M6 8h.01" />
                                <path d="M10 8h.01" />
                                <path d="M14 8h.01" />
                            </svg>
                        </div>
                    </div>

                    <h3
                        style="font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">
                        Point of Sales</h3>
                    <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                        Lightning-fast POS with barcode scanning, prescription handling, and automatic receipt
                        generation.
                    </p>

                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#15803d" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Barcode & QR scanning
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#15803d" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Insurance claim processing
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#15803d" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Multi-payment support
                        </li>
                    </ul>
                </div>

                <!-- 2. Inventory Module -->
                <div class="glass-card feature-card reveal reveal-delay-1"
                    style="background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%); border: 1px solid rgba(14, 165, 233, 0.2);">
                    <div style="margin-bottom: 1.5rem; position: relative; z-index: 1;">
                        <!-- Pill Label -->
                        <div
                            style="font-size: 0.75rem; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; display: inline-block; background: rgba(14, 165, 233, 0.1); padding: 0.25rem 0.75rem; border-radius: 100px;">
                            Module 02
                        </div>
                        <div class="feature-icon-lg"
                            style="background: #fff; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.1);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                <line x1="12" y1="22.08" x2="12" y2="12" />
                            </svg>
                        </div>
                    </div>

                    <h3
                        style="font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">
                        Inventory Management</h3>
                    <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                        Real-time stock tracking with FEFO compliance, automated reorder points, and batch-level expiry
                        management.
                    </p>

                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#0369a1" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            FEFO expiry tracking
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#0369a1" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Auto-reorder alerts
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#0369a1" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Multi-branch sync
                        </li>
                    </ul>
                </div>

                <!-- 3. Finance Module -->
                <div class="glass-card feature-card reveal reveal-delay-2"
                    style="background: linear-gradient(180deg, #ffffff 0%, #f5f3ff 100%); border: 1px solid rgba(139, 92, 246, 0.2);">
                    <div style="margin-bottom: 1.5rem; position: relative; z-index: 1;">
                        <!-- Pill Label -->
                        <div
                            style="font-size: 0.75rem; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; display: inline-block; background: rgba(139, 92, 246, 0.1); padding: 0.25rem 0.75rem; border-radius: 100px;">
                            Module 03
                        </div>
                        <div class="feature-icon-lg"
                            style="background: #fff; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.1);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                    </div>

                    <h3
                        style="font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">
                        Embedded Finance</h3>
                    <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                        Integrated accounting, credit management for wholesale customers, and automated financial
                        reporting.
                    </p>

                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #ede9fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#7c3aed" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Profit & loss reports
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #ede9fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#7c3aed" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Customer credit limits
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #ede9fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#7c3aed" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Tax compliance
                        </li>
                    </ul>
                </div>

                <!-- 4. Wholesale Module -->
                <div class="glass-card feature-card reveal reveal-delay-3"
                    style="background: linear-gradient(180deg, #ffffff 0%, #fefce8 100%); border: 1px solid rgba(234, 179, 8, 0.2);">
                    <div style="margin-bottom: 1.5rem; position: relative; z-index: 1;">
                        <!-- Pill Label -->
                        <div
                            style="font-size: 0.75rem; font-weight: 700; color: #ca8a04; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; display: inline-block; background: rgba(234, 179, 8, 0.1); padding: 0.25rem 0.75rem; border-radius: 100px;">
                            Module 04
                        </div>
                        <div class="feature-icon-lg"
                            style="background: #fff; box-shadow: 0 4px 6px -1px rgba(234, 179, 8, 0.1);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#eab308"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                    </div>

                    <h3
                        style="font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">
                        Wholesale E-Commerce</h3>
                    <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                        B2B ordering portal for retail pharmacies with tiered pricing, order tracking, and delivery
                        management.
                    </p>

                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #fef9c3; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#ca8a04" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Tiered pricing engine
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #fef9c3; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#ca8a04" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Order status tracking
                        </li>
                        <li
                            style="font-size: 0.85rem; color: #334155; display: flex; align-items: center; gap: 0.75rem;">
                            <span
                                style="width: 20px; height: 20px; background: #fef9c3; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="#ca8a04" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            Delivery scheduling
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>


    <!-- Contact Section -->
    <section class="features" id="contact" x-data="contactForm()">
        <div class="features-inner">

            <div style="text-align:center; margin-bottom: 3rem;">
                <div class="section-badge">Get In Touch</div>
                <h2 class="section-title">Ready to Upgrade Your Pharmacy?</h2>
                <p class="section-desc">
                    Fill out the form below and our team will get back to you within an hour.
                </p>
            </div>

            <div class="glass-card" style="max-width: 900px; margin: 0 auto;">
                <div style="display: grid; grid-template-columns: 1fr 1.5fr;">

                    <!-- LEFT -->
                    <div
                        style="background: linear-gradient(135deg, #06c4a2, #0f766e); padding: 2rem; color: white; display:flex; flex-direction:column; justify-content:space-between;">

                        <div>
                            <h3>Let's start a conversation</h3>
                            <p>Our tech experts are ready to help you streamline operations.</p>

                            <!-- CONTACT DETAILS -->
                            <div class="flex flex-col gap-6 mt-6">

                                <!-- Phone -->
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs opacity-70">Call Us</div>
                                        <div class="font-semibold">+254 741 473 024</div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path d="M4 4h16v16H4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs opacity-70">Email Us</div>
                                        <div class="font-semibold">sales@dawapoint.com</div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs opacity-70">Visit Us</div>
                                        <div class="font-semibold">Nairobi, Westlands</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- SOCIALS -->
                        <div>
                            <div class="text-xs opacity-70 mb-2">Follow Us</div>
                            <div class="flex gap-3">

                                <!-- Facebook -->
                                <a href="#"
                                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                        </path>
                                    </svg>
                                </a>

                                <!-- Instagram -->
                                <a href="#"
                                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                </a>

                                <!-- LinkedIn -->
                                <a href="#"
                                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                        </path>
                                        <rect x="2" y="9" width="4" height="12"></rect>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg>
                                </a>

                            </div>
                        </div>

                    </div>

                    <!-- RIGHT FORM -->
                    <div style="padding: 3rem 2.5rem; background: #ffffff; position: relative; min-height: 550px;">
    
    <!-- SUCCESS OVERLAY (Modern Centered Design) -->
    <div x-show="formSubmitted" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         style="
            position: absolute; 
            inset: 0; 
            background: #ffffff; 
            z-index: 10; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            padding: 2rem;
            border-radius: 0 1.5rem 1.5rem 0;
         ">
        
        <!-- Success Icon Circle -->
        <div style="
            width: 80px; 
            height: 80px; 
            background: #dcfce7; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(6, 196, 162, 0.2);
        ">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        
        <h3 style="
            font-family: 'Space Grotesk', sans-serif; 
            font-size: 1.75rem; 
            font-weight: 700; 
            color: #0f172a; 
            margin-bottom: 0.5rem;">
            Message Sent!
        </h3>
        <p style="color: #64748b; font-size: 1rem;">Our team will contact you shortly.</p>
    </div>

    <!-- MODERN FORM -->
    <form @submit.prevent="submitForm($event)" style="display: flex; flex-direction: column; gap: 1.25rem; height: 100%;">
        
        @csrf

        <!-- Full Name Input -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label style="font-size: 0.875rem; font-weight: 600; color: #475569;">Full Name</label>
            <input type="text" name="full_name" placeholder="e.g. Wilfred " required
                style="
                    width: 100%; 
                    padding: 0.875rem 1rem; 
                    border-radius: 0.75rem; 
                    border: 1px solid #e2e8f0; 
                    background: #f8fafc; 
                    font-family: inherit; 
                    font-size: 0.95rem;
                    transition: all 0.2s;
                    outline: none;
                    color: #334155;
                "
                onfocus="this.style.borderColor='#06c4a2'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(6,196,162,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
        </div>

        <!-- Two Column Grid for Phone & Pharmacy -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label style="font-size: 0.875rem; font-weight: 600; color: #475569;">Phone Number</label>
                <input type="text" name="phone_number" placeholder="+254741473024 " required
                    style="
                        width: 100%; 
                        padding: 0.875rem 1rem; 
                        border-radius: 0.75rem; 
                        border: 1px solid #e2e8f0; 
                        background: #f8fafc; 
                        font-family: inherit; 
                        font-size: 0.95rem;
                        transition: all 0.2s;
                        outline: none;
                        color: #334155;
                    "
                    onfocus="this.style.borderColor='#06c4a2'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(6,196,162,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label style="font-size: 0.875rem; font-weight: 600; color: #475569;">Pharmacy or Chemist Name</label>
                <input type="text" name="pharmacy_name" placeholder="e.g. Medicare "
                    style="
                        width: 100%; 
                        padding: 0.875rem 1rem; 
                        border-radius: 0.75rem; 
                        border: 1px solid #e2e8f0; 
                        background: #f8fafc; 
                        font-family: inherit; 
                        font-size: 0.95rem;
                        transition: all 0.2s;
                        outline: none;
                        color: #334155;
                    "
                    onfocus="this.style.borderColor='#06c4a2'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(6,196,162,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
            </div>
        </div>

        <!-- Email Input -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label style="font-size: 0.875rem; font-weight: 600; color: #475569;">Email Address</label>
            <input type="email" name="email" placeholder="wilfred@medicare.com" required
                style="
                    width: 100%; 
                    padding: 0.875rem 1rem; 
                    border-radius: 0.75rem; 
                    border: 1px solid #e2e8f0; 
                    background: #f8fafc; 
                    font-family: inherit; 
                    font-size: 0.95rem;
                    transition: all 0.2s;
                    outline: none;
                    color: #334155;
                "
                onfocus="this.style.borderColor='#06c4a2'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(6,196,162,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
        </div>

        <!-- Message Textarea -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label style="font-size: 0.875rem; font-weight: 600; color: #475569;">Message</label>
            <textarea name="message" rows="4" placeholder="Tell us about your needs..." required
                style="
                    width: 100%; 
                    padding: 0.875rem 1rem; 
                    border-radius: 0.75rem; 
                    border: 1px solid #e2e8f0; 
                    background: #f8fafc; 
                    font-family: inherit; 
                    font-size: 0.95rem;
                    transition: all 0.2s;
                    outline: none;
                    color: #334155;
                    resize: vertical;
                    min-height: 100px;
                "
                onfocus="this.style.borderColor='#06c4a2'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(6,196,162,0.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"></textarea>
        </div>

        <!-- Submit Button -->
        <div style="margin-top: auto;">
            <button type="submit" :disabled="loading"
                style="
                    width: 100%; 
                    padding: 0.875rem 1.5rem; 
                    border-radius: 0.75rem; 
                    font-size: 1rem; 
                    font-weight: 700; 
                    color: #ffffff; 
                    background: linear-gradient(135deg, #06c4a2, #02a385); 
                    border: none; 
                    cursor: pointer; 
                    transition: all 0.3s; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    gap: 0.5rem;
                    box-shadow: 0 4px 12px rgba(6, 196, 162, 0.3);
                "
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(6, 196, 162, 0.4)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(6, 196, 162, 0.3)'">
                
                <!-- Loading Spinner (Hidden by default) -->
                <template x-if="loading">
                    <svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                </template>
                
                <span x-text="loading ? 'Sending...' : 'Send Message'"></span>
            </button>
        </div>
    </form>

    <!-- Inline Style for Spinner Animation -->
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</div>
                </div>
            </div>

        </div>
    </section>
    <!-- ALPINE FUNCTION -->
    <script>
        function contactForm() {
            return {
                loading: false,
                formSubmitted: false,

                submitForm(event) {
                    const form = event.target;
                    const formData = new FormData(form);

                    this.loading = true;

                    fetch("{{ route('contact.submit') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(res => {
                            if (!res.ok) return res.json().then(err => {
                                throw err
                            });
                            return res.json();
                        })
                        .then(data => {
                            this.loading = false;

                            if (data.status === "success") {
                                this.formSubmitted = true;
                                form.reset();

                                setTimeout(() => this.formSubmitted = false, 3000);
                            }
                        })
                        .catch(err => {
                            this.loading = false;

                            if (err.errors) {
                                alert(Object.values(err.errors).flat().join('\n'));
                            } else {
                                alert("Server error");
                            }

                            console.error(err);
                        });
                }
            }
        }
    </script>

    <!-- Pricing Section -->
    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="features-inner">
            <!-- Section Header -->
            <div style="text-align:center; margin-bottom: 3rem;">
                <div class="section-badge reveal">Our Pricing</div>
                <h2 class="section-title reveal reveal-delay-1">Simple, Transparent Pricing</h2>
                <p class="section-desc reveal reveal-delay-2" style="margin:0 auto">
                    Choose the package that fits your pharmacy's scale. No hidden fees, cancel anytime.
                </p>
            </div>

            <div class="pricing-grid">

                <!-- 1. Basic Package -->
                <div class="pricing-card glass-card reveal"
                    style="border: 1px solid #e2e8f0; background: #fff; display: flex; flex-direction: column;">
                    <div class="price-header"
                        style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; margin-bottom: 1rem; text-align: center;">
                        <div class="plan-name"
                            style="color: #64748b; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Basic</div>
                        <div
                            style="display: flex; align-items: baseline; justify-content: center; gap: 0.25rem; margin-bottom: 0.25rem;">
                            <span style="font-size: 1rem; color: #64748b; font-weight: 600;">Ksh</span>
                            <span class="price-amount"
                                style="font-size: 2rem; color: #0f172a; font-weight: 700; line-height: 1;">2,000</span>
                            <span style="font-size: 0.875rem; color: #64748b; font-weight: 500;">/ month</span>
                        </div>
                        <div class="registration-fee"
                            style="font-size: 0.75rem; color: #64748b; background: #f8fafc; padding: 0.15rem 0.5rem; border-radius: 100px; display: inline-block;">
                            One-time Setup: Ksh 5,000
                        </div>
                    </div>

                    <div class="price-features" style="flex-grow: 1; margin-bottom: 1.5rem;">
                        <!-- Updated List -->
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <!-- Existing -->
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Full Inventory Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Point Of Sales (POS) System
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Financial Reporting
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Customer Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Supplier Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Purchase Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Expiry Tracking & Alerts
                            </li>
                            <!-- NEW ADDITIONS -->
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Multi-Branch Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Role-Based Access (Owner/Staff)
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Secure Cloud Backup
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="btn-ghost" style="justify-content:center; width: 100%;">Get
                        Started</a>
                </div>

                <!-- 2. Standard Package (Most Popular) -->
                <div class="pricing-card popular glass-card reveal reveal-delay-1"
                    style="border: 2px solid var(--accent); background: #fff; position: relative; transform: scale(1.02); z-index: 2; box-shadow: 0 20px 25px -5px rgba(6, 196, 162, 0.1), 0 8px 10px -6px rgba(6, 196, 162, 0.1); display: flex; flex-direction: column;">
                    <!-- Popular Badge -->
                    <div
                        style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: var(--accent); color: white; padding: 0.15rem 1rem; border-radius: 100px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 6px rgba(6, 196, 162, 0.25);">
                        Most Popular
                    </div>

                    <div class="price-header"
                        style="border-bottom: 1px solid #f0fdfa; padding-bottom: 1rem; margin-bottom: 1rem; text-align: center;">
                        <div class="plan-name"
                            style="color: var(--accent); font-size: 1rem; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Standard</div>
                        <div
                            style="display: flex; align-items: baseline; justify-content: center; gap: 0.25rem; margin-bottom: 0.25rem;">
                            <span style="font-size: 1rem; color: #64748b; font-weight: 600;">Ksh</span>
                            <span class="price-amount"
                                style="font-size: 2.25rem; color: #0f172a; font-weight: 800; line-height: 1;">5,000</span>
                            <span style="font-size: 0.875rem; color: #64748b; font-weight: 500;">/ 3 months</span>
                        </div>
                        <div class="registration-fee"
                            style="font-size: 0.75rem; color: #0d9488; background: #f0fdfa; padding: 0.15rem 0.5rem; border-radius: 100px; display: inline-block; border: 1px solid rgba(6, 196, 162, 0.2);">
                            One-time Setup: Ksh 5,000
                        </div>
                    </div>
                    <div class="price-features" style="flex-grow: 1; margin-bottom: 1.5rem;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Full Inventory Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Point Of Sales (POS) System
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Financial Reporting
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Customer Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Supplier Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Purchase Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Expiry Tracking & Alerts
                            </li>
                            <!-- NEW ADDITIONS -->
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Multi-Branch Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Role-Based Access (Owner/Staff)
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Secure Cloud Backup
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="btn-primary"
                        style="justify-content:center; width: 100%;">Choose Standard</a>
                </div>

                <!-- 3. Premium Package -->
                <div class="pricing-card glass-card reveal reveal-delay-2"
                    style="border: 1px solid #e2e8f0; background: #fff; display: flex; flex-direction: column;">
                    <div class="price-header"
                        style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; margin-bottom: 1rem; text-align: center;">
                        <div class="plan-name"
                            style="color: #b45309; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            Premium</div>
                        <div
                            style="display: flex; align-items: baseline; justify-content: center; gap: 0.25rem; margin-bottom: 0.25rem;">
                            <span style="font-size: 1rem; color: #64748b; font-weight: 600;">Ksh</span>
                            <span class="price-amount"
                                style="font-size: 2rem; color: #0f172a; font-weight: 700; line-height: 1;">17,500</span>
                            <span style="font-size: 0.875rem; color: #64748b; font-weight: 500;">/ Annually</span>
                        </div>
                        <div class="registration-fee"
                            style="font-size: 0.75rem; color: #64748b; background: #f8fafc; padding: 0.15rem 0.5rem; border-radius: 100px; display: inline-block;">
                            One-time Setup: Ksh 5,000
                        </div>
                    </div>
                    <div class="price-features" style="flex-grow: 1; margin-bottom: 1.5rem;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Full Inventory Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Point Of Sales (POS) System
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Financial Reporting
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Customer Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Supplier Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Purchase Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Expiry Tracking & Alerts
                            </li>
                            <!-- NEW ADDITIONS -->
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Multi-Branch Management
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Role-Based Access (Owner/Staff)
                            </li>
                            <li
                                style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span
                                    style="width: 16px; height: 16px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                                Secure Cloud Backup
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="btn-ghost"
                        style="justify-content:center; width: 100%;">Choose Premium</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="cta">
        <div class="cta-inner">
            <div class="cta-glow"></div>
            <div class="section-badge reveal">Contact Us</div>
            <h2 class="cta-title reveal reveal-delay-1">Ready to Transform Your <span
                    style="background:linear-gradient(135deg,var(--accent),#0d9488);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Pharmacy</span>?
            </h2>
            <p class="cta-desc reveal reveal-delay-2">Join thousands of pharmacies that have already upgraded their
                operations. Contact our sales team for a demo.</p>
            <div class="cta-actions reveal reveal-delay-3">
                <a href="tel:+254741473024" class="btn-ghost d-flex align-items-center gap-2"
                    style="padding: 0.5rem 1.25rem; font-size: 0.9rem; color: #28a745; border-color: #28a745;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                        </path>
                    </svg>
                    +254 741 473 024
                </a>
                <a href="#pricing" class="btn-primary">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <!-- Logo links to Home route -->
                <div style="display:flex;align-items:center;gap:.75rem">
                    <div class="logo-icon" style="width:36px;height:36px;border-radius:10px">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.5 20.5H6.5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h4" />
                            <path d="M13.5 20.5h4a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4" />
                            <path d="M10 7V4a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v3" />
                            <path d="M8 14h8" />
                            <path d="M8 11h8" />
                        </svg>
                    </div>
                    <span class="logo-text" style="font-size:1.2rem">DawaPoint</span>
                </div>
                <p>All-in-one pharmacy management platform built for modern pharmacies. Simplify operations, boost
                    revenue, and deliver better patient care.</p>
            </div>
            <div class="footer-col">
                <h4>Product</h4>
                <ul>
                    <!-- Anchor links for same page navigation -->
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#features">Services</a></li>
                    <li><a href="#">Integrations</a></li>
                    <li><a href="#">Updates</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <!-- Placeholders (Update these with actual routes later) -->
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <!-- Placeholders (Update these with actual routes later) -->
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Status Page</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>2026 DawaPoint. All rights reserved.</span>
            <div style="display:flex;gap:1.5rem">
                <!-- Placeholders for Legal pages -->
                <a href="#" style="color:#94a3b8;text-decoration:none;transition:color .3s"
                    onmouseover="this.style.color='#06c4a2'" onmouseout="this.style.color='#94a3b8'">Privacy
                    Policy</a>
                <a href="#" style="color:#94a3b8;text-decoration:none;transition:color .3s"
                    onmouseover="this.style.color='#06c4a2'" onmouseout="this.style.color='#94a3b8'">Terms of
                    Service</a>
            </div>
        </div>
    </footer>

    <script>
        // ── Navbar scroll effect ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        });

        // ── Scroll reveal ──
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Trigger stat counter if applicable
                    if (entry.target.querySelector('.stat-number')) {
                        animateCounter(entry.target.querySelector('.stat-number'));
                    }
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -40px 0px'
        });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // ── Counter animation ──
        const animatedCounters = new Set();

        function animateCounter(el) {
            if (animatedCounters.has(el)) return;
            animatedCounters.add(el);
            const target = parseInt(el.dataset.target);
            const duration = 2000;
            const start = performance.now();

            function update(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(eased * target);
                el.textContent = current.toLocaleString();
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        // ── Hero dashboard counters ──
        function animateHeroCounters() {
            const salesEl = document.getElementById('salesCounter');
            const ordersEl = document.getElementById('ordersCounter');
            const duration = 2500;
            const start = performance.now();

            function update(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                salesEl.textContent = 'Ksh ' + Math.round(eased * 4827).toLocaleString();
                ordersEl.textContent = Math.round(eased * 156).toLocaleString();
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        // Start hero counters after a brief delay
        setTimeout(animateHeroCounters, 800);

        // ── Mini Chart (Canvas) ──
        function drawChart() {
            const canvas = document.getElementById('miniChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            const rect = canvas.parentElement.getBoundingClientRect();
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            ctx.scale(dpr, dpr);
            const w = rect.width;
            const h = rect.height;

            // Data points (simulated weekly sales)
            const data = [32, 45, 38, 62, 55, 78, 68, 82, 74, 95, 88, 105];
            const maxVal = Math.max(...data);
            const padding = {
                top: 10,
                bottom: 10,
                left: 5,
                right: 5
            };
            const chartW = w - padding.left - padding.right;
            const chartH = h - padding.top - padding.bottom;
            const stepX = chartW / (data.length - 1);

            // Gradient fill
            const gradient = ctx.createLinearGradient(0, padding.top, 0, h - padding.bottom);
            gradient.addColorStop(0, 'rgba(6,196,162,0.2)');
            gradient.addColorStop(1, 'rgba(6,196,162,0.0)');

            // Draw filled area
            ctx.beginPath();
            ctx.moveTo(padding.left, h - padding.bottom);
            data.forEach((val, i) => {
                const x = padding.left + i * stepX;
                const y = padding.top + chartH - (val / maxVal) * chartH;
                if (i === 0) ctx.lineTo(x, y);
                else {
                    const prevX = padding.left + (i - 1) * stepX;
                    const prevY = padding.top + chartH - (data[i - 1] / maxVal) * chartH;
                    const cpX = (prevX + x) / 2;
                    ctx.bezierCurveTo(cpX, prevY, cpX, y, x, y);
                }
            });
            ctx.lineTo(padding.left + (data.length - 1) * stepX, h - padding.bottom);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();

            // Draw line
            ctx.beginPath();
            data.forEach((val, i) => {
                const x = padding.left + i * stepX;
                const y = padding.top + chartH - (val / maxVal) * chartH;
                if (i === 0) ctx.moveTo(x, y);
                else {
                    const prevX = padding.left + (i - 1) * stepX;
                    const prevY = padding.top + chartH - (data[i - 1] / maxVal) * chartH;
                    const cpX = (prevX + x) / 2;
                    ctx.bezierCurveTo(cpX, prevY, cpX, y, x, y);
                }
            });
            ctx.strokeStyle = '#06c4a2';
            ctx.lineWidth = 2.5;
            ctx.stroke();

            // Draw end dot with glow
            const lastX = padding.left + (data.length - 1) * stepX;
            const lastY = padding.top + chartH - (data[data.length - 1] / maxVal) * chartH;
            ctx.beginPath();
            ctx.arc(lastX, lastY, 4, 0, Math.PI * 2);
            ctx.fillStyle = '#06c4a2';
            ctx.fill();
            ctx.beginPath();
            ctx.arc(lastX, lastY, 8, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(6,196,162,0.25)';
            ctx.fill();
        }

        // Draw chart after DOM is ready, and on resize
        window.addEventListener('load', () => {
            setTimeout(drawChart, 100);
        });
        window.addEventListener('resize', drawChart);

        // ── Mouse parallax on hero visual ──
        const heroVisual = document.querySelector('.hero-visual');
        if (heroVisual) {
            document.addEventListener('mousemove', (e) => {
                if (window.innerWidth < 1024) return;
                const x = (e.clientX / window.innerWidth - 0.5) * 12;
                const y = (e.clientY / window.innerHeight - 0.5) * 12;
                heroVisual.style.transform = `translate(${x}px, ${y}px)`;
            });
        }
    </script>
</body>

</html>
