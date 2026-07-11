<?php
/**
 * CyberX Services Page
 * Minimalist Swiss Style Bento Grid overview + Clean details view
 * Professional Antigravity Theme - High Performance
 */
$page_title = 'Services';
require_once __DIR__ . '/../layouts/header.php';

// Get category from URL
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Fetch services from database
$all_services = $db->fetchAll("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC");

// Convert to associative array by category_key (language-aware)
$services = [];
$service_order = [];
foreach ($all_services as $svc) {
    $use_ar = ($current_lang === 'ar');
    $services[$svc['category_key']] = [
        'id' => $svc['id'],
        'title' => ($use_ar && !empty($svc['title_ar'])) ? $svc['title_ar'] : $svc['title'],
        'subtitle' => ($use_ar && !empty($svc['subtitle_ar'])) ? $svc['subtitle_ar'] : $svc['subtitle'],
        'icon' => $svc['icon'],
        'gradient' => $svc['gradient'],
        'color' => $svc['color'],
        'description' => ($use_ar && !empty($svc['description_ar'])) ? $svc['description_ar'] : $svc['description'],
        'features' => json_decode(($use_ar && !empty($svc['features_ar'])) ? $svc['features_ar'] : $svc['features'], true) ?? [],
        'technologies' => json_decode($svc['technologies'], true) ?? [],
        'faqs' => json_decode($svc['faqs'] ?? '[]', true) ?? [],
        'sort_order' => (int)$svc['sort_order']
    ];
    $service_order[] = $svc['category_key'];
}

// Map color values to solid CSS colors and RGB values that match the home page service section
$accent_map = [
    'neon-blue' => ['hex' => '#0c5ca1', 'rgb' => '12, 92, 161'],
    'neon-purple' => ['hex' => '#5f2a94', 'rgb' => '95, 42, 148'],
    'green-400' => ['hex' => '#1f6f45', 'rgb' => '31, 111, 69'],
    'orange-400' => ['hex' => '#b25a08', 'rgb' => '178, 90, 8'],
    'red-400' => ['hex' => '#b23a47', 'rgb' => '178, 58, 71'],
    'indigo-400' => ['hex' => '#4b4fb8', 'rgb' => '75, 79, 184'],
    'business' => ['hex' => '#7dd3fc', 'rgb' => '125, 211, 252'],
    'development' => ['hex' => '#00d4ff', 'rgb' => '0, 212, 255'],
    'design' => ['hex' => '#fb7185', 'rgb' => '251, 113, 133'],
    'academic' => ['hex' => '#22c55e', 'rgb' => '34, 197, 94'],
    'documents' => ['hex' => '#f59e0b', 'rgb' => '245, 158, 11'],
];

// Get selected service or show all
$selected = $category && isset($services[$category]) ? $services[$category] : null;

// Build features string for CTA pre-fill
$features_str = '';
if ($selected) {
    $feature_names = array_map(function($f) { return $f['title']; }, $selected['features']);
    $features_str = implode(',', $feature_names);
}

$selected_color = $selected ? ($accent_map[$selected['color']]['hex'] ?? '#00d4ff') : '#00d4ff';
?>

<!-- ═══════════════════════════════════════════════
     SERVICES PAGE STYLES — Swiss Minimalism Style
     ═══════════════════════════════════════════════ -->
<style>
/* ── Base / Reset ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Swiss Typographic Hierarchy ── */
.svc-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--selected-accent, #00d4ff);
    margin-bottom: 16px;
}
.svc-kicker::before {
    content: '';
    width: 20px;
    height: 1px;
    background: var(--selected-accent, #00d4ff);
}

.svc-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 800;
    line-height: 1.1;
    color: #fff;
    margin: 0 0 16px;
    letter-spacing: -0.03em;
}
.svc-headline .svc-cyan { color: #00d4ff; }

.svc-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #8892B0;
    max-width: 520px;
    line-height: 1.6;
    margin: 0 auto;
}

/* ── Page Hero ── */
#svc-hero {
    background: #000000;
    padding: 100px 0 70px;
    text-align: center;
    position: relative;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

/* ═══════════════════════════════════════════════
   STICKY NAV TABS — Clean & Functional
   ═══════════════════════════════════════════════ */
