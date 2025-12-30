<?php
/**
 * Register Admin - RESTRICTED ACCESS
 * This file should be DELETED after creating your admin accounts!
 * 
 * To use this file, you must either:
 * 1. Be logged in as an existing admin, OR
 * 2. Access with a secret key: register_admin?key=YOUR_SECRET_KEY
 * 
 * IMPORTANT: Change the secret key below or delete this file after use!
 */

// Secret registration key - CHANGE THIS!
define('REGISTRATION_SECRET_KEY', 'wirasat_admin_2024_secret');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security check - either must be logged in OR have secret key
$isAuthorized = false;

// Check if logged in
if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
    $isAuthorized = true;
}

// Check secret key
if (isset($_GET['key']) && $_GET['key'] === REGISTRATION_SECRET_KEY) {
    $isAuthorized = true;
}

// If not authorized, deny access
if (!$isAuthorized) {
    http_response_code(403);
    die('
    <!DOCTYPE html>
    <html>
    <head>
        <title>Access Denied</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
                margin: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .container { 
                text-align: center; 
                color: white;
                padding: 40px;
                background: rgba(0,0,0,0.3);
                border-radius: 10px;
            }
            h1 { font-size: 72px; margin: 0; }
            p { font-size: 18px; }
            a { color: #fff; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>403</h1>
            <h2>Access Denied</h2>
            <p>You are not authorized to access this page.</p>
            <p><a href="login">Go to Login</a></p>
        </div>
    </body>
    </html>
    ');
}

include 'include/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $fname = mysqli_real_escape_string($conn, trim($_POST['fname']));
    $lname = mysqli_real_escape_string($conn, trim($_POST['lname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate input
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM admin WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new admin
            $insert_query = "INSERT INTO admin (fname, lname, email, password) VALUES ('$fname', '$lname', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                $message = "Admin registered successfully! You can now <a href='login'>login here</a>.";
            } else {
                $error = "Error: " . mysqli_error($conn);
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
    <title>Register Admin - RESTRICTED</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }
        .warning-banner {
            background: #ff4444;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="warning-banner">
            ⚠️ SECURITY WARNING: DELETE THIS FILE AFTER CREATING YOUR ADMIN ACCOUNTS! ⚠️
        </div>
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Register Admin User</h3>
                
                <div class="security-notice">
                    <strong>🔒 Security Notice:</strong> This page is protected. Only logged-in admins or users with the secret key can access it.
                    After creating your admin accounts, please DELETE this file for security.
                </div>
                
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="fname" placeholder="Enter first name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lname" placeholder="Enter last name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email address" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter password (min 6 characters)" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="register" class="btn btn-primary">
                            Register Admin <i class="fas fa-user-plus ms-1"></i>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="login" class="text-muted">Already have an account? Login here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
