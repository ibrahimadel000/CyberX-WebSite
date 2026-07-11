<?php
/**
 * CyberX Homepage
 * Professional Antigravity Theme
 */
$page_title = 'Home';
require_once __DIR__ . '/../layouts/header.php';

// Fetch featured courses for Academy section
$featured_courses = $db->fetchAll("SELECT * FROM courses WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT 6");

// Fetch software solutions
$solutions = $db->fetchAll("SELECT * FROM software_solutions WHERE status = 'active' ORDER BY id ASC LIMIT 4");

// Fetch service categories for Services section
$service_categories = $db->fetchAll("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC");

// Get stats
$total_students = $db->count('enrollments', "status = 'approved'");
$total_courses = $db->count('courses', "status = 'active'");
$total_solutions = $db->count('software_solutions', "status = 'active'");
$total_services = $db->count('services', "status = 'active'");
?>

<!-- Hero Section - Full Screen Scroll Scrubbed Animation -->
<section id="hero-scroll-section" class="relative w-full bg-black tracking-tight" style="height: 600vh;">

    <!-- Hero Global Styles -->
    <style>
        /* Slide-up keyframe for text entrance */
        @keyframes heroSlideUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-phase-enter .hero-eyebrow {
            animation: heroSlideUp 0.55s cubic-bezier(0.22,1,0.36,1) both;
            animation-delay: 0ms;
        }
        .hero-phase-enter .hero-headline {
            animation: heroSlideUp 0.6s cubic-bezier(0.22,1,0.36,1) both;
            animation-delay: 80ms;
        }
        .hero-phase-enter .hero-sub {
            animation: heroSlideUp 0.6s cubic-bezier(0.22,1,0.36,1) both;
            animation-delay: 180ms;
        }
        .hero-phase-enter .hero-cta {
            animation: heroSlideUp 0.55s cubic-bezier(0.22,1,0.36,1) both;
            animation-delay: 270ms;
        }
        /* Cyan accent */
        .hero-accent { color: #00e5ff; }
        /* Eyebrow pill */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #00e5ff;
            border: 1px solid rgba(0,229,255,0.35);
            border-radius: 999px;
            padding: 5px 14px;
            margin-bottom: 22px;
            background: rgba(0,229,255,0.07);
        }
        .hero-eyebrow-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #00e5ff;
            display: inline-block;
            animation: heroPulse 2s infinite;
        }
        @keyframes heroPulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.4; transform:scale(1.5); }
        }
        /* CTA buttons */
        .hero-btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Inter', sans-serif; font-weight: 600;
            font-size: 0.95rem; letter-spacing: 0.02em;
            padding: 13px 30px; border-radius: 6px;
            background: #00e5ff; color: #000;
            border: 2px solid #00e5ff;
            transition: background 0.25s, color 0.25s, box-shadow 0.25s;
            text-decoration: none;
        }
        .hero-btn-primary:hover {
            background: transparent; color: #00e5ff;
            box-shadow: 0 0 24px rgba(0,229,255,0.35);
        }
        .hero-btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Inter', sans-serif; font-weight: 500;
            font-size: 0.95rem; letter-spacing: 0.02em;
            padding: 13px 28px; border-radius: 6px;
            background: transparent; color: #fff;
            border: 1px solid rgba(255,255,255,0.35);
            transition: border-color 0.25s, color 0.25s, background 0.25s;
            text-decoration: none;
        }
        .hero-btn-outline:hover {
            border-color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.06);
        }
        /* Thin divider above CTA row */
        .hero-divider {
            margin-top: 36px; padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }
        /* Stats inline row for phase 3 */
        .hero-stats {
            display: flex; gap: 36px; margin-top: 32px; flex-wrap: wrap;
        }
        .hero-stat-num {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2rem; font-weight: 700;
            color: #00e5ff; line-height: 1;
        }
        .hero-stat-label {
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem; letter-spacing: 0.1em;
            text-transform: uppercase; color: #888;
            margin-top: 4px;
        }

        @media (max-width: 767px) {
            #hero-canvas {
                position: fixed !important; top: 60px !important; left: 0 !important; right: 0 !important;
                bottom: auto !important; width: 100vw !important; height: 50vh !important;
                opacity: 1 !important; z-index: 0 !important; object-fit: cover !important;
                -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%) !important;
                mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%) !important;
                background: #000;
            }
            #hero-scroll-section .left-col-hero {
                grid-column: 1 / -1 !important; z-index: 10 !important;
            }
            #hero-scroll-section .right-col-hero { 
                position: absolute !important; top: 0 !important; left: 0 !important; 
                width: 100% !important; height: 100% !important; z-index: 0 !important; 
                display: block !important;
            }
            #hero-t1, #hero-t2, #hero-t3, #hero-t4 {
                left: 20px !important; right: 20px !important; top: 48vh !important;
                max-width: none !important;
            }
            .hero-headline {
                font-size: clamp(2.2rem, 9.5vw, 2.8rem) !important; line-height: 1.15 !important;
            }
            .hero-sub { margin-top: 14px !important; font-size: 0.95rem !important; }
            .hero-stats { gap: 18px; justify-content: flex-start; margin-top: 24px !important; }
            .hero-stat-num { font-size: 1.6rem !important; }
            .hero-cta { flex-direction: column; align-items: flex-start; width: 100%; gap: 12px !important; padding-top: 16px !important; }
            .hero-cta a { width: 100%; justify-content: center; }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            #hero-scroll-section > div.fixed {
                display: block !important;
            }
            #hero-scroll-section .left-col-hero {
                position: absolute !important; top: 0; left: 0; 
                width: 100% !important; height: 100% !important; z-index: 10 !important;
            }
            #hero-scroll-section .right-col-hero {
                position: absolute !important; top: 0 !important; left: 0 !important;
                width: 100vw !important; height: 100vh !important; z-index: 0 !important;
            }
            #hero-canvas {
                position: fixed !important; right: auto !important; left: 0 !important; top: 0 !important;
                width: 100vw !important; height: 100vh !important;
                opacity: 1 !important;
            }
            #hero-t1, #hero-t2, #hero-t3, #hero-t4 {
                left: 40px !important;
                right: auto !important;
                top: 20% !important;
                max-width: 55% !important;
            }
            .hero-headline {
                font-size: clamp(2.4rem, 4.5vw, 3.8rem) !important;
                line-height: 1.1 !important;
            }
            .hero-sub {
                font-size: 1.1rem !important;
                margin-top: 14px !important;
            }
            .hero-stats { gap: 24px; margin-top: 24px !important; }
            .hero-stat-num { font-size: 1.8rem !important; }
        }
    </style>

    <!-- Fixed Inner Container -->
    <div class="fixed top-0 left-0 w-full overflow-hidden" style="height: 100vh; display: grid; grid-template-columns: 1fr 1fr; align-items: start; padding: 0; padding-top: 50px; z-index: 1;">

        <!-- Left Image Background (Flips in RTL) -->
        <div class="absolute inset-0 pointer-events-none" style="z-index: 5;">
            <img src="assets/images/<?php echo isset($is_rtl) && $is_rtl ? 'hero-image-ar.webp' : 'hero-image.png'; ?>" class="absolute <?php echo isset($is_rtl) && $is_rtl ? 'right-0' : 'left-0'; ?> top-0 h-full object-cover" style="width: 55vw; -webkit-mask-image: linear-gradient(<?php echo isset($is_rtl) && $is_rtl ? 'to left' : 'to right'; ?>, rgba(0,0,0,1) 40%, rgba(0,0,0,0) 100%); mask-image: linear-gradient(<?php echo isset($is_rtl) && $is_rtl ? 'to left' : 'to right'; ?>, rgba(0,0,0,1) 40%, rgba(0,0,0,0) 100%); object-position: center <?php echo isset($is_rtl) && $is_rtl ? 'right' : 'left'; ?>;">
            <div class="absolute <?php echo isset($is_rtl) && $is_rtl ? 'right-0' : 'left-0'; ?> top-0 h-full" style="width: 55vw; background: linear-gradient(<?php echo isset($is_rtl) && $is_rtl ? 'to left' : 'to right'; ?>, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);"></div>
        </div>

        <!-- Left: Text Area -->
        <div class="left-col-hero relative w-full h-full" style="z-index: 20;">

            <!-- ═══ PHASE 1 ═══ -->
            <div id="hero-t1" style="position: absolute; top: 16%; left: clamp(40px, 6vw, 90px); right: 40px; max-width: 560px; opacity: 1; transition: opacity 0.3s;">
                
                <h1 class="hero-headline text-white font-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.6rem, 4.2vw, 4.8rem); line-height: 1.08; margin: 0;">
                    <?php echo t('home.hero1_headline'); ?>
                </h1>
                <p class="hero-sub" style="font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #999; margin-top: 18px; max-width: 420px; line-height: 1.65;">
                    <?php echo t('home.hero1_sub'); ?>
                </p>
                <div class="hero-divider hero-cta">
                    <a href="#services" class="hero-btn-outline"><?php echo t('home.hero1_cta'); ?></a>
                </div>
            </div>

            <!-- ═══ PHASE 2 ═══ -->
            <div id="hero-t2" style="position: absolute; top: 16%; left: clamp(40px, 6vw, 90px); right: 40px; max-width: 560px; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
                
                <h1 class="hero-headline text-white font-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.6rem, 4.2vw, 4.8rem); line-height: 1.08; margin: 0;">
                    <?php echo t('home.hero2_headline'); ?>
                </h1>
                <p class="hero-sub" style="font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #999; margin-top: 18px; max-width: 420px; line-height: 1.65;">
                    <?php echo t('home.hero2_sub'); ?>
                </p>
                <div class="hero-divider hero-cta">
                    <a href="#services" class="hero-btn-outline"><?php echo t('home.hero2_cta'); ?></a>
                </div>
            </div>

            <!-- ═══ PHASE 3 ═══ -->
            <div id="hero-t3" style="position: absolute; top: 16%; left: clamp(40px, 6vw, 90px); right: 40px; max-width: 560px; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
               
                <h1 class="hero-headline text-white font-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.6rem, 4.2vw, 4.8rem); line-height: 1.08; margin: 0;">
                    <?php echo t('home.hero3_headline'); ?>
                </h1>
                <p class="hero-sub" style="font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #999; margin-top: 18px; max-width: 420px; line-height: 1.65;">
                    <?php echo t('home.hero3_sub'); ?>
                </p>
                <div class="hero-stats hero-cta">
                    <div>
                        <div class="hero-stat-num"><?php echo t('home.hero3_stat1_num'); ?></div>
                        <div class="hero-stat-label"><?php echo t('home.hero3_stat1_label'); ?></div>
                    </div>
                    <div>
                        <div class="hero-stat-num"><?php echo t('home.hero3_stat2_num'); ?></div>
                        <div class="hero-stat-label"><?php echo t('home.hero3_stat2_label'); ?></div>
                    </div>
                    <div>
                        <div class="hero-stat-num"><?php echo t('home.hero3_stat3_num'); ?></div>
                        <div class="hero-stat-label"><?php echo t('home.hero3_stat3_label'); ?></div>
                    </div>
                </div>
            </div>

            <!-- ═══ PHASE 4 ═══ -->
            <div id="hero-t4" style="position: absolute; top: 16%; left: clamp(40px, 6vw, 90px); right: 40px; max-width: 560px; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
                
                <h1 class="hero-headline text-white font-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.6rem, 4.2vw, 4.8rem); line-height: 1.08; margin: 0;">
                    <?php echo t('home.hero4_headline'); ?>
                </h1>
                <p class="hero-sub" style="font-family: 'Inter', sans-serif; font-size: 1.05rem; color: #999; margin-top: 18px; max-width: 400px; line-height: 1.65;">
                    <?php echo t('home.hero4_sub'); ?>
                </p>
                <div class="hero-divider hero-cta" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <a href="<?php echo SITE_URL; ?>/contact" class="hero-btn-primary"><?php echo t('home.hero4_cta1'); ?></a>
                    <a href="#services" class="hero-btn-outline"><?php echo t('home.hero4_cta2'); ?></a>
                </div>
            </div>

        </div> <!-- end left column -->

        <!-- Right: Canvas -->
        <div class="right-col-hero w-full relative" style="height: 100vh; z-index: 10;">
            <canvas id="hero-canvas" class="pointer-events-none absolute" style="bottom: 0; right: -190px; height: 100vh; width: 65vw;"></canvas>
        </div>

        <!-- Black fade at end of section -->
        <div id="hero-black-fade" class="absolute inset-0 bg-black pointer-events-none opacity-0" style="z-index: 30; transition: opacity 0.4s ease;"></div>
    </div>
