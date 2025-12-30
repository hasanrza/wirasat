<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$ceoMessage = new CeoMessage($db);
$formHandler = new FormHandler();
$controller = new CeoMessageController($ceoMessage, $formHandler);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRequest();
    // Reload data after submission
    $controller->loadCeoData();
} else {
    // Load existing data
    $controller->loadCeoData();
}

// Get CEO message data
$ceoData = $controller->getCeoData();

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
                            <h4 class="page-title">CEO Message</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">CEO Message</li>
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
                        <!-- CEO Message Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">CEO Message Fields</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">CEO Picture 1:</label>
                                        <input class="form-control" type="file" name="ceo_picture_1" id="ceo_picture_1" accept="image/*">
                                        <small class="text-muted">Upload CEO picture 1</small>
                                        <?php if (!empty($ceoData['ceo_picture_1']) && file_exists('uploads/ceo/' . $ceoData['ceo_picture_1'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/ceo/<?php echo htmlspecialchars($ceoData['ceo_picture_1']); ?>" alt="CEO Picture 1" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">CEO Picture 2:</label>
                                        <input class="form-control" type="file" name="ceo_picture_2" id="ceo_picture_2" accept="image/*">
                                        <small class="text-muted">Upload CEO picture 2</small>
                                        <?php if (!empty($ceoData['ceo_picture_2']) && file_exists('uploads/ceo/' . $ceoData['ceo_picture_2'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/ceo/<?php echo htmlspecialchars($ceoData['ceo_picture_2']); ?>" alt="CEO Picture 2" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">CEO Message Paragraph 1: <span class="text-danger">*</span></label>
                                        <div id="ceo_message_paragraph_1_editor" style="height: 250px;"></div>
                                        <textarea class="form-control d-none" name="ceo_message_paragraph_1" id="ceo_message_paragraph_1"></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">CEO Message Paragraph 2:</label>
                                        <div id="ceo_message_paragraph_2_editor" style="height: 250px;"></div>
                                        <textarea class="form-control d-none" name="ceo_message_paragraph_2" id="ceo_message_paragraph_2"></textarea>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- CEO Message Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">CEO Message Actions</h4>
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
// Initialize Quill Editors for CEO Message Paragraphs
var ceoMessageQuill1 = new Quill('#ceo_message_paragraph_1_editor', {
    theme: 'snow',
    placeholder: 'Enter CEO message paragraph 1'
});

var ceoMessageQuill2 = new Quill('#ceo_message_paragraph_2_editor', {
    theme: 'snow',
    placeholder: 'Enter CEO message paragraph 2'
});

// Load existing content if available
<?php if (!empty($ceoData['ceo_message_paragraph_1'])): ?>
ceoMessageQuill1.root.innerHTML = <?php echo json_encode($ceoData['ceo_message_paragraph_1']); ?>;
<?php endif; ?>

<?php if (!empty($ceoData['ceo_message_paragraph_2'])): ?>
ceoMessageQuill2.root.innerHTML = <?php echo json_encode($ceoData['ceo_message_paragraph_2']); ?>;
<?php endif; ?>

// Sync Quill content to hidden textareas on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('ceo_message_paragraph_1').value = ceoMessageQuill1.root.innerHTML;
    document.getElementById('ceo_message_paragraph_2').value = ceoMessageQuill2.root.innerHTML;
});
</script>

