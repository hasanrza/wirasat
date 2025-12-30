<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$companyProfile = new CompanyProfile($db);
$formHandler = new FormHandler();
$controller = new CompanyProfileController($companyProfile, $formHandler);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRequest();
    // Reload data after submission
    $controller->loadProfileData();
} else {
    // Load existing data
    $controller->loadProfileData();
}

// Get profile data
$profileData = $controller->getProfileData();

// Get messages
$successMessage = $controller->getSuccessMessage();
$errorMessage = $controller->getErrorMessage();

include 'include/header_cdn.php'; 
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
                            <h4 class="page-title">Company Profile</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Company Profile</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <?php if ($successMessage): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo htmlspecialchars($successMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?php echo htmlspecialchars($errorMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Company Profile Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Company Profile Fields</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <input type="hidden" name="comp_id" value="999999">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Company Name: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="company_name" id="company_name" placeholder="Enter company name" value="<?php echo htmlspecialchars($profileData['company_name'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Company Logo:</label>
                                        <input class="form-control" type="file" name="company_logo" id="company_logo" accept="image/*">
                                        <small class="text-muted">Upload company logo image</small>
                                        <?php if (!empty($profileData['company_logo']) && file_exists('uploads/company/' . $profileData['company_logo'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/company/<?php echo htmlspecialchars($profileData['company_logo']); ?>" alt="Company Logo" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Company Background Picture:</label>
                                        <input class="form-control" type="file" name="company_background" id="company_background" accept="image/*">
                                        <small class="text-muted">Upload company background picture</small>
                                        <?php if (!empty($profileData['company_background']) && file_exists('uploads/company/' . $profileData['company_background'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/company/<?php echo htmlspecialchars($profileData['company_background']); ?>" alt="Company Background" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Contact Us Footer Image:</label>
                                        <input class="form-control" type="file" name="footer_image" id="footer_image" accept="image/*">
                                        <small class="text-muted">Upload contact us footer image</small>
                                        <?php if (!empty($profileData['footer_image']) && file_exists('uploads/company/' . $profileData['footer_image'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/company/<?php echo htmlspecialchars($profileData['footer_image']); ?>" alt="Footer Image" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Company Address (HO):</label>
                                        <div id="company_address_editor" style="height: 150px;"></div>
                                        <textarea class="form-control d-none" name="company_address" id="company_address"></textarea>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Social Links</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Facebook Link:</label>
                                        <input class="form-control" type="url" name="facebook_link" id="facebook_link" placeholder="https://facebook.com/yourpage" value="<?php echo htmlspecialchars($profileData['facebook_link'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">YouTube Link:</label>
                                        <input class="form-control" type="url" name="youtube_link" id="youtube_link" placeholder="https://youtube.com/yourchannel" value="<?php echo htmlspecialchars($profileData['youtube_link'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Twitter Link:</label>
                                        <input class="form-control" type="url" name="twitter_link" id="twitter_link" placeholder="https://twitter.com/yourhandle" value="<?php echo htmlspecialchars($profileData['twitter_link'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Instagram Link:</label>
                                        <input class="form-control" type="url" name="instagram_link" id="instagram_link" placeholder="https://instagram.com/yourprofile" value="<?php echo htmlspecialchars($profileData['instagram_link'] ?? ''); ?>">
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Contact Information</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Website URL:</label>
                                        <input class="form-control" type="url" name="website_url" id="website_url" placeholder="https://www.example.com" value="<?php echo htmlspecialchars($profileData['website_url'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Email Address: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email_address" id="email_address" placeholder="Enter email address" value="<?php echo htmlspecialchars($profileData['email_address'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">UAN:</label>
                                        <input class="form-control" type="text" name="uan" id="uan" placeholder="Enter UAN number" value="<?php echo htmlspecialchars($profileData['uan'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Mobile No. 1:</label>
                                                <input class="form-control" type="tel" name="mobile_1" id="mobile_1" placeholder="Enter mobile number 1" value="<?php echo htmlspecialchars($profileData['mobile_1'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Mobile No. 2:</label>
                                                <input class="form-control" type="tel" name="mobile_2" id="mobile_2" placeholder="Enter mobile number 2" value="<?php echo htmlspecialchars($profileData['mobile_2'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">PTCL Number:</label>
                                        <input class="form-control" type="tel" name="ptcl_number" id="ptcl_number" placeholder="Enter PTCL number" value="<?php echo htmlspecialchars($profileData['ptcl_number'] ?? ''); ?>">
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Company Location</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Google Longitude:</label>
                                                <input class="form-control" type="text" name="longitude" id="longitude" placeholder="Enter longitude" value="<?php echo htmlspecialchars($profileData['longitude'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Google Latitude:</label>
                                                <input class="form-control" type="text" name="latitude" id="latitude" placeholder="Enter latitude" value="<?php echo htmlspecialchars($profileData['latitude'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Company Profile Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Company Profile Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Save</button>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-4-->
                    </div><!--end row-->
                </form>
                                        
            </div><!-- container -->
            
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->
<?php include 'include/footer_cdn.php'; ?>
<script>
    // Initialize Quill Editor for Company Address
    var companyAddressQuill = new Quill('#company_address_editor', {
        theme: 'snow',
        placeholder: 'Enter company head office address'
    });

    // Load existing content if available
    <?php if (!empty($profileData['company_address'])): ?>
    companyAddressQuill.root.innerHTML = <?php echo json_encode($profileData['company_address']); ?>;
    <?php endif; ?>

    // Sync Quill content to hidden textarea on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('company_address').value = companyAddressQuill.root.innerHTML;
    });
</script>
