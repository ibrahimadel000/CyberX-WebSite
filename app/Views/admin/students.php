<?php
/**
 * CyberX Admin - Students Management
 */
$page_title = 'Manage Students';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Handle approve/reject/delete actions (POST only with CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Security token expired. Please try again.');
        redirect(SITE_URL . '/admin/students');
    }
    
    if (isset($_POST['approve'])) {
        $id = (int)$_POST['approve'];
        $db->update('enrollments', ['status' => 'approved'], 'id = :id', ['id' => $id]);
        
        // Increment course student count
        $enrollment = $db->fetch("SELECT course_id FROM enrollments WHERE id = :id", ['id' => $id]);
        if ($enrollment) {
            $db->query("UPDATE courses SET students_enrolled = students_enrolled + 1 WHERE id = :id", ['id' => $enrollment['course_id']]);
        }
        
        set_flash('success', 'Enrollment approved!');
        redirect(SITE_URL . '/admin/students');
    }

    if (isset($_POST['reject'])) {
        $id = (int)$_POST['reject'];
        $db->update('enrollments', ['status' => 'rejected'], 'id = :id', ['id' => $id]);
        set_flash('success', 'Enrollment rejected.');
        redirect(SITE_URL . '/admin/students');
    }

    if (isset($_POST['delete'])) {
        $id = (int)$_POST['delete'];
        $db->delete('enrollments', 'id = :id', ['id' => $id]);
        set_flash('success', 'Enrollment deleted.');
        redirect(SITE_URL . '/admin/students');
    }
}

// Filter by status
$status_filter = isset($_GET['status']) && in_array($_GET['status'], ['pending', 'approved', 'rejected']) ? $_GET['status'] : '';

// Get enrollments with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

$where = '1';
$params = [];
if ($status_filter) {
    $where = 'e.status = :status';
    $params['status'] = $status_filter;
}

$total = $db->fetchColumn("SELECT COUNT(*) FROM enrollments e WHERE $where", $params);
$total_pages = ceil($total / $per_page);

$enrollments = $db->fetchAll(
    "SELECT e.*, c.title as course_title, c.type as course_type,
     COALESCE(s.profile_image, e.profile_image) as profile_image
     FROM enrollments e 
     LEFT JOIN courses c ON e.course_id = c.id 
     LEFT JOIN students s ON e.student_id = s.id
     WHERE $where 
     ORDER BY e.created_at DESC 
     LIMIT $per_page OFFSET $offset",
    $params
);

// Get counts
$pending_count = $db->count('enrollments', "status = 'pending'");
$approved_count = $db->count('enrollments', "status = 'approved'");
$rejected_count = $db->count('enrollments', "status = 'rejected'");

// View single enrollment
$view_enrollment = null;
if (isset($_GET['view'])) {
    $view_enrollment = $db->fetch(
        "SELECT e.*, c.title as course_title, c.type as course_type, c.price as course_price,
         COALESCE(s.profile_image, e.profile_image) as profile_image
         FROM enrollments e 
         LEFT JOIN courses c ON e.course_id = c.id 
         LEFT JOIN students s ON e.student_id = s.id
         WHERE e.id = :id",
        ['id' => (int)$_GET['view']]
    );
}
?>

<div class="page-header-admin">
    <h1><i class="fas fa-user-graduate"></i> Manage Students</h1>
    <div style="display: flex; gap: 0.5rem;">
        <a href="?status=" class="btn btn-sm <?php echo !$status_filter ? 'btn-primary' : 'btn-secondary'; ?>">All (<?php echo $pending_count + $approved_count + $rejected_count; ?>)</a>
        <a href="?status=pending" class="btn btn-sm <?php echo $status_filter === 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">Pending (<?php echo $pending_count; ?>)</a>
        <a href="?status=approved" class="btn btn-sm <?php echo $status_filter === 'approved' ? 'btn-primary' : 'btn-secondary'; ?>">Approved (<?php echo $approved_count; ?>)</a>
        <a href="?status=rejected" class="btn btn-sm <?php echo $status_filter === 'rejected' ? 'btn-primary' : 'btn-secondary'; ?>">Rejected (<?php echo $rejected_count; ?>)</a>
    </div>
</div>

