<?php
/**
 * CyberX About Us Page
 * Fully redesigned to match the Home page design system
 */
$page_title = 'About Us';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- ═══════════════════════════════════════════════
     PAGE-LEVEL FONTS & SHARED STYLES
════════════════════════════════════════════════ -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ── Shared eyebrow pill (matches hero & services) ── */
/*.ab-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #00d4ff;
    border: 1px solid rgba(0,212,255,0.35);
    border-radius: 999px;
    padding: 5px 14px;
    background: rgba(0,212,255,0.07);
    margin-bottom: 22px;
}
.ab-eyebrow-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #00d4ff;
    animation: abDotPulse 2s infinite;
}
@keyframes abDotPulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.4; transform:scale(1.5); }
}*/

/* ── Shared section headline ── */
.ab-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.2rem, 3.8vw, 3.6rem);
    font-weight: 800;
    line-height: 1.08;
    color: #fff;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}
.ab-headline .ab-cyan { color: #00d4ff; }

/* ── Sub text ── */
.ab-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #888;
    line-height: 1.7;
}

/* ── Scroll reveal ── */
.ab-reveal {
    opacity: 0;
    transform: translateY(32px);
    transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1);
}
.ab-reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
/* Shared IntersectionObserver for .ab-reveal elements */
document.addEventListener('DOMContentLoaded', function() {
    var els = document.querySelectorAll('.ab-reveal');
    if (!els.length) return;
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.classList.add('revealed');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function(el) { io.observe(el); });
    } else {
        els.forEach(function(el) { el.classList.add('revealed'); });
    }
});
</script>


<!-- ═══════════════════════════════════════════════
     SECTION 1 — HERO / PAGE HEADER
════════════════════════════════════════════════ -->
<style>
#ab-hero {
    background: #000000;
    padding: 100px 20px 110px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
/* Ambient glow blobs */
#ab-hero::before {
    content: '';
    position: absolute;
    top: -120px; left: 50%;
    transform: translateX(-50%);
    width: 700px; height: 500px;
    background: radial-gradient(ellipse, rgba(0,212,255,0.13) 0%, transparent 70%);
    pointer-events: none;
}
.ab-hero-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(3rem, 6vw, 5.5rem);
    font-weight: 800;
    line-height: 1.06;
    color: #fff;
    letter-spacing: -0.03em;
    margin: 0;
}
.ab-hero-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    color: #666;
    max-width: 520px;
    margin: 28px auto 0;
    line-height: 1.72;
}
/* Hero underline draw animation */
.ab-hero-underline-wrap { position: relative; display: inline-block; }
.ab-hero-underline-svg {
    position: absolute;
    left: 0; bottom: -10px;
    width: 100%; height: 12px;
    overflow: visible; pointer-events: none;
}
.ab-hero-underline-path {
    fill: none;
    stroke: #00d4ff;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-dasharray: 500;
    stroke-dashoffset: 500;
    transition: stroke-dashoffset 0.9s ease;
}
.ab-hero-underline-path.draw { stroke-dashoffset: 0; }
</style>

<section id="ab-hero">
    <div class="ab-reveal" style="position: relative; z-index: 1;">
        
        <h1 class="ab-hero-headline">
            <?php echo t('about.hero_line1'); ?>
            <br><?php echo t('about.hero_line2'); ?>
            <br><?php echo t('about.hero_line3_prefix'); ?>
            <span class="ab-hero-underline-wrap">
                <span style="color: #00d4ff;" id="ab-hero-word"><?php echo t('about.hero_line3_accent'); ?></span>
                <svg class="ab-hero-underline-svg" viewBox="0 0 500 12" preserveAspectRatio="none" aria-hidden="true">
                    <path class="ab-hero-underline-path" id="ab-hero-upath" d="M4 8 Q125 3 250 8 Q375 13 496 8" />
                </svg>
            </span>
        </h1>
        <p class="ab-hero-sub">
            <?php echo t('about.hero_sub'); ?>
        </p>
    </div>
</section>

<script>
(function(){
    var path = document.getElementById('ab-hero-upath');
    var word = document.getElementById('ab-hero-word');
    if (!path || !word) return;
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(e){ if (e.isIntersecting){ path.classList.add('draw'); io.disconnect(); }});
        }, { threshold: 0.5 });
        io.observe(word);
    } else { path.classList.add('draw'); }
})();
</script>