</section>

<!-- Wrapper to ensure content z-index stacks over the fixed hero -->
<div class="relative w-full bg-space-dark z-20">

<!-- Additional required fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('hero-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d', { alpha: false });
    const section = document.getElementById('hero-scroll-section');

    ctx.fillStyle = '#000000';

    let canvasWidth, canvasHeight;
    const updateCanvasSize = () => {
        canvasWidth = canvas.clientWidth;
        canvasHeight = canvas.clientHeight;
        const dpr = window.devicePixelRatio || 1;
        canvas.width = canvasWidth * dpr;
        canvas.height = canvasHeight * dpr;
        ctx.scale(dpr, dpr);
        ctx.fillRect(0, 0, canvasWidth, canvasHeight);
    };
    updateCanvasSize();
    window.addEventListener('resize', updateCanvasSize);

    const framesCount = 143;
    const images = [];
    let imagesLoadedCount = 0;
    let initialDrawDone = false;
    let preloaderCompleted = false;

    const preloaderBar = document.getElementById('preloader-bar');
    const preloaderPercent = document.getElementById('preloader-percent');

    const preloaderThreshold = framesCount;
    
    // Determine whether to load high-res desktop or low-res compressed mobile frames
    const isMobileDevice = window.innerWidth < 768;
    const framesFolder = isMobileDevice ? 'assets/frames-mobile' : 'assets/frames';

    const updatePreloaderUI = () => {
        if (preloaderCompleted) return;
        
        let percentage = Math.floor((imagesLoadedCount / preloaderThreshold) * 100);
        
        // Update DOM
        if (preloaderBar) preloaderBar.style.width = Math.min(percentage, 100) + '%';
        if (preloaderPercent) preloaderPercent.innerText = Math.min(percentage, 100) + '%';
        
        if (imagesLoadedCount >= preloaderThreshold) {
            preloaderCompleted = true;
            if (preloaderBar) preloaderBar.style.width = '100%';
            if (preloaderPercent) preloaderPercent.innerText = '100%';
            
            setTimeout(() => {
                if (typeof window.hidePreloader === 'function') window.hidePreloader();
            }, 400); 
        }
    };

    // Safety fallback: Give it more time (up to 20s) since we are loading all frames
    let safetyTimeout = setTimeout(() => {
        if (!preloaderCompleted && typeof window.hidePreloader === 'function') {
            preloaderCompleted = true;
            window.hidePreloader();
        }
    }, 20000);

    const loadFrame = (index) => {
        return new Promise((resolve) => {
            const img = new Image();
            const frameStr = index.toString().padStart(4, '0');
            
            // Apply the responsive folder trick
            img.src = `${framesFolder}/frame${frameStr}.webp`;
            
            const onComplete = () => {
                images[index - 1] = img;
                if (index <= preloaderThreshold) {
                    imagesLoadedCount++;
                    updatePreloaderUI();
                }
                if (index === 1 && !initialDrawDone) { 
                    drawImage(1); 
                    initialDrawDone = true; 
                }
                resolve();
            };
            
            img.onload = onComplete;
            img.onerror = onComplete;
        });
    };

    const startLoading = async () => {
        // Concurrency queue: Load multiple frames safely without DDoS'ing the browser limits
        const concurrencyLimit = window.innerWidth < 768 ? 4 : 8;
        let currentIndex = 1;

        const loadNextInQueue = async () => {
            while (currentIndex <= framesCount) {
                const idx = currentIndex++;
                await loadFrame(idx);
            }
        };

        const workers = [];
        for (let i = 0; i < concurrencyLimit; i++) {
            workers.push(loadNextInQueue());
        }
        
        await Promise.all(workers);
    };

    // Pre-allocate array and start loading
    for (let i = 0; i < framesCount; i++) images.push(null);
    startLoading();

    const drawImage = (index) => {
        // Find the closest loaded frame to prevent blank canvas when scrolling fast
        let drawIdx = index;
        while (drawIdx > 1 && (!images[drawIdx - 1] || !images[drawIdx - 1].complete || images[drawIdx - 1].naturalWidth === 0)) {
            drawIdx--;
        }
        const img = images[drawIdx - 1];
        if (!img || !img.complete || img.naturalWidth === 0) return;
        ctx.fillStyle = '#000000';
        ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        const imgAspect = img.naturalWidth / img.naturalHeight;
        
        let drawH = canvasHeight;
        let drawY = 0;
        
        // Downscale statue drawing for tablets so it does not overwhelm the screen
        if (window.innerWidth >= 768 && window.innerWidth <= 1024) {
            drawH = canvasHeight * 0.75;
            drawY = (canvasHeight - drawH) / 2;
        }
        
        let drawW = drawH * imgAspect;
        let drawX = canvasWidth - drawW;
        
        if (window.innerWidth < 768) {
            // On mobile, perfectly center the statue for top/bottom split
            drawX = (canvasWidth - drawW) / 2;
        } else if (window.innerWidth >= 768 && window.innerWidth <= 1024) {
            // On tablet, offset slightly right to make space for left-text layout
            drawX = (canvasWidth - drawW) / 2 + 140;
        }
        
        ctx.drawImage(img, drawX, drawY, drawW, drawH);
    };

    const phases = [
        document.getElementById('hero-t1'),
        document.getElementById('hero-t2'),
        document.getElementById('hero-t3'),
        document.getElementById('hero-t4'),
    ];
    const blackFade = document.getElementById('hero-black-fade');

    // Track which phase is active to trigger slide-up animation
    let activePhase = -1;

    const triggerEnter = (el) => {
        el.classList.remove('hero-phase-enter');
        void el.offsetWidth; // force reflow
        el.classList.add('hero-phase-enter');
    };

    let targetProgress = 0;
    let currentProgress = 0;
    let lastIndex = -1;

    // Only update targetProgress during native scroll events
    const handleScroll = () => {
        const rect = section.getBoundingClientRect();
        const sectionTop = -rect.top;
        const scrollRange = rect.height - window.innerHeight;

        if (scrollRange > 0) {
            targetProgress = Math.max(0, Math.min(1, sectionTop / scrollRange));
        } else {
            targetProgress = sectionTop > 0 ? 1 : 0;
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Initial read

    // Continuous loop eases currentProgress toward targetProgress (Lerp)
    const renderLoop = () => {
        // Smooth interpolation step - faster transition on mobile
        const lerpFactor = window.innerWidth < 768 ? 0.25 : 0.08;
        currentProgress += (targetProgress - currentProgress) * lerpFactor;

        // Snap to target if very close to stop unnecessary micro-computations
        if (Math.abs(targetProgress - currentProgress) < 0.0001) {
            currentProgress = targetProgress;
        }

        // 1. Calculate and draw the correct frame
        let frameIndex = Math.floor(currentProgress * 142) + 1;
        frameIndex = Math.max(1, Math.min(143, frameIndex));
        if (frameIndex !== lastIndex) { 
            drawImage(frameIndex); 
            lastIndex = frameIndex; 
        }

        // 2. Handle text opacity and entrance logic
        const isMobile = window.innerWidth < 768;
        const ranges = isMobile
            ? [[0, 0.45, 0.1], [-1, -1, 0], [-1, -1, 0], [0.55, 1.0, 0.1]]
            : [[0, 0.21, 0.05], [0.26, 0.49, 0.05], [0.54, 0.74, 0.05], [0.79, 1.0, 0.05]];

        let newActive = -1;

        ranges.forEach(([pStart, pEnd, fRange], idx) => {
            const el = phases[idx];
            if (!el) return;

            if (pStart < 0) {
                if (el.style.opacity !== '0') { el.style.opacity = '0'; el.style.pointerEvents = 'none'; }
                return;
            }

            let op = 1;
            if (currentProgress < pStart - fRange || currentProgress > pEnd + fRange) {
                op = 0;
            } else if (currentProgress < pStart) {
                op = (currentProgress - (pStart - fRange)) / fRange;
            } else if (currentProgress > pEnd) {
                op = 1 - ((currentProgress - pEnd) / fRange);
            }
            op = Math.max(0, Math.min(1, op));

            const opacityStr = op.toFixed(3);
            if (el.style.opacity !== opacityStr) {
                el.style.opacity = opacityStr;
            }
            
            const pointerEvents = op > 0.5 ? 'auto' : 'none';
            if (el.style.pointerEvents !== pointerEvents) {
                el.style.pointerEvents = pointerEvents;
            }

            if (op > 0.5) newActive = idx;
        });

        if (newActive !== activePhase && newActive !== -1) {
            triggerEnter(phases[newActive]);
            activePhase = newActive;
        }

        // 3. Black fade at the end of section
        if (currentProgress > 0.95) {
            let fadeOp = Math.max(0, Math.min(1, (currentProgress - 0.95) / 0.05));
            const fadeOpStr = fadeOp.toFixed(3);
            if (blackFade.style.opacity !== fadeOpStr) {
                blackFade.style.opacity = fadeOpStr;
                blackFade.style.pointerEvents = fadeOp > 0.5 ? 'auto' : 'none';
            }
        } else {
            if (blackFade.style.opacity !== '0') { 
                blackFade.style.opacity = '0'; 
                blackFade.style.pointerEvents = 'none'; 
            }
        }

        requestAnimationFrame(renderLoop);
    };

    // Start Phase 1 manually
    triggerEnter(phases[0]);
    activePhase = 0;
    
    // Kick off the rendering engine
    requestAnimationFrame(renderLoop);
});
</script>

