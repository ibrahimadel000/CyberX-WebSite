<?php
/**
 * CyberX Courses Page (Academy)
 * Professional Antigravity Theme
 */
$page_title = 'Academy';
require_once __DIR__ . '/../layouts/header.php';

// Get filter parameters
$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Build query
$where = "status = 'active'";
$params = [];

if ($type && in_array($type, ['online', 'offline'])) {
    $where .= " AND type = :type";
    $params['type'] = $type;
}

if ($category) {
    $where .= " AND category = :category";
    $params['category'] = $category;
}

if ($search) {
    $where .= " AND (title LIKE :search OR description LIKE :search2 OR instructor LIKE :search3)";
    $params['search'] = "%$search%";
    $params['search2'] = "%$search%";
    $params['search3'] = "%$search%";
}

// Get total count
$total = $db->count('courses', $where, $params);
$total_pages = ceil($total / $per_page);

// Fetch courses
$courses = $db->fetchAll("SELECT * FROM courses WHERE $where ORDER BY featured DESC, created_at DESC LIMIT $per_page OFFSET $offset", $params);

// Get categories for filter
$categories = $db->fetchAll("SELECT DISTINCT category FROM courses WHERE status = 'active' AND category IS NOT NULL ORDER BY category");
?>

<!-- ═══════════════════════════════════════════════
     COURSES PAGE STYLES — matching index
═══════════════════════════════════════════════════ -->
<style>
*, *::before, *::after { box-sizing: border-box; }

/* ── Page hero ── */
#acad-hero {
    background: #000000;
    padding: 100px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(0,212,255,0.1);
}
#acad-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(0,212,255,0.06) 0%, transparent 70%);
    pointer-events: none;
}

/* ── Section headings ── */
.acad-headline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.2rem, 3.8vw, 3.6rem);
    font-weight: 800;
    line-height: 1.08;
    color: #fff;
    margin: 0 0 16px;
    letter-spacing: -0.02em;
}
.acad-headline .acad-cyan { color: #00d4ff; }
.acad-sub {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    color: #888;
    max-width: 480px;
    line-height: 1.7;
    margin: 0 auto;
}

/* ── Main layout ── */
#acad-main {
    background: #000000;
    padding: 80px 0 100px;
}

/* Filter/CTA buttons */
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.02em;
    padding: 11px 24px;
    border-radius: 6px;
    background: #00d4ff;
    color: #000;
    border: 2px solid #00d4ff;
    transition: background 0.25s, color 0.25s, box-shadow 0.25s;
    text-decoration: none;
    cursor: pointer;
    width: 100%;
    justify-content: center;
}
.btn-primary:hover {
    background: transparent;
    color: #00d4ff;
    box-shadow: 0 0 24px rgba(0,212,255,0.3);
}

