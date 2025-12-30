<?php 
include 'check_login.php';
require_once 'config/autoload.php';

$showThankYou = false;
$success_message = '';
$error_message = '';

// Initialize database and controller
$database = Database::getInstance();
$db = $database->getConnection();
$contactMessages = new ContactMessages($db);
$contactController = new ContactMessagesController($contactMessages);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    if ($contactController->submitContactForm($_POST)) {
        $showThankYou = true;
        $success_message = $contactController->getSuccessMessage();
    } else {
        $error_message = $contactController->getErrorMessage();
    }
}
?>
<?php include 'include/header_cdn.php'; ?>
<?php include 'include/header.php'; ?>
<?php include 'include/menu.php'; ?>

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                            <h4 class="page-title">Contact Us</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Contact Us</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <?php if ($showThankYou): ?>
                <!-- Thank You Message -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="las la-check-circle text-success" style="font-size: 72px;"></i>
                                </div>
                                <h3 class="mb-3 text-success">Thank You for Contacting Us!</h3>
                                <p class="text-muted mb-4" style="font-size: 16px; max-width: 600px; margin: 0 auto;">
                                    We appreciate you taking the time to reach out to us. Your message has been successfully received 
                                    and our team will review it shortly. We aim to respond to all inquiries within 24-48 hours.
                                </p>
                                <p class="text-muted mb-4">
                                    If your matter is urgent, please feel free to call us directly at our office.
                                </p>
                                <div class="mt-4">
                                    <a href="contact_us" class="btn btn-primary me-2">
                                        <i class="las la-envelope"></i> Send Another Message
                                    </a>
                                    <a href="index" class="btn btn-outline-secondary">
                                        <i class="las la-home"></i> Back to Home
                                    </a>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
                <?php else: ?>
                <!-- Display Error Message if Any -->
                <?php if (!empty($error_message)): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Contact Form -->
                <form method="POST" action="contact_us" id="contactForm">
                    <div class="row">
                        <!-- Contact Form Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Contact Form</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Full Name: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="full_name" id="full_name" 
                                               placeholder="Enter your full name" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Email Address: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email_address" id="email_address" 
                                               placeholder="Enter your email address" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Phone Number:</label>
                                        <input class="form-control" type="tel" name="phone_number" id="phone_number" 
                                               placeholder="Enter your phone number (optional)">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Subject: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="message_subject" id="message_subject" 
                                               placeholder="Enter message subject" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Message: <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message_body" id="message_body" rows="6" 
                                                  placeholder="Enter your message here..." required></textarea>
                                        <small class="text-muted">Please provide as much detail as possible</small>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Contact Info & Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Submit Form</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group">
                                        <button type="submit" name="submit_contact" class="btn btn-primary w-100 mb-2">
                                            <i class="las la-paper-plane"></i> Submit Message
                                        </button>
                                        <button type="reset" class="btn btn-secondary w-100">
                                            <i class="las la-redo"></i> Reset Form
                                        </button>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Contact Information</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="mb-2"><i class="las la-map-marker text-primary"></i> Address</h6>
                                        <p class="text-muted mb-0">123 Business Street<br>
                                        City, State 12345<br>
                                        Country</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="mb-2"><i class="las la-phone text-primary"></i> Phone</h6>
                                        <p class="text-muted mb-0">+1 (234) 567-8900</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="mb-2"><i class="las la-envelope text-primary"></i> Email</h6>
                                        <p class="text-muted mb-0">info@company.com</p>
                                    </div>
                                    
                                    <div class="mb-0">
                                        <h6 class="mb-2"><i class="las la-clock text-primary"></i> Business Hours</h6>
                                        <p class="text-muted mb-0">
                                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                                            Saturday: 10:00 AM - 4:00 PM<br>
                                            Sunday: Closed
                                        </p>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-4-->
                    </div><!--end row-->
                </form>
                <?php endif; ?>
                                        
            </div><!-- container -->
            
            <!--Start Footer-->
            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        ©
                                        <script> document.write(new Date().getFullYear()) </script>
                                        Mifty
                                        <span
                                            class="text-muted d-none d-sm-inline-block float-end">
                                            Design with
                                            <i class="iconoir-heart-solid text-danger align-middle"></i>
                                            by Mannatthemes</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

<?php include 'include/footer_cdn.php'; ?>

<script>
// Form validation
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    const contactNo = document.getElementById('contact_no').value;
    const email = document.getElementById('email').value;
    
    // Basic phone number validation
    const phoneRegex = /^[\d\s\-\+\(\)]+$/;
    if (!phoneRegex.test(contactNo)) {
        e.preventDefault();
        alert('Please enter a valid contact number');
        return false;
    }
    
    // Email validation is handled by HTML5 type="email"
    // Additional validation can be added here if needed
    
    return true;
});

// Auto-hide success message after 10 seconds
<?php if ($showThankYou): ?>
setTimeout(function() {
    // Optionally fade out or redirect
    // window.location.href = 'index.php';
}, 10000);
<?php endif; ?>
</script>


