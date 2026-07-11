<?php
/**
 * CyberX Admin - Courses Management
 */
$page_title = 'Manage Courses';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid security token');
        redirect(SITE_URL . '/admin/courses');
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $data = [
            'title' => sanitize($_POST['title']),
            'short_description' => sanitize($_POST['short_description']),
            'description' => sanitize($_POST['description']),
            'type' => in_array($_POST['type'], ['online', 'offline']) ? $_POST['type'] : 'online',
            'category' => sanitize($_POST['category']),
            'price' => (float)$_POST['price'],
            'duration' => sanitize($_POST['duration']),
            'instructor' => sanitize($_POST['instructor']),
            'status' => isset($_POST['status']) ? 'active' : 'inactive',
            'featured' => isset($_POST['featured']) ? 1 : 0
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'courses/');
            if ($upload['success']) {
                $data['image'] = $upload['filename'];
            }
        }
        
        if ($action === 'add') {
            $db->insert('courses', $data);
            set_flash('success', 'Course added successfully!');
        } else {
            $id = (int)$_POST['id'];
            $db->update('courses', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Course updated successfully!');
        }
        redirect(SITE_URL . '/admin/courses');
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->delete('courses', 'id = :id', ['id' => $id]);
    set_flash('success', 'Course deleted successfully!');
    redirect(SITE_URL . '/admin/courses');
}

// Handle status toggle
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $course = $db->fetch("SELECT status FROM courses WHERE id = :id", ['id' => $id]);
    if ($course) {
        $new_status = $course['status'] === 'active' ? 'inactive' : 'active';
        $db->update('courses', ['status' => $new_status], 'id = :id', ['id' => $id]);
        set_flash('success', 'Course status updated!');
    }
    redirect(SITE_URL . '/admin/courses');
}

// Get courses with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;
$total = $db->count('courses');
$total_pages = ceil($total / $per_page);

$courses = $db->fetchAll("SELECT * FROM courses ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");

// Get course for editing
$edit_course = null;
if (isset($_GET['edit'])) {
    $edit_course = $db->fetch("SELECT * FROM courses WHERE id = :id", ['id' => (int)$_GET['edit']]);
}

$categories = ['Ethical Hacking', 'Network Security', 'Cloud Security', 'SOC Analyst', 'DFIR', 'Malware Analysis', 'Web Security', 'Threat Intelligence'];
?>

<div class="page-header-admin">
    <h1><i class="fas fa-book"></i> Manage Courses</h1>
    <button class="btn btn-primary" onclick="openModal('courseModal')">
        <i class="fas fa-plus"></i> Add New Course
    </button>
</div>

<!-- Courses Table -->
<div class="admin-card">
    <div class="admin-card-body" style="padding: 0;">
        <?php if (empty($courses)): ?>
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>No Courses Yet</h3>
            <p>Start by adding your first course.</p>
        </div>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td>
                        <img src="<?php echo $course['image'] ? upload_url('courses/' . $course['image']) : asset_url('images/course-default.jpg'); ?>" alt="" class="table-image">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                        <?php if ($course['featured']): ?>
                        <span class="badge badge-warning" style="margin-left: 0.5rem; font-size: 0.65rem;">Featured</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo type_badge($course['type']); ?></td>
                    <td><?php echo htmlspecialchars($course['category']); ?></td>
                    <td><?php echo format_price($course['price']); ?></td>
                    <td><?php echo number_format($course['students_enrolled']); ?></td>
                    <td>
                        <a href="?toggle=<?php echo $course['id']; ?>" class="toggle-switch" title="Toggle status">
                            <input type="checkbox" <?php echo $course['status'] === 'active' ? 'checked' : ''; ?> disabled>
                            <span class="toggle-slider"></span>
                        </a>
                    </td>
                    <td>
                        <div class="table-actions">
                            <?php if ($course['type'] === 'offline'): ?>
                            <a href="lessons?course=<?php echo $course['id']; ?>" class="view" title="Manage Lessons" style="color: var(--accent);"><i class="fas fa-video"></i></a>
                            <?php endif; ?>
                            <a href="?edit=<?php echo $course['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $course['id']; ?>" class="delete" title="Delete" onclick="return confirm('Are you sure you want to delete this course?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active" style="background:var(--accent);color:var(--primary-dark);"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="courseModal" <?php echo $edit_course ? 'style="opacity:1;visibility:visible;"' : ''; ?>>
    <div class="modal" <?php echo $edit_course ? 'style="transform:scale(1);"' : ''; ?>>
        <div class="modal-header">
            <h3><?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?></h3>
            <button class="modal-close" onclick="closeModal('courseModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_course ? 'edit' : 'add'; ?>">
                <?php if ($edit_course): ?>
                <input type="hidden" name="id" value="<?php echo $edit_course['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Course Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_course['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="online" <?php echo ($edit_course['type'] ?? '') === 'online' ? 'selected' : ''; ?>>Online</option>
                            <option value="offline" <?php echo ($edit_course['type'] ?? '') === 'offline' ? 'selected' : ''; ?>>Offline</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" class="form-control" required>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo ($edit_course['category'] ?? '') === $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price ($) *</label>
                        <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $edit_course['price'] ?? '0'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" class="form-control" placeholder="e.g. 40 Hours" value="<?php echo htmlspecialchars($edit_course['duration'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Instructor</label>
                    <input type="text" name="instructor" class="form-control" value="<?php echo htmlspecialchars($edit_course['instructor'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_description" class="form-control" maxlength="500" value="<?php echo htmlspecialchars($edit_course['short_description'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Full Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($edit_course['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Course Image</label>
                    <div class="file-upload">
                        <input type="file" name="image" accept="image/*">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload image</p>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="remember-me">
                            <input type="checkbox" name="status" <?php echo ($edit_course['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
                            <span>Active</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="remember-me">
                            <input type="checkbox" name="featured" <?php echo !empty($edit_course['featured']) ? 'checked' : ''; ?>>
                            <span>Featured</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('courseModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_course ? 'Update' : 'Add'; ?> Course
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
