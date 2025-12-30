<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$projectsObj = new Projects($db);
$formHandler = new FormHandler();
$controller = new ProjectsController($projectsObj, $formHandler);

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $projectId = (int)$_GET['id'];
    $controller->deleteProject($projectId);
    header('Location: projects_list');
    exit;
}

// Get all projects
$projects = $controller->getAllProjects();

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
                            <h4 class="page-title">Projects List</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Projects List</li>
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
                                <h4 class="card-title">All Projects</h4>
                                <a href="projects" class="btn btn-primary btn-sm">
                                    <i class="las la-plus"></i> Add New Project
                                </a>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <?php if (empty($projects)): ?>
                                <div class="alert alert-info" role="alert">
                                    <strong>No projects found.</strong> <a href="projects">Create your first project</a>
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Project ID</th>
                                                <th class="align-middle">Project Name</th>
                                                <th class="align-middle">Map Thumbnail</th>
                                                <th class="align-middle">Amenities</th>
                                                <th class="align-middle">Status</th>
                                                <th class="align-middle">Created</th>
                                                <th class="align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($projects as $index => $project): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($project['project_id'] ?: 'N/A'); ?></span>
                                                </td>
                                                <td>
                                                    <h6 class="m-0"><?php echo htmlspecialchars($project['project_name']); ?></h6>
                                                </td>
                                                <td>
                                                    <?php if (!empty($project['project_map_thumbnail']) && file_exists('uploads/projects/' . $project['project_map_thumbnail'])): ?>
                                                    <img src="uploads/projects/<?php echo htmlspecialchars($project['project_map_thumbnail']); ?>" alt="Map Thumbnail" style="max-width: 60px; max-height: 60px; border-radius: 4px;">
                                                    <?php else: ?>
                                                    <span class="badge bg-light text-dark">No Image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <p class="m-0 text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo strip_tags($project['project_amenities'] ?: 'No amenities'); ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?php if ($project['status'] == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?php echo date('M d, Y', strtotime($project['created_at'])); ?></small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="projects_edit?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="las la-edit"></i> Edit
                                                        </a>
                                                        <a href="projects_list?action=delete&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.');" title="Delete">
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

                <!-- Projects Stats Cards -->
                <?php if (!empty($projects)): ?>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                <i class="las la-project-diagram"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total Projects</p>
                                        <h4 class="mb-0"><?php echo count($projects); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                                <i class="las la-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Active Projects</p>
                                        <h4 class="mb-0"><?php echo count(array_filter($projects, function($p) { return $p['status'] == 1; })); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-3">
                                                <i class="las la-times-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Inactive Projects</p>
                                        <h4 class="mb-0"><?php echo count(array_filter($projects, function($p) { return $p['status'] == 0; })); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                                        
            </div><!-- container -->
            
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

<?php include 'include/footer_cdn.php'; ?>