.svc-sticky-nav {
    position: sticky;
    top: 80px;
    z-index: 40;
    background: #060D18; /* Solid, crisp matching the body background */
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.svc-sticky-nav::-webkit-scrollbar { display: none; }
.svc-sticky-nav-inner {
    display: flex;
    gap: 0;
    min-width: max-content;
    padding: 0;
}
.svc-nav-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 18px 24px;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    color: #8892B0;
    text-decoration: none;
    white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: all 0.25s ease;
}
.svc-nav-tab i {
    font-size: 0.9rem;
}
.svc-nav-tab:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.02);
}
.svc-nav-tab.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
    background: rgba(255, 255, 255, 0.03);
}

/* ═══════════════════════════════════════════════
   BENTO GRID — Clean Swiss Layout (High Performance)
   ═══════════════════════════════════════════════ */
#svc-overview {
    background: #000000;
    padding: 80px 0;
    position: relative;
}

.svc-bento-grid {
    display: grid;
    grid-template-columns: 1.3fr 0.7fr;
    grid-template-rows: 1fr 1fr auto;
    gap: 24px;
}

/* Areas assignment */
.bento-dev      { grid-column: 1 / 2; grid-row: 1 / 3; }
.bento-design   { grid-column: 2 / 3; grid-row: 1 / 2; }
.bento-academic { grid-column: 2 / 3; grid-row: 2 / 3; }
.bento-docs     { grid-column: 1 / 3; grid-row: 3 / 4; }

/* Bento Card Style */
.bento-card {
    position: relative;
    background: var(--accent);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 18px;
    padding: 36px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    min-height: 220px;
    box-shadow: 0 16px 28px rgba(0, 0, 0, 0.22);
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.bento-icon-box {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: rgba(var(--accent-rgb), 0.14);
    border: 1px solid rgba(var(--accent-rgb), 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.bento-card:hover .bento-icon-box {
    border-color: rgba(var(--accent-rgb), 0.5);
    background: rgba(var(--accent-rgb), 0.22);
}

.bento-icon-box i {
    font-size: 1.25rem;
    color: #ffffff;
}

.bento-feat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--text-color);
    background: var(--badge-bg, rgba(255, 255, 255, 0.16));
    border: 1px solid var(--badge-border, rgba(255, 255, 255, 0.24));
    border-radius: 6px;
    padding: 4px 10px;
}

.bento-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--text-color);
    margin: 0 0 6px;
}

.bento-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text-color);
    margin: 0 0 10px;
    line-height: 1.2;
}

.bento-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.88rem;
    color: var(--text-soft);
    line-height: 1.6;
    margin: 0;
}

.bento-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.bento-link-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
    padding-top: 20px;
}
.bento-link-text {
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-color);
    transition: color 0.25s ease;
}
.bento-link-arrow {
    font-size: 0.75rem;
    color: var(--text-color);
    transition: transform 0.25s ease;
}
.bento-card:hover .bento-link-text {
    color: var(--text-color);
}
.bento-card:hover .bento-link-arrow {
    color: var(--text-color);
    transform: translateX(4px);
}

/* Sizing variations */
.bento-card.bento-tall {
    min-height: 380px;
}
.bento-card.bento-tall .bento-title {
    font-size: 1.6rem;
}
.bento-card.bento-tall .bento-desc {
    max-width: 95%;
}

.bento-card.bento-sm {
    min-height: auto;
}
.bento-card.bento-sm .bento-desc {
    -webkit-line-clamp: 3;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bento-card.bento-wide {
    min-height: 160px;
    flex-direction: row;
    align-items: center;
    gap: 24px;
}
.bento-card.bento-wide .bento-card-top {
    margin-bottom: 0;
    flex-shrink: 0;
}
.bento-card.bento-wide .bento-title {
    font-size: 1.25rem;
    margin-bottom: 4px;
}
.bento-card.bento-wide .bento-desc {
    max-width: 90%;
}
.bento-card.bento-wide .bento-link-wrap {
    padding-top: 0;
    margin-top: 0;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════════
   DETAIL VIEW — Clean Typographic List
   ═══════════════════════════════════════════════ */
#svc-detail {
    background: #000000;
    padding: 70px 0 50px;
}

.feat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.feat-card {
    background: #0A1628;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 28px;
    transition: transform 0.25s ease, border-color 0.25s ease;
}
.feat-card:hover {
    transform: translateY(-4px);
    border-color: var(--selected-accent);
}

.feat-icon-box {
    width: 44px; height: 44px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    transition: border-color 0.25s;
}
.feat-card:hover .feat-icon-box {
    border-color: var(--selected-accent);
}
.feat-icon-box i {
    font-size: 1.15rem;
    color: var(--selected-accent);
}

.feat-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 8px;
}
.feat-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    color: #8892B0;
    line-height: 1.6;
    margin: 0;
}

