<?php
/**
 * CyberX Contact Page
 * Professional Antigravity Theme
 */
require_once __DIR__ . '/../layouts/header.php';

// Get solution if specified
$solution_id = isset($_GET['solution']) ? (int)$_GET['solution'] : 0;
$service = isset($_GET['service']) ? sanitize($_GET['service']) : '';
$package = isset($_GET['package']) ? sanitize($_GET['package']) : '';
$features = isset($_GET['features']) ? sanitize($_GET['features']) : '';

$message = '';
if (!empty($features)) {
    $message = "Hello CyberX Team,\n\nI am interested in obtaining a quote/consultation for the following services:\n- " . str_replace(',', "\n- ", $features) . "\n\nPlease contact me with more details.";
}

$solution = null;
if ($solution_id) {
    $solution = $db->fetch("SELECT * FROM software_solutions WHERE id = :id AND status = 'active'", ['id' => $solution_id]);
}

// Fetch all active services for multi-select
$all_services = $db->fetchAll("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC");

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token. Please refresh and try again.';
    } else {
        // Get and sanitize inputs
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $message = sanitize($_POST['message'] ?? '');
        $selected_solution = (int)($_POST['solution_id'] ?? 0);
        $selected_services = isset($_POST['selected_services']) ? array_map('sanitize', $_POST['selected_services']) : [];
        
        // Validate
        if (empty($name)) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        // Email is optional, but if entered, ensure validity
        if (!empty($email) && !is_valid_email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Phone is required
        if (empty($phone)) {
            $errors['phone'] = 'Phone / WhatsApp is required';
        } elseif (!is_valid_phone($phone)) {
            $errors['phone'] = 'Please enter a valid phone number';
        }
        
        if (empty($message)) {
            $errors['message'] = 'Message is required';
        } elseif (strlen($message) < 10) {
            $errors['message'] = 'Message must be at least 10 characters';
        }
        
        // Insert message
        if (empty($errors)) {
            try {
                // Format selected services for the message
                $services_text = '';
                if (!empty($selected_services)) {
                    $service_names = [
                        'development' => 'Web & Software Development',
                        'design' => 'Creative Design & Branding',
                        'academic' => 'Academic Support & Research',
                        'documents' => 'Business & Career Documents'
                    ];
                    $selected_names = array_map(function($key) use ($service_names) {
                        return $service_names[$key] ?? ucfirst($key);
                    }, $selected_services);
                    $services_text = "[Services Interested: " . implode(', ', $selected_names) . "]\n\n";
                }
                
                $db->insert('messages', [
                    'name' => $name,
                    'email' => $email ?: null,
                    'phone' => $phone,
                    'message' => $services_text . $message,
                    'solution_id' => $selected_solution ?: null
                ]);
                
                $success = true;
                set_flash('success', 'Message sent successfully! We will get back to you soon.');
                
                // Clear form
                $name = $email = $phone = $message = '';
                
            } catch (Exception $e) {
                $errors[] = 'An error occurred. Please try again.';
            }
        }
    }
}

$page_title = 'Contact Us';

// Get page title based on context
$header_title = 'Get In Touch';
$header_subtitle = 'We\'d love to hear from you';

if ($solution) {
    $header_subtitle = 'Request: ' . htmlspecialchars($solution['title']);
} elseif ($service) {
    $service_names = [
        'development' => 'Web Development',
        'design' => 'Brand Design',
        'academic' => 'Academic Support',
        'documents' => 'Business Documents'
    ];
    $header_subtitle = 'Inquiry: ' . ($service_names[$service] ?? ucfirst($service));
} elseif ($package) {
    $package_names = [
        'startup' => 'The Startup Pack',
        'graduate' => 'The Graduate Hero Pack'
    ];
    $header_subtitle = 'Package: ' . ($package_names[$package] ?? ucfirst($package));
}
?>

<!-- Additional required fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* Minimalist Contact Page Styles */
body { background-color: #000000; }



.c-heading {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.02em;
    color: #ffffff;
    margin-bottom: 24px;
}

.c-subtext {
    font-family: 'Inter', sans-serif;
    font-size: 1.1rem;
    line-height: 1.6;
    color: #888;
    margin-bottom: 48px;
    max-width: 480px;
}

.c-info-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #555;
    margin-bottom: 8px;
}

.c-info-value {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
    display: inline-block;
}
.c-info-value:hover { color: #00d4ff; }

.c-social-btn {
    width: 44px;
    height: 44px;
    border: 1px solid #1a1a1a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888;
    background: #000;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 1rem;
}
.c-social-btn:hover {
    border-color: #00d4ff;
    color: #00d4ff;
    background: rgba(0,212,255,0.05);
    transform: translateY(-2px);
}

.c-form-wrap {
    background: #050505;
    border: 1px solid rgba(0, 212, 255, 0.35); /* Little neon blue border */
    box-shadow: 0 0 25px rgba(0, 212, 255, 0.08); /* Little neon glow */
    border-radius: 24px;
    padding: 32px;
    position: relative;
    overflow: hidden;
}
@media (min-width: 768px) {
    .c-form-wrap { padding: 48px; }
}

.c-form-wrap::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,212,255,0.04) 0%, transparent 70%);
    pointer-events: none;
}

