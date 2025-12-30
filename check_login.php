<?php
/**
 * Check Login - Enhanced Security
 * Verifies if admin is logged in with additional security measures
 */

// Set secure session settings before starting
if (session_status() === PHP_SESSION_NONE) {
    // Secure session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
    
    // Set secure cookie if HTTPS
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    
    // Set SameSite attribute
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    session_start();
}

// Include database connection (keeping backward compatibility)
include_once __DIR__ . '/include/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Clear any existing session data
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header('Location: login');
    exit();
}

// Session timeout - 30 minutes of inactivity
$session_timeout = 30 * 60; // 30 minutes in seconds

if (isset($_SESSION['last_activity'])) {
    $inactive_time = time() - $_SESSION['last_activity'];
    
    if ($inactive_time > $session_timeout) {
        // Session expired
        session_unset();
        session_destroy();
        header('Location: login?timeout=1');
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically (every 5 minutes) to prevent session fixation
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Verify session integrity - check if IP changed (optional - can cause issues with mobile users)
if (isset($_SESSION['user_ip'])) {
    if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
        // Possible session hijacking - destroy session
        session_unset();
        session_destroy();
        header('Location: login?security=1');
        exit();
    }
} else {
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
}

// Verify user agent hasn't changed
if (isset($_SESSION['user_agent'])) {
    if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        // Possible session hijacking - destroy session
        session_unset();
        session_destroy();
        header('Location: login?security=1');
        exit();
    }
} else {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// Optional: Load PDO connection for new OOP structure
if (!defined('DB_CONNECTED_PDO')) {
    require_once __DIR__ . '/config/Database.php';
    $database = Database::getInstance();
    $pdo = $database->getConnection();
    define('DB_CONNECTED_PDO', true);
}
?>
