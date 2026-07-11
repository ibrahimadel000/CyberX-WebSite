<?php
/**
 * CyberX Admin - Messages Management
 */
$page_title = 'Messages';
require_once __DIR__ . '/../../../includes/admin-header.php';

// Handle mark as read
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $db->update('messages', ['is_read' => 1], 'id = :id', ['id' => $id]);
    redirect(SITE_URL . '/admin/messages?view=' . $id);
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->delete('messages', 'id = :id', ['id' => $id]);
    set_flash('success', 'Message deleted!');
    redirect(SITE_URL . '/admin/messages');
}

// Mark all as read
if (isset($_GET['mark_all_read'])) {
    $db->update('messages', ['is_read' => 1], '1');
    set_flash('success', 'All messages marked as read!');
    redirect(SITE_URL . '/admin/messages');
}

// Get messages with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;
$total = $db->count('messages');
$total_pages = ceil($total / $per_page);

$messages = $db->fetchAll(
    "SELECT m.*, s.title as solution_title 
     FROM messages m 
     LEFT JOIN software_solutions s ON m.solution_id = s.id 
     ORDER BY m.is_read ASC, m.created_at DESC 
     LIMIT $per_page OFFSET $offset"
);

$unread_count = $db->count('messages', 'is_read = 0');

// View single message
$view_message = null;
if (isset($_GET['view'])) {
    $view_message = $db->fetch(
        "SELECT m.*, s.title as solution_title 
         FROM messages m 
         LEFT JOIN software_solutions s ON m.solution_id = s.id 
         WHERE m.id = :id",
        ['id' => (int)$_GET['view']]
    );
    
    // Mark as read
    if ($view_message && !$view_message['is_read']) {
        $db->update('messages', ['is_read' => 1], 'id = :id', ['id' => $view_message['id']]);
        $view_message['is_read'] = 1;
    }
}
?>

<div class="page-header-admin">
    <h1>
        <i class="fas fa-envelope"></i> Messages
        <?php if ($unread_count > 0): ?>
        <span class="badge badge-danger"><?php echo $unread_count; ?> unread</span>
        <?php endif; ?>
    </h1>
    <?php if ($unread_count > 0): ?>
    <a href="?mark_all_read=1" class="btn btn-secondary">
        <i class="fas fa-check-double"></i> Mark All as Read
    </a>
    <?php endif; ?>
</div>

<div class="admin-grid">
    <!-- Messages List -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Inbox</h3>
            <span style="color: var(--text-muted);"><?php echo $total; ?> messages</span>
        </div>
        <div class="admin-card-body" style="padding: 0;">
            <?php if (empty($messages)): ?>
            <div class="empty-state">
                <i class="fas fa-envelope-open"></i>
                <h3>No Messages</h3>
                <p>Messages from visitors will appear here.</p>
            </div>
            <?php else: ?>
            <div class="message-list">
                <?php foreach ($messages as $msg): ?>
                <a href="?view=<?php echo $msg['id']; ?>" class="message-item <?php echo !$msg['is_read'] ? 'unread' : ''; ?> <?php echo (isset($_GET['view']) && (int)$_GET['view'] === $msg['id']) ? 'active' : ''; ?>" style="<?php echo (isset($_GET['view']) && (int)$_GET['view'] === $msg['id']) ? 'background: rgba(0,212,255,0.1);' : ''; ?>">
                    <div class="message-avatar">
                        <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <h4><?php echo htmlspecialchars($msg['name']); ?></h4>
                            <span><?php echo time_ago($msg['created_at']); ?></span>
                        </div>
                        <p class="message-preview"><?php echo htmlspecialchars(truncate($msg['message'], 50)); ?></p>
                        <?php if ($msg['solution_title']): ?>
                        <span class="badge badge-secondary" style="font-size: 0.65rem; margin-top: 0.25rem;">
                            <i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars(truncate($msg['solution_title'], 20)); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            
            <?php if ($total_pages > 1): ?>
            <div class="pagination" style="padding: 1rem;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" <?php echo $i === $page ? 'style="background:var(--accent);color:var(--primary-dark);"' : ''; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Message Detail -->
    <div class="admin-card">
        <?php if ($view_message): ?>
        <div class="admin-card-header">
            <h3>Message Details</h3>
            <div class="table-actions">
                <a href="mailto:<?php echo htmlspecialchars($view_message['email']); ?>" class="view" title="Reply via Email"><i class="fas fa-reply"></i></a>
                <a href="?delete=<?php echo $view_message['id']; ?>" class="delete" title="Delete" onclick="return confirm('Delete this message?')"><i class="fas fa-trash"></i></a>
            </div>
        </div>
        <div class="admin-card-body">
            <!-- Sender Info -->
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="message-avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <?php echo strtoupper(substr($view_message['name'], 0, 1)); ?>
                </div>
                <div>
                    <h3 style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($view_message['name']); ?></h3>
                    <p style="color: var(--text-muted); margin: 0;">
                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($view_message['email']); ?>
                        <?php if ($view_message['phone']): ?>
                        <br><i class="fas fa-phone"></i> <?php echo htmlspecialchars($view_message['phone']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <?php if ($view_message['solution_title']): ?>
            <div style="margin-bottom: 1rem; padding: 0.75rem; background: rgba(0,212,255,0.1); border-radius: var(--radius-sm);">
                <small style="color: var(--text-muted);">Regarding Solution:</small>
                <p style="margin: 0.25rem 0 0;"><i class="fas fa-shield-alt" style="color: var(--accent);"></i> <?php echo htmlspecialchars($view_message['solution_title']); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Message -->
            <div style="background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                <p style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($view_message['message']); ?></p>
            </div>
            
            <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.85rem;">
                <span><i class="fas fa-calendar"></i> <?php echo format_date($view_message['created_at'], 'M d, Y \a\t H:i'); ?></span>
                <span><?php echo $view_message['is_read'] ? '<i class="fas fa-check-double"></i> Read' : '<i class="fas fa-circle"></i> Unread'; ?></span>
            </div>
            
            <!-- Quick Actions -->
            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                <a href="mailto:<?php echo htmlspecialchars($view_message['email']); ?>?subject=Re: CyberX Inquiry" class="btn btn-primary">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                <?php if ($view_message['phone']): ?>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $view_message['phone']); ?>" target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="admin-card-body">
            <div class="empty-state">
                <i class="fas fa-hand-pointer"></i>
                <h3>Select a Message</h3>
                <p>Click on a message to view its details.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