<!-- ═══════════════════════════════════════════════
     SECTION 1 — BRAND STATEMENT
═══════════════════════════════════════════════════ -->
<style>
/* ── Brand Statement ── */
#brand-statement {
    background: #000000;
    padding: 110px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.bs-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(3rem, 6vw, 5rem);
    font-weight: 800;
    line-height: 1.12;
    color: #fff;
    margin: 0;
    letter-spacing: -0.02em;
}
.bs-headline-cyan { color: #00d4ff; }
.bs-underline-wrap {
    position: relative;
    display: inline-block;
}
.bs-underline-svg {
    position: absolute;
    left: 0;
    bottom: -12px;
    width: 100%;
    height: 14px;
    overflow: visible;
    pointer-events: none;
}
.bs-underline-path {
    fill: none;
    stroke: #00d4ff;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-dasharray: 400;
    stroke-dashoffset: 400;
    transition: stroke-dashoffset 0.8s ease;
}
.bs-underline-path.bs-draw { stroke-dashoffset: 0; }
.bs-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #666;
    max-width: 500px;
    margin: 48px auto 0;
    line-height: 1.7;
}
@media (max-width: 600px) {
    #brand-statement { padding: 80px 24px; }
}
</style>

<section id="brand-statement">
    <p class="bs-headline"><?php echo t('home.brand_line1'); ?></p>
    <p class="bs-headline" style="margin-top: 10px;">
        <?php echo t('home.brand_line2_prefix'); ?>
        <span class="bs-underline-wrap">
            <span class="bs-headline-cyan" id="bs-unforgettable"><?php echo t('home.brand_line2_accent'); ?></span>
            <svg class="bs-underline-svg" viewBox="0 0 400 14" preserveAspectRatio="none" aria-hidden="true">
                <path class="bs-underline-path" id="bs-underline-path" d="M4 8 Q100 3 200 8 Q300 13 396 8" />
            </svg>
        </span>
    </p>
    <p class="bs-sub"><?php echo t('home.brand_sub'); ?></p>
</section>

<script>
(function(){
    var path = document.getElementById('bs-underline-path');
    var target = document.getElementById('bs-unforgettable');
    if (!path || !target) return;
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(e){
                if (e.isIntersecting) { path.classList.add('bs-draw'); io.disconnect(); }
            });
        }, { threshold: 0.5 });
        io.observe(target);
    } else {
        path.classList.add('bs-draw');
    }
})();
</script>

<!-- ═══════════════════════════════════════════════
     TRUST BANNER (from about us)