/* ── Technologies bar ── */
.tech-section {
    margin: 50px 0;
    text-align: center;
}
.tech-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #444;
    margin-bottom: 16px;
    display: block;
}
.tech-tag {
    display: inline-block;
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    font-weight: 600;
    color: #fff;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    padding: 6px 14px;
    margin: 4px;
    transition: all 0.2s ease;
}
.tech-tag:hover {
    border-color: var(--selected-accent);
    color: #000;
    background: var(--selected-accent);
}

.svc-section-divider {
    border: none;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    margin: 50px 0;
}

.svc-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    color: #8892B0;
    text-decoration: none;
    transition: color 0.25s;
    letter-spacing: 0.02em;
}
.svc-back-link:hover { color: var(--selected-accent); }

.svc-sub-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 700;
    color: #fff;
    margin: 0 0 12px;
    letter-spacing: -0.01em;
}

/* ═══════════════════════════════════════════════
   FAQ ACCORDION
   ═══════════════════════════════════════════════ */
.svc-faq-section {
    max-width: 800px;
    margin: 0 auto 50px;
}
.svc-faq-item {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 4px 0;
}
.svc-faq-q {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    width: 100%;
    padding: 18px 0;
    background: none;
    border: none;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    color: #ddd;
    text-align: left;
    transition: color 0.25s;
    line-height: 1.4;
}
.svc-faq-q:hover { color: var(--selected-accent); }
.svc-faq-q-icon {
    flex-shrink: 0;
    width: 20px; height: 20px;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    font-size: 0.75rem;
    color: #888;
}
.svc-faq-q:hover .svc-faq-q-icon {
    border-color: var(--selected-accent);
    color: var(--selected-accent);
}
.svc-faq-q.open .svc-faq-q-icon {
    background: var(--selected-accent);
    border-color: var(--selected-accent);
    color: #000;
    transform: rotate(45deg);
}
.svc-faq-a {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s ease;
    padding: 0;
}
.svc-faq-a.open {
    max-height: 400px;
    padding-bottom: 18px;
}
.svc-faq-a p {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #8892B0;
    line-height: 1.6;
    margin: 0;
}

/* ═══════════════════════════════════════════════
   CTA SECTION — Typographic & Spacious
   ═══════════════════════════════════════════════ */
