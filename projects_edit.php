<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and classes
$database = Database::getInstance();
$db = $database->getConnection();

$projectsObj = new Projects($db);
$formHandler = new FormHandler();
$projectsController = new ProjectsController($projectsObj, $formHandler);

// Get project ID from URL parameter - required for edit page
$projectId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Redirect to list if no ID provided
if (!$projectId) {
    header('Location: projects_list');
    exit;
}

// Load existing data
$projectsController->loadProjectData($projectId);
$projectData = $projectsController->getProjectData();

// Redirect if project not found
if (!$projectData) {
    header('Location: projects_list');
    exit;
}

// Process form submissions (update only)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($projectsController->processRequest($projectId)) {
        // Redirect to list after successful update
        header('Location: projects_list');
        exit;
    }
    // Reload data after failed submission to show errors
    $projectsController->loadProjectData($projectId);
    $projectData = $projectsController->getProjectData();
}

// Get messages
$successMessage = $projectsController->getSuccessMessage();
$errorMessage = $projectsController->getErrorMessage();

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
                            <h4 class="page-title">Edit Project</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="projects_list">Projects List</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Edit Project</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Success!</strong> <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Error!</strong> <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Projects Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Edit Project Details</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <input type="hidden" name="comp_id" id="comp_id" value="<?php echo htmlspecialchars($projectData['comp_id'] ?? '999999'); ?>">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project ID:</label>
                                        <input class="form-control" type="text" name="project_id" id="project_id" placeholder="Enter project ID" value="<?php echo htmlspecialchars($projectData['project_id'] ?? ''); ?>">
                                        <small class="text-muted">Unique identifier for this project</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Name: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="project_name" id="project_name" placeholder="Enter project name" value="<?php echo htmlspecialchars($projectData['project_name'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Project Images</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Map Thumbnail:</label>
                                        <input class="form-control" type="file" name="project_map_thumbnail" id="project_map_thumbnail" accept="image/*">
                                        <small class="text-muted">Upload project map thumbnail image</small>
                                        <?php if (!empty($projectData['project_map_thumbnail']) && file_exists('uploads/projects/' . $projectData['project_map_thumbnail'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/projects/<?php echo htmlspecialchars($projectData['project_map_thumbnail']); ?>" alt="Map Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted small mb-0 mt-1">Current image (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Map Full Image:</label>
                                        <input class="form-control" type="file" name="project_map_full" id="project_map_full" accept="image/*">
                                        <small class="text-muted">Upload project map full image</small>
                                        <?php if (!empty($projectData['project_map_full']) && file_exists('uploads/projects/' . $projectData['project_map_full'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/projects/<?php echo htmlspecialchars($projectData['project_map_full']); ?>" alt="Map Full" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted small mb-0 mt-1">Current image (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Payment Plan Image:</label>
                                        <input class="form-control" type="file" name="project_payment_plan" id="project_payment_plan" accept="image/*">
                                        <small class="text-muted">Upload project payment plan image</small>
                                        <?php if (!empty($projectData['project_payment_plan']) && file_exists('uploads/projects/' . $projectData['project_payment_plan'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/projects/<?php echo htmlspecialchars($projectData['project_payment_plan']); ?>" alt="Payment Plan" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted small mb-0 mt-1">Current image (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Project Amenities</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Amenities (Bullet Paragraph):</label>
                                        <div id="project_amenities_editor" style="height: 250px;"></div>
                                        <textarea class="form-control d-none" name="project_amenities" id="project_amenities"></textarea>
                                        <small class="text-muted">Enter each amenity on a new line or use bullet points</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Project Amenities Image:</label>
                                        <input class="form-control" type="file" name="project_amenities_image" id="project_amenities_image" accept="image/*">
                                        <small class="text-muted">Upload project amenities image</small>
                                        <?php if (!empty($projectData['project_amenities_image']) && file_exists('uploads/projects/' . $projectData['project_amenities_image'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/projects/<?php echo htmlspecialchars($projectData['project_amenities_image']); ?>" alt="Amenities Image" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted small mb-0 mt-1">Current image (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Project Downloads</h5>
                                    
                                    <div id="documents-container">
                                        <?php if (!empty($projectData['documents'])): ?>
                                            <?php foreach ($projectData['documents'] as $index => $doc): ?>
                                            <div class="card border mb-3 document-card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0">Document <?php echo $index + 1; ?></h6>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument(this)">
                                                            <i class="las la-times"></i> Remove
                                                        </button>
                                                    </div>
                                                    
                                                    <input type="hidden" name="doc_db_id[]" value="<?php echo $doc['id']; ?>">
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document ID (doc_id):</label>
                                                        <input class="form-control" type="text" name="doc_id[]" placeholder="Enter document ID" value="<?php echo htmlspecialchars($doc['doc_id'] ?? ''); ?>">
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document Name:</label>
                                                        <input class="form-control" type="text" name="document_name[]" placeholder="Enter document name" value="<?php echo htmlspecialchars($doc['document_name'] ?? ''); ?>">
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document Thumbnail:</label>
                                                        <input class="form-control" type="file" name="document_thumbnail[]" accept="image/*">
                                                        <small class="text-muted">Upload document thumbnail image (JPG, PNG, GIF)</small>
                                                        <?php if (!empty($doc['document_thumbnail']) && file_exists('uploads/projects/' . $doc['document_thumbnail'])): ?>
                                                        <div class="mt-2">
                                                            <img src="uploads/projects/<?php echo htmlspecialchars($doc['document_thumbnail']); ?>" alt="Document Thumbnail" class="img-thumbnail" style="max-width: 100px;">
                                                            <p class="text-muted small mb-0 mt-1">Current (leave empty to keep)</p>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document File:</label>
                                                        <input class="form-control" type="file" name="document_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                                                        <small class="text-muted">Upload document file (PDF, DOC, XLS, PPT, ZIP)</small>
                                                        <?php if (!empty($doc['document_file']) && file_exists('uploads/projects/' . $doc['document_file'])): ?>
                                                        <div class="mt-2">
                                                            <a href="uploads/projects/<?php echo htmlspecialchars($doc['document_file']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="las la-file-download"></i> View Current File
                                                            </a>
                                                            <p class="text-muted small mb-0 mt-1">Current: <?php echo htmlspecialchars($doc['document_file']); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="card border mb-3 document-card">
                                                <div class="card-body">
                                                    <input type="hidden" name="doc_db_id[]" value="0">
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document ID (doc_id):</label>
                                                        <input class="form-control" type="text" name="doc_id[]" placeholder="Enter document ID">
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document Name:</label>
                                                        <input class="form-control" type="text" name="document_name[]" placeholder="Enter document name">
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document Thumbnail:</label>
                                                        <input class="form-control" type="file" name="document_thumbnail[]" accept="image/*">
                                                        <small class="text-muted">Upload document thumbnail image (JPG, PNG, GIF)</small>
                                                    </div>
                                                    
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Document File:</label>
                                                        <input class="form-control" type="file" name="document_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                                                        <small class="text-muted">Upload document file (PDF, DOC, XLS, PPT, ZIP)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDocument()">
                                        <i class="las la-plus"></i> Add Another Document
                                    </button>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Projects Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Project Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" <?php echo ($projectData['status'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="status">Active</label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Update Project</button>
                                    </div>
                                    <div class="form-group">
                                        <a href="projects_list" class="btn btn-danger w-100">Cancel</a>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Project Info</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-2"><strong>ID:</strong> <?php echo $projectId; ?></p>
                                    <p class="mb-2"><strong>Company ID:</strong> <?php echo htmlspecialchars($projectData['comp_id'] ?? 'N/A'); ?></p>
                                    <?php if (!empty($projectData['created_at'])): ?>
                                    <p class="mb-2"><strong>Created:</strong> <?php echo date('M d, Y', strtotime($projectData['created_at'])); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($projectData['updated_at'])): ?>
                                    <p class="mb-0"><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($projectData['updated_at'])); ?></p>
                                    <?php endif; ?>
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
// Initialize Quill Editor for Project Amenities
var projectAmenitiesQuill = new Quill('#project_amenities_editor', {
    theme: 'snow',
    placeholder: 'Enter project amenities (one per line or bullet points)'
});

// Load existing content
<?php if (!empty($projectData['project_amenities'])): ?>
projectAmenitiesQuill.root.innerHTML = <?php echo json_encode($projectData['project_amenities']); ?>;
<?php endif; ?>

// Sync Quill content to hidden textarea on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('project_amenities').value = projectAmenitiesQuill.root.innerHTML;
});

let documentCount = document.querySelectorAll('.document-card').length;

function addDocument() {
    documentCount++;
    const container = document.getElementById('documents-container');
    const newDocument = document.createElement('div');
    newDocument.className = 'card border mb-3 document-card';
    newDocument.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Document ${documentCount}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDocument(this)">
                    <i class="las la-times"></i> Remove
                </button>
            </div>
            
            <input type="hidden" name="doc_db_id[]" value="0">
            
            <div class="form-group mb-3">
                <label class="form-label">Document ID (doc_id):</label>
                <input class="form-control" type="text" name="doc_id[]" placeholder="Enter document ID">
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Document Name:</label>
                <input class="form-control" type="text" name="document_name[]" placeholder="Enter document name">
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Document Thumbnail:</label>
                <input class="form-control" type="file" name="document_thumbnail[]" accept="image/*">
                <small class="text-muted">Upload document thumbnail image (JPG, PNG, GIF)</small>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Document File:</label>
                <input class="form-control" type="file" name="document_file[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                <small class="text-muted">Upload document file (PDF, DOC, XLS, PPT, ZIP)</small>
            </div>
        </div>
    `;
    container.appendChild(newDocument);
}

function removeDocument(button) {
    const card = button.closest('.document-card');
    if (document.querySelectorAll('.document-card').length === 1) {
        card.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
        card.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
        card.querySelector('input[name="doc_db_id[]"]').value = '0';
    } else {
        card.remove();
    }
}
</script>