════════════════════════════════════════════════ -->
<style>
#ab-trusted {
    background: #000;
    padding: 40px 0;
    border-top: 1px solid #111;
    overflow: hidden;
}
.ab-trusted-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(4.5rem, 4vw, 4rem);
    font-weight: 700;
    color: #fff;
    margin: 0 0 24px;
    text-align: center;
    letter-spacing: -0.01em;

}
.ab-trusted-marquee-wrap {
    position: relative;
    overflow: hidden;
    width: 100%;
}
.ab-trusted-marquee-wrap::before,
.ab-trusted-marquee-wrap::after {
    content: '';
    position: absolute;
    top: 0; bottom: 0; z-index: 2;
    width: 120px;
    pointer-events: none;
}
.ab-trusted-marquee-wrap::before {
    left: 0;
    background: linear-gradient(to right, #000 0%, transparent 100%);
}
.ab-trusted-marquee-wrap::after {
    right: 0;
    background: linear-gradient(to left, #000 0%, transparent 100%);
}
.ab-trusted-track {
    display: flex;
    width: max-content;
    animation: abMarqueePartner 45s linear infinite;
    align-items: center;
    gap: 0;
    direction: ltr; /* Force LTR so translateX marquee works in RTL */
}
@keyframes abMarqueePartner {
    from { transform: translateX(0); }
    to   { transform: translateX(-33.333%); }
}
.ab-trusted-logo {
    height: 120px;
    width: auto;
    object-fit: contain;
    opacity: 0.7;
    transition: opacity 0.3s ease;
    flex-shrink: 0;
    padding: 0 20px; /* Reduced from 40px */
}
.ab-trusted-logo:hover {
    opacity: 1;
}
@media (max-width: 768px) {
    .ab-trusted-logo { height: 80px; padding: 0 10px; } /* Reduced from 24px to bring much closer on mobile */
}
</style>

<section id="ab-trusted">
    <div class="ab-reveal">
        <div class="ab-trusted-marquee-wrap" style="direction: ltr;">
            <div class="ab-trusted-track">
                <!-- Set 1 -->
                <img src="assets/images/logos/1.png" alt="" class="ab-trusted-logo" style="height: 220px;">
               <img src="assets/images/logos/2.png" alt="" class="ab-trusted-logo" style="height: 350px; position: relative; top: 20px;">
                <img src="assets/images/logos/3.png" alt="" class="ab-trusted-logo" style="height: 340px;">
                <img src="assets/images/logos/4.png" alt="" class="ab-trusted-logo" style="height: 495px;">
                <img src="assets/images/logos/5.png" alt="" class="ab-trusted-logo" style="height: 120px;">
                <img src="assets/images/logos/6.png" alt="" class="ab-trusted-logo" style="height: 100px;">
                <!-- Set 2 -->
                <img src="assets/images/logos/1.png" alt="" class="ab-trusted-logo" style="height: 220px;">
               <img src="assets/images/logos/2.png" alt="" class="ab-trusted-logo" style="height: 350px; position: relative; top: 20px;">
                <img src="assets/images/logos/3.png" alt="" class="ab-trusted-logo" style="height: 340px;">
                <img src="assets/images/logos/4.png" alt="" class="ab-trusted-logo" style="height: 495px;">
                <img src="assets/images/logos/5.png" alt="" class="ab-trusted-logo" style="height: 120px;">
                <img src="assets/images/logos/6.png" alt="" class="ab-trusted-logo" style="height: 100px;">
                <!-- Set 3 -->
                <img src="assets/images/logos/1.png" alt="" class="ab-trusted-logo" style="height: 220px;">
               <img src="assets/images/logos/2.png" alt="" class="ab-trusted-logo" style="height: 350px; position: relative; top: 20px;">
                <img src="assets/images/logos/3.png" alt="" class="ab-trusted-logo" style="height: 340px;">
                <img src="assets/images/logos/4.png" alt="" class="ab-trusted-logo" style="height: 495px;">
                <img src="assets/images/logos/5.png" alt="" class="ab-trusted-logo" style="height: 120px;">
                <img src="assets/images/logos/6.png" alt="" class="ab-trusted-logo" style="height: 100px;">
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     SECTION 3 — SERVICES
═══════════════════════════════════════════════════ -->
<style>
/* ── Services Section ── */
#services {
    background: #000000;
    padding: 120px 0 100px;
    position: relative;
    overflow: hidden;
}

/* Light Static Neon Glow Background */
/* Soft spectral accents (low-cost) */
#services::before {
    content: '';
    position: absolute;
    top: 0; bottom: 0; left: 0; right: 0;
    margin: -80px; /* small bleed for soft edges */
    background:
        radial-gradient(400px 300px at 15% 20%, rgba(0,212,255,0.18), transparent 20%),
        radial-gradient(360px 260px at 75% 25%, rgba(138,43,226,0.12), transparent 18%),
        radial-gradient(420px 320px at 50% 70%, rgba(245,158,11,0.10), transparent 20%);
    filter: blur(28px);
    z-index: 0;
    pointer-events: none;
}

/* Fade out to adjacent sections smoothly */
#services::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, #000000 0%, transparent 20%, transparent 80%, #000000 100%);
    z-index: 0;
    pointer-events: none;
}

/* removed heavy neonSpin animation to reduce paint cost */

/* Eyebrow pill — matches hero exactly */


@keyframes svcDotPulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.4; transform:scale(1.5); }
}

/* Section heading */
.svc-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.2rem, 3.8vw, 3.6rem);
    font-weight: 800;
    line-height: 1.08;
    color: #fff;
    margin: 0 0 16px;
}
.svc-headline .svc-cyan { color: #00d4ff; }
.svc-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #888;
    max-width: 480px;
    line-height: 1.7;
    margin: 0 auto;
}

@keyframes rainbowPan {
    0% { background-position: 0% 0%, 0% 0%; }
    100% { background-position: 0% 0%, 200% 0%; }
}

/* Service card (Solid Color & Glow) */
.svc-card {
    position: relative;
    background: #000000; /* Solid black */
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 20px;
    padding: 36px 32px;
    overflow: hidden;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    height: 100%;
    /* Flex item properties to allow beautiful centering of orphans */
    flex: 1 1 320px;
    max-width: 420px;
    min-height: 280px;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
}

/* Special Card Styling - Rainbow Border */
.svc-card.svc-card-special {
    background: 
        linear-gradient(#000000, #000000) padding-box,
        linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff, #ff0000) border-box;
    background-size: 100% 100%, 200% 100%;
    border: 2px solid transparent;
    box-shadow: 0 0 40px rgba(138, 43, 226, 0.15);
    transform: translateY(-4px); /* Slightly raised by default */
    padding: 35px 31px; /* Offset the thicker 2px border */
}
.svc-card.svc-card-special::after {
    content: '';
    position: absolute;
    top: 16px;
    right: 16px;
    background: linear-gradient(135deg, #00d4ff, #8a2be2);
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 4px 10px rgba(138, 43, 226, 0.3);
}
.svc-card.svc-card-special:hover {
    background: 
        linear-gradient(#080808, #080808) padding-box,
        linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff, #ff0000) border-box;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 50px rgba(138, 43, 226, 0.4);
}

/* Glowing Top Border Effect (for regular cards) */
.svc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #00d4ff, transparent);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.svc-card.svc-card-special::before {
    display: none; /* Hide top border effect for special card since it has full rainbow border */
}

.svc-card:hover {
    transform: translateY(-8px);
    border-color: rgba(0, 212, 255, 0.3);
    background: #080808; /* Lighter solid grey on hover */
    box-shadow: 0 20px 40px rgba(0,0,0,0.5), 0 0 30px rgba(0, 212, 255, 0.1);
}

.svc-card:hover::before {
    opacity: 1;
}

/* Card icon box */
.svc-icon-box {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(0,212,255,0.08), rgba(138,43,226,0.08));
    border: 1px solid rgba(0,212,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    transition: all 0.4s ease;
}
.svc-card.svc-card-special .svc-icon-box {
    background: linear-gradient(135deg, rgba(138,43,226,0.15), rgba(0,212,255,0.15));
    border-color: rgba(138,43,226,0.3);
}

.svc-card:hover .svc-icon-box {
    background: linear-gradient(135deg, #00d4ff, #8a2be2);
    border-color: transparent;
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3);
}
.svc-card.svc-card-special:hover .svc-icon-box {
    background: linear-gradient(135deg, #8a2be2, #00d4ff);
    box-shadow: 0 10px 20px rgba(138, 43, 226, 0.4);
}

.svc-icon-box i {
    font-size: 1.6rem;
    color: #00d4ff;
    transition: color 0.4s ease;
}
.svc-card.svc-card-special .svc-icon-box i {
    color: #8a2be2;
}
.svc-card:hover .svc-icon-box i {
    color: #fff;
}

/* Text */
.svc-card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 10px;
    line-height: 1.3;
}
.svc-card-sub {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #999;
    line-height: 1.6;
    margin: 0 0 32px;
    flex-grow: 1;
}

/* Learn More Link Structure */
.svc-card-link-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.06);
    margin-top: auto;
}
.svc-card.svc-card-special .svc-card-link-wrapper {
    border-top-color: rgba(138, 43, 226, 0.15);
}

.svc-card-link-text {
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #fff;
    transition: color 0.3s ease;
}
.svc-card-link-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.svc-card:hover .svc-card-link-text {
    color: #00d4ff;
}
.svc-card.svc-card-special:hover .svc-card-link-text {
    color: #8a2be2;
}

.svc-card:hover .svc-card-link-wrapper {
    border-top-color: rgba(0, 212, 255, 0.2);
}
.svc-card.svc-card-special:hover .svc-card-link-wrapper {
    border-top-color: rgba(138, 43, 226, 0.3);
}

.svc-card:hover .svc-card-link-icon {
    background: #00d4ff;
    color: #000;
    transform: translateX(4px);
}
.svc-card.svc-card-special:hover .svc-card-link-icon {
    background: #8a2be2;
    color: #fff;
}

/* New Flex Container */
.svc-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 24px;
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* Mobile responsive ordering */
@media (max-width: 767px) {
    .svc-card {
        order: 5; /* Push normal cards down */
    }
    /* We need to specifically target the first few cards to order them before the special card */
    .svc-card:nth-child(1) { order: 1; }
    .svc-card:nth-child(3) { order: 2; }
    
    .svc-card.svc-card-special {
        order: 3 !important; /* Force special card to be 3rd on mobile */
    }
}
</style>

<style>
/* Services refinement: lightweight spectrum accent, responsive grid, and controlled accents */
#services {
    --svc-surface: rgba(255, 255, 255, 0.045);
    --svc-surface-strong: rgba(255, 255, 255, 0.075);
    --svc-line: rgba(255, 255, 255, 0.1);
    --svc-spectrum: #06b6d4, #60a5fa, #f472b6, #f59e0b, #34d399;
    background:
        linear-gradient(180deg, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.62) 14%, rgba(0,0,0,0.62) 86%, rgba(0,0,0,0.88) 100%),
        linear-gradient(130deg, #08101e 0%, #075985 18%, #3b82f6 32%, #ec4899 52%, #f59e0b 70%, #22c55e 88%, #081b1c 100%);
    padding: clamp(88px, 10vw, 140px) 0;
    position: relative;
}

#services::before {
    inset: 0;
    height: auto;
    margin: 0;
    background:
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px) 0 0 / 84px 84px,
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px) 0 0 / 84px 84px;
    filter: none;
    opacity: 0.3;
}