.c-input-group {
    margin-bottom: 24px;
}
.c-input-label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 8px;
}
.c-input {
    width: 100%;
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    border-radius: 12px;
    padding: 16px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    color: #fff;
    transition: all 0.3s;
    outline: none;
}
.c-input::placeholder { color: #444; }
.c-input:focus {
    border-color: #00d4ff;
    box-shadow: 0 0 0 2px rgba(0,212,255,0.1);
    background: #000;
}
.c-input.error { border-color: #ff3366; }

.c-textarea {
    resize: vertical;
    min-height: 140px;
}

.c-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    background: #fff;
    color: #000;
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    padding: 18px 32px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.c-btn-submit:hover {
    background: #00d4ff;
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,212,255,0.2);
}

/* Service Options */
.service-pill-label {
    cursor: pointer;
    display: inline-block;
    user-select: none;
}
.service-pill-input {
    display: none;
}
.service-pill-content {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    padding: 10px 20px;
    border-radius: 999px;
    font-family: 'Inter', sans-serif;
    font-size: 0.9rem;
    color: #888;
    transition: all 0.3s;
}
.service-pill-label:hover .service-pill-content {
    border-color: rgba(0,212,255,0.3);
}
.service-pill-input:checked + .service-pill-content {
    background: rgba(0,212,255,0.08);
    border-color: #00d4ff;
    color: #fff;
}
.service-pill-content i {
    font-size: 0.8rem;
}
.service-pill-input:checked + .service-pill-content i {
    color: #00d4ff;
}

.c-form-error {
    color: #ff3366;
    font-size: 0.85rem;
    margin-top: 6px;
    font-family: 'Inter', sans-serif;
}
.badges-container {
    margin-bottom: 32px;
}
.c-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    border-radius: 12px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
}
.c-badge.solution-badge { background: rgba(0,212,255,0.05); border-color: rgba(0,212,255,0.2); }
.c-badge.package-badge { background: rgba(187,134,252,0.05); border-color: rgba(187,134,252,0.2); }

