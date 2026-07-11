<?php
/**
 * CyberX AJAX Course Search API
 */
header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../includes/functions.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';

if (strlen($query) < 1) {
    echo json_encode(['results' => []]);
    exit;
}

$search = "%$query%";
$courses = $db->fetchAll(
    "SELECT id, title, type, image, price, instructor 
     FROM courses 
     WHERE status = 'active' 
       AND (title LIKE :q1 OR description LIKE :q2 OR instructor LIKE :q3 OR category LIKE :q4)
     ORDER BY featured DESC, title ASC 
     LIMIT 5",
    ['q1' => $search, 'q2' => $search, 'q3' => $search, 'q4' => $search]
);

$results = [];
foreach ($courses as $course) {
    $results[] = [
        'id' => $course['id'],
        'title' => $course['title'],
        'type' => $course['type'],
        'price' => format_price($course['price']),
        'instructor' => $course['instructor'],
        'image' => !empty($course['image']) ? UPLOADS_URL . 'courses/' . $course['image'] : SITE_URL . '/assets/images/course-default.jpg',
        'url' => SITE_URL . '/enroll?course=' . $course['id']
    ];
}

echo json_encode(['results' => $results]);
