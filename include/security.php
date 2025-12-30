<?php
/**
 * Security Configuration File
 * Include this file at the top of pages requiring extra security
 */

// Prevent direct access to this file
if (!defined('SECURE_ACCESS')) {
    define('SECURE_ACCESS', true);
}

// Set secure session settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Security headers (in case .htaccess doesn't work)
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: no-store, no-cache, must-revalidate, private');
header('Pragma: no-cache');
header_remove('X-Powered-By');

// Function to output anti-source-view JavaScript
function outputSecurityScripts() {
?>
<script>
// Disable right-click context menu
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Disable keyboard shortcuts for viewing source
document.addEventListener('keydown', function(e) {
    // Ctrl+U (View Source)
    if (e.ctrlKey && e.keyCode === 85) {
        e.preventDefault();
        return false;
    }
    // Ctrl+Shift+I (Developer Tools)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
        e.preventDefault();
        return false;
    }
    // Ctrl+Shift+J (Console)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
        e.preventDefault();
        return false;
    }
    // Ctrl+Shift+C (Inspect Element)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
        e.preventDefault();
        return false;
    }
    // F12 (Developer Tools)
    if (e.keyCode === 123) {
        e.preventDefault();
        return false;
    }
    // Ctrl+S (Save Page)
    if (e.ctrlKey && e.keyCode === 83) {
        e.preventDefault();
        return false;
    }
});

// Disable text selection
document.addEventListener('selectstart', function(e) {
    e.preventDefault();
    return false;
});

// Disable drag
document.addEventListener('dragstart', function(e) {
    e.preventDefault();
    return false;
});

// Detect DevTools (basic detection)
(function() {
    var devtools = {open: false};
    var threshold = 160;
    
    setInterval(function() {
        if (window.outerWidth - window.innerWidth > threshold || 
            window.outerHeight - window.innerHeight > threshold) {
            if (!devtools.open) {
                devtools.open = true;
                // Redirect or show warning
                document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;background:#000;color:#fff;font-size:24px;text-align:center;"><div><h1>⚠️ Access Denied</h1><p>Developer tools are not allowed on this page.</p></div></div>';
            }
        } else {
            devtools.open = false;
        }
    }, 500);
})();

// Console warning
console.log('%c⚠️ STOP!', 'color: red; font-size: 50px; font-weight: bold;');
console.log('%cThis is a protected area. Any unauthorized access attempts may be logged and reported.', 'color: red; font-size: 16px;');
</script>
<style>
/* Disable text selection via CSS */
body {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
/* Allow selection in form inputs */
input, textarea, select {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}
</style>
<?php
}
?>