.c-badge-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
}
.solution-badge .c-badge-icon { background: rgba(0,212,255,0.1); color: #00d4ff; }
.package-badge .c-badge-icon { background: rgba(187,134,252,0.1); color: #bb86fc; }
</style>

<section class="min-h-screen pt-24 pb-24 lg:pt-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <!-- Header Text -->
        <div class="text-center py-16 lg:py-20 mb-8">
            <h1 class="c-heading text-center mx-auto">
                <?php echo t('contact.heading_prefix'); ?> <br class="sm:hidden"><span style="color: #00d4ff;"><?php echo t('contact.heading_accent'); ?></span>
            </h1>
            
            <p class="c-subtext mx-auto text-center" style="margin-bottom: 0;">
                <?php echo t('contact.sub'); ?>
            </p>
        </div>

        <?php if ($success): ?>
        <div class="text-center max-w-2xl mx-auto mt-12 bg-[#050505] border border-[rgba(0,212,255,0.35)] rounded-3xl p-12 shadow-[0_0_25px_rgba(0,212,255,0.08)]">
            <div class="w-20 h-20 mx-auto rounded-full bg-[rgba(0,212,255,0.1)] text-[#00d4ff] flex items-center justify-center text-3xl mb-6">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="font-jakarta text-3xl font-bold text-white mb-4"><?php echo t('contact.success_title'); ?></h2>
            <p class="font-inter text-gray-400 mb-8 max-w-md mx-auto line-height-1.6"><?php echo t('contact.success_sub'); ?></p>
            <a href="<?php echo SITE_URL; ?>/contact" class="c-btn-submit" style="display: inline-flex; width: auto;">
                <?php echo t('contact.success_btn'); ?>
            </a>
        </div>
        <?php else: ?>
        
        <!-- Form Section -->
        <div class="max-w-3xl mx-auto">
            <div class="c-form-wrap">
                <h3 class="font-jakarta text-2xl text-white font-bold mb-8"><?php echo t('contact.form_title'); ?></h3>
                
                <?php if (!empty($errors) && !is_array(array_values($errors)[0])): ?>
                <div style="padding: 16px; border-radius: 12px; background: rgba(255,51,102,0.05); border: 1px solid rgba(255,51,102,0.2); color: #ff3366; font-family: 'Inter', sans-serif; font-size: 0.9rem; margin-bottom: 24px;">
                    <?php foreach ($errors as $error): if (!is_array($error)) echo $error . '<br>'; endforeach; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" id="contactForm">
                    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                    <input type="hidden" name="solution_id" value="<?php echo $solution_id; ?>">
                    
                    <?php if ($solution || $package): ?>
                    <div class="badges-container">
                        <?php if ($solution): ?>
                        <div class="c-badge solution-badge">
                            <div class="c-badge-icon">
                                <i class="fas <?php echo htmlspecialchars($solution['icon']); ?>"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-family:'Inter',sans-serif; font-size: 0.75rem; color:#888;"><?php echo t('contact.requesting_solution'); ?></div>
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight: 600; color:#fff;"><?php echo htmlspecialchars($solution['title']); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($package): ?>
                        <div class="c-badge package-badge">
                            <div class="c-badge-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-family:'Inter',sans-serif; font-size: 0.75rem; color:#888;"><?php echo t('contact.selected_package'); ?></div>
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight: 600; color:#fff;"><?php echo $package_names[$package] ?? ucfirst($package); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Services Selection (Pills) -->
                    <div class="c-input-group mb-8">
                        <label class="c-input-label"><?php echo t('contact.interested_label'); ?></label>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <?php foreach ($all_services as $svc): ?>
                            <label class="service-pill-label">
                                <input type="checkbox" name="selected_services[]" value="<?php echo htmlspecialchars($svc['category_key']); ?>" class="service-pill-input" <?php echo $service === $svc['category_key'] ? 'checked' : ''; ?>>
                                <span class="service-pill-content">
                                    <i class="fas <?php echo $svc['icon']; ?>"></i>
                                    <?php 
                                    $pill_title = ($current_lang === 'ar' && !empty($svc['title_ar'])) ? $svc['title_ar'] : $svc['title'];
                                    echo htmlspecialchars($pill_title); 
                                    ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-x-6">
                        <div class="c-input-group">
                            <label for="name" class="c-input-label"><?php echo t('contact.name_label'); ?></label>
                            <input type="text" name="name" id="name" class="c-input <?php echo isset($errors['name']) ? 'error' : ''; ?>" placeholder="<?php echo t('contact.name_placeholder'); ?>" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            <?php if (isset($errors['name'])): ?><div class="c-form-error"><?php echo $errors['name']; ?></div><?php endif; ?>
                        </div>
                        
                        <!-- Email matches criteria: Optional -->
                        <div class="c-input-group">
                            <label for="email" class="c-input-label"><?php echo t('contact.email_label'); ?></label>
                            <input type="email" name="email" id="email" class="c-input <?php echo isset($errors['email']) ? 'error' : ''; ?>" placeholder="<?php echo t('contact.email_placeholder'); ?>" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            <?php if (isset($errors['email'])): ?><div class="c-form-error"><?php echo $errors['email']; ?></div><?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Phone matches criteria: Required -->
                    <div class="c-input-group">
                        <label for="phone" class="c-input-label"><?php echo t('contact.phone_label'); ?></label>
                        <input type="tel" name="phone" id="phone" class="c-input <?php echo isset($errors['phone']) ? 'error' : ''; ?>" placeholder="<?php echo t('contact.phone_placeholder'); ?>" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                        <?php if (isset($errors['phone'])): ?><div class="c-form-error"><?php echo $errors['phone']; ?></div><?php endif; ?>
                    </div>
                    
                    <div class="c-input-group">
                        <label for="message" class="c-input-label"><?php echo t('contact.message_label'); ?></label>
                        <textarea name="message" id="message" class="c-input c-textarea <?php echo isset($errors['message']) ? 'error' : ''; ?>" placeholder="<?php echo t('contact.message_placeholder'); ?>" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        <?php if (isset($errors['message'])): ?><div class="c-form-error"><?php echo $errors['message']; ?></div><?php endif; ?>
                    </div>
                    
                    <button type="submit" class="c-btn-submit mt-4" data-loading-text="<?php echo t('contact.processing'); ?>">
                        <?php echo t('contact.submit_btn'); ?> <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <?php endif; ?>

        <!-- Bottom Contact Info Section Above Footer -->
        <div class="grid md:grid-cols-2 gap-12 text-center border-t border-[rgba(255,255,255,0.05)] pt-16 mt-20 pb-12">
            <div>
                <p class="c-info-label"><?php echo t('contact.email_us'); ?></p>
                <!-- Email click to Gmail Compose -->
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=cyberx.yemen@gmail.com" target="_blank" class="c-info-value">cyberx.yemen@gmail.com</a>
            </div>
            
            <div>
                <p class="c-info-label"><?php echo t('contact.call_whatsapp'); ?></p>
                <!-- Phone click to wa.me -->
                <a href="https://wa.me/967733388080" target="_blank" class="c-info-value">+967 733388080</a>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
