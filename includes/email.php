<?php
/**
 * CyberX Email Functions using PHPMailer
 */

// Include PHPMailer classes
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Create and configure PHPMailer instance
 */
function create_mailer() {
    $mail = new PHPMailer(true);
    
    try {
        // Check if SMTP is enabled and configured
        if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->SMTPDebug = SMTP_DEBUG;
            
            // Set from address from SMTP config
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        } else {
            // Fallback to PHP mail() function
            $mail->isMail();
            $mail->setFrom(SITE_EMAIL, SITE_NAME);
        }
        
        // Common settings
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->isHTML(true);
        
    } catch (Exception $e) {
        error_log("PHPMailer configuration error: " . $e->getMessage());
    }
    
    return $mail;
}

/**
 * Send email using PHPMailer
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body HTML body content
 * @return array ['success' => bool, 'message' => string]
 */
function send_email($to, $subject, $body) {
    $mail = create_mailer();
    
    try {
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = get_email_template($subject, $body);
        // NOTE: We don't set AltBody because the simplified PHPMailer doesn't 
        // properly handle multipart MIME encoding, causing emails to appear as
        // empty with a "noname" attachment.
        
        $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully'];
        
    } catch (Exception $e) {
        $error = "Email could not be sent. Error: " . $mail->ErrorInfo;
        error_log($error);
        return ['success' => false, 'message' => $error];
    }
}

/**
 * Test SMTP connection
 * 
 * @return array ['success' => bool, 'message' => string]
 */