#services::after {
    inset: 0;
    height: auto;
    background:
        linear-gradient(to bottom, #000 0%, transparent 10%, transparent 90%, #000 100%),
        radial-gradient(ellipse at 50% 40%, rgba(255,255,255,0.05) 0%, transparent 55%);
    pointer-events: none;
}

.svc-shell {
    position: relative;
    z-index: 1;
    width: min(1180px, calc(100% - 40px));
    margin: 0 auto;
}

.svc-shell::before {
    display: none;
}

.svc-shell::after {
    display: none;
}

.svc-header {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(240px, 340px);
    gap: clamp(28px, 5vw, 72px);
    align-items: end;
    margin-bottom: clamp(34px, 5vw, 58px);
}

.svc-kicker {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #9defff;
    margin-bottom: 18px;
}

.svc-kicker::before {
    content: '';
    width: 28px;
    height: 1px;
    background: linear-gradient(90deg, var(--svc-spectrum));
}

.svc-headline {
    font-size: clamp(2.5rem, 5vw, 5.6rem);
    line-height: 0.95;
    margin: 0;
    letter-spacing: 0;
}

.svc-headline .svc-cyan {
    display: block;
    background: linear-gradient(90deg, #00d4ff 0%, #7dd3fc 28%, #fb7185 52%, #f59e0b 74%, #22c55e 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
}

.svc-sub {
    max-width: none;
    color: #a8b3ba;
    line-height: 1.8;
    margin: 0;
}

.svc-proof {
    border-left: 1px solid var(--svc-line);
    padding-left: 24px;
}

[dir="rtl"] .svc-proof {
    border-left: 0;
    border-right: 1px solid var(--svc-line);
    padding-left: 0;
    padding-right: 24px;
}

.svc-proof-number {
    display: block;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2rem, 4vw, 3.4rem);
    font-weight: 800;
    line-height: 1;
    color: #fff;
    margin-bottom: 8px;
}

.svc-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 18px;
    max-width: none;
    margin: 0;
}

.svc-grid .svc-card,
.svc-grid .svc-card:nth-child(1),
.svc-grid .svc-card:nth-child(3),
.svc-grid .svc-card.svc-card-special {
    order: initial !important;
}

.svc-card {
    --svc-accent: #00d4ff;
    --svc-accent-rgb: 0, 212, 255;
    grid-column: span 2;
    min-height: 306px;
    max-width: none;
    flex: initial;
    height: auto;
    background: rgba(0, 0, 0, 0.98);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 28px;
    isolation: isolate;
    box-shadow: 0 12px 36px rgba(0,0,0,0.32);
    will-change: transform, opacity;
    transition:
        transform 320ms cubic-bezier(0.22,1,0.36,1),
        opacity 420ms ease;
    order: initial;
}

.svc-card[data-svc-card] {
    opacity: 0;
    transform: translateY(28px);
    transition-delay: var(--svc-delay, 0ms);
}

.svc-card[data-svc-card].svc-is-visible {
    opacity: 1;
    transform: translateY(0);
}

.svc-card[data-svc-card].svc-is-visible:hover {
    transform: translateY(-8px);
    transition-delay: 0ms;
}

.svc-card::before {
    inset: 0;
    height: auto;
    background:
        linear-gradient(135deg, rgba(var(--svc-accent-rgb), 0.18), transparent 42%);
    opacity: 0.28;
    z-index: -1;
}

.svc-card::after,
.svc-card.svc-card-special::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    padding: 0;
    border-radius: 0;
    background: linear-gradient(90deg, var(--svc-accent), transparent 74%);
    box-shadow: none;
    opacity: 0.72;
}

.svc-card:hover,
.svc-card.svc-card-special:hover {
    transform: translateY(-6px);
    border-color: rgba(var(--svc-accent-rgb), 0.5);
    background:
        linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.032));
    box-shadow:
        0 24px 70px rgba(0,0,0,0.38),
        0 0 0 1px rgba(var(--svc-accent-rgb), 0.12);
}

.svc-card:hover::before {
    opacity: 0.55;
}

@media (prefers-reduced-motion: reduce) {
    .svc-card,
    .svc-card[data-svc-card],
    .svc-card[data-svc-card].svc-is-visible,
    #services::before {
        transition: none !important;
        animation: none !important;
        filter: none !important;
    }
}

.svc-card:nth-last-child(2):nth-child(3n + 1) {
    grid-column: 2 / span 2;
}

.svc-card:last-child:nth-child(3n + 1) {
    grid-column: 3 / span 2;
}

.svc-card.svc-card-special {
    background:
        linear-gradient(180deg, var(--svc-surface-strong), rgba(255,255,255,0.02));
    border: 1px solid rgba(var(--svc-accent-rgb), 0.38);
    padding: 28px;
    transform: none;
    box-shadow: 0 18px 60px rgba(0,0,0,0.28);
}

.svc-card.svc-card-special::before {
    display: block;
}

.svc-card-special {
    border-color: rgba(var(--svc-accent-rgb), 0.38);
}

.svc-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 34px;
}

.svc-card-index {
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--svc-accent);
    letter-spacing: 0.12em;
}

.svc-card-special .svc-card-index::after {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--svc-accent);
    margin-left: 10px;
    box-shadow: 0 0 18px rgba(var(--svc-accent-rgb), 0.8);
}

[dir="rtl"] .svc-card-special .svc-card-index::after {
    margin-left: 0;
    margin-right: 10px;
}

.svc-icon-box {
    width: 46px;
    height: 46px;
    border-radius: 8px;
    background: rgba(var(--svc-accent-rgb), 0.12);
    border: 1px solid rgba(var(--svc-accent-rgb), 0.24);
    margin-bottom: 0;
}

.svc-card.svc-card-special .svc-icon-box {
    background: rgba(var(--svc-accent-rgb), 0.12);
    border-color: rgba(var(--svc-accent-rgb), 0.24);
}

.svc-icon-box i,
.svc-card.svc-card-special .svc-icon-box i {
    font-size: 1.1rem;
    color: var(--svc-accent);
}

.svc-card:hover .svc-icon-box,
.svc-card.svc-card-special:hover .svc-icon-box {
    background: var(--svc-accent);
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: none;
}

.svc-card:hover .svc-icon-box i {
    color: #020404;
}

.svc-card-title {
    font-size: clamp(1.18rem, 1.4vw, 1.42rem);
    line-height: 1.25;
    letter-spacing: 0;
    margin-bottom: 12px;
}

.svc-card-sub {
    color: #a8b3ba;
    margin-bottom: 34px;
}

.svc-card-link-wrapper {
    gap: 18px;
    padding-top: 18px;
    border-top-color: rgba(255,255,255,0.08);
}

.svc-card-link-text {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    color: #d8eef3;
}

.svc-card-link-icon {
    width: 34px;
    height: 34px;
    border-radius: 6px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    color: #d8eef3;
    flex: 0 0 auto;
}

.svc-card:hover .svc-card-link-text,
.svc-card.svc-card-special:hover .svc-card-link-text {
    color: var(--svc-accent);
}

.svc-card:hover .svc-card-link-wrapper,
.svc-card.svc-card-special:hover .svc-card-link-wrapper {
    border-top-color: rgba(var(--svc-accent-rgb), 0.24);
}

.svc-card:hover .svc-card-link-icon,
.svc-card.svc-card-special:hover .svc-card-link-icon {
    background: var(--svc-accent);
    border-color: transparent;
    color: #020404;
    transform: translateX(4px);
}

[dir="rtl"] .svc-card:hover .svc-card-link-icon {
    transform: translateX(-4px);
}

@media (max-width: 1024px) {
    .svc-header {
        grid-template-columns: 1fr;
        align-items: start;
    }

    .svc-proof {
        border-left: 0;
        border-top: 1px solid var(--svc-line);
        padding-left: 0;
        padding-top: 22px;
        max-width: 520px;
    }

    [dir="rtl"] .svc-proof {
        border-right: 0;
        padding-right: 0;
    }

    .svc-card,
    .svc-card:nth-last-child(2):nth-child(3n + 1),
    .svc-card:last-child:nth-child(3n + 1) {
        grid-column: span 3;
    }
}

@media (max-width: 640px) {
    .svc-shell {
        width: min(100% - 32px, 1180px);
    }

    .svc-grid {
        grid-template-columns: 1fr;
    }

    .svc-card,
    .svc-card.svc-card-special,
    .svc-card:nth-last-child(2):nth-child(3n + 1),
    .svc-card:last-child:nth-child(3n + 1) {
        grid-column: 1;
        min-height: 270px;
        padding: 24px;
    }

    .svc-card-top {
        margin-bottom: 26px;
    }
}
</style>

