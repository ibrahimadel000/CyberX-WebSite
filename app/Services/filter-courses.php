<?php
/**
 * CyberX AJAX Course Filter API
 * Returns filtered courses as HTML or JSON
 */
header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Get filter parameters
$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$format = isset($_GET['format']) ? $_GET['format'] : 'html'; // html or json
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
$courses = $db->fetchAll(
    "SELECT * FROM courses WHERE $where ORDER BY featured DESC, created_at DESC LIMIT $per_page OFFSET $offset",
    $params
);

// Return JSON format
if ($format === 'json') {
    $results = [];
    foreach ($courses as $course) {
        $results[] = [
            'id' => $course['id'],
            'title' => $course['title'],
            'short_description' => $course['short_description'],
            'type' => $course['type'],
            'category' => $course['category'],
            'price' => format_price($course['price']),
            'duration' => $course['duration'],
            'instructor' => $course['instructor'],
            'rating' => $course['rating'],
            'students_enrolled' => $course['students_enrolled'],
            'image' => !empty($course['image']) 
                ? UPLOADS_URL . 'courses/' . $course['image'] 
                : SITE_URL . '/assets/images/course-default.jpg',
            'url' => SITE_URL . '/enroll?course=' . $course['id']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'courses' => $results,
        'total' => $total,
        'page' => $page,
        'total_pages' => $total_pages,
        'showing' => count($courses)
    ]);
    exit;
}

// Return HTML format (default)
ob_start();

if (empty($courses)) {
    ?>
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>No Courses Found</h3>
        <p>Try adjusting your filters or search terms.</p>
        <a href="<?php echo SITE_URL; ?>/courses" class="btn btn-primary" style="margin-top: var(--space-md);">
            View All Courses
        </a>
    </div>
    <?php
} else {
    ?>
    <div class="courses-grid" id="coursesGrid">
        <?php foreach ($courses as $course): ?>
        <div class="course-card">
            <div class="course-image">
                <img src="<?php echo !empty($course['image']) ? upload_url('courses/' . $course['image']) : asset_url('images/course-default.jpg'); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                <?php echo type_badge($course['type']); ?>
            </div>
            <div class="course-content">
                <h3><a href="<?php echo SITE_URL; ?>/enroll?course=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></h3>
                <div class="course-instructor">
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($course['instructor']); ?></span>
                </div>
                <div class="course-rating">
                    <?php echo rating_stars($course['rating']); ?>
                    <span>(<?php echo $course['rating']; ?>)</span>
                </div>
                <p class="course-description"><?php echo htmlspecialchars($course['short_description']); ?></p>
                <div class="course-meta">
                    <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                    <span><i class="fas fa-users"></i> <?php echo number_format($course['students_enrolled']); ?></span>
                </div>
                <div class="course-footer">
                    <span class="course-price"><?php echo format_price($course['price']); ?></span>
                    <a href="<?php echo SITE_URL; ?>/enroll?course=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">Enroll Now</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <div class="pagination" id="pagination">
        <?php
        $query_params = $_GET;
        unset($query_params['format']);
        
        if ($page > 1):
            $query_params['page'] = $page - 1;
        ?>
        <a href="javascript:void(0)" onclick="loadPage(<?php echo $page - 1; ?>)"><i class="fas fa-chevron-left"></i></a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
        <a href="javascript:void(0)" onclick="loadPage(<?php echo $i; ?>)" class="<?php echo $i === $page ? 'active' : ''; ?>" <?php echo $i === $page ? 'style="background:var(--accent);color:var(--primary-dark);"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
        <a href="javascript:void(0)" onclick="loadPage(<?php echo $page + 1; ?>)"><i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php
}

$html = ob_get_clean();

echo json_encode([
    'success' => true,
    'html' => $html,
    'total' => $total,
    'page' => $page,
    'total_pages' => $total_pages,
    'showing' => count($courses)
]);
