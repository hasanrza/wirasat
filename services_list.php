<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$ourServices = new OurServices($db);
$formHandler = new FormHandler();
$controller = new OurServicesController($ourServices, $formHandler);

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $serviceId = (int)$_GET['id'];
    $controller->deleteService($serviceId);
    header('Location: services_list');
    exit;
}

// Get all services
$services = $controller->getAllServices();

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
                            <h4 class="page-title">Services List</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Services List</li>
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
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">All Services</h4>
                                <a href="our_services" class="btn btn-primary btn-sm">
                                    <i class="las la-plus"></i> Add New Service
                                </a>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <?php if (empty($services)): ?>
                                <div class="alert alert-info" role="alert">
                                    <strong>No services found.</strong> <a href="our_services">Create your first service</a>
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="align-middle">Service Title</th>
                                                <th class="align-middle">Description</th>
                                                <th class="align-middle">Image</th>
                                                <th class="align-middle">Status</th>
                                                <th class="align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($services as $service): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="m-0"><?php echo htmlspecialchars($service['service_title']); ?></h6>
                                                </td>
                                                <td>
                                                    <p class="m-0 text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo strip_tags($service['service_description']); ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?php if (!empty($service['service_image']) && file_exists('uploads/services/' . $service['service_image'])): ?>
                                                    <img src="uploads/services/<?php echo htmlspecialchars($service['service_image']); ?>" alt="Service Image" style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                                                    <?php else: ?>
                                                    <span class="badge bg-light text-dark">No Image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($service['status'] == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="our_services?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">
                                                            <i class="las la-edit"></i> Edit
                                                        </a>
                                                        <a href="services_list?action=delete&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this service?');">
                                                            <i class="las la-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
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