<!-- ═══════════════════════════════════════════════
     SECTION 3 — BY THE NUMBERS
════════════════════════════════════════════════ -->
<style>
#ab-numbers {
    background: #000000;
    padding: 100px 40px;
    position: relative;
    overflow: hidden;
}
.ab-nums-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: clamp(40px, 8vw, 140px);
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: nowrap;
}

.ab-num-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    min-width: 0;
}
.ab-num-value {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(4rem, 9vw, 10rem);
    font-weight: 300;
    color: #ffffff;
    line-height: 1;
    letter-spacing: -0.03em;
    display: block;
}
.ab-num-label {
    font-family: 'Inter', sans-serif;
    font-size: clamp(0.75rem, 1.1vw, 0.9rem);
    font-weight: 400;
    color: #555;
    letter-spacing: 0.02em;
    margin-top: 18px;
    display: block;
}
@media (max-width: 767px) {
    .ab-nums-row { 
        flex-direction: column; 
        gap: 40px; 
    }
    .ab-num-item { padding: 10px 20px; }
}
</style>

<section id="ab-numbers">
    <div class="ab-nums-row">
        <div class="ab-num-item ab-reveal" style="transition-delay:0ms;">
            <span class="ab-num-value"><?php echo t('about.num1_value'); ?></span>
            <span class="ab-num-label"><?php echo t('about.num1_label'); ?></span>
        </div>
        <div class="ab-num-item ab-reveal" style="transition-delay:100ms;">
            <span class="ab-num-value"><?php echo t('about.num2_value'); ?></span>
            <span class="ab-num-label"><?php echo t('about.num2_label'); ?></span>
        </div>
        <div class="ab-num-item ab-reveal" style="transition-delay:200ms;">
            <span class="ab-num-value"><?php echo t('about.num3_value'); ?></span>
            <span class="ab-num-label"><?php echo t('about.num3_label'); ?></span>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════════
     SECTION 4 — OUR STORY
════════════════════════════════════════════════ -->
<style>
#ab-story {
    background: #000;
    padding: 120px 0;
    border-top: 1px solid #1a1a1a;
}
.story-wrap {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 80px;
    align-items: start;
}
@media (max-width: 780px) {
    .story-wrap { grid-template-columns: 1fr; gap: 36px; }
}
.story-left-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #444;
    display: block;
    margin-bottom: 24px;
}
.story-left-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.2rem, 4vw, 3.6rem);
    font-weight: 800;
    color: #fff;
    margin: 0;
    line-height: 1.05;
    letter-spacing: -0.03em;
}
.story-right p {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #555;
    line-height: 1.85;
    margin: 0 0 28px;
}
.story-right p:last-child { margin: 0; }
</style>

<section id="ab-story">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="story-wrap ab-reveal">
            <div class="story-left">
                <span class="story-left-label" style="color: #00d4ff;"><?php echo t('about.story_label'); ?></span>
            <h2 class="story-left-title"><?php echo t('about.story_title_line1'); ?><br><?php echo t('about.story_title_line2'); ?></h2>
            </div>
            <div class="story-right">
                <p><?php echo t('about.story_p1'); ?></p>
                <p><?php echo t('about.story_p2'); ?></p>
            </div>
        </div>
    </div>
</section>




<!-- ═══════════════════════════════════════════════
     SECTION 5 — CORE VALUES / CLIENT PROMISE
════════════════════════════════════════════════ -->
<style>
#ab-values {
    background: #000;
    padding: 120px 0;
    border-top: 1px solid #1a1a1a;
}

/* ── Section label ── */
.cv-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #444;
    margin-bottom: 48px;
    display: block;
}

/* ── Values list ── */
.cv-list {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 1px solid #1e1e1e;
}
.cv-item {
    display: grid;
    grid-template-columns: 60px 1fr 1fr;
    gap: 32px;
    align-items: start;
    padding: 40px 0;
    border-bottom: 1px solid #1e1e1e;
    transition: background 0.2s;
}
.cv-item:hover { background: #0a0a0a; }
.cv-num {
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 400;
    color: #333;
    letter-spacing: 0.06em;
    padding-top: 4px;
}
.cv-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(1.1rem, 1.8vw, 1.45rem);
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.01em;
    line-height: 1.2;
}
.cv-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #555;
    line-height: 1.7;
    margin: 0;
    padding-top: 4px;
}
@media (max-width: 700px) {
    .cv-item {
        grid-template-columns: 40px 1fr;
        gap: 20px;
    }
    .cv-desc { grid-column: 2; }
}