.svc-cta-section {
    padding: 100px 20px 110px;
    background: #000;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    position: relative;
    cursor: pointer;
    text-align: center;
}
.svc-cta-big {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    text-transform: uppercase;
    line-height: 0.92;
    letter-spacing: -0.02em;
    display: block;
    color: #fff;
    transition: transform 0.4s ease;
}
.svc-cta-section:hover .svc-cta-big { 
    transform: scale(1.01);
}
.svc-cta-line {
    height: 2px;
    background: var(--selected-accent, #00d4ff);
    margin-top: 16px;
    width: 60px;
    margin-left: auto;
    margin-right: auto;
    transition: width 0.3s ease;
}
.svc-cta-section:hover .svc-cta-line {
    width: 120px;
}

/* ═══════════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════════ */
@media (max-width: 1023px) {
    .feat-grid { grid-template-columns: repeat(2, 1fr) !important; }

    .svc-bento-grid {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
    }
    .bento-dev      { grid-column: 1 / 2; grid-row: 1 / 3; }
    .bento-design   { grid-column: 2 / 3; grid-row: 1 / 2; }
    .bento-academic { grid-column: 2 / 3; grid-row: 2 / 3; }
    .bento-docs     { grid-column: 1 / 3; grid-row: 3 / 4; }

    .bento-card.bento-tall .bento-desc { max-width: 100%; }
    .bento-card.bento-wide .bento-desc { max-width: 100%; }
    .bento-card.bento-wide { flex-wrap: wrap; }
}

@media (max-width: 767px) {
    #svc-hero { padding: 80px 0 60px; }
    #svc-detail, #svc-overview { padding: 60px 0; }
    .feat-grid { grid-template-columns: 1fr !important; }
    .svc-nav-tab { padding: 12px 18px; font-size: 0.75rem; }
    .svc-nav-tab i { display: none; }

    .svc-bento-grid { grid-template-columns: 1fr; }
    .bento-dev, .bento-design, .bento-academic, .bento-docs {
        grid-column: 1 / 2;
        grid-row: auto;
    }
    .bento-card.bento-wide {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        padding: 28px;
    }
    .bento-card.bento-tall { min-height: auto; }
    .bento-card.bento-wide .bento-card-top { margin-bottom: 0; }
    .bento-card.bento-wide .bento-link-wrap { margin-top: 16px; }
}
</style>

<!-- ═══════════════════════════════════════════════
     PAGE HERO / HEADER
     ═══════════════════════════════════════════════ -->
<section id="svc-hero" style="--selected-accent: <?php echo $selected_color; ?>;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position:relative;z-index:1;">
        <?php if ($selected): ?>
            <span class="svc-kicker" style="--selected-accent: <?php echo $selected_color; ?>;"><span class="svc-eyebrow-dot" style="background: <?php echo $selected_color; ?>;"></span>&nbsp;<?php echo htmlspecialchars($selected['subtitle']); ?></span>
            <h1 class="svc-headline" style="margin:0 0 16px;"><?php echo htmlspecialchars($selected['title']); ?></h1>
            <p class="svc-sub"><?php echo htmlspecialchars($selected['description']); ?></p>
        <?php else: ?>
            <span class="svc-kicker"><span class="svc-eyebrow-dot"></span>&nbsp;<?php echo t('nav.services'); ?></span>
            <h1 class="svc-headline" style="margin:0 0 16px;"><?php echo t('services.hero_headline_prefix'); ?> <span class="svc-cyan"><?php echo t('services.hero_headline_accent'); ?></span></h1>
            <p class="svc-sub"><?php echo t('services.hero_sub'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if ($selected): ?>
<!-- ═══════════════════════════════════════════════
     STICKY SERVICE NAV TABS
     ═══════════════════════════════════════════════ -->
<nav class="svc-sticky-nav" id="svcStickyNav">
    <div class="max-w-7xl mx-auto" style="padding:0;">
        <div class="svc-sticky-nav-inner">
            <?php 
            foreach ($services as $key => $svc): 
                $tab_color = $accent_map[$svc['color']]['hex'] ?? '#00d4ff';
                $tab_style = "--accent: " . $tab_color . ";";
            ?>
            <a href="<?php echo SITE_URL; ?>/services?category=<?php echo urlencode($key); ?>"
               class="svc-nav-tab <?php echo $key === $category ? 'active' : ''; ?>"
               data-svc-nav="<?php echo $key; ?>"
               style="<?php echo $tab_style; ?>">
                <i class="fas <?php echo htmlspecialchars($svc['icon']); ?>"></i>
                <?php echo htmlspecialchars($svc['title']); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>

<!-- ═══════════════════════════════════════════════
     SINGLE CATEGORY DETAIL VIEW
     ═══════════════════════════════════════════════ -->
<section id="svc-detail" style="--selected-accent: <?php echo $selected_color; ?>;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Features Grid -->
        <div style="margin-bottom:16px;">
            <div class="feat-grid">
                <?php foreach ($selected['features'] as $feature): ?>
                <div class="feat-card">
                    <div class="feat-icon-box">
                        <i class="fas <?php echo htmlspecialchars($feature['icon']); ?>"></i>
                    </div>
                    <h3 class="feat-title"><?php echo htmlspecialchars($feature['title']); ?></h3>
                    <p class="feat-desc"><?php echo htmlspecialchars($feature['desc']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Technologies -->
        <div class="tech-section">
            <span class="tech-label"><?php echo t('services.tools_tech'); ?></span>
            <div>
                <?php foreach ($selected['technologies'] as $tech): ?>
                <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <hr class="svc-section-divider">

        <!-- ── FAQ ACCORDION ── -->
        <?php if (!empty($selected['faqs'])): ?>
        <div class="svc-faq-section">
            <h2 class="svc-sub-headline" style="text-align:center;margin-bottom:40px;">
                <?php echo t('services.faq_title'); ?>
            </h2>
            <?php foreach ($selected['faqs'] as $faq): ?>
            <div class="svc-faq-item">
                <button class="svc-faq-q" onclick="toggleFaq(this)">
                    <span><?php echo htmlspecialchars($faq['q']); ?></span>
                    <span class="svc-faq-q-icon">+</span>
                </button>
                <div class="svc-faq-a">
                    <p><?php echo htmlspecialchars($faq['a']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Actions: Personalized CTA + Back -->
        <div style="text-align:center; margin-top:48px;">
            <a href="<?php echo SITE_URL; ?>/contact?service=<?php echo urlencode($category); ?>&features=<?php echo urlencode($features_str); ?>"
               class="hero-btn-primary" style="display:inline-flex; align-items:center; gap:8px; font-family:'Inter',sans-serif; font-weight:600; font-size:0.9rem; padding:13px 26px; border-radius:6px; background:<?php echo $selected_color; ?>; color:#000; border:2px solid <?php echo $selected_color; ?>; text-decoration:none; transition:all 0.25s; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                <?php echo t('services.cta_line3'); ?> <i class="fas fa-arrow-<?php echo $is_rtl ? 'left' : 'right'; ?>"></i>
            </a>
            <div style="margin-top: 20px;">
                <a href="<?php echo SITE_URL; ?>/services" class="svc-back-link">
                    <i class="fas fa-arrow-<?php echo $is_rtl ? 'right' : 'left'; ?>"></i> <?php echo t('services.view_all'); ?>
                </a>
            </div>
        </div>

    </div>
</section>

<?php else: ?>
<!-- ═══════════════════════════════════════════════
     ALL SERVICES OVERVIEW — Bento Grid
     ═══════════════════════════════════════════════ -->
<section id="svc-overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="svc-bento-grid">

            <?php
            $order_map = [];
            foreach ($service_order as $i => $key) {
                $order_map[$key] = $i;
            }
            $keys = $service_order;
            $bento_dev = $keys[0] ?? 'development';
            $bento_design = $keys[1] ?? 'design';
            $bento_academic = $keys[2] ?? 'academic';
            $bento_docs = $keys[3] ?? 'documents';

            $card_accent_palette = [
                'development' => ['hex' => '#0c5ca1', 'rgb' => '12, 92, 161'],
                'business'    => ['hex' => '#1479a4', 'rgb' => '20, 121, 164'],
                'design'      => ['hex' => '#5f2a94', 'rgb' => '95, 42, 148'],
                'academic'    => ['hex' => '#1f6f45', 'rgb' => '31, 111, 69'],
                'documents'   => ['hex' => '#b25a08', 'rgb' => '178, 90, 8'],
            ];

            // Helper to render a clean, high-performance bento card
            function render_bento_card($key, $svc, $accent_map, $class_extra, $accent_color, $card_accent_palette) {
                $feat_count = count($svc['features']);
                $accent = $accent_color;
                $accent_rgb = '0, 0, 0';

                if (isset($card_accent_palette[$key])) {
                    $accent = $card_accent_palette[$key]['hex'];
                    $accent_rgb = $card_accent_palette[$key]['rgb'];
                } elseif (isset($accent_map[$svc['color']])) {
                    $accent = $accent_map[$svc['color']]['hex'];
                    $accent_rgb = $accent_map[$svc['color']]['rgb'];
                }

                $text_color = '#ffffff';
                $text_soft = 'rgba(255,255,255,0.92)';
                $badge_bg = 'rgba(255,255,255,0.14)';
                $badge_border = 'rgba(255,255,255,0.24)';

                ?>
                <a href="<?php echo SITE_URL; ?>/services?category=<?php echo urlencode($key); ?>"
                   class="bento-card <?php echo $class_extra; ?>"
                   style="--accent: <?php echo $accent; ?>; --accent-rgb: <?php echo $accent_rgb; ?>; --text-color: <?php echo $text_color; ?>; --text-soft: <?php echo $text_soft; ?>; --badge-bg: <?php echo $badge_bg; ?>; --badge-border: <?php echo $badge_border; ?>;">
                    <div class="bento-card-top">
                        <div class="bento-icon-box">
                            <i class="fas <?php echo htmlspecialchars($svc['icon']); ?>"></i>
                        </div>
                        <span class="bento-feat-badge">
                            <i class="fas fa-cube"></i>
                            <?php echo $feat_count; ?> <?php echo t('services.services_label'); ?>
                        </span>
                    </div>
                    <div class="bento-content">
                        <p class="bento-subtitle"><?php echo htmlspecialchars($svc['subtitle']); ?></p>
                        <h3 class="bento-title"><?php echo htmlspecialchars($svc['title']); ?></h3>
                        <p class="bento-desc"><?php echo htmlspecialchars($svc['description']); ?></p>
                    </div>
                    <div class="bento-link-wrap">
                        <span class="bento-link-text"><?php echo t('services.view_details'); ?></span>
                        <span class="bento-link-arrow"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                <?php
            }
            ?>

            <!-- Tall: Development -->
            <?php render_bento_card($bento_dev, $services[$bento_dev], $accent_map, 'bento-tall bento-dev', '#0c5ca1', $card_accent_palette); ?>

            <!-- Small top-right: Design -->
            <?php render_bento_card($bento_design, $services[$bento_design], $accent_map, 'bento-sm bento-design', '#5f2a94', $card_accent_palette); ?>

            <!-- Small bottom-right: Academic -->
            <?php render_bento_card($bento_academic, $services[$bento_academic], $accent_map, 'bento-sm bento-academic', '#1f6f45', $card_accent_palette); ?>

            <!-- Full-width bottom: Documents -->
            <?php render_bento_card($bento_docs, $services[$bento_docs], $accent_map, 'bento-wide bento-docs', '#b25a08', $card_accent_palette); ?>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════
     TYPOGRAPHIC CTA
     ═══════════════════════════════════════════════ -->
<section class="svc-cta-section"
         onclick="window.location.href='<?php echo SITE_URL; ?>/contact<?php echo $selected ? '?service=' . urlencode($category) . '&features=' . urlencode($features_str) : ''; ?>'"
         style="--selected-accent: <?php echo $selected_color; ?>;">
    <div style="position:relative; z-index:1; text-align:center;">
        <div style="transform: rotate(-1.5deg); display: inline-block; width: 100%;">
            <span class="svc-cta-big" style="font-size: clamp(3rem, 10vw, 12rem);">
                <?php echo $selected ? htmlspecialchars($selected['title']) : t('services.cta_line1'); ?>
            </span>
            <span class="svc-cta-big" style="font-size: clamp(2.4rem, 8vw, 10rem); color: <?php echo $selected_color; ?>;">
                <?php echo t('services.cta_line2'); ?>
            </span>
        </div>
        <div style="margin-top: 36px; display: inline-block;">
            <span style="font-family:'Inter',sans-serif; font-size: clamp(1.1rem,2.5vw,2.5rem); font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.08em;">
                <?php echo t('services.cta_line3'); ?>
            </span>
            <div class="svc-cta-line" style="background: <?php echo $selected_color; ?>;"></div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     JAVASCRIPT: FAQ Toggle
     ═══════════════════════════════════════════════ -->
<script>
(function(){
    // ── FAQ Toggle ──
    window.toggleFaq = function(btn) {
        var answer = btn.nextElementSibling;
        if (!answer) return;
        var isOpen = answer.classList.contains('open');
        // Close all FAQs in this page
        document.querySelectorAll('.svc-faq-a.open').forEach(function(el) {
            el.classList.remove('open');
            el.previousElementSibling.classList.remove('open');
        });
        // Toggle current FAQ
        if (!isOpen) {
            answer.classList.add('open');
            btn.classList.add('open');
        }
    };
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
