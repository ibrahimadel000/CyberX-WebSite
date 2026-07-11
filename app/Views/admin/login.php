<?php
/**
 * CyberX Admin Login
 */

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Redirect if already logged in
if (is_admin_logged_in()) {
    redirect(SITE_URL . '/admin/dashboard');
}

// Check for Remember Me cookie auto-login
if (!is_admin_logged_in() && verify_remember_token('admin')) {
    set_flash('success', 'Welcome back!');
    redirect(SITE_URL . '/admin/dashboard');
}

$errors = [];

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!is_valid_email($email)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }
    
    if (empty($errors)) {
        $admin = $db->fetch("SELECT * FROM admins WHERE email = :email", ['email' => $email]);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            
            // Create Remember Me token if checkbox was checked
            if ($remember) {
                create_remember_token('admin', $admin['id']);
            }
            
            set_flash('success', 'Welcome back, ' . $admin['name'] . '!');
            redirect(SITE_URL . '/admin/dashboard');
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | CyberX</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fonts.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/vendor/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <img src="<?php echo SITE_URL; ?>/assets/images/photo_2024-03-26_03-16-00-removebg.png" alt="CyberX Logo" class="login-logo">
                </div>
                <p>Admin Portal</p>
            </div>
            
            <?php if (!empty($errors) && !isset($errors['email']) && !isset($errors['password'])): ?>
            <div class="admin-alert admin-alert-error" style="margin: 0 0 1rem 0;">
                <?php echo implode('<br>', $errors); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                    <small style="color: var(--danger);"><?php echo $errors['email']; ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?> required>
                    <?php if (isset($errors['password'])): ?>
                    <small style="color: var(--danger);"><?php echo $errors['password']; ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember Me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="login-footer">
                <a href="<?php echo SITE_URL; ?>"><i class="fas fa-arrow-left"></i> Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
