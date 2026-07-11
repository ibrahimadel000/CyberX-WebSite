<?php
/**
 * CyberX Admin Header
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Check admin login
require_admin_login();

// Get admin info
$admin = $db->fetch("SELECT * FROM admins WHERE id = :id", ['id' => $_SESSION['admin_id']]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME . ' Admin' : SITE_NAME . ' Admin Dashboard'; ?></title>
    
    <!-- Local Fonts (System fallbacks for offline support) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fonts.css">
    
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fontawesome.min.css">
    
    <!-- Admin Stylesheet -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="<?php echo SITE_URL; ?>/admin/dashboard" class="sidebar-brand">
                    <img src="<?php echo SITE_URL; ?>/assets/images/photo_2024-03-26_03-16-00-removebg.png" alt="CyberX Logo" class="sidebar-logo">
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?php echo SITE_URL; ?>/admin/dashboard" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/courses" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/courses') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/students" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/students') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/solutions" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/solutions') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-shield-alt"></i>
                    <span>Solutions</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/services" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/services') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-layer-group"></i>
                    <span>Services</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/messages" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/messages') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    <?php 
                    $unread = $db->count('messages', 'is_read = 0');
                    if ($unread > 0): 
                    ?>
                    <span class="nav-badge"><?php echo $unread; ?></span>
                    <?php endif; ?>
                </a>
                
                <div class="sidebar-divider"></div>
                
                <a href="<?php echo SITE_URL; ?>" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Website</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/admin/logout" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="topbar-right">
                    <div class="admin-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['name']); ?>&background=00D4FF&color=0A1628" alt="Admin">
                        <span><?php echo htmlspecialchars($admin['name']); ?></span>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php if ($flash = get_flash()): ?>
            <div class="admin-alert admin-alert-<?php echo $flash['type']; ?>">
                <span><?php echo $flash['message']; ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="admin-content">
