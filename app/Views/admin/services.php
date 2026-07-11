<?php
/**
 * CyberX Admin - Services Management
 * CRUD interface for managing the 4 service categories
 */
$page_title = 'Manage Services';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid security token');
        redirect(SITE_URL . '/admin/services');
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        // Process features JSON
        $features = [];
        if (!empty($_POST['feature_icons'])) {
            foreach ($_POST['feature_icons'] as $i => $icon) {
                if (!empty($_POST['feature_titles'][$i])) {
                    $features[] = [
                        'icon' => sanitize($icon),
                        'title' => sanitize($_POST['feature_titles'][$i]),
                        'desc' => sanitize($_POST['feature_descs'][$i] ?? '')
                    ];
                }
            }
        }
        
        // Process technologies array
        $technologies = array_filter(array_map('trim', explode(',', $_POST['technologies'] ?? '')));
        
        $data = [
            'category_key' => sanitize($_POST['category_key']),
            'title' => sanitize($_POST['title']),
            'subtitle' => sanitize($_POST['subtitle']),
            'icon' => sanitize($_POST['icon']),
            'gradient' => sanitize($_POST['gradient']),
            'color' => sanitize($_POST['color']),
            'description' => sanitize($_POST['description']),
            'features' => json_encode($features),
            'technologies' => json_encode($technologies),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status' => isset($_POST['status']) ? 'active' : 'inactive'
        ];
        
        if ($action === 'add') {
            $db->insert('services', $data);
            set_flash('success', 'Service added successfully!');
        } else {
            $id = (int)$_POST['id'];
            $db->update('services', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Service updated successfully!');
        }
        redirect(SITE_URL . '/admin/services');
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->delete('services', 'id = :id', ['id' => $id]);
    set_flash('success', 'Service deleted!');
    redirect(SITE_URL . '/admin/services');
}

// Get services
$services = $db->fetchAll("SELECT * FROM services ORDER BY sort_order ASC, id ASC");

// Get service for editing
$edit_service = null;
if (isset($_GET['edit'])) {
    $edit_service = $db->fetch("SELECT * FROM services WHERE id = :id", ['id' => (int)$_GET['edit']]);
    if ($edit_service) {
        $edit_service['features_arr'] = json_decode($edit_service['features'], true) ?? [];
        $edit_service['technologies_str'] = implode(', ', json_decode($edit_service['technologies'], true) ?? []);
    }
}

// Available icons for dropdown
$icons = [
    'fa-code', 'fa-palette', 'fa-graduation-cap', 'fa-file-alt',
    'fa-laptop-code', 'fa-mobile-alt', 'fa-shopping-cart', 'fa-globe',
    'fa-cogs', 'fa-database', 'fa-pen-nib', 'fa-video',
    'fa-chart-line', 'fa-shield-alt', 'fa-users', 'fa-rocket'
];

// Available gradient presets
$gradients = [
    'from-neon-blue to-neon-cyan' => 'Blue → Cyan',
    'from-neon-purple to-pink-500' => 'Purple → Pink',
    'from-green-500 to-emerald-500' => 'Green → Emerald',
    'from-orange-500 to-amber-500' => 'Orange → Amber',
    'from-red-500 to-rose-500' => 'Red → Rose',
    'from-indigo-500 to-purple-500' => 'Indigo → Purple'
];

// Available color presets
$colors = ['neon-blue', 'neon-purple', 'green-400', 'orange-400', 'red-400', 'indigo-400'];
?>

<div class="page-header-admin">
    <h1><i class="fas fa-layer-group"></i> Manage Services</h1>
    <button class="btn btn-primary" onclick="openModal('serviceModal')">
        <i class="fas fa-plus"></i> Add New Service
    </button>
</div>

