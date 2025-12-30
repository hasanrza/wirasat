<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$ourServices = new OurServices($db);
$formHandler = new FormHandler();
$controller = new OurServicesController($ourServices, $formHandler);

// Get service ID from URL parameter
$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->processRequest($serviceId)) {
        // Redirect to list after successful save
        header('Location: services_list');
        exit;
    }
} else {
    // Load existing data if editing
    if ($serviceId) {
        $controller->loadServiceData($serviceId);
    }
}

// Get service data
$serviceData = $controller->getServiceData();

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
                            <h4 class="page-title"><?php echo $serviceId ? 'Edit Service' : 'Add New Service'; ?></h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="services_list">Services List</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active"><?php echo $serviceId ? 'Edit Service' : 'Add New Service'; ?></li>
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
                        <!-- Our Services Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?php echo $serviceId ? 'Edit Service Details' : 'Create New Service'; ?></h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Service Title: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="service_title" id="service_title" placeholder="Enter service title" value="<?php echo htmlspecialchars($serviceData['service_title'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Service Description: <span class="text-danger">*</span></label>
                                        <div id="service_description_editor" style="height: 250px;"></div>
                                        <textarea class="form-control d-none" name="service_description" id="service_description"></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Service Image:</label>
                                        <input class="form-control" type="file" name="service_image" id="service_image" accept="image/*">
                                        <small class="text-muted">Upload service image</small>
                                        <?php if (!empty($serviceData['service_image']) && file_exists('uploads/services/' . $serviceData['service_image'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/services/<?php echo htmlspecialchars($serviceData['service_image']); ?>" alt="Service Image" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Our Services Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Service Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" <?php echo (!isset($serviceData['status']) || $serviceData['status'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="status">Active</label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100 mb-2"><?php echo $serviceId ? 'Update Service' : 'Create Service'; ?></button>
                                    </div>
                                    <div class="form-group">
                                        <a href="services_list" class="btn btn-danger w-100">Cancel</a>
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
    // Initialize Quill Editor for Service Description
    var serviceDescriptionQuill = new Quill('#service_description_editor', {
        theme: 'snow',
        placeholder: 'Enter service description'
    });

    // Load existing content if available
    <?php if (!empty($serviceData['service_description'])): ?>
    serviceDescriptionQuill.root.innerHTML = <?php echo json_encode($serviceData['service_description']); ?>;
    <?php endif; ?>

    // Sync Quill content to hidden textarea on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('service_description').value = serviceDescriptionQuill.root.innerHTML;
    });
</script>