/* ── Results bar ── */
.acad-results-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.acad-results-count {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #555;
}
.acad-results-count strong { color: #fff; }
.acad-results-count .acad-highlight { color: #00d4ff; }

/* ── Course cards ── */
.acad-card {
    position: relative;
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    border-radius: 16px;
    overflow: hidden;
    transition: border-color 0.3s ease, transform 0.3s ease;
    display: flex;
    flex-direction: column;
}
.acad-card:hover {
    border-color: #00d4ff;
    transform: translateY(-6px);
}

/* Card image */
.acad-card-img {
    position: relative;
    height: 188px;
    overflow: hidden;
    flex-shrink: 0;
}
.acad-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.acad-card:hover .acad-card-img img { transform: scale(1.06); }

/* Badges */
.acad-badge {
    position: absolute;
    top: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    padding: 4px 10px;
    border-radius: 999px;
}
.acad-badge-type-online  { right:12px; background:rgba(34,197,94,0.15); color:#22c55e; }
.acad-badge-type-offline { right:12px; background:rgba(59,130,246,0.15); color:#60a5fa; }
.acad-badge-featured     { left:12px;  background:rgba(0,212,255,0.12);  color:#00d4ff; }

/* Card body */
.acad-card-body { padding: 24px; display: flex; flex-direction: column; flex: 1; }

.acad-card-category {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #444;
    margin-bottom: 8px;
}
.acad-card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 8px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.25s;
}
.acad-card:hover .acad-card-title { color: #00d4ff; }

.acad-card-instructor {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    color: #555;
    margin-bottom: 12px;
}
.acad-card-instructor i { color: #00d4ff; font-size: 0.75rem; }

/* Star rating */
.acad-stars { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
.acad-stars-icons { display: flex; gap: 2px; }
.acad-stars-icons i { font-size: 0.65rem; color: #f59e0b; }
.acad-stars-icons i.dim { opacity: 0.25; }
.acad-stars-val {
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    color: #555;
}

/* Desc */
.acad-card-desc {
    font-family: 'Inter', sans-serif;
    font-size: 0.86rem;
    color: #555;
    line-height: 1.65;
    margin: 0 0 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Meta row */
.acad-card-meta {
    display: flex;
    gap: 18px;
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    color: #444;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.acad-card-meta span { display: flex; align-items: center; gap: 5px; }
.acad-card-meta i { font-size: 0.7rem; }
.acad-card-meta .meta-clock i  { color: #00d4ff; }
.acad-card-meta .meta-users i  { color: #a855f7; }

/* Footer */
.acad-card-footer {
    margin-top: auto;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.acad-price {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #00d4ff;
}
.acad-enroll-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #888;
    text-decoration: none;
    opacity: 0;
    transform: translateY(4px);
    transition: opacity 0.3s ease, transform 0.3s ease, gap 0.3s ease;
    cursor: not-allowed;
}
.acad-card:hover .acad-enroll-btn {
    opacity: 1;
    transform: translateY(0);
    gap: 10px;
    color: #aaa;
}

/* ── Empty state ── */
.acad-empty {
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    border-radius: 16px;
    padding: 80px 32px;
    text-align: center;
}
.acad-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: rgba(0,212,255,0.06);
    border: 1px solid rgba(0,212,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}
.acad-empty-icon i { font-size: 1.6rem; color: #00d4ff; opacity: 0.5; }
.acad-empty-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 10px;
}
.acad-empty-sub {
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #555;
    margin: 0 0 28px;
    line-height: 1.65;
}

/* ── Pagination ── */
.acad-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 60px;
}
.acad-page-btn {
    width: 40px; height: 40px;
    border-radius: 8px;
    background: #0d0d0d;
    border: 1px solid #1a1a1a;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: border-color 0.25s, color 0.25s, background 0.25s;
}
.acad-page-btn:hover {
    border-color: rgba(0,212,255,0.4);
    color: #00d4ff;
}
.acad-page-btn.active {
    background: #00d4ff;
    border-color: #00d4ff;
    color: #000;
}

/* ── Responsive ── */
@media (max-width: 600px) {
    #acad-hero { padding: 80px 0 60px; }
    #acad-main { padding: 60px 0 80px; }
    .acad-results-bar { flex-direction: column; align-items: flex-start; gap: 8px; }
}
</style>

<!-- ═══════════════════════════════════════════════
     PAGE HERO
═══════════════════════════════════════════════════ -->
<section id="acad-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="position:relative;z-index:1;">
        <h1 class="acad-headline"><?php echo t('courses.hero_headline_prefix'); ?><br><span class="acad-cyan"><?php echo t('courses.hero_headline_accent'); ?></span></h1>
        <p class="acad-sub"><?php echo t('courses.hero_sub'); ?></p>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MAIN CONTENT — Courses Grid
═══════════════════════════════════════════════════ -->
<section id="acad-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            <!-- ── Courses Grid ── -->
            <div>
                <!-- Results bar -->
                <div class="acad-results-bar">
                    <p class="acad-results-count">
                        <?php echo t('courses.showing'); ?> <strong><?php echo count($courses); ?></strong> <?php echo t('courses.of'); ?>
                        <strong><?php echo $total; ?></strong> <?php echo t('courses.courses_label'); ?>
                        <?php if ($search): ?>
                        <?php echo t('courses.for_search'); ?> "<span class="acad-highlight"><?php echo htmlspecialchars($search); ?></span>"
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (empty($courses)): ?>
                <!-- Empty state -->
                <div class="acad-empty" data-acad-animate>
                    <div class="acad-empty-icon"><i class="fas fa-search"></i></div>
                    <h3 class="acad-empty-title"><?php echo t('courses.no_courses_title'); ?></h3>
                    <p class="acad-empty-sub"><?php echo t('courses.no_courses_sub'); ?></p>
                    <a href="<?php echo SITE_URL; ?>/courses" class="btn-primary" style="width:auto; display:inline-flex; margin:0 auto;">
                        <?php echo t('courses.view_all'); ?>
                    </a>
                </div>
                <?php else: ?>
                <!-- Grid -->
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;" class="acad-grid">
                    <?php foreach ($courses as $course): ?>
                    <div class="acad-card" data-acad-animate>

                        <!-- Image -->
                        <div class="acad-card-img">
                            <img src="<?php echo !empty($course['image']) ? upload_url('courses/' . $course['image']) : asset_url('images/course-default.jpg'); ?>"
                                 alt="<?php echo htmlspecialchars($course['title']); ?>">

                            <!-- Type badge -->
                            <span class="acad-badge <?php echo $course['type'] === 'online' ? 'acad-badge-type-online' : 'acad-badge-type-offline'; ?>">
                                <i class="fas <?php echo $course['type'] === 'online' ? 'fa-globe' : 'fa-building'; ?>"></i>
                                <?php echo ucfirst($course['type']); ?>
                            </span>

                            <?php if ($course['featured']): ?>
                            <span class="acad-badge acad-badge-featured">
                                <i class="fas fa-star"></i> <?php echo t('courses.featured'); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <!-- Body -->
                        <div class="acad-card-body">
                            <?php if (!empty($course['category'])): ?>
                            <p class="acad-card-category"><?php echo htmlspecialchars($course['category']); ?></p>
                            <?php endif; ?>

                            <h3 class="acad-card-title">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </h3>

                            <div class="acad-card-instructor">
                                <i class="fas fa-user-tie"></i>
                                <span><?php echo htmlspecialchars($course['instructor']); ?></span>
                            </div>

                            <!-- Stars -->
                            <div class="acad-stars">
                                <div class="acad-stars-icons">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $course['rating'] ? '' : ' dim'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="acad-stars-val">(<?php echo $course['rating']; ?>)</span>
                            </div>

                            <p class="acad-card-desc"><?php echo htmlspecialchars($course['short_description']); ?></p>

                            <!-- Meta -->
                            <div class="acad-card-meta">
                                <span class="meta-clock">
                                    <i class="fas fa-clock"></i>
                                    <?php echo htmlspecialchars($course['duration']); ?>
                                </span>
                                <span class="meta-users">
                                    <i class="fas fa-users"></i>
                                    <?php echo number_format($course['students_enrolled']); ?>
                                </span>
                            </div>

                            <!-- Footer -->
                            <div class="acad-card-footer">
                                <span class="acad-price"><?php echo format_price($course['price']); ?></span>
                                <span class="acad-enroll-btn">
                                    <?php echo t('courses.coming_soon') ?: 'Coming Soon'; ?>
                                </span>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="acad-pagination">
                    <?php
                    $query_params = $_GET;
                    if ($page > 1):
                        $query_params['page'] = $page - 1;
                    ?>
                    <a href="?<?php echo http_build_query($query_params); ?>" class="acad-page-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++):
                        $query_params['page'] = $i;
                    ?>
                    <a href="?<?php echo http_build_query($query_params); ?>" class="acad-page-btn <?php echo $i === $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>

                    <?php
                    if ($page < $total_pages):
                        $query_params['page'] = $page + 1;
                    ?>
                    <a href="?<?php echo http_build_query($query_params); ?>" class="acad-page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php endif; ?>
            </div><!-- end courses grid col -->

        </div><!-- end layout grid -->
    </div>
</section>

<!-- ── Entrance animations (IntersectionObserver) — matching index pattern ── -->
<script>
(function(){
    var items = document.querySelectorAll('[data-acad-animate]');
    if (!items.length) return;
    items.forEach(function(el){
        el.style.opacity = '0';
        el.style.transform = 'translateY(28px)';
        el.style.transition = 'opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1), border-color 0.3s ease';
    });
    function reveal(el){ el.style.opacity='1'; el.style.transform='translateY(0)'; }
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(e){ if(e.isIntersecting){ reveal(e.target); io.unobserve(e.target); } });
        }, { threshold: 0, rootMargin: '0px 0px -20px 0px' });
        items.forEach(function(el){ io.observe(el); });
    } else {
        items.forEach(function(el){ reveal(el); });
    }
})();
</script>

<!-- Auto-submit filter on radio change -->
<script>
(function(){
    var form = document.getElementById('filterForm');
    if (!form) return;
    form.querySelectorAll('input[type="radio"]').forEach(function(r){
        r.addEventListener('change', function(){ form.submit(); });
    });
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
            <span class="svc-cta-big" style="font-size: clamp(3rem, 10vw, 12rem);"><?php echo t('courses.cta_line1'); ?></span>
            <span class="svc-cta-big" style="font-size: clamp(2.4rem, 8vw, 10rem); color: #00d4ff;"><?php echo t('courses.cta_line2'); ?></span>
        </div>
        <div style="margin-top: 40px; display: inline-block;">
            <span style="font-family:'Inter',sans-serif; font-size: clamp(1.2rem,3vw,3rem); font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.08em;">
                <?php echo t('courses.cta_line3'); ?>
            </span>
            <div class="svc-cta-line"></div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
