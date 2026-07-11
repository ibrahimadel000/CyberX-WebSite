<?php
/**
 * CyberX Admin Dashboard
 */
$page_title = 'Dashboard';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Get statistics
$total_students = $db->count('enrollments');
$approved_students = $db->count('enrollments', "status = 'approved'");
$pending_applications = $db->count('enrollments', "status = 'pending'");
$total_courses = $db->count('courses');
$active_courses = $db->count('courses', "status = 'active'");
$total_solutions = $db->count('software_solutions', "status = 'active'");
$unread_messages = $db->count('messages', 'is_read = 0');

// Get recent enrollments
$recent_enrollments = $db->fetchAll(
    "SELECT e.*, c.title as course_title 
     FROM enrollments e 
     LEFT JOIN courses c ON e.course_id = c.id 
     ORDER BY e.created_at DESC 
     LIMIT 5"
);

// Get recent messages
$recent_messages = $db->fetchAll(
    "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5"
);
?>

<div class="page-header-admin">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <span style="color: var(--text-muted);">Welcome back, <?php echo htmlspecialchars($admin['name']); ?>!</span>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon students">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($approved_students); ?></h3>
            <p>Total Students</p>
            <span class="stat-change positive"><i class="fas fa-arrow-up"></i> Active enrollments</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($pending_applications); ?></h3>
            <p>Pending Applications</p>
            <span class="stat-change <?php echo $pending_applications > 0 ? 'negative' : 'positive'; ?>">
                <i class="fas fa-<?php echo $pending_applications > 0 ? 'exclamation-circle' : 'check'; ?>"></i>
                <?php echo $pending_applications > 0 ? 'Needs attention' : 'All processed'; ?>
            </span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon courses">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($active_courses); ?></h3>
            <p>Active Courses</p>
            <span class="stat-change positive"><i class="fas fa-check"></i> <?php echo $total_courses; ?> total</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon solutions">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($total_solutions); ?></h3>
            <p>Solutions</p>
            <span class="stat-change positive"><i class="fas fa-check"></i> Active services</span>
        </div>
    </div>
</div>

<div class="admin-grid">
    <!-- Recent Enrollments -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fas fa-user-plus"></i> Recent Enrollments</h3>
            <a href="<?php echo SITE_URL; ?>/admin/students" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="admin-card-body" style="padding: 0;">
            <?php if (empty($recent_enrollments)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Enrollments Yet</h3>
                <p>Student enrollments will appear here.</p>
            </div>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_enrollments as $enrollment): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <img src="<?php echo $enrollment['profile_image'] ? upload_url($enrollment['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($enrollment['full_name']) . '&background=00D4FF&color=0A1628'; ?>" alt="" style="width: 32px; height: 32px; border-radius: 50%;">
                                <div>
                                    <strong><?php echo htmlspecialchars($enrollment['full_name']); ?></strong>
                                    <small style="display: block; color: var(--text-muted);"><?php echo htmlspecialchars($enrollment['email']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars(truncate($enrollment['course_title'] ?? 'N/A', 30)); ?></td>
                        <td><?php echo format_date($enrollment['created_at']); ?></td>
                        <td><?php echo status_badge($enrollment['status']); ?></td>
                        <td>
                            <div class="table-actions">
                                <?php if ($enrollment['status'] === 'pending'): ?>
                                <a href="?approve=<?php echo $enrollment['id']; ?>" class="approve" title="Approve"><i class="fas fa-check"></i></a>
                                <a href="?reject=<?php echo $enrollment['id']; ?>" class="reject" title="Reject"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                                <a href="students?view=<?php echo $enrollment['id']; ?>" class="view" title="View"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Messages -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fas fa-envelope"></i> Recent Messages</h3>
            <?php if ($unread_messages > 0): ?>
            <span class="badge badge-danger"><?php echo $unread_messages; ?> unread</span>
            <?php endif; ?>
        </div>
        <div class="admin-card-body" style="padding: 0;">
            <?php if (empty($recent_messages)): ?>
            <div class="empty-state">
                <i class="fas fa-envelope-open"></i>
                <h3>No Messages</h3>
                <p>Messages from visitors will appear here.</p>
            </div>
            <?php else: ?>
            <div class="message-list">
                <?php foreach ($recent_messages as $message): ?>
                <a href="<?php echo SITE_URL; ?>/admin/messages?view=<?php echo $message['id']; ?>" class="message-item <?php echo !$message['is_read'] ? 'unread' : ''; ?>">
                    <div class="message-avatar">
                        <?php echo strtoupper(substr($message['name'], 0, 1)); ?>
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                            <span><?php echo time_ago($message['created_at']); ?></span>
                        </div>
                        <p class="message-preview"><?php echo htmlspecialchars(truncate($message['message'], 60)); ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <div style="padding: 1rem; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="<?php echo SITE_URL; ?>/admin/messages" class="btn btn-sm btn-secondary">View All Messages</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
