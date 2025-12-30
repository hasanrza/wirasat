<?php 
include 'check_login.php';
include 'include/header_cdn.php'; 

// Load autoloader and required classes
require_once 'config/autoload.php';

// Initialize database and classes
$database = Database::getInstance();
$db = $database->getConnection();

$adminObj = new Admin($db);
$formHandler = new FormHandler();
$profileController = new ProfileController($adminObj, $formHandler);

// Get admin ID from session
$admin_id = $_SESSION['admin_id'];

// Load admin data
$profileController->loadAdminData($admin_id);
$admin = $profileController->getAdminData();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileController->processRequest($admin_id);
    // Reload admin data after update
    $admin = $profileController->getAdminData();
}

// Get messages
$success_message = $profileController->getSuccessMessage();
$error_message = $profileController->getErrorMessage();
?>
<?php include 'include/header.php'; ?>
<?php include 'include/menu.php'; ?>

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                            <h4 class="page-title">Profile Settings</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Pages</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Success!</strong> <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>Error!</strong> <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">                      
                                        <h4 class="card-title">Personal Information</h4>                      
                                    </div><!--end col-->                                                       
                                </div>  <!--end row-->                                  
                            </div><!--end card-header-->
                            <div class="card-body pt-0">                       
                                <form method="POST" action="">
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">First Name</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="text" name="first_name" id="first_name" placeholder="Enter first name" value="<?php echo htmlspecialchars($admin['fname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Last Name</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="text" name="last_name" id="last_name" placeholder="Enter last name" value="<?php echo htmlspecialchars($admin['lname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Email Address</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="las la-at"></i></span>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </div>                                                    
                                </form>
                            </div><!--end card-body-->                                            
                        </div><!--end card-->
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change Password</h4>
                            </div><!--end card-header-->
                            <div class="card-body pt-0"> 
                                <form method="POST" action="">
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Current Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="current_password" id="current_password" placeholder="Enter current password" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">New Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="new_password" id="new_password" placeholder="Enter new password" minlength="6" required>
                                            <small class="text-muted">Password must be at least 6 characters long.</small>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center form-label">Confirm New Password</label>
                                        <div class="col-lg-9 col-xl-8">
                                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" minlength="6" required>
                                            <small class="text-danger" id="password-match-error" style="display: none;">Passwords do not match!</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                                            <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!--end card-body-->                                            
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->                                         
            </div><!-- container -->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

<?php include 'include/footer_cdn.php'; ?>

<script>
// Password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    const errorElement = document.getElementById('password-match-error');
    
    if (confirmPassword && newPassword !== confirmPassword) {
        errorElement.style.display = 'block';
    } else {
        errorElement.style.display = 'none';
    }
});

// Prevent form submission if passwords don't match
document.querySelector('form[action=""] button[name="update_password"]').parentElement.parentElement.parentElement.addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        document.getElementById('password-match-error').style.display = 'block';
        alert('Passwords do not match!');
    }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 5000);
</script>

