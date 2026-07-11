<?php
/**
 * CyberX - Test Email Configuration
 * Admin-only page to test SMTP settings
 */
$page_title = 'Test Email';
require_once __DIR__ . '/../../../includes/admin-header.php';
require_once __DIR__ . '/../../../includes/email.php';

$test_result = null;
$send_result = null;

// Test SMTP connection
if (isset($_POST['test_connection'])) {
    $test_result = test_email_connection();
}

// Send test email
if (isset($_POST['send_test'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $send_result = ['success' => false, 'message' => 'Invalid security token'];
    } else {
        $test_email = sanitize($_POST['test_email'] ?? '');
        if (!is_valid_email($test_email)) {
            $send_result = ['success' => false, 'message' => 'Please enter a valid email address'];
        } else {
            $body = '
                <h2 style="color: #00ff88; margin-top: 0;">Test Email from CyberX</h2>
                <p>This is a test email to verify your SMTP configuration is working correctly.</p>
                <p style="color: #888;">If you received this email, your email settings are configured properly!</p>
                <p style="font-size: 12px; color: #666;">Sent at: ' . date('Y-m-d H:i:s') . '</p>
            ';
            $send_result = send_email($test_email, 'Test Email - ' . SITE_NAME, $body);
        }
    }
}
?>

<div class="page-header-admin">
    <h1><i class="fas fa-envelope"></i> Email Configuration</h1>
    <span style="color: var(--text-muted);">Test your SMTP settings</span>
</div>

<!-- SMTP Status Card -->
<div class="admin-card" style="margin-bottom: 1.5rem;">
    <div class="admin-card-header">
        <h3><i class="fas fa-server"></i> SMTP Configuration Status</h3>
    </div>
    <div class="admin-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="config-item">
                <span class="config-label">SMTP Enabled:</span>
                <span class="config-value <?php echo SMTP_ENABLED ? 'text-success' : 'text-warning'; ?>">
                    <i class="fas <?php echo SMTP_ENABLED ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <?php echo SMTP_ENABLED ? 'Yes' : 'No'; ?>
                </span>
            </div>
            <div class="config-item">
                <span class="config-label">SMTP Host:</span>
                <span class="config-value"><?php echo htmlspecialchars(SMTP_HOST); ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">SMTP Port:</span>
                <span class="config-value"><?php echo SMTP_PORT; ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">Security:</span>
                <span class="config-value"><?php echo strtoupper(SMTP_SECURE); ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">From Email:</span>
                <span class="config-value"><?php echo htmlspecialchars(SMTP_FROM_EMAIL); ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">Username:</span>
                <span class="config-value"><?php echo htmlspecialchars(SMTP_USER); ?></span>
            </div>
        </div>
        
        <?php if (!SMTP_ENABLED): ?>
        <div class="alert alert-warning" style="margin-top: 1.5rem; background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107; padding: 1rem; border-radius: 8px;">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>SMTP Not Configured</strong>
            <p style="margin: 0.5rem 0 0;">To enable email sending, edit <code>config/config</code> and set <code>SMTP_ENABLED</code> to <code>true</code> with your Gmail credentials.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Configuration Instructions -->
<div class="admin-card" style="margin-bottom: 1.5rem;">
    <div class="admin-card-header">
        <h3><i class="fas fa-book"></i> Setup Instructions</h3>
    </div>
    <div class="admin-card-body">
        <ol style="line-height: 2; color: var(--text-muted);">
            <li>Open <code>config/config</code> in your editor</li>
            <li>Go to your <a href="https://myaccount.google.com/security" target="_blank" style="color: var(--accent);">Google Account Security</a></li>
            <li>Enable <strong>2-Step Verification</strong> (required for App Passwords)</li>
            <li>Search for "App Passwords" in Google Account settings</li>
            <li>Create a new App Password for "Mail"</li>
            <li>Copy the 16-character password to <code>SMTP_PASS</code> in config</li>
            <li>Update <code>SMTP_USER</code> and <code>SMTP_FROM_EMAIL</code> with your Gmail address</li>
            <li>Set <code>SMTP_ENABLED</code> to <code>true</code></li>
        </ol>
    </div>
</div>

<!-- Test Connection -->
<div class="admin-grid">
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fas fa-plug"></i> Test Connection</h3>
        </div>
        <div class="admin-card-body">
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Test if the SMTP server is reachable and authentication works.</p>
            
            <?php if ($test_result): ?>
            <div class="alert <?php echo $test_result['success'] ? 'alert-success' : 'alert-error'; ?>" style="margin-bottom: 1rem;">
                <i class="fas <?php echo $test_result['success'] ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                <?php echo htmlspecialchars($test_result['message']); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <button type="submit" name="test_connection" class="btn btn-secondary">
                    <i class="fas fa-plug"></i> Test SMTP Connection
                </button>
            </form>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fas fa-paper-plane"></i> Send Test Email</h3>
        </div>
        <div class="admin-card-body">
            <p style="color: var(--text-muted); margin-bottom: 1rem;">Send a test email to verify everything is working.</p>
            
            <?php if ($send_result): ?>
            <div class="alert <?php echo $send_result['success'] ? 'alert-success' : 'alert-error'; ?>" style="margin-bottom: 1rem;">
                <i class="fas <?php echo $send_result['success'] ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                <?php echo htmlspecialchars($send_result['message']); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <div class="form-group">
                    <input type="email" name="test_email" class="form-control" 
                           placeholder="Enter email address" required
                           value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>">
                </div>
                <button type="submit" name="send_test" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Test Email
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.config-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

.config-label {
    color: var(--text-muted);
}

.config-value {
    font-weight: 600;
}

.text-success {
    color: var(--accent);
}

.text-warning {
    color: #ffc107;
}

.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 8px;
}

.alert-success {
    background: rgba(0, 255, 136, 0.1);
    border: 1px solid rgba(0, 255, 136, 0.3);
    color: #00ff88;
}

.alert-error {
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid rgba(255, 68, 68, 0.3);
    color: #ff4444;
}

code {
    background: rgba(0, 212, 255, 0.1);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    color: var(--accent-secondary);
    font-family: 'Consolas', monospace;
}
</style>

<?php require_once __DIR__ . '/../../../includes/admin-footer.php'; ?>
