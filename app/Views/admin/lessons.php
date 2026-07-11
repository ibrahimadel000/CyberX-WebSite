<?php
/**
 * CyberX Admin - Lessons Management for Offline Courses
 */
$page_title = 'Manage Lessons';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Get course ID
$course_id = isset($_GET['course']) ? (int)$_GET['course'] : 0;

// Verify course exists and is offline type
$course = $db->fetch("SELECT * FROM courses WHERE id = :id AND type = 'offline'", ['id' => $course_id]);
if (!$course) {
    set_flash('error', 'Invalid course or course is not offline type');
    redirect(SITE_URL . '/admin/courses');
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid security token');
        redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $data = [
            'course_id' => $course_id,
            'title' => sanitize($_POST['title']),
            'description' => sanitize($_POST['description']),
            'duration' => sanitize($_POST['duration']),
            'status' => isset($_POST['status']) ? 'active' : 'inactive',
            'sort_order' => (int)$_POST['sort_order']
        ];
        
        // Handle video upload
        if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_video($_FILES['video'], 'videos/');
            if ($upload['success']) {
                $data['video_file'] = $upload['filename'];
            } else {
                set_flash('error', $upload['error']);
                redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
            }
        }
        
        if ($action === 'add') {
            if (empty($data['video_file'])) {
                set_flash('error', 'Video file is required');
                redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
            }
            $db->insert('course_lessons', $data);
            set_flash('success', 'Lesson added successfully!');
        } else {
            $id = (int)$_POST['id'];
            // If no new video uploaded, don't update video_file
            if (empty($data['video_file'])) {
                unset($data['video_file']);
            } else {
                // Delete old video
                $old_lesson = $db->fetch("SELECT video_file FROM course_lessons WHERE id = :id", ['id' => $id]);
                if ($old_lesson && $old_lesson['video_file']) {
                    delete_file('videos/' . $old_lesson['video_file']);
                }
            }
            unset($data['course_id']); // Don't update course_id
            $db->update('course_lessons', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Lesson updated successfully!');
        }
        redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $lesson = $db->fetch("SELECT video_file FROM course_lessons WHERE id = :id AND course_id = :course_id", ['id' => $id, 'course_id' => $course_id]);
    if ($lesson) {
        // Delete video file
        if ($lesson['video_file']) {
            delete_file('videos/' . $lesson['video_file']);
        }
        $db->delete('course_lessons', 'id = :id', ['id' => $id]);
        set_flash('success', 'Lesson deleted successfully!');
    }
    redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
}

// Handle status toggle
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $lesson = $db->fetch("SELECT status FROM course_lessons WHERE id = :id AND course_id = :course_id", ['id' => $id, 'course_id' => $course_id]);
    if ($lesson) {
        $new_status = $lesson['status'] === 'active' ? 'inactive' : 'active';
        $db->update('course_lessons', ['status' => $new_status], 'id = :id', ['id' => $id]);
        set_flash('success', 'Lesson status updated!');
    }
    redirect(SITE_URL . '/admin/lessons?course=' . $course_id);
}

// Get lessons
$lessons = $db->fetchAll("SELECT * FROM course_lessons WHERE course_id = :course_id ORDER BY sort_order ASC, created_at ASC", ['course_id' => $course_id]);

// Get lesson for editing
$edit_lesson = null;
if (isset($_GET['edit'])) {
    $edit_lesson = $db->fetch("SELECT * FROM course_lessons WHERE id = :id AND course_id = :course_id", ['id' => (int)$_GET['edit'], 'course_id' => $course_id]);
}

// Get next sort order
$next_order = $db->fetch("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM course_lessons WHERE course_id = :course_id", ['course_id' => $course_id]);
$next_sort_order = $next_order['next_order'];
?>

<div class="page-header-admin">
    <div>
        <a href="<?php echo SITE_URL; ?>/admin/courses" class="btn btn-secondary" style="margin-right: 1rem;">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
        <h1 style="display: inline-block; vertical-align: middle;"><i class="fas fa-video"></i> Lessons: <?php echo htmlspecialchars($course['title']); ?></h1>
    </div>
    <button class="btn btn-primary" onclick="openModal('lessonModal')">
        <i class="fas fa-plus"></i> Add New Lesson
    </button>