/* ── Timeline grid ── */
.cv-timeline {
    margin-top: 80px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-top: 1px solid #1e1e1e;
    border-left: 1px solid #1e1e1e;
}
@media (max-width: 800px) {
    .cv-timeline { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .cv-timeline { grid-template-columns: 1fr; }
}
.cv-timeline-cell {
    padding: 36px 32px;
    border-right: 1px solid #1e1e1e;
    border-bottom: 1px solid #1e1e1e;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: background 0.2s;
}
.cv-timeline-cell:hover { background: #080808; }
.cv-timeline-year {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 400;
    color: #333;
    letter-spacing: 0.12em;
}
.cv-timeline-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.01em;
}
.cv-timeline-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: #555;
    line-height: 1.65;
    margin: 0;
}

</style>

<section id="ab-values">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="ab-reveal">
            <span class="cv-label" style="color: #00d4ff;"><?php echo t('about.values_label'); ?></span>
            <ul class="cv-list">
                <li class="cv-item">
                    <span class="cv-num" style="color: #00d4ff;">01</span>
                    <h3 class="cv-name "><?php echo t('about.value1_name'); ?></h3>
                    <p class="cv-desc"><?php echo t('about.value1_desc'); ?></p>
                </li>
                <li class="cv-item">
                    <span class="cv-num" style="color: #00d4ff;">02</span>
                    <h3 class="cv-name" ><?php echo t('about.value2_name'); ?></h3>
                    <p class="cv-desc"><?php echo t('about.value2_desc'); ?></p>
                </li>
                <li class="cv-item">
                    <span class="cv-num" style="color: #00d4ff;">03</span>
                    <h3 class="cv-name" ><?php echo t('about.value3_name'); ?></h3>
                    <p class="cv-desc"><?php echo t('about.value3_desc'); ?></p>
                </li>
                <li class="cv-item">
                    <span class="cv-num" style="color: #00d4ff;">04</span>
                    <h3 class="cv-name"><?php echo t('about.value4_name'); ?></h3>
                    <p class="cv-desc"><?php echo t('about.value4_desc'); ?></p>
                </li>
            </ul>
        </div>

        <!-- Our Story Timeline -->
        <div class="cv-timeline ab-reveal" style="transition-delay: 100ms;">
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2020</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2020_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2020_desc'); ?></p>
            </div>
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2021</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2021_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2021_desc'); ?></p>
            </div>
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2022</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2022_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2022_desc'); ?></p>
            </div>
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2023</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2023_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2023_desc'); ?></p>
            </div>
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2025</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2025_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2025_desc'); ?></p>
            </div>
            <div class="cv-timeline-cell">
                <span class="cv-timeline-year" style="color: #00d4ff;">2026</span>
                <h4 class="cv-timeline-title"><?php echo t('about.timeline_2026_title'); ?></h4>
                <p class="cv-timeline-desc"><?php echo t('about.timeline_2026_desc'); ?></p>
            </div>
        </div>

    </div>
</section>





<!-- ═══════════════════════════════════════════════
     SECTION 7 — CLIENT TESTIMONIALS
