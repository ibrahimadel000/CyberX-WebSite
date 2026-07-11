<?php
/**
 * CyberX Admin - Solutions Management
 */
$page_title = 'Manage Solutions';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid security token');
        redirect(SITE_URL . '/admin/solutions');
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        // Process technologies array
        $technologies = array_filter(array_map('trim', explode(',', $_POST['technologies'] ?? '')));
        
        $data = [
            'title' => sanitize($_POST['title']),
            'short_description' => sanitize($_POST['short_description']),
            'description' => sanitize($_POST['description']),
            'icon' => sanitize($_POST['icon']),
            'technologies' => json_encode($technologies),
            'status' => isset($_POST['status']) ? 'active' : 'inactive'
        ];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['image'], 'solutions/');
            if ($upload['success']) {
                $data['image'] = $upload['filename'];
            }
        }
        
        if ($action === 'add') {
            $db->insert('software_solutions', $data);
            set_flash('success', 'Solution added successfully!');
        } else {
            $id = (int)$_POST['id'];
            $db->update('software_solutions', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Solution updated successfully!');
        }
        redirect(SITE_URL . '/admin/solutions');
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->delete('software_solutions', 'id = :id', ['id' => $id]);
    set_flash('success', 'Solution deleted!');
    redirect(SITE_URL . '/admin/solutions');
}

// Get solutions
$solutions = $db->fetchAll("SELECT * FROM software_solutions ORDER BY id ASC");

// Get solution for editing
$edit_solution = null;
if (isset($_GET['edit'])) {
    $edit_solution = $db->fetch("SELECT * FROM software_solutions WHERE id = :id", ['id' => (int)$_GET['edit']]);
    if ($edit_solution) {
        $edit_solution['technologies_str'] = implode(', ', json_decode($edit_solution['technologies'], true) ?? []);
    }
}

$icons = ['fa-user-secret', 'fa-clipboard-check', 'fa-code', 'fa-desktop', 'fa-fire-extinguisher', 'fa-graduation-cap', 'fa-shield-alt', 'fa-lock', 'fa-bug', 'fa-network-wired'];
?>

<div class="page-header-admin">
    <h1><i class="fas fa-shield-alt"></i> Manage Solutions</h1>
    <button class="btn btn-primary" onclick="openModal('solutionModal')">
        <i class="fas fa-plus"></i> Add New Solution
    </button>
</div>

<!-- Solutions Table -->
<div class="admin-card">
    <div class="admin-card-body" style="padding: 0;">
        <?php if (empty($solutions)): ?>
        <div class="empty-state">
            <i class="fas fa-tools"></i>
            <h3>No Solutions Yet</h3>
            <p>Start by adding your first solution.</p>
        </div>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Technologies</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solutions as $sol): ?>
                <tr>
                    <td>
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                            <i class="fas <?php echo htmlspecialchars($sol['icon']); ?>"></i>
                        </div>
                    </td>
                    <td><strong><?php echo htmlspecialchars($sol['title']); ?></strong></td>
                    <td style="max-width: 250px;"><?php echo htmlspecialchars(truncate($sol['short_description'] ?? $sol['description'], 60)); ?></td>
                    <td>
                        <?php 
                        $techs = json_decode($sol['technologies'], true) ?? [];
                        foreach (array_slice($techs, 0, 3) as $tech): 
                        ?>
                        <span class="badge badge-secondary" style="margin: 0.1rem;"><?php echo htmlspecialchars($tech); ?></span>
                        <?php endforeach; ?>
                        <?php if (count($techs) > 3): ?>
                        <span class="badge badge-secondary">+<?php echo count($techs) - 3; ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo status_badge($sol['status']); ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="?edit=<?php echo $sol['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $sol['id']; ?>" class="delete" title="Delete" onclick="return confirm('Delete this solution?')"><i class="fas fa-trash"></i></a>
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
<div class="modal-overlay" id="solutionModal" <?php echo $edit_solution ? 'style="opacity:1;visibility:visible;"' : ''; ?>>
    <div class="modal" <?php echo $edit_solution ? 'style="transform:scale(1);"' : ''; ?>>
        <div class="modal-header">
            <h3><?php echo $edit_solution ? 'Edit Solution' : 'Add New Solution'; ?></h3>
            <button class="modal-close" onclick="closeModal('solutionModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_solution ? 'edit' : 'add'; ?>">
                <?php if ($edit_solution): ?>
                <input type="hidden" name="id" value="<?php echo $edit_solution['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_solution['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Icon *</label>
                    <select name="icon" class="form-control" required>
                        <?php foreach ($icons as $icon): ?>
                        <option value="<?php echo $icon; ?>" <?php echo ($edit_solution['icon'] ?? '') === $icon ? 'selected' : ''; ?>>
                            <?php echo $icon; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_description" class="form-control" maxlength="500" value="<?php echo htmlspecialchars($edit_solution['short_description'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Full Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($edit_solution['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Technologies (comma separated)</label>
                    <input type="text" name="technologies" class="form-control" placeholder="Python, Kali Linux, Metasploit" value="<?php echo htmlspecialchars($edit_solution['technologies_str'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="remember-me">
                        <input type="checkbox" name="status" <?php echo ($edit_solution['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('solutionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_solution ? 'Update' : 'Add'; ?> Solution
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