<section id="services" class="relative">
    <div class="svc-shell">

        <header class="svc-header">
            <div>
                <span class="svc-kicker"><?php echo t('nav.services'); ?></span>
                <h2 class="svc-headline"><?php echo t('home.svc_headline_prefix'); ?> <span class="svc-cyan"><?php echo t('home.svc_headline_accent'); ?></span></h2>
            </div>
            <!-- proof panel removed per request -->
        </header>

        <div class="svc-grid">

            <?php 
            $delay = 0;
            $card_number = 1;
            $decode_service_text = function($value) {
                $text = (string)($value ?? '');
                for ($i = 0; $i < 4; $i++) {
                    $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    if ($decoded === $text) break;
                    $text = $decoded;
                }
                return trim($text);
            };
            $service_visuals = [
                'development' => ['accent' => '#00d4ff', 'rgb' => '0, 212, 255'],
                'business'    => ['accent' => '#7dd3fc', 'rgb' => '125, 211, 252'],
                'design'      => ['accent' => '#fb7185', 'rgb' => '251, 113, 133'],
                'academic'    => ['accent' => '#22c55e', 'rgb' => '34, 197, 94'],
                'documents'   => ['accent' => '#f59e0b', 'rgb' => '245, 158, 11'],
            ];
            foreach ($service_categories as $svc): 
                $category_key = $svc['category_key'] ?? '';
                $title = $decode_service_text($is_rtl && !empty($svc['title_ar']) ? $svc['title_ar'] : $svc['title']);
                $subtitle = $decode_service_text($is_rtl && !empty($svc['subtitle_ar']) ? $svc['subtitle_ar'] : $svc['subtitle']);
                $icon = preg_replace('/[^a-z0-9\-\s]/i', '', !empty($svc['icon']) ? $svc['icon'] : 'fa-cogs');
                $visual = $service_visuals[$category_key] ?? ['accent' => '#00d4ff', 'rgb' => '0, 212, 255'];
                $special_class = ($category_key === 'business') ? ' svc-card-special' : '';
                $card_style = '--svc-accent:' . $visual['accent'] . '; --svc-accent-rgb:' . $visual['rgb'] . '; --svc-delay:' . $delay . 'ms;';
            ?>
            <a href="<?php echo SITE_URL; ?>/services?category=<?php echo urlencode($category_key); ?>" class="svc-card<?php echo $special_class; ?>" data-svc-card style="<?php echo htmlspecialchars($card_style, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="svc-card-top">
                    <span class="svc-card-index"><?php echo str_pad((string)$card_number, 2, '0', STR_PAD_LEFT); ?></span>
                    <div class="svc-icon-box"><i class="fas <?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
                </div>
                <h3 class="svc-card-title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="svc-card-sub"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="svc-card-link-wrapper">
                    <span class="svc-card-link-text"><?php echo t('home.svc_learn_more'); ?></span>
                    <div class="svc-card-link-icon"><i class="fas fa-arrow-<?php echo $is_rtl ? 'left' : 'right'; ?>"></i></div>
                </div>
            </a>
            <?php 
                $delay += 80;
                $card_number++;
            endforeach; 
            ?>

        </div><!-- end grid -->

    </div>
</section>

<!-- Card entrance animation: IntersectionObserver -->
<script>
(function(){
    var cards = document.querySelectorAll('[data-svc-card]');
    if (!cards.length) return;
    function reveal(el) { el.classList.add('svc-is-visible'); }
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(e){ if (e.isIntersecting){ reveal(e.target); io.unobserve(e.target); } });
        }, { threshold: 0, rootMargin: '0px 0px -20px 0px' });
        cards.forEach(function(c){ io.observe(c); });
    } else {
        cards.forEach(function(c){ reveal(c); });
    }
})();
</script>

<style>
/* ═══════════════════════════════════════
   WHY CYBERX — Vertical Timeline (fixed)
═══════════════════════════════════════ */
#why-us {
    background: #000000;
    padding: 120px 0;
    position: relative;
    overflow: hidden;
}

/* ── Full-width timeline container ── */
.wtl-inner {
    position: relative;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
    box-sizing: border-box;
}

/* ONE continuous vertical line through all rows */
.wtl-inner::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    background: rgba(0, 212, 255, 0.4);
    z-index: 0;
    pointer-events: none;
}

/* ── Center column ── */
.wtl-center-col {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    align-self: stretch;
    z-index: 2;
}

/* ── Each row: 3 equal columns [1fr] [60px] [1fr] ── */
.wtl-item {
    display: grid;
    grid-template-columns: 1fr 60px 1fr;
    align-items: center;
    min-height: 520px;
    position: relative;
}

/* ── Node sits on the line ── */
.wtl-node {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 2px solid #00d4ff;
    background: #0a0a0a;
    color: #00d4ff;
    font-family: 'Inter', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
    box-shadow: 0 0 0 4px #000;  /* gap between circle and line */
}

/* ── Image column ── */
.wtl-img-col {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 60px;
    box-sizing: border-box;
    height: 100%;
    align-self: stretch;
}
.wtl-img-slot {
    width: min(90%, 520px);
    height: min(90%, 520px);
    max-width: 100%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.wtl-img-slot img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

/* ── Text column ── */
.wtl-text-col {
    display: flex;
    align-items: center;
    padding: 40px 48px;
    box-sizing: border-box;
    height: 100%;
}
.wtl-text-col.align-right { justify-content: flex-end; }
.wtl-text-col.align-left  { justify-content: flex-start; }

.wtl-text { max-width: 520px; width: 100%; }
.wtl-text.text-right { text-align: right; }
.wtl-text.text-left  { text-align: left; }

.wtl-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #00d4ff;
    margin-bottom: 14px;
    display: block;
}
.wtl-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2rem, 3vw, 3rem);
    font-weight: 700;
    color: #ffffff;
    line-height: 1.1;
    margin: 0 0 18px;
}
.wtl-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.97rem;
    color: #666;
    line-height: 1.8;
    margin: 0;
}

/* ── Mobile ── */
@media (max-width: 767px) {
    .wtl-inner::before { display: none; }
    .wtl-inner { padding: 0 20px; }

    .wtl-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: unset;
        padding-bottom: 72px;
    }
    .wtl-item:last-child { padding-bottom: 0; }

    .wtl-img-col   { order: 1; padding: 0 0 24px; height: auto; justify-content: center; }
    .wtl-center-col { order: 2; margin-bottom: 24px; }
    .wtl-text-col  { order: 3; padding: 0 8px; height: auto; justify-content: center !important; }

    .wtl-text.text-right,
    .wtl-text.text-left { text-align: center; }
    .wtl-img-slot { width: 260px; height: 260px; }
}
</style>

<!-- ═══════════════════════════════════════
     WHY CYBERX — Vertical Timeline
═══════════════════════════════════════ -->
<section id="why-us">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
           
            <h2 class="svc-headline"><?php echo t('home.why_headline_line1'); ?><br><?php echo t('home.why_headline_line2'); ?></h2>
            <p class="svc-sub"><?php echo t('home.why_sub'); ?></p>
        </div>
    </div>

    <!-- Timeline rows container -->
    <div class="wtl-inner">

        <!-- ── ROW 1: Image LEFT, Text RIGHT ── -->
        <div class="wtl-item">

            <!-- LEFT col: image -->
            <div class="wtl-img-col">
                <div class="wtl-img-slot">
                    <!-- PLACEHOLDER 1 | File: why-speed.png | Place in: assets/images/why/ -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/why/why-speed.png"
                         alt="Lightning Fast Delivery"
                         onerror="this.style.opacity='0'">
                </div>
            </div>

            <!-- CENTER col: line + node -->
            <div class="wtl-center-col">
                <div class="wtl-node">01</div>
            </div>

            <!-- RIGHT col: text -->
            <div class="wtl-text-col align-left">
                <div class="wtl-text text-left">
                    <span class="wtl-label"><?php echo t('home.why1_label'); ?></span>
                    <h3 class="wtl-title"><?php echo t('home.why1_title'); ?></h3>
                    <p class="wtl-desc"><?php echo t('home.why1_desc'); ?></p>
                </div>
            </div>

        </div><!-- end row 1 -->

        <!-- ── ROW 2: Text LEFT, Image RIGHT ── -->
        <div class="wtl-item">

            <!-- LEFT col: text -->
            <div class="wtl-text-col align-right">
                <div class="wtl-text text-right">
                    <span class="wtl-label"><?php echo t('home.why2_label'); ?></span>
                    <h3 class="wtl-title"><?php echo t('home.why2_title'); ?></h3>
                    <p class="wtl-desc"><?php echo t('home.why2_desc'); ?></p>
                </div>
            </div>

            <!-- CENTER col: line + node -->
            <div class="wtl-center-col">
                <div class="wtl-node">02</div>
            </div>

            <!-- RIGHT col: image -->
            <div class="wtl-img-col">
                <div class="wtl-img-slot">
                    <!-- PLACEHOLDER 2 | File: why-quality.png | Place in: assets/images/why/ -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/why/why-quality.png"
                         alt="Expert-Grade Work"
                         onerror="this.style.opacity='0'">
                </div>
            </div>

        </div><!-- end row 2 -->

        <!-- ── ROW 3: Image LEFT, Text RIGHT ── -->
        <div class="wtl-item">

            <!-- LEFT col: image -->
            <div class="wtl-img-col">
                <div class="wtl-img-slot">
                    <!-- PLACEHOLDER 3 | File: why-privacy.png | Place in: assets/images/why/ -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/why/why-privacy.png"
                         alt="100% Confidential"
                         onerror="this.style.opacity='0'">
                </div>
            </div>

            <!-- CENTER col: line + node -->
            <div class="wtl-center-col">
                <div class="wtl-node">03</div>
            </div>

            <!-- RIGHT col: text -->
            <div class="wtl-text-col align-left">
                <div class="wtl-text text-left">
                    <span class="wtl-label"><?php echo t('home.why3_label'); ?></span>
                    <h3 class="wtl-title"><?php echo t('home.why3_title'); ?></h3>
                    <p class="wtl-desc"><?php echo t('home.why3_desc'); ?></p>
                </div>
            </div>

        </div><!-- end row 3 -->

    </div><!-- end wtl-inner -->