════════════════════════════════════════════════ -->
<style>
#ab-testimonials {
    background: #060606;
    padding: 120px 0;
    border-top: 1px solid #111;
    position: relative;
    overflow: hidden;
}
.ab-testi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 60px;
}
@media (max-width: 900px) { .ab-testi-grid { grid-template-columns: 1fr 1fr; } }
.mobile-br { display: none; }
@media (max-width: 768px) { 
    .ab-testi-grid { grid-template-columns: 1fr; } 
    .ab-headline { font-size: clamp(1.8rem, 8vw, 2.22rem); line-height: 1.2; }
    .mobile-br { display: initial !important; }
}
.ab-testi-card {
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    border-radius: 16px;
    padding: 32px 28px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.3s ease, transform 0.3s ease;
}
.ab-testi-card:hover {
    border-color: rgba(0,212,255,0.35);
    transform: translateY(-5px);
}
.ab-testi-card::before {
    content: '"';
    position: absolute;
    top: 16px; right: 24px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 5rem;
    font-weight: 900;
    color: rgba(0,212,255,0.07);
    line-height: 1;
    pointer-events: none;
}
.ab-stars {
    display: flex;
    gap: 4px;
    margin-bottom: 16px;
}
.ab-stars i { font-size: 0.8rem; color: #fbbf24; }
.ab-testi-quote {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #aaa;
    line-height: 1.72;
    margin: 0 0 24px;
}
.ab-testi-author {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ab-testi-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: rgba(0,212,255,0.1);
    border: 1px solid rgba(0,212,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    font-size: 1rem;
    color: #00d4ff;
    flex-shrink: 0;
}
.ab-testi-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    color: #fff;
}
.ab-testi-role {
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    color: #555;
    margin-top: 2px;
}
/* Featured large testimonial */
.ab-testi-card.ab-testi-featured {
    grid-column: span 2;
    background: #0d0d0d;
    border-color: rgba(0,212,255,0.2);
}
@media (max-width: 900px) {
    .ab-testi-card.ab-testi-featured { grid-column: span 2; }
}
@media (max-width: 600px) {
    .ab-testi-card.ab-testi-featured { grid-column: span 1; }
}
.ab-testi-featured .ab-testi-quote {
    font-size: 1.05rem;
}
</style>

<section id="ab-testimonials">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center ab-reveal">
            
            <h2 class="ab-headline"><?php echo t('about.testi_headline_prefix'); ?> <span class="ab-cyan"><?php echo t('about.testi_headline_accent'); ?></span> <br class="mobile-br"><?php echo t('about.testi_headline_suffix'); ?></h2>
            <p class="ab-sub" style="max-width:460px; margin: 0 auto;"><?php echo t('about.testi_sub'); ?></p>
        </div>

        <div class="ab-testi-grid">
            <!-- Featured testimonial -->
            <div class="ab-testi-card ab-testi-featured ab-reveal" style="transition-delay:0ms;">
                <div class="ab-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="ab-testi-quote">"<?php echo t('about.testi1_quote'); ?>"</p>
                <div class="ab-testi-author">
                    <div class="ab-testi-avatar"><?php echo mb_substr(t('about.testi1_name'), 0, 1); ?></div>
                    <div>
                        <div class="ab-testi-name"><?php echo t('about.testi1_name'); ?></div>
                        <div class="ab-testi-role"><?php echo t('about.testi1_role'); ?></div>
                    </div>
                </div>
            </div>
            <!-- Testimonial 2 -->
            <div class="ab-testi-card ab-reveal" style="transition-delay:80ms;">
                <div class="ab-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="ab-testi-quote">"<?php echo t('about.testi2_quote'); ?>"</p>
                <div class="ab-testi-author">
                    <div class="ab-testi-avatar"><?php echo mb_substr(t('about.testi2_name'), 0, 1); ?></div>
                    <div>
                        <div class="ab-testi-name"><?php echo t('about.testi2_name'); ?></div>
                        <div class="ab-testi-role"><?php echo t('about.testi2_role'); ?></div>
                    </div>
                </div>
            </div>
            <!-- Testimonial 3 -->
            <div class="ab-testi-card ab-reveal" style="transition-delay:160ms;">
                <div class="ab-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="ab-testi-quote">"<?php echo t('about.testi3_quote'); ?>"</p>
                <div class="ab-testi-author">
                    <div class="ab-testi-avatar"><?php echo mb_substr(t('about.testi3_name'), 0, 1); ?></div>
                    <div>
                        <div class="ab-testi-name"><?php echo t('about.testi3_name'); ?></div>
                        <div class="ab-testi-role"><?php echo t('about.testi3_role'); ?></div>
                    </div>
                </div>
            </div>
            <!-- Testimonial 4 -->
            <div class="ab-testi-card ab-reveal" style="transition-delay:240ms;">
                <div class="ab-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="ab-testi-quote">"<?php echo t('about.testi4_quote'); ?>"</p>
                <div class="ab-testi-author">
                    <div class="ab-testi-avatar"><?php echo mb_substr(t('about.testi4_name'), 0, 1); ?></div>
                    <div>
                        <div class="ab-testi-name"><?php echo t('about.testi4_name'); ?></div>
                        <div class="ab-testi-role"><?php echo t('about.testi4_role'); ?></div>
                    </div>
                </div>
            </div>
            <!-- Testimonial 5 -->
            <div class="ab-testi-card ab-reveal" style="transition-delay:320ms;">
                <div class="ab-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="ab-testi-quote">"<?php echo t('about.testi5_quote'); ?>"</p>
                <div class="ab-testi-author">
                    <div class="ab-testi-avatar"><?php echo mb_substr(t('about.testi5_name'), 0, 1); ?></div>
                    <div>
                        <div class="ab-testi-name"><?php echo t('about.testi5_name'); ?></div>
                        <div class="ab-testi-role"><?php echo t('about.testi5_role'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════════
     SECTION 8 — OUR PARTNERS (Logos)
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
    padding: 0 20px;
}
.ab-trusted-logo:hover {
    opacity: 1;
}
@media (max-width: 768px) {
    .ab-trusted-logo { height: 80px; padding: 0 10px; }
}
</style>

<section id="ab-trusted">
    <div class="ab-reveal">
        <h2 class="ab-trusted-title"><?php echo t('about.partners_title_prefix'); ?> <span style="color: #00d4ff;"><?php echo t('about.partners_title_accent'); ?></span></h2>
        <p style="text-align: center; color: #999; font-family: 'Inter', sans-serif; font-size: clamp(0.90rem, 2.5vw, 1.1rem); margin-top: -10px; margin-bottom: 35px; margin-left: auto; margin-right: auto; letter-spacing: 0.02em; max-width: 500px; padding: 0 24px; line-height: 1.6;"><?php echo t('about.partners_sub'); ?></p>
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
     SECTION 9 — TYPOGRAPHIC CTA (matches homepage)
════════════════════════════════════════════════ -->
<style>
.ab-cta-section {
    margin-top: 0;
    padding: 80px 20px 100px;
    background: #000;
    border-top: 1px solid rgba(255,255,255,0.05);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
@media (min-width: 768px) { .ab-cta-section { padding: 120px 20px 140px; } }
.ab-cta-glow {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 900px; height: 700px;
    background: radial-gradient(circle, rgba(0,212,255,0.12) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.7s ease;
    pointer-events: none;
}
.ab-cta-section:hover .ab-cta-glow { opacity: 1; }
.ab-cta-big {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    text-transform: uppercase;
    line-height: 0.92;
    letter-spacing: -0.02em;
    display: block;
    color: #fff;
    transition: transform 0.5s cubic-bezier(0.175,0.885,0.32,1.275);
}
.ab-cta-section:hover .ab-cta-big { transform: scale(1.015); }
.ab-cta-line {
    height: 5px;
    background: #00d4ff;
    margin-top: 12px;
    animation: abCtaPulse 2.2s ease-in-out infinite;
    transition: all 0.4s ease;
}
.ab-cta-section:hover .ab-cta-line {
    box-shadow: 0 0 24px rgba(0,229,255,0.9);
    animation: none;
}
@keyframes abCtaPulse {
    0%,100% { box-shadow: 0 0 6px rgba(0,212,255,0.35); }
    50%      { box-shadow: 0 0 16px rgba(0,212,255,0.75); }
}
</style>

<section class="ab-cta-section" onclick="window.location.href='<?php echo SITE_URL; ?>/contact'">
    <div class="ab-cta-glow"></div>
    <div style="position:relative; z-index:1; text-align:center;">
       
        <div style="transform: rotate(-2.5deg); display: inline-block; width: 100%;">
            <span class="ab-cta-big" style="font-size: clamp(3rem, 10vw, 12rem);"><?php echo t('about.cta_line1'); ?></span>
            <span class="ab-cta-big" style="font-size: clamp(2.4rem, 8vw, 10rem); color: #00d4ff;"><?php echo t('about.cta_line2'); ?></span>
        </div>
        <div style="margin-top: 40px; display: inline-block;">
            <span style="font-family:'Inter',sans-serif; font-size: clamp(1.2rem,3vw,3rem); font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.08em;">
                <?php echo t('about.cta_line3'); ?>
            </span>
            <div class="ab-cta-line"></div>
        </div>
    </div>
</section>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