<!-- Enrollments Table -->
<div class="admin-card">
    <div class="admin-card-body" style="padding: 0;">
        <?php if (empty($enrollments)): ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No Enrollments Found</h3>
            <p><?php echo $status_filter ? 'No ' . $status_filter . ' enrollments.' : 'Student enrollments will appear here.'; ?></p>
        </div>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Contact</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $e): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <?php 
                            $e_initials = '';
                            $e_name_parts = explode(' ', trim($e['full_name']));
                            foreach ($e_name_parts as $part) {
                                $e_initials .= strtoupper(substr($part, 0, 1));
                            }
                            $e_initials = substr($e_initials, 0, 2);
                            $e_avatar_url = !empty($e['profile_image']) 
                                ? upload_url($e['profile_image']) 
                                : 'https://ui-avatars.com/api/?name=' . urlencode($e['full_name']) . '&background=00D4FF&color=0A1628';
                            ?>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #00D4FF, #0A1628); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: bold; color: white; overflow: hidden; flex-shrink: 0;">
                                <img src="<?php echo $e_avatar_url; ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='<?php echo $e_initials; ?>';">
                            </div>
                            <strong><?php echo htmlspecialchars($e['full_name']); ?></strong>
                        </div>
                    </td>
                    <td>
                        <div>
                            <small style="display: block;"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($e['email']); ?></small>
                            <small style="display: block; color: var(--text-muted);"><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($e['phone']); ?></small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <?php echo htmlspecialchars(truncate($e['course_title'] ?? 'N/A', 25)); ?>
                            <?php if ($e['course_type']): echo type_badge($e['course_type']); endif; ?>
                        </div>
                    </td>
                    <td><?php echo format_date($e['created_at'], 'M d, Y H:i'); ?></td>
                    <td><?php echo status_badge($e['status']); ?></td>
                    <td>
                        <div class="table-actions">
                            <?php if ($e['status'] === 'pending'): ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Approve this enrollment?')">
                                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                                <button type="submit" name="approve" value="<?php echo $e['id']; ?>" class="approve" title="Approve"><i class="fas fa-check"></i></button>
                            </form>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Reject this enrollment?')">
                                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                                <button type="submit" name="reject" value="<?php echo $e['id']; ?>" class="reject" title="Reject"><i class="fas fa-times"></i></button>
                            </form>
                            <?php endif; ?>
                            <a href="?view=<?php echo $e['id']; ?>" class="view" title="View Details"><i class="fas fa-eye"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this enrollment?')">
                                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                                <button type="submit" name="delete" value="<?php echo $e['id']; ?>" class="delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php 
            $query_params = $_GET;
            for ($i = 1; $i <= $total_pages; $i++): 
                $query_params['page'] = $i;
            ?>
            <a href="?<?php echo http_build_query($query_params); ?>" <?php echo $i === $page ? 'style="background:var(--accent);color:var(--primary-dark);"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- View Modal -->
<?php if ($view_enrollment): ?>
<div class="modal-overlay active" id="viewModal">
    <div class="modal" style="transform: scale(1);">
        <div class="modal-header">
            <h3>Enrollment Details</h3>
            <a href="<?php echo SITE_URL; ?>/admin/students<?php echo $status_filter ? '?status=' . $status_filter : ''; ?>" class="modal-close"><i class="fas fa-times"></i></a>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <?php 
                $initials = '';
                $name_parts = explode(' ', trim($view_enrollment['full_name']));
                foreach ($name_parts as $part) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
                $initials = substr($initials, 0, 2);
                $avatar_url = !empty($view_enrollment['profile_image']) 
                    ? upload_url($view_enrollment['profile_image']) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($view_enrollment['full_name']) . '&background=00D4FF&color=0A1628&size=100';
                ?>
                <div style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 1rem; background: linear-gradient(135deg, #00D4FF, #0A1628); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: white; overflow: hidden;">
                    <img src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($view_enrollment['full_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='<?php echo $initials; ?>';">
                </div>
                <h3 style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($view_enrollment['full_name']); ?></h3>
                <?php echo status_badge($view_enrollment['status']); ?>
            </div>
            
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);"><i class="fas fa-envelope"></i> Email</span>
                    <span><?php echo htmlspecialchars($view_enrollment['email']); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);"><i class="fab fa-whatsapp"></i> Phone</span>
                    <span><?php echo htmlspecialchars($view_enrollment['phone']); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);"><i class="fas fa-book"></i> Course</span>
                    <span><?php echo htmlspecialchars($view_enrollment['course_title'] ?? 'N/A'); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);"><i class="fas fa-dollar-sign"></i> Price</span>
                    <span style="color: var(--accent);"><?php echo format_price($view_enrollment['course_price'] ?? 0); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(255,255,255,0.02); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);"><i class="fas fa-calendar"></i> Applied</span>
                    <span><?php echo format_date($view_enrollment['created_at'], 'M d, Y H:i'); ?></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php if ($view_enrollment['status'] === 'pending'): ?>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Approve this enrollment?')">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <button type="submit" name="approve" value="<?php echo $view_enrollment['id']; ?>" class="btn btn-success">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Reject this enrollment?')">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <button type="submit" name="reject" value="<?php echo $view_enrollment['id']; ?>" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
            <?php endif; ?>
            <a href="<?php echo SITE_URL; ?>/admin/students<?php echo $status_filter ? '?status=' . $status_filter : ''; ?>" class="btn btn-secondary">Close</a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