function test_email_connection() {
    if (!defined('SMTP_ENABLED') || !SMTP_ENABLED) {
        return [
            'success' => false, 
            'message' => 'SMTP is not enabled. Configure SMTP in config.php first.'
        ];
    }
    
    $mail = create_mailer();
    
    try {
        // Try to connect without sending
        if ($mail->smtpConnect()) {
            $mail->smtpClose();
            return ['success' => true, 'message' => 'SMTP connection successful!'];
        }
        return ['success' => false, 'message' => 'Could not connect to SMTP server.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Get email HTML template
 */
function get_email_template($title, $content) {
    $year = date('Y');
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'CyberX';
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #ffffff; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #0a1628;">
    <div style="background: linear-gradient(135deg, #0a1628 0%, #1a2942 100%); padding: 30px; border-radius: 16px; border: 1px solid rgba(0, 255, 136, 0.2);">
        <!-- Logo/Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #ffffff; margin: 0; font-size: 32px;">
                <span style="color: #00d4ff;">Cyber</span><span style="color: #00ff88;">X</span>
            </h1>
            <p style="color: #888; margin: 5px 0 0; font-size: 14px;">Cybersecurity Education Platform</p>
        </div>
        
        <!-- Content -->
        <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
            {$content}
        </div>
        
        <!-- Footer -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: #666; font-size: 12px; margin: 0;">
                &copy; {$year} {$siteName}. All rights reserved.
            </p>
            <p style="color: #666; font-size: 12px; margin: 10px 0 0;">
                This email was sent because you registered on our platform.
            </p>
        </div>
    </div>
</body>
</html>
HTML;
}

/**
 * Send verification email (with link - legacy)
 */
function send_verification_email($student) {
    $verify_url = SITE_URL . '/student/verify.php?token=' . $student['verification_token'];
    $name = htmlspecialchars($student['full_name']);
    
    $body = <<<HTML
<h2 style="color: #00ff88; margin-top: 0; font-size: 24px;">Welcome to CyberX!</h2>
<p style="color: #ffffff;">Hi <strong>{$name}</strong>,</p>
<p style="color: #cccccc;">Thank you for registering. Please verify your email address by clicking the button below:</p>

<div style="text-align: center; margin: 35px 0;">
    <a href="{$verify_url}" style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 14px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);">
        ✓ Verify Email Address
    </a>
</div>

<p style="color: #888; font-size: 13px;">If the button doesn't work, copy and paste this link into your browser:</p>
<p style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 6px; word-break: break-all;">
    <a href="{$verify_url}" style="color: #00d4ff; text-decoration: none; font-size: 12px;">{$verify_url}</a>
</p>

<div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); padding: 12px; border-radius: 8px; margin-top: 20px;">
    <p style="color: #ffc107; margin: 0; font-size: 13px;">
        <strong>⏰ This link expires in 24 hours.</strong>
    </p>
</div>
HTML;
    
    return send_email($student['email'], 'Verify Your Email - ' . SITE_NAME, $body);
}
/**
 * Send verification code email (6-digit OTP)
 */
function send_verification_code_email($student) {
    $code = $student['verification_code'] ?? '';
    $name = htmlspecialchars($student['full_name'] ?? 'User');
    
    // Debug: Log the code being sent
    error_log("Sending verification code: " . $code . " to " . $student['email']);
    
    // Ensure we have a code
    if (empty($code)) {
        return ['success' => false, 'message' => 'No verification code provided'];
    }
    
    // Format code with spaces for readability
    $formatted_code = substr($code, 0, 3) . ' ' . substr($code, 3, 3);
    
    $body = '<h2 style="color: #00ff88; margin-top: 0; font-size: 24px;">Verify Your Email</h2>';
    $body .= '<p style="color: #ffffff;">Hi <strong>' . $name . '</strong>,</p>';
    $body .= '<p style="color: #cccccc;">Thank you for registering with CyberX! Use the verification code below to complete your registration:</p>';
    
    $body .= '<div style="text-align: center; margin: 35px 0;">';
    $body .= '<div style="background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 212, 255, 0.1)); border: 2px solid rgba(0, 255, 136, 0.3); border-radius: 12px; padding: 25px; display: inline-block;">';
    $body .= '<p style="color: #888; margin: 0 0 10px; font-size: 14px;">Your verification code:</p>';
    $body .= '<div style="font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #00ff88; font-family: Courier New, monospace;">';
    $body .= $formatted_code;
    $body .= '</div>';
    $body .= '</div>';
    $body .= '</div>';
    
    $body .= '<p style="color: #cccccc; text-align: center;">Enter this code on the verification page to activate your account.</p>';
    
    $body .= '<div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); padding: 12px; border-radius: 8px; margin-top: 25px;">';
    $body .= '<p style="color: #ffc107; margin: 0; font-size: 13px;">';
    $body .= '<strong>⏰ This code expires in 15 minutes.</strong>';
    $body .= '</p>';
    $body .= '</div>';
    
    $body .= '<div style="background: rgba(255, 68, 68, 0.1); border: 1px solid rgba(255, 68, 68, 0.3); padding: 12px; border-radius: 8px; margin-top: 15px;">';
    $body .= '<p style="color: #ff6666; margin: 0; font-size: 12px;">';
    $body .= '<strong>⚠️ Security Note:</strong> If you did not create an account, please ignore this email.';
    $body .= '</p>';
    $body .= '</div>';
    
    return send_email($student['email'], 'Verify Your Email - ' . SITE_NAME, $body);
}

/**
 * Send password reset email
 */
function send_password_reset_email($student) {
    $reset_url = SITE_URL . '/student/reset-password.php?token=' . $student['reset_token'];
    $name = htmlspecialchars($student['full_name']);
    
    $body = <<<HTML
<h2 style="color: #00d4ff; margin-top: 0; font-size: 24px;">Password Reset Request</h2>
<p style="color: #ffffff;">Hi <strong>{$name}</strong>,</p>
<p style="color: #cccccc;">We received a request to reset your password. Click the button below to create a new password:</p>

<div style="text-align: center; margin: 35px 0;">
    <a href="{$reset_url}" style="background: linear-gradient(135deg, #00d4ff 0%, #00ff88 100%); color: #0a1628; padding: 14px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);">
        🔐 Reset Password
    </a>
</div>

<p style="color: #888; font-size: 13px;">If the button doesn't work, copy and paste this link into your browser:</p>
<p style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 6px; word-break: break-all;">
    <a href="{$reset_url}" style="color: #00d4ff; text-decoration: none; font-size: 12px;">{$reset_url}</a>
</p>

<div style="background: rgba(255, 68, 68, 0.1); border: 1px solid rgba(255, 68, 68, 0.3); padding: 12px; border-radius: 8px; margin-top: 20px;">
    <p style="color: #ff6666; margin: 0; font-size: 13px;">
        <strong>⚠️ This link expires in 1 hour.</strong><br>
        If you didn't request this, you can safely ignore this email.
    </p>
</div>
HTML;
    
    return send_email($student['email'], 'Password Reset - ' . SITE_NAME, $body);
}

/**
 * Send enrollment status notification
 */
function send_enrollment_notification($student, $course, $status) {
    $name = htmlspecialchars($student['full_name']);
    $courseTitle = htmlspecialchars($course['title']);
    $dashboardUrl = SITE_URL . '/student/dashboard.php';
    
    if ($status === 'approved') {
        $body = <<<HTML
<h2 style="color: #00ff88; margin-top: 0; font-size: 24px;">🎉 Enrollment Approved!</h2>
<p style="color: #ffffff;">Hi <strong>{$name}</strong>,</p>
<p style="color: #cccccc;">Great news! Your enrollment for <strong style="color: #00d4ff;">{$courseTitle}</strong> has been <span style="color: #00ff88; font-weight: bold;">approved</span>!</p>

<p style="color: #cccccc;">You can now access the course content from your dashboard:</p>

<div style="text-align: center; margin: 35px 0;">
    <a href="{$dashboardUrl}" style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 14px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);">
        📚 Go to Dashboard
    </a>
</div>

<div style="background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.3); padding: 15px; border-radius: 8px;">
    <p style="color: #00ff88; margin: 0; font-size: 14px;">
        <strong>🚀 Start learning today!</strong><br>
        <span style="color: #cccccc;">Your cybersecurity journey begins now.</span>
    </p>
</div>
HTML;
    } else {
        $body = <<<HTML
<h2 style="color: #ff6666; margin-top: 0; font-size: 24px;">Enrollment Update</h2>
<p style="color: #ffffff;">Hi <strong>{$name}</strong>,</p>
<p style="color: #cccccc;">We regret to inform you that your enrollment for <strong style="color: #00d4ff;">{$courseTitle}</strong> has been <span style="color: #ff6666; font-weight: bold;">rejected</span>.</p>

<p style="color: #cccccc;">If you have any questions or believe this was a mistake, please contact us at:</p>
<p style="color: #00d4ff;">{SITE_EMAIL}</p>

<div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 8px; margin-top: 20px;">
    <p style="color: #aaa; margin: 0; font-size: 13px;">
        You may also re-apply for this course with updated information.
    </p>
</div>
HTML;
    }
    
    $status_text = $status === 'approved' ? 'Approved' : 'Rejected';
    return send_email($student['email'], "Enrollment {$status_text} - " . SITE_NAME, $body);
}