</section>

<!-- ═══════════════════════════════════════
     OUR PROJECTS — Portfolio Grid
═══════════════════════════════════════ -->
<style>
/* ── Portfolio Section ── */
#portfolio {
    background: #000000;
    padding: 100px 0;
}

/* Header */
.pf-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 52px;
    flex-wrap: wrap;
    gap: 20px;
}
.pf-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 48px;
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: -0.01em;
    line-height: 1;
    margin: 0;
}
.pf-all-btn {
    font-family: 'Inter', sans-serif;
    font-size: 0.88rem;
    font-weight: 500;
    color: #ffffff;
    background: transparent;
    border: 1px solid #ffffff;
    padding: 12px 24px;
    text-decoration: none;
    transition: background 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.pf-all-btn:hover {
    background: #00d4ff;
    border-color: #00d4ff;
    color: #000000;
}

/* Filter Tabs */
.pf-filters {
    display: flex;
    align-items: center;
    gap: 32px;
    margin-bottom: 40px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.pf-filters::-webkit-scrollbar { display: none; }
.pf-filter-btn {
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    background: none;
    border: none;
    border-bottom: 1px solid transparent;
    padding: 4px 0 6px;
    cursor: pointer;
    transition: color 0.25s ease, border-color 0.25s ease;
    white-space: nowrap;
}
.pf-filter-btn:hover { color: #ffffff; }
.pf-filter-btn.pf-active {
    color: #00d4ff;
    border-bottom-color: #00d4ff;
}

/* Grid */
/* Carousel Wrapper & Track */
.pf-carousel-wrap {
    position: relative;
    width: 100%;
    overflow: hidden;
    direction: ltr; /* Force LTR so translateX carousel logic works in both EN and AR */
}
.pf-track {
    display: flex;
    transition: transform 0.5s ease-in-out;
    direction: ltr; /* Prevent RTL from reversing flex item order */
    /* We will use a 2px gap in JS to match design */
}

/* Item */
.pf-item {
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    display: block;
    text-decoration: none;
    background: #000;
}
.pf-item.pf-hidden { display: none; }

/* Image wrapper with 16/10 aspect */
.pf-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
}
.pf-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.pf-item:hover .pf-img-wrap img {
    transform: scale(1.03);
}

/* Hover overlay */
.pf-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}
.pf-item:hover .pf-overlay { opacity: 1; }
.pf-overlay-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.pf-overlay-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
    text-align: center;
    padding: 0 16px;
}
.pf-overlay-icon {
    font-size: 1.5rem;
    color: #ffffff;
    line-height: 1;
}

/* Info row below image */
.pf-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 4px 4px;
    background: #000000;
}
.pf-name {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 500;
    color: #ffffff;
}
.pf-arrow {
    font-size: 16px;
    color: #ffffff;
    flex-shrink: 0;
}
.pf-tag {
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #00d4ff;
    margin-top: 4px;
    display: block;
    padding: 0 4px;
}

/* Carousel Navigation */
.pf-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 40px;
}
.pf-nav-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
    background: transparent;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.pf-nav-btn:hover {
    background: #00d4ff;
    border-color: #00d4ff;
    color: #000000;
}

/* Mobile */
@media (max-width: 767px) {
    .pf-header { flex-direction: column; align-items: flex-start; }
    .pf-title { font-size: 32px; }
    .pf-filters { gap: 20px; }
}
</style>

<section id="portfolio">
    <div class="max-w-screen-xl mx-auto px-6 lg:px-10">

        <!-- Header row -->
        <div class="pf-header" style="direction: <?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">
            <h2 class="pf-title"><?php echo t('home.pf_title'); ?></h2>
            <a href="<?php echo SITE_URL; ?>/portfolio" class="pf-all-btn"><?php echo t('home.pf_all_btn'); ?></a>
        </div>

        <!-- Carousel -->
        <div class="pf-carousel-wrap" id="pfWrap">
            <div class="pf-track" id="pfTrack" style="gap: 2px;">

                <!-- Sadeem Website -->
                <div class="pf-item" data-pf-cat="development">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/sadeem.png" alt="Sadeem Website" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Sadeem Website</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Sadeem Website</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Development</span>
                </div>

                <!-- Yafa Cafe System -->
                <div class="pf-item" data-pf-cat="development">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Yafa Cafe System.png" alt="Yafa Cafe System" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Yafa Cafe System</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Yafa Cafe System</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Development</span>
                </div>

                <!-- Clinic System -->
                <div class="pf-item" data-pf-cat="development">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Clinic System.png" alt="Clinic System" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Clinic System</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Clinic System</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Development</span>
                </div>

                <!-- Labanzo -->
                <div class="pf-item" data-pf-cat="development">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/labanzo.png" alt="Labanzo" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Labanzo</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Labanzo</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Development</span>
                </div>

                <!-- Luma -->
                <div class="pf-item" data-pf-cat="design">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Luma.png" alt="Luma" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Luma</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Luma</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Design</span>
                </div>

                <!-- Meta Ads Campaign -->
                <div class="pf-item" data-pf-cat="design">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Meta Ads.png" alt="Meta Ads Campaign" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Meta Ads Campaign</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Meta Ads Campaign</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Design</span>
                </div>

                <!-- Assignment Project -->
                <div class="pf-item" data-pf-cat="academic">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Assignment project.png" alt="Assignment Project" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Assignment Project</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Assignment Project</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Academic</span>
                </div>

                <!-- Zakat Project -->
                <div class="pf-item" data-pf-cat="academic">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Zakat Project.png" alt="Zakat Project" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Zakat Project</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Zakat Project</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Academic</span>
                </div>

                <!-- Private Tutoring -->
                <div class="pf-item" data-pf-cat="academic">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/Private tutoring.png" alt="Private Tutoring" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Private Tutoring</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Private Tutoring</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Academic</span>
                </div>

                <!-- Professional CV -->
                <div class="pf-item" data-pf-cat="documents">
                    <div class="pf-img-wrap">
                        <img src="<?php echo SITE_URL; ?>/assets/images/projects/CV .png" alt="Professional CV" loading="lazy">
                        <div class="pf-overlay">
                            <div class="pf-overlay-inner">
                                <span class="pf-overlay-name">Professional CV</span>
                                <span class="pf-overlay-icon">↗</span>
                            </div>
                        </div>
                    </div>
                    <div class="pf-info">
                        <span class="pf-name">Professional CV</span>
                        <span class="pf-arrow">↗</span>
                    </div>
                    <span class="pf-tag">Documents</span>
                </div>

            </div>
        </div>

        <!-- Navigation -->
        <div class="pf-nav">
            <button class="pf-nav-btn" id="pfPrev"><i class="fas fa-arrow-left"></i></button>
            <button class="pf-nav-btn" id="pfNext"><i class="fas fa-arrow-right"></i></button>
        </div>

    </div>
</section>

