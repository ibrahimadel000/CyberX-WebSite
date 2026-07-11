<?php
/**
 * CyberX Helper Functions
 */

/**
 * Handle file upload
 */
function upload_file($file, $directory = 'uploads/', $allowed_types = null) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'File too large. Maximum size: 5MB'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    
    $allowed = $allowed_types ?? ALLOWED_IMAGE_TYPES;
    if (!in_array($mime_type, $allowed)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . strtolower($extension);
    
    $upload_path = UPLOADS_PATH . $directory;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }
    
    $destination = $upload_path . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename, 'path' => $directory . $filename];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Delete uploaded file
 */
function delete_file($path) {
    $full_path = UPLOADS_PATH . $path;
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}

/**
 * Get asset URL
 */
function asset_url($path) {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Get upload URL
 */
function upload_url($path) {
    if (empty($path)) {
        return asset_url('images/placeholder.jpg');
    }
    return UPLOADS_URL . ltrim($path, '/');
}

/**
 * Format date
 */
function format_date($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Format price
 */
function format_price($price) {
    return '$' . number_format($price, 2);
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generate rating stars HTML
 */
function rating_stars($rating, $max = 5) {
    $html = '<div class="rating-stars">';
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    
    for ($i = 1; $i <= $max; $i++) {
        if ($i <= $full_stars) {
            $html .= '<i class="fas fa-star"></i>';
        } elseif ($half_star && $i == $full_stars + 1) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Generate badge HTML
 */
function type_badge($type) {
    $class = $type === 'online' ? 'badge-online' : 'badge-offline';
    $icon = $type === 'online' ? 'fa-globe' : 'fa-building';
    return '<span class="badge ' . $class . '"><i class="fas ' . $icon . '"></i> ' . ucfirst($type) . '</span>';
}

/**
 * Generate status badge
 */
function status_badge($status) {
    $classes = [
        'pending' => 'badge-warning',
        'approved' => 'badge-success',
        'rejected' => 'badge-danger',
        'active' => 'badge-success',
        'inactive' => 'badge-secondary'
    ];
    $class = $classes[$status] ?? 'badge-secondary';
    return '<span class="badge ' . $class . '">' . ucfirst($status) . '</span>';
}

/**
 * Validate email
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone
 */
function is_valid_phone($phone) {
    return preg_match('/^[\+]?[0-9\s\-\(\)]{8,20}$/', $phone);
}

/**
 * Generate technologies from JSON
 */
function render_technologies($json) {
    $technologies = json_decode($json, true) ?? [];
    $html = '<div class="tech-tags">';
    foreach ($technologies as $tech) {
        $html .= '<span class="tech-tag">' . htmlspecialchars($tech) . '</span>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Get time ago string
 */
function time_ago($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return format_date($datetime);
    }
}

/**
 * Format file size to human readable
 */
function format_file_size($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Upload video file with larger size limit
 */
function upload_video($file, $directory = 'videos/') {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed'];
    }
    
    if ($file['size'] > MAX_VIDEO_SIZE) {
        return ['success' => false, 'error' => 'Video too large. Maximum size: 300MB'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    
    if (!in_array($mime_type, ALLOWED_VIDEO_TYPES)) {
        return ['success' => false, 'error' => 'Invalid video type. Allowed: MP4, WebM, OGG'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . strtolower($extension);
    
    $upload_path = UPLOADS_PATH . $directory;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }
    
    $destination = $upload_path . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $filename, 'path' => $directory . $filename];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}
