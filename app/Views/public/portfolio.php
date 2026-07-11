<?php
/**
 * CyberX Portfolio Page
 */
$page_title = 'Portfolio';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- ═══════════════════════════════════════════════
     PORTFOLIO STYLES
═══════════════════════════════════════════════════ -->
<style>
/* ── Base / Reset ── */
*, *::before, *::after { box-sizing: border-box; }



/* ── Section heading ── */
.svc-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.2rem, 3.8vw, 3.6rem);
    font-weight: 800;
    line-height: 1.08;
    color: #fff;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
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

/* ── Page hero / header section ── */
#pf-hero {
    background: #000000;
    padding: 100px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(0,212,255,0.1);
}
#pf-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(0,212,255,0.06) 0%, transparent 70%);
    pointer-events: none;
}

/* ── Portfolio Grid Section ── */
#portfolio-grid {
    background: #000000;
    padding: 100px 0;
}

.pf-grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media (max-width: 1023px) {
    .pf-grid-container { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767px) {
    .pf-grid-container { grid-template-columns: 1fr; }
}

/* ── Cards (Matched with home page) ── */
.pf-item {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    display: block;
    text-decoration: none;
    background: #000;
}

/* Image wrapper with 16/10 aspect */
.pf-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #0a0a0a;
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
    transition: transform 0.3s;
}
.pf-item:hover .pf-arrow {
    transform: translateX(4px) translateY(-4px);
    color: #00d4ff;
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


</style>

<!-- ═══════════════════════════════════════════════
     PAGE HERO / HEADER
═══════════════════════════════════════════════════ -->
<section id="pf-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position:relative;z-index:1;">
       
        <h1 class="svc-headline"><?php echo t('portfolio.hero_headline_prefix'); ?> <span class="svc-cyan"><?php echo t('portfolio.hero_headline_accent'); ?></span></h1>
        <p class="svc-sub"><?php echo t('portfolio.hero_sub'); ?></p>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     PORTFOLIO GRID
═══════════════════════════════════════════════════ -->
<section id="portfolio-grid">
    <div class="max-w-screen-xl mx-auto px-6 lg:px-10">
        
        <div class="pf-grid-container">
            <!-- Sadeem Website -->
            <div class="pf-item" data-pf-cat="development" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="development" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="development" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="development" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="design" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="design" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="academic" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="academic" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="academic" data-svc-animate>
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
            <div class="pf-item" data-pf-cat="documents" data-svc-animate>
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
</section>

<!-- Entrance animation (IntersectionObserver) -->
<script>
(function(){
    var items = document.querySelectorAll('[data-svc-animate]');
    if (!items.length) return;
    items.forEach(function(el){
        el.style.opacity = '0';
        el.style.transform = 'translateY(28px)';
        el.style.transition = 'opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1), border-color 0.3s ease';
    });
    function reveal(el) { el.style.opacity = '1'; el.style.transform = 'translateY(0)'; }
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(e){ if (e.isIntersecting){ reveal(e.target); io.unobserve(e.target); } });
        }, { threshold: 0, rootMargin: '0px 0px -20px 0px' });
        items.forEach(function(el){ io.observe(el); });
    } else {
        items.forEach(function(el){ reveal(el); });
    }
})();
</script>

<!-- ═══════════════════════════════════════════════
     TYPOGRAPHIC CTA
═══════════════════════════════════════════════════ -->
<style>
.svc-cta-section {
    margin-top: 0;
    padding: 80px 20px 100px;
    background: #000;
    border-top: 1px solid rgba(255,255,255,0.05);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
@media (min-width: 768px) { .svc-cta-section { padding: 120px 20px 140px; } }
.svc-cta-glow {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 900px; height: 700px;
    background: radial-gradient(circle, rgba(0,212,255,0.12) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.7s ease;
    pointer-events: none;
}
.svc-cta-section:hover .svc-cta-glow { opacity: 1; }
.svc-cta-big {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    text-transform: uppercase;
    line-height: 0.92;
    letter-spacing: -0.02em;
    display: block;
    color: #fff;
    transition: transform 0.5s cubic-bezier(0.175,0.885,0.32,1.275);
}
.svc-cta-section:hover .svc-cta-big { transform: scale(1.015); }
.svc-cta-line {
    height: 5px;
    background: #00d4ff;
    margin-top: 12px;
    animation: svcCtaPulse 2.2s ease-in-out infinite;
    transition: all 0.4s ease;
}
.svc-cta-section:hover .svc-cta-line {
    box-shadow: 0 0 24px rgba(0,229,255,0.9);
    animation: none;
}
@keyframes svcCtaPulse {
    0%,100% { box-shadow: 0 0 6px rgba(0,212,255,0.35); }
    50%      { box-shadow: 0 0 16px rgba(0,212,255,0.75); }
}
</style>

<section class="svc-cta-section" onclick="window.location.href='<?php echo SITE_URL; ?>/contact'">
    <div class="svc-cta-glow"></div>
    <div style="position:relative; z-index:1; text-align:center;">
       
        <div style="transform: rotate(-2.5deg); display: inline-block; width: 100%;">
            <span class="svc-cta-big" style="font-size: clamp(3rem, 10vw, 12rem);"><?php echo t('portfolio.cta_line1'); ?></span>
            <span class="svc-cta-big" style="font-size: clamp(2.4rem, 8vw, 10rem); color: #00d4ff;"><?php echo t('portfolio.cta_line2'); ?></span>
        </div>
        <div style="margin-top: 40px; display: inline-block;">
            <span style="font-family:'Inter',sans-serif; font-size: clamp(1.2rem,3vw,3rem); font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.08em;">
                <?php echo t('portfolio.cta_line3'); ?>
            </span>
            <div class="svc-cta-line"></div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