</div>

<!-- Course Info Card -->
<div class="admin-card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
    <div class="admin-card-body" style="display: flex; align-items: center; gap: 1.5rem;">
        <img src="<?php echo $course['image'] ? upload_url('courses/' . $course['image']) : asset_url('images/course-default.jpg'); ?>" alt="" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
        <div>
            <h3 style="margin: 0; color: var(--accent);"><?php echo htmlspecialchars($course['title']); ?></h3>
            <p style="margin: 0.5rem 0 0; opacity: 0.8; color: #fff;"><?php echo htmlspecialchars($course['short_description'] ?? ''); ?></p>
        </div>
        <div style="margin-left: auto; text-align: right;">
            <div style="font-size: 2rem; font-weight: 700; color: var(--accent);"><?php echo count($lessons); ?></div>
            <div style="opacity: 0.8; color: #fff;">Lessons</div>
        </div>
    </div>
</div>

<!-- Lessons Table -->
<div class="admin-card">
    <div class="admin-card-body" style="padding: 0;">
        <?php if (empty($lessons)): ?>
        <div class="empty-state">
            <i class="fas fa-video"></i>
            <h3>No Lessons Yet</h3>
            <p>Start by adding your first video lesson.</p>
        </div>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Order</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lessons as $lesson): ?>
                <tr>
                    <td>
                        <span class="badge badge-secondary"><?php echo $lesson['sort_order']; ?></span>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
                        <?php if ($lesson['description']): ?>
                        <p style="margin: 0.25rem 0 0; opacity: 0.7; font-size: 0.85rem;"><?php echo truncate(htmlspecialchars($lesson['description']), 80); ?></p>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($lesson['duration'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="<?php echo upload_url('videos/' . $lesson['video_file']); ?>" target="_blank" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                            <i class="fas fa-play"></i> Preview
                        </a>
                    </td>
                    <td>
                        <a href="?course=<?php echo $course_id; ?>&toggle=<?php echo $lesson['id']; ?>" class="toggle-switch" title="Toggle status">
                            <input type="checkbox" <?php echo $lesson['status'] === 'active' ? 'checked' : ''; ?> disabled>
                            <span class="toggle-slider"></span>
                        </a>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="?course=<?php echo $course_id; ?>&edit=<?php echo $lesson['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="?course=<?php echo $course_id; ?>&delete=<?php echo $lesson['id']; ?>" class="delete" title="Delete" onclick="return confirm('Delete this lesson and its video file?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="lessonModal" <?php echo $edit_lesson ? 'style="opacity:1;visibility:visible;"' : ''; ?>>
    <div class="modal" <?php echo $edit_lesson ? 'style="transform:scale(1);"' : ''; ?>>
        <div class="modal-header">
            <h3><?php echo $edit_lesson ? 'Edit Lesson' : 'Add New Lesson'; ?></h3>
            <button class="modal-close" onclick="closeModal('lessonModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_lesson ? 'edit' : 'add'; ?>">
                <?php if ($edit_lesson): ?>
                <input type="hidden" name="id" value="<?php echo $edit_lesson['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Lesson Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_lesson['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" class="form-control" placeholder="e.g. 15:30" value="<?php echo htmlspecialchars($edit_lesson['duration'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo $edit_lesson['sort_order'] ?? $next_sort_order; ?>" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($edit_lesson['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Video File <?php echo $edit_lesson ? '' : '*'; ?></label>
                    <?php if ($edit_lesson && $edit_lesson['video_file']): ?>
                    <p style="margin-bottom: 0.5rem; font-size: 0.85rem; opacity: 0.7;">
                        <i class="fas fa-video"></i> Current: <?php echo htmlspecialchars($edit_lesson['video_file']); ?>
                    </p>
                    <?php endif; ?>
                    <div class="file-upload">
                        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg" <?php echo $edit_lesson ? '' : 'required'; ?>>
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><?php echo $edit_lesson ? 'Upload new video to replace' : 'Click to upload video (MP4, WebM, OGG - Max 300MB)'; ?></p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="remember-me">
                        <input type="checkbox" name="status" <?php echo ($edit_lesson['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('lessonModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_lesson ? 'Update' : 'Add'; ?> Lesson
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