<!-- Services Table -->
<div class="admin-card">
    <div class="admin-card-body" style="padding: 0;">
        <?php if (empty($services)): ?>
        <div class="empty-state">
            <i class="fas fa-layer-group"></i>
            <h3>No Services Yet</h3>
            <p>Start by adding your first service category.</p>
        </div>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Category Key</th>
                    <th>Features</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                <tr>
                    <td>
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                            <i class="fas <?php echo htmlspecialchars($service['icon']); ?>"></i>
                        </div>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($service['title']); ?></strong>
                        <br><small class="text-muted"><?php echo htmlspecialchars($service['subtitle']); ?></small>
                    </td>
                    <td><code><?php echo htmlspecialchars($service['category_key']); ?></code></td>
                    <td>
                        <?php 
                        $features = json_decode($service['features'], true) ?? [];
                        echo count($features) . ' features';
                        ?>
                    </td>
                    <td><?php echo $service['sort_order']; ?></td>
                    <td><?php echo status_badge($service['status']); ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="?edit=<?php echo $service['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $service['id']; ?>" class="delete" title="Delete" onclick="return confirm('Delete this service?')"><i class="fas fa-trash"></i></a>
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
<div class="modal-overlay" id="serviceModal" <?php echo $edit_service ? 'style="opacity:1;visibility:visible;"' : ''; ?>>
    <div class="modal" <?php echo $edit_service ? 'style="transform:scale(1);"' : ''; ?> style="max-width: 700px;">
        <div class="modal-header">
            <h3><?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?></h3>
            <button class="modal-close" onclick="closeModal('serviceModal')"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST">
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <input type="hidden" name="action" value="<?php echo $edit_service ? 'edit' : 'add'; ?>">
                <?php if ($edit_service): ?>
                <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_service['title'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Category Key *</label>
                        <input type="text" name="category_key" class="form-control" value="<?php echo htmlspecialchars($edit_service['category_key'] ?? ''); ?>" required placeholder="e.g. development">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($edit_service['subtitle'] ?? ''); ?>" placeholder="e.g. Web & Software Development">
                </div>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Icon *</label>
                        <select name="icon" class="form-control" required style="background-color: #0A1628;">
                            <?php foreach ($icons as $icon): ?>
                            <option value="<?php echo $icon; ?>" <?php echo ($edit_service['icon'] ?? '') === $icon ? 'selected' : ''; ?> style="background-color: #0A1628; color: #FFFFFF;">
                                <?php echo $icon; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gradient</label>
                        <select name="gradient" class="form-control" style="background-color: #0A1628;">
                            <?php foreach ($gradients as $val => $label): ?>
                            <option value="<?php echo $val; ?>" <?php echo ($edit_service['gradient'] ?? '') === $val ? 'selected' : ''; ?> style="background-color: #0A1628; color: #FFFFFF;">
                                <?php echo $label; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <select name="color" class="form-control" style="background-color: #0A1628;">
                            <?php foreach ($colors as $color): ?>
                            <option value="<?php echo $color; ?>" <?php echo ($edit_service['color'] ?? '') === $color ? 'selected' : ''; ?> style="background-color: #0A1628; color: #FFFFFF;">
                                <?php echo $color; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($edit_service['description'] ?? ''); ?></textarea>
                </div>
                
                <!-- Features Section -->
                <div class="form-group">
                    <label>Features</label>
                    <div id="featuresContainer">
                        <?php 
                        $features_arr = $edit_service['features_arr'] ?? [];
                        if (empty($features_arr)) $features_arr = [['icon' => '', 'title' => '', 'desc' => '']];
                        foreach ($features_arr as $i => $feature): 
                        ?>
                        <div class="feature-row" style="display: grid; grid-template-columns: 120px 1fr 2fr 40px; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center;">
                            <select name="feature_icons[]" class="form-control" style="padding: 0.5rem; background-color: #0A1628;">
                                <option value="" style="background-color: #0A1628; color: #FFFFFF;">Icon</option>
                                <?php foreach ($icons as $icon): ?>
                                <option value="<?php echo $icon; ?>" <?php echo ($feature['icon'] ?? '') === $icon ? 'selected' : ''; ?> style="background-color: #0A1628; color: #FFFFFF;"><?php echo str_replace('fa-', '', $icon); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="feature_titles[]" class="form-control" placeholder="Feature title" value="<?php echo htmlspecialchars($feature['title'] ?? ''); ?>">
                            <input type="text" name="feature_descs[]" class="form-control" placeholder="Description" value="<?php echo htmlspecialchars($feature['desc'] ?? ''); ?>">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="padding: 0.5rem;"><i class="fas fa-times"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addFeatureRow()">
                        <i class="fas fa-plus"></i> Add Feature
                    </button>
                </div>
                
                <div class="form-group">
                    <label>Technologies (comma separated)</label>
                    <input type="text" name="technologies" class="form-control" placeholder="React, Node.js, PHP" value="<?php echo htmlspecialchars($edit_service['technologies_str'] ?? ''); ?>">
                </div>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo $edit_service['sort_order'] ?? 0; ?>" min="0">
                    </div>
                    <div class="form-group" style="display: flex; align-items: end;">
                        <label class="remember-me" style="margin-bottom: 0;">
                            <input type="checkbox" name="status" <?php echo ($edit_service['status'] ?? 'active') === 'active' ? 'checked' : ''; ?>>
                            <span>Active</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('serviceModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_service ? 'Update' : 'Add'; ?> Service
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addFeatureRow() {
    const container = document.getElementById('featuresContainer');
    const icons = <?php echo json_encode($icons); ?>;
    let options = '<option value="" style="background-color: #0A1628; color: #FFFFFF;">Icon</option>';
    icons.forEach(icon => {
        options += `<option value="${icon}" style="background-color: #0A1628; color: #FFFFFF;">${icon.replace('fa-', '')}</option>`;
    });
    
    const row = document.createElement('div');
    row.className = 'feature-row';
    row.style.cssText = 'display: grid; grid-template-columns: 120px 1fr 2fr 40px; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center;';
    row.innerHTML = `
        <select name="feature_icons[]" class="form-control" style="padding: 0.5rem; background-color: #0A1628;">${options}</select>
        <input type="text" name="feature_titles[]" class="form-control" placeholder="Feature title">
        <input type="text" name="feature_descs[]" class="form-control" placeholder="Description">
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()" style="padding: 0.5rem;"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(row);
}
</script>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