/**
 * Send enrollment confirmation email
 */
function send_enrollment_confirmation($student, $course) {
    $name = htmlspecialchars($student['full_name']);
    $courseTitle = htmlspecialchars($course['title']);
    $dashboardUrl = SITE_URL . '/student/dashboard.php';
    
    $body = <<<HTML
<h2 style="color: #00ff88; margin-top: 0; font-size: 24px;">📋 Enrollment Submitted</h2>
<p style="color: #ffffff;">Hi <strong>{$name}</strong>,</p>
<p style="color: #cccccc;">Your enrollment for <strong style="color: #00d4ff;">{$courseTitle}</strong> has been submitted successfully.</p>

<p style="color: #cccccc;">Our team will review your application and you will receive an email once it's approved.</p>

<div style="text-align: center; margin: 35px 0;">
    <a href="{$dashboardUrl}" style="background: linear-gradient(135deg, #00d4ff 0%, #6c5ce7 100%); color: #ffffff; padding: 14px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);">
        📊 View Dashboard
    </a>
</div>

<div style="background: rgba(0, 212, 255, 0.1); border: 1px solid rgba(0, 212, 255, 0.3); padding: 15px; border-radius: 8px;">
    <p style="color: #00d4ff; margin: 0; font-size: 14px;">
        <strong>⏳ What happens next?</strong><br>
        <span style="color: #cccccc;">We typically review applications within 24-48 hours.</span>
    </p>
</div>
HTML;
    
    return send_email($student['email'], 'Enrollment Submitted - ' . SITE_NAME, $body);
}