<script>
(function(){
    var track = document.getElementById('pfTrack');
    var navPrev = document.getElementById('pfPrev');
    var navNext = document.getElementById('pfNext');
    
    var originalCards = [];
    var items = track.querySelectorAll('.pf-item');
    for (var i = 0; i < items.length; i++) {
        originalCards.push(items[i]);
    }
    
    var visibleCards = originalCards.slice();
    var currentIndex = 0;
    var autoTimer = null;
    var busy = false;
    var GAP = 2; // matches gap: 2px in grid
    var totalCards = 0;

    function getPerPage() {
        if (window.innerWidth < 768) return 1;
        if (window.innerWidth < 1024) return 2;
        return 3;
    }

    function getCardWidth() {
        var pp = getPerPage();
        var wrapWidth = track.parentElement.getBoundingClientRect().width;
        return (wrapWidth - (pp - 1) * GAP) / pp;
    }

    function setAllWidths() {
        var pp = getPerPage();
        var g = (pp - 1) * GAP;
        for (var i = 0; i < track.children.length; i++) {
            track.children[i].style.width = 'calc((100% - ' + g + 'px) / ' + pp + ')';
        }
    }

    function buildClones() {
        var old = track.querySelectorAll('[data-clone]');
        for (var i = 0; i < old.length; i++) old[i].remove();

        totalCards = visibleCards.length;
        var pp = getPerPage();
        if (totalCards <= pp) return;

        for (var i = 0; i < totalCards; i++) {
            var c = visibleCards[i].cloneNode(true);
            c.setAttribute('data-clone', 'before');
            track.insertBefore(c, visibleCards[0]);
        }

        for (var i = 0; i < totalCards; i++) {
            var c = visibleCards[i].cloneNode(true);
            c.setAttribute('data-clone', 'after');
            track.appendChild(c);
        }
    }

    function setPosition(realIdx, animate) {
        var cw = getCardWidth();
        var pp = getPerPage();
        if (totalCards <= pp) {
            track.style.transition = 'none';
            track.style.transform = 'translateX(0)';
            return;
        }
        var slot = totalCards + realIdx;
        var px = slot * (cw + GAP);
        
        if (animate) {
            track.style.transition = 'transform 500ms ease-in-out';
        } else {
            track.style.transition = 'none';
        }
        track.style.transform = 'translateX(-' + px + 'px)';
    }

    function wrapIndex(idx) {
        if (totalCards === 0) return 0;
        return ((idx % totalCards) + totalCards) % totalCards;
    }

    function snapIfNeeded() {
        if (currentIndex >= totalCards || currentIndex < 0) {
            var wrapped = wrapIndex(currentIndex);
            currentIndex = wrapped;
            
            // Instantly jump to the original index without animation
            track.style.transition = 'none';
            var cw = getCardWidth();
            var px = (totalCards + currentIndex) * (cw + GAP);
            track.style.transform = 'translateX(-' + px + 'px)';
            
            // Force browser reflow to apply the jump synchronously
            void track.offsetHeight;
        }
        busy = false;
    }

    function next() {
        if (busy) return;
        var pp = getPerPage();
        if (totalCards <= pp) return;

        busy = true;
        currentIndex++;
        setPosition(currentIndex, true);

        // Safe snap timeout fallback
        setTimeout(function() { snapIfNeeded(); }, 500);
    }

    function prev() {
        if (busy) return;
        var pp = getPerPage();
        if (totalCards <= pp) return;

        busy = true;
        currentIndex--;
        setPosition(currentIndex, true);

        // Safe snap timeout fallback
        setTimeout(function() { snapIfNeeded(); }, 500);
    }

    navPrev.addEventListener('click', function() { prev(); resetTimer(); });
    navNext.addEventListener('click', function() { next(); resetTimer(); });

    function resetTimer() { 
        clearInterval(autoTimer); 
        autoTimer = setInterval(next, 4000); 
    }
    document.getElementById('pfWrap').addEventListener('mouseenter', function() { clearInterval(autoTimer); });
    document.getElementById('pfWrap').addEventListener('mouseleave', function() { resetTimer(); });

    var touchX = 0;
    track.addEventListener('touchstart', function(e) { touchX = e.changedTouches[0].screenX; }, { passive: true });
    track.addEventListener('touchend', function(e) {
        var diff = e.changedTouches[0].screenX - touchX;
        if (Math.abs(diff) > 50) { 
            if (diff > 0) prev(); 
            else next(); 
            resetTimer(); 
        }
    }, { passive: true });

    /* Mouse Wheel Scroll */
    var wheelTimer = null;
    document.getElementById('pfWrap').addEventListener('wheel', function(e) {
        e.preventDefault(); // Stop vertical page scroll while over carousel
        if (busy) return;
        
        // Debounce slightly so normal scroll doesn't fire 100 events
        if (wheelTimer) clearTimeout(wheelTimer);
        wheelTimer = setTimeout(function() {
            if (e.deltaY > 0) {
                next();
            } else if (e.deltaY < 0) {
                prev();
            }
            resetTimer();
        }, 40); // 40ms threshold protects against hyper-sensitive trackpads
    }, { passive: false });

    function rebuild() {
        buildClones();
        setAllWidths();
        setPosition(currentIndex, false);
        resetTimer();
    }

    window.addEventListener('resize', function() { 
        busy = false; 
        rebuild(); 
    });
    
    rebuild();
})();
</script>
</section>



<!-- Typographic CTA Section - Get Free Consultation -->
<style>
.cta-new-business {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    color: #ffffff;
    line-height: 0.9; /* tighter line height for huge text */
    display: block;
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-transform: uppercase;
}

@keyframes cta-pulse {
    0% {
        box-shadow: 0 0 8px rgba(0, 212, 255, 0.4);
    }
    50% {
        box-shadow: 0 0 16px rgba(0, 212, 255, 0.8), 0 0 24px rgba(0, 212, 255, 0.4);
    }
    100% {
        box-shadow: 0 0 8px rgba(0, 212, 255, 0.4);
    }
}

.cta-lets-work {
    font-family: 'Inter', sans-serif;
    color: #ffffff;
    font-weight: 700;
    letter-spacing: 0.08em;
    position: relative;
    display: inline-block;
    text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    transition: all 0.4s ease;
}

.cta-section-wrapper:hover .cta-lets-work {
    text-shadow: 0 0 20px rgba(0, 229, 255, 0.7);
}

.cta-red-line {
    width: 100%;
    height: 6px; 
    background-color: #00d4ff;
    margin-top: 14px;
    transition: all 0.4s ease;
    animation: cta-pulse 2s infinite ease-in-out;
}

.cta-red-line-thin {
    width: 85%;
    height: 2px;
    background-color: #00d4ff;
    margin-top: 6px;
    margin-left: 7.5%;
    transition: all 0.4s ease;
    animation: cta-pulse 2s infinite ease-in-out;
    animation-delay: 0.2s;
}

.cta-section-wrapper:hover .cta-new-business {
    transform: scale(1.02);
}

.cta-section-wrapper:hover .cta-red-line {
    width: 110%;
    margin-left: -5%;
    background-color: #00e5ff;
    box-shadow: 0 0 25px rgba(0, 229, 255, 1);
    animation: none;
}

.cta-section-wrapper:hover .cta-red-line-thin {
    width: 100%;
    margin-left: 0;
    background-color: #00e5ff;
    box-shadow: 0 0 20px rgba(0, 229, 255, 1);
    animation: none;
}

.cta-bg-glow {
    background: radial-gradient(circle, rgba(0,212,255,0.15) 0%, rgba(0,0,0,0) 70%); 
    opacity: 0;
    transition: opacity 0.7s ease;
}

.cta-section-wrapper:hover .cta-bg-glow {
    opacity: 1;
}

/* Custom massive text classes using viewport width for dramatic scaling */
.text-massive-first {
    font-size: clamp(1.8rem, 9vw, 16rem);
    letter-spacing: -0.02em;
}
.text-massive-second {
    font-size: clamp(2rem, 9vw, 13rem);
    letter-spacing: 0.02em; /* Added tracking so CONSULTATION matches GET FREE width roughly */
}
.text-massive-sub {
    font-size: clamp(1.3rem, 4vw, 4rem);
}

/* Spacing for CTA Section */
.cta-section-spacing {
    margin-top: 40px;
    padding-top: 64px;
    padding-bottom: 64px;
}
@media (min-width: 768px) {
    .cta-section-spacing {
        margin-top: 64px;
        padding-top: 128px;
        padding-bottom: 128px;
    }
}
</style>

<section class="cta-section-spacing relative overflow-hidden bg-black cta-section-wrapper cursor-pointer group" onclick="window.location.href='<?php echo SITE_URL; ?>/contact'" style="border-top: 1px solid rgba(255,255,255,0.05);">
    
    <!-- Subtle hover glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1200px] h-[1200px] cta-bg-glow rounded-full pointer-events-none z-0"></div>
    
    <div class="max-w-[100rem] mx-auto px-4 flex flex-col items-center justify-center text-center relative z-10 w-full overflow-hidden">
        
        <!-- Typography Container to keep them flush -->
        <div class="flex flex-col items-center justify-center mx-auto w-full text-center" style="transform: rotate(-3deg);">
            <!-- GET FREE -->
            <div class="mb-2 whitespace-nowrap w-full flex justify-center">
                <h2 class="cta-new-business text-massive-first text-white">
                    <?php echo t('home.cta_get'); ?> <span class="text-[#00d4ff]"><?php echo t('home.cta_free'); ?></span>
                </h2>
            </div>

            <!-- CONSULTATION -->
            <div class="mb-16 sm:mb-28 whitespace-nowrap w-full flex justify-center" style="transform: translateX(-2%);">
                <h2 class="cta-new-business text-massive-second text-[#ffffff]">
                    <?php echo t('home.cta_consultation'); ?>
                </h2>
            </div>
        </div>
        
        <!-- LET'S WORK -->
        <div class="transform transition-transform duration-500 group-hover:translate-y-2 mt-8 sm:mt-12">
            <div class="cta-lets-work text-massive-sub uppercase">
                <?php echo t('home.cta_lets_work'); ?>
                <div class="cta-red-line"></div>
                <div class="cta-red-line-thin"></div>
            </div>
        </div>
        
    </div>
</section>



<!-- End z-index wrapper for relative content -->
</div>



<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
