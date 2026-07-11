<?php
/**
 * CyberX Header Include
 * Professional Antigravity Theme — with EN/AR multi-language support
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/language.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberX - Your Digital, Creative, and Academic Partner. Software development, design, student services, and professional courses.">
    <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME . ' - Digital Solutions & Education Platform'; ?></title>
    
    <!-- Critical Inline CSS for Preloader to prevent white-screen delays -->
    <style>
        #cyberx-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        #cyberx-preloader .preloader-text {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            color: #ffffff;
            letter-spacing: 0.1em;
            margin-bottom: 20px;
        }
        
        #cyberx-preloader .preloader-text span {
            color: #00e5ff;
        }
        
        .preloader-progress-container {
            width: 250px;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        
        .preloader-progress-bar {
            width: 0%;
            height: 100%;
            background: #00e5ff;
            box-shadow: 0 0 10px #00e5ff, 0 0 20px #00b4d8;
            transition: width 0.15s ease-out;
        }
        
        .preloader-percentage {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            color: #888;
            letter-spacing: 0.05em;
        }
    </style>

    <!-- Local Fonts (System fallbacks for offline support) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fonts.css">
    
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fontawesome.min.css">
    
    <!-- Tailwind CSS (Local) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/tailwind.min.css">

    <?php if ($is_rtl): ?>
    <!-- Arabic Font — IBM Plex Sans Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Cairo:wght@600;700;800;900&display=swap" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Premium Futuristic Styles -->
    <style>
        /* ========== Base Theme - Starfield Background ========== */
        body {
            background: #000000 !important;
            min-height: 100vh;
        }
        
        /* ========== Subtle Cosmic Particles ========== */
        .cosmic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, rgba(0, 229, 255, 0.3), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(0, 180, 216, 0.2), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255, 255, 255, 0.3), transparent),
                radial-gradient(2px 2px at 130px 80px, rgba(0, 229, 255, 0.2), transparent),
                radial-gradient(1px 1px at 160px 120px, rgba(124, 58, 237, 0.3), transparent),
                radial-gradient(2px 2px at 200px 50px, rgba(255, 255, 255, 0.2), transparent),
                radial-gradient(1px 1px at 250px 160px, rgba(0, 229, 255, 0.25), transparent),
                radial-gradient(2px 2px at 300px 100px, rgba(0, 180, 216, 0.15), transparent);
            background-size: 350px 200px;
            opacity: 0.6;
            /* Reduce animation impact - use only opacity changes */
            animation: cosmicFade 8s ease-in-out infinite;
        }
        
        @keyframes cosmicFade {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 0.4; }
        }
        
        /* ========== Minimal Navbar — matches homepage ========== */
        .glass-nav {
            background: #000000 !important;
            border-bottom: 1px solid #1a1a1a;
            border-radius: 0;
            margin: 0;
        }
        
        /* ========== Glass Card Effect ========== */
        .glass-card {
            background: rgba(10, 15, 26, 0.6);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .glass-card:hover {
            transform: translateY(-16px) scale(1.02);
            border-color: rgba(0, 229, 255, 0.4);
            box-shadow: 
                0 30px 60px -12px rgba(0, 0, 0, 0.5),
                0 0 50px rgba(0, 229, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        
        /* ========== Glowing CTA Buttons ========== */
        .glow-btn-primary {
            background: linear-gradient(135deg, #00e5ff 0%, #00b4d8 100%);
            color: #020617;
            font-weight: 600;
            box-shadow: 
                0 0 20px rgba(0, 229, 255, 0.4),
                0 4px 15px rgba(0, 229, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .glow-btn-primary:hover {
            box-shadow: 
                0 0 40px rgba(0, 229, 255, 0.6),
                0 0 80px rgba(0, 229, 255, 0.3),
                0 8px 25px rgba(0, 229, 255, 0.4);
            transform: translateY(-2px);
        }
        
        .glow-btn-outline {
            background: transparent;
            border: 2px solid rgba(0, 229, 255, 0.6);
            color: #00e5ff;
            transition: all 0.3s ease;
        }
        
        .glow-btn-outline:hover {
            background: rgba(0, 229, 255, 0.1);
            border-color: #00e5ff;
            box-shadow: 0 0 30px rgba(0, 229, 255, 0.2);
        }
        
        /* ========== Glowing Text Effect ========== */
        .glow-text {
            text-shadow: 
                0 0 10px rgba(0, 229, 255, 0.5),
                0 0 20px rgba(0, 229, 255, 0.3),
                0 0 40px rgba(0, 229, 255, 0.2);
        }
        
        .glow-text-subtle {
            text-shadow: 
                0 0 8px rgba(0, 229, 255, 0.3),
                0 0 16px rgba(0, 229, 255, 0.15);
        }
        
        /* ========== Statue Rim Lighting Effect ========== */
        .rim-light {
            filter: drop-shadow(0 0 30px rgba(0, 180, 216, 0.25)) 
                    drop-shadow(-5px 0 40px rgba(0, 229, 255, 0.15))
                    drop-shadow(5px 0 40px rgba(124, 58, 237, 0.1));
        }
        
        /* ========== Service Card Glass Shard ========== */
        .service-card-glass {
            background: linear-gradient(145deg, rgba(10, 15, 26, 0.7) 0%, rgba(2, 6, 23, 0.8) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .service-card-glass:hover {
            transform: translateY(-20px) scale(1.02);
            border-color: rgba(0, 229, 255, 0.5);
            box-shadow: 
                0 35px 70px -15px rgba(0, 0, 0, 0.5),
                0 0 60px rgba(0, 229, 255, 0.15);
        }
        
        /* ========== WhatsApp FAB ========== */
        .whatsapp-fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .whatsapp-fab:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
        }
        
        /* ========== Scrollbar Styling ========== */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #0a0f1a 0%, #1a2332 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00e5ff 0%, #00b4d8 100%);
        }
        
        /* ========== Horizontal Scroll ========== */
        .scroll-container {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #00e5ff #0a0f1a;
        }
        
        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }
        
        /* ========== Premium Typography ========== */
        .cinzel-heading {
            font-family: 'Cinzel', serif;
            letter-spacing: 0.03em;
        }
        
        /* ========== Smooth Gradient Backgrounds ========== */
        .gradient-section {
            background: linear-gradient(180deg, rgba(2, 6, 23, 0) 0%, rgba(10, 15, 26, 0.5) 50%, rgba(2, 6, 23, 0) 100%);
        }
        
        /* ========== PERFORMANCE OPTIMIZATIONS ========== */
        
        /* Disable CSS smooth scroll - let Lenis handle it for better performance */
        html {
            scroll-behavior: auto !important;
        }
        
        /* DO NOT apply universal transitions - causes major performance issues */
        /* Transitions should be applied to specific interactive elements only */
        
        /* Page load fade-in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Animate elements on page load */
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        
        .animate-slide-right {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }
        
        /* Staggered animation delays */
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
        .delay-600 { animation-delay: 0.6s; opacity: 0; }
        
        /* Smooth hover lift effect for cards and buttons */
        .smooth-lift {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                        box-shadow 0.4s ease !important;
        }
        
        .smooth-lift:hover {
            transform: translateY(-8px);
        }
        
        /* Smooth scale effect */
        .smooth-scale {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        }
        
        .smooth-scale:hover {
            transform: scale(1.05);
        }
        
        /* Smooth glow effect */
        .smooth-glow {
            transition: box-shadow 0.4s ease, filter 0.4s ease !important;
        }
        
        .smooth-glow:hover {
            box-shadow: 0 0 30px rgba(0, 229, 255, 0.3);
        }
        
        /* Smooth image zoom on hover */
        .img-zoom-container {
            overflow: hidden;
        }
        
        .img-zoom-container img {
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        }
        
        .img-zoom-container:hover img {
            transform: scale(1.08);
        }
        
        /* Smooth underline animation for links */
        .smooth-underline {
            position: relative;
        }
        
        .smooth-underline::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00e5ff, #00b4d8);
            transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .smooth-underline:hover::after {
            width: 100%;
        }
        
        /* Smooth button press effect */
        button, .btn, a[class*="btn"] {
            transition: transform 0.2s ease, box-shadow 0.3s ease, background-color 0.3s ease !important;
        }
        
        button:active, .btn:active, a[class*="btn"]:active {
            transform: scale(0.97);
        }
        
        /* Smooth focus states */
        input, textarea, select {
            transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease !important;
        }
        
        input:focus, textarea:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.2);
        }
        
        /* Smooth section transitions */
        section, .section {
            transition: opacity 0.5s ease;
        }
        
        /* Smooth icon animations */
        .icon-spin:hover i,
        .icon-spin:hover svg {
            animation: spin 0.8s ease-in-out;
        }
        
        .icon-bounce:hover i,
        .icon-bounce:hover svg {
            animation: bounce 0.6s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            25% { transform: translateY(-8px); }
            50% { transform: translateY(0); }
            75% { transform: translateY(-4px); }
        }
        
        /* Smooth card reveal on scroll */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Smooth parallax-like effect */
        .parallax-slow {
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        /* Smooth text highlighting */
        ::selection {
            background: rgba(0, 229, 255, 0.3);
            color: #fff;
        }
        
        /* Smooth loading skeleton animation */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .skeleton {
            background: linear-gradient(90deg, 
                rgba(255,255,255,0.05) 25%, 
                rgba(255,255,255,0.1) 50%, 
                rgba(255,255,255,0.05) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* ========== Navigation Link Styles ========== */
        .nav-link {
            position: relative;
            font-family: 'Inter', sans-serif;
            color: #999;
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: color 0.3s ease;
            padding: 8px 4px;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            color: #ffffff;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #ffffff;
            transition: width 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link.active {
            color: #ffffff;
        }
        
        .nav-link.active::after {
            width: 100%;
        }

        .nav-left,
        .nav-center,
        .nav-right {
            display: flex;
            align-items: center;
        }

        .nav-center {
            flex: 1;
            justify-content: center;
        }

        .nav-right {
            justify-content: flex-end;
        }
        
        /* ========== RESPONSIVE NAV OVERRIDES ==========
           Tailwind's compiled CSS is missing md:flex, md:hidden, md:items-center,
           md:justify-between, etc. — force the correct layout here. ========== */
        @media (max-width: 767px) {
            .cx-nav-desktop { display: none !important; }
            .cx-nav-mobile  { display: flex !important; }
        }
        @media (min-width: 768px) {
            .cx-nav-desktop { display: flex !important; align-items: center !important; justify-content: space-between !important; }
            .cx-nav-mobile  { display: none !important; }
        }

        /* ========== Nav CTA Button — matches hero-btn-primary ========== */
        .nav-cta-btn {
            display: inline-flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.02em;
            padding: 10px 24px;
            border-radius: 6px;
            background: #00e5ff;
            color: #000;
            border: 2px solid #00e5ff;
            transition: background 0.25s, color 0.25s, box-shadow 0.25s;
            text-decoration: none;
        }
        .nav-cta-btn:hover {
            background: transparent;
            color: #00e5ff;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
        }
        
        /* ========== Mobile Menu Overrides ========== */
        #mobileMenu {
            border-top: 1px solid #1a1a1a !important;
        }

        /* ========== Tablet (iPad Mini) Nav Spacing Overrides ========== */
        @media (min-width: 768px) and (max-width: 1024px) {
            nav .md\:grid-cols-3 {
                display: flex !important;
                justify-content: space-between !important;
                grid-template-columns: none !important;
                width: 100%;
            }
            nav .md\:grid-cols-3 > div {
                flex-shrink: 0;
            }
            nav .md\:grid-cols-3 > div:nth-child(2) {
                margin-left: 0 !important; /* Remove rigid -110px centering offset */
                flex-shrink: 1;
            }
            nav .space-x-6 > * + * {
                margin-left: 0.75rem !important; /* Compress nav link gaps */
            }
            nav .space-x-4 > * + * {
                margin-left: 0.5rem !important; 
            }
            nav .nav-link {
                font-size: 0.75rem !important;
            }
            nav .nav-cta-btn {
                padding: 6px 14px !important;
                font-size: 0.75rem !important;
            }
            nav .logo-link img {
                height: 44px !important;
            }
        }
        
        /* Performance optimization - minimal will-change usage */
        /* Only apply will-change to actively animating elements */
        .cosmic-bg {
            will-change: opacity;
        }
        
        /* Force GPU acceleration for smoother animations */
        .smooth-lift,
        .smooth-scale,
        .animate-fade-in-up,
        .animate-slide-left,
        .animate-slide-right {
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* ========== Language Toggle ========== */
        .lang-toggle {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            padding: 3px;
            gap: 0;
            cursor: pointer;
            transition: border-color 0.3s ease;
            flex-shrink: 0;
        }
        .lang-toggle:hover {
            border-color: rgba(0, 229, 255, 0.35);
        }
        .lang-toggle a {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            padding: 5px 12px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.3s ease;
            color: #666;
            white-space: nowrap;
        }
        .lang-toggle a.active {
            background: #00e5ff;
            color: #000;
            box-shadow: 0 0 10px rgba(0, 229, 255, 0.25);
        }
        .lang-toggle a:not(.active):hover {
            color: #fff;
        }

        /* ========== Mobile Language Toggle ========== */
        .lang-toggle-mobile {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            padding: 3px;
            gap: 0;
        }
        .lang-toggle-mobile a {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            padding: 7px 16px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.3s ease;
            color: #666;
        }
        .lang-toggle-mobile a.active {
            background: #00e5ff;
            color: #000;
        }

    </style>

    <?php if ($is_rtl): ?>
    <!-- ========== RTL Overrides ========== -->
    <style>
        /* === RTL Font Family === */
        body, 
        .nav-link, .nav-cta-btn, .lang-toggle a, .lang-toggle-mobile a,
        input, textarea, select, button,
        .c-input-label, .c-input, .c-btn-submit,
        .svc-sub, .svc-card-sub, .svc-card-desc,
        .acad-sub, .acad-card-desc, .acad-results-count,
        .ab-sub, .ab-testi-quote, .ab-testi-role,
        .story-right p, .cv-desc, .cv-timeline-desc,
        .feat-desc, .tech-label, .tech-tag,
        .preloader-percentage,
        .c-subtext, .c-info-label {
            font-family: 'IBM Plex Sans Arabic', 'Inter', sans-serif !important;
        }

        h1, h2, h3, h4, h5, h6,
        .hero-headline, .hero-stat-num, .hero-eyebrow,
        .bs-headline, .ab-headline, .ab-hero-headline,
        .svc-headline, .svc-card-title, .acad-headline, .acad-card-title,
        .hero-btn-primary, .hero-btn-outline,
        .ab-num-value, .story-left-title,
        .cv-name, .cv-timeline-title,
        .ab-testi-name, .ab-trusted-title,
        .svc-sub-headline, .feat-title,
        .acad-price,
        .c-heading, .c-info-value,
        .svc-cta-big,
        .pf-overlay-name, .pf-name {
            font-family: 'Cairo', 'Plus Jakarta Sans', sans-serif !important;
        }

        /* === RTL Direction Fixes === */
        html[dir="rtl"] .nav-link::after {
            left: auto;
            right: 0;
        }

        html[dir="rtl"] nav .space-x-6 > * + * {
            margin-left: 0;
            margin-right: 1.5rem;
        }

        html[dir="rtl"] nav .space-x-4 > * + * {
            margin-left: 0;
            margin-right: 1rem;
        }

        html[dir="rtl"] nav .space-x-2 > * + * {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        /* Flex gaps & icon spacing */
        html[dir="rtl"] .mr-2 { margin-right: 0 !important; margin-left: 0.5rem !important; }
        html[dir="rtl"] .ml-2 { margin-left: 0 !important; margin-right: 0.5rem !important; }

        /* WhatsApp float */
        html[dir="rtl"] .whatsapp-float {
            right: auto;
            left: 30px;
        }
        html[dir="rtl"] .whatsapp-tooltip {
            right: auto;
            left: 75px;
        }
        html[dir="rtl"] .whatsapp-tooltip::after {
            right: auto;
            left: -6px;
            border-color: transparent rgba(255, 255, 255, 0.9) transparent transparent;
        }

        /* Flex direction reversals for info rows */
        html[dir="rtl"] .flex.items-center.space-x-2 {
            flex-direction: row-reverse;
        }

        /* Logo spacing */
        html[dir="rtl"] .logo-link .space-x-2 > * + * {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        /* Service cards, feature cards icon spacing */
        html[dir="rtl"] .svc-card-link {
            direction: rtl;
        }

        /* Tablet RTL overrides */
        @media (min-width: 768px) and (max-width: 1024px) {
            html[dir="rtl"] nav .md\:grid-cols-3 > div:nth-child(2) {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            html[dir="rtl"] nav .space-x-6 > * + * {
                margin-left: 0 !important;
                margin-right: 0.75rem !important;
            }
            html[dir="rtl"] nav .space-x-4 > * + * {
                margin-left: 0 !important;
                margin-right: 0.5rem !important;
            }
        }

        /* Mobile RTL */
        @media (max-width: 767px) {
            html[dir="rtl"] .whatsapp-float {
                left: 20px;
                right: auto;
            }
        }

        /* Text alignment fixes */
        html[dir="rtl"] .text-left { text-align: right !important; }
        html[dir="rtl"] .text-right { text-align: left !important; }

        /* Grid/Flex gap RTL fixes for common patterns */
        html[dir="rtl"] .gap-4 > * { text-align: right; }

        /* Form inputs RTL */
        html[dir="rtl"] input,
        html[dir="rtl"] textarea,
        html[dir="rtl"] select {
            text-align: right;
        }

        /* Smooth underline RTL */
        html[dir="rtl"] .smooth-underline::after {
            left: auto;
            right: 0;
        }
    </style>
    <?php endif; ?>
    
    <!-- Lenis Smooth Scroll Library (Local) -->
    <script src="<?php echo SITE_URL; ?>/assets/vendor/js/lenis.min.js"></script>
    
    <!-- Main Stylesheet (for other pages) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body class="bg-space-dark text-slate-300 font-inter min-h-screen">
    <!-- Preloader -->
    <div id="cyberx-preloader">
        <span class="preloader-text"><?php echo t('preloader.text'); ?> <span><?php echo t('preloader.brand'); ?></span>...</span>
        <div class="preloader-progress-container">
            <div class="preloader-progress-bar" id="preloader-bar"></div>
        </div>
        <div class="preloader-percentage" id="preloader-percent">0%</div>
    </div>
    
    <!-- Ultra-resilient inline preloader logic to prevent infinite loading -->
    <script>
        window.hidePreloader = function() {
            try {
                var pl = document.getElementById('cyberx-preloader');
                if (pl && pl.style.opacity !== '0') {
                    pl.style.opacity = '0';
                    pl.style.visibility = 'hidden';
                    setTimeout(function() { 
                        if (pl && pl.parentNode) pl.parentNode.removeChild(pl); 
                    }, 500);
                }
            } catch (e) {}
        };
        
        // Broadest compatibility fallback: trigger hidePreloader after 20 seconds no matter what.
        var cyberxFallbackId = setTimeout(window.hidePreloader, 20000);
        
        // Show preloader only ONCE per session to make navigation lightning fast
        try {
            if (window.sessionStorage && sessionStorage.getItem('cyberx_preloader_shown')) {
                var pl = document.getElementById('cyberx-preloader');
                if (pl && pl.parentNode) pl.parentNode.removeChild(pl);
                clearTimeout(cyberxFallbackId); // No need for fallback if removed
            } else if (window.sessionStorage) {
                sessionStorage.setItem('cyberx_preloader_shown', 'true');
            }
        } catch (e) {
            // Ignore sessionStorage errors (e.g. iOS Private Browsing quota errors)
        }
        
        // Also hide immediately on load if not on a specialized page like the canvas hero
        window.addEventListener('load', function() {
            if (!document.getElementById('hero-canvas')) {
                window.hidePreloader();
            }
        });
    </script>
    
    <!-- Subtle Cosmic Particles Background -->
    <div class="cosmic-bg"></div>
    
    <!-- Navigation -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Desktop: 3-column grid layout -->
            <!-- Desktop: 3-column layout (uses cx-nav-desktop for responsive control) -->
            <div class="cx-nav-desktop hidden md:flex md:items-center md:justify-between">
                <!-- Left: Logo -->
                <div class="flex items-center justify-start nav-left">
                    <a href="<?php echo SITE_URL; ?>" class="logo-link flex items-center space-x-2">
                        <img src="<?php echo SITE_URL; ?>/assets/images/photo_2024-03-26_03-16-00-removebg.png" alt="CyberX Logo" class="h-14 w-auto">
                    </a>
                </div>
                
                <div class="flex items-center justify-center space-x-6 nav-center">
                    <a href="<?php echo SITE_URL; ?>/" class="nav-link" data-page="home"><?php echo t('nav.home'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/about" class="nav-link" data-page="about"><?php echo t('nav.about'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/services" class="nav-link" data-page="services"><?php echo t('nav.services'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/portfolio" class="nav-link" data-page="portfolio"><?php echo t('nav.portfolio'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/courses" class="nav-link" data-page="courses"><?php echo t('nav.academy'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/contact" class="nav-link" data-page="contact"><?php echo t('nav.contact'); ?></a>
                </div>
                
                <!-- Right: Language Toggle + CTA Buttons -->
                <div class="flex items-center justify-end space-x-4 nav-right">
                    <!-- Language Toggle -->
                    <div class="lang-toggle" id="langToggle">
                        <a href="?lang=en" class="<?php echo $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
                        <a href="?lang=ar" class="<?php echo $current_lang === 'ar' ? 'active' : ''; ?>">AR</a>
                    </div>

                    <a href="<?php echo SITE_URL; ?>/contact" class="nav-cta-btn">
                        <?php echo t('nav.free_consultation'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Mobile: Flex layout (uses cx-nav-mobile for responsive control) -->
            <div class="cx-nav-mobile flex md:hidden items-center justify-between">
                <!-- Logo -->
                <div class="mobile-logo flex items-center justify-start">
                    <a href="<?php echo SITE_URL; ?>" class="logo-link flex items-center space-x-2">
                        <img src="<?php echo SITE_URL; ?>/assets/images/photo_2024-03-26_03-16-00-removebg.png" alt="CyberX Logo" class="h-12 w-auto">
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4">
                <div class="flex flex-col space-y-4 pt-4">
                    <a href="<?php echo SITE_URL; ?>/" class="nav-link" data-page="home"><?php echo t('nav.home'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/about" class="nav-link" data-page="about"><?php echo t('nav.about'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/services" class="nav-link" data-page="services"><?php echo t('nav.services'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/portfolio" class="nav-link" data-page="portfolio"><?php echo t('nav.portfolio'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/courses" class="nav-link" data-page="courses"><?php echo t('nav.academy'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/contact" class="nav-link" data-page="contact"><?php echo t('nav.contact'); ?></a>
                    
                    <!-- Mobile Language Toggle -->
                    <div class="lang-toggle-mobile" style="align-self: flex-start;">
                        <a href="?lang=en" class="<?php echo $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
                        <a href="?lang=ar" class="<?php echo $current_lang === 'ar' ? 'active' : ''; ?>">AR</a>
                    </div>

                    <a href="<?php echo SITE_URL; ?>/contact" class="nav-cta-btn" style="text-align:center;">
                        <?php echo t('nav.free_consultation'); ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if ($flash = get_flash()): ?>
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-4" id="flashMessage">
        <div class="glass-card rounded-lg p-4 <?php echo $flash['type'] === 'success' ? 'border-green-500/50' : ($flash['type'] === 'error' ? 'border-red-500/50' : 'border-yellow-500/50'); ?>">
            <div class="flex items-center justify-between">
                <span class="<?php echo $flash['type'] === 'success' ? 'text-green-400' : ($flash['type'] === 'error' ? 'text-red-400' : 'text-yellow-400'); ?>">
                    <?php echo $flash['message']; ?>
                </span>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <?php
endif; ?>
    
    <!-- Main Content -->
    <main class="pt-20">
