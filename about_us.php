<?php 
include 'check_login.php';
require_once 'config/autoload.php';

$database = Database::getInstance();
$db = $database->getConnection();

$aboutObj = new AboutUs($db);
$formHandler = new FormHandler();
$aboutController = new AboutUsController($aboutObj, $formHandler);

// Load existing data
$aboutData = $aboutController->loadAboutData();

// Process POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aboutController->processRequest();
    // reload data after save
    $aboutData = $aboutController->loadAboutData();
}

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
                            <h4 class="page-title">About Us</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">About Us</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <?php if ($formHandler->getFirstSuccess()): ?>
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?php echo htmlspecialchars($formHandler->getFirstSuccess()); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($formHandler->getFirstError()): ?>
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo htmlspecialchars($formHandler->getFirstError()); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" action="about_us">
                    <div class="row">
                        <!-- About Us Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">About Us Fields</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    <div class="form-group mb-3">
                                        <label class="form-label">About Us Paragraph: <span class="text-danger">*</span></label>
                                        <div id="about_us_paragraph_editor" style="height: 300px;"><?php echo $aboutData ? htmlspecialchars_decode($aboutData['about_us_paragraph']) : ''; ?></div>
                                        <textarea class="form-control d-none" name="about_us_paragraph" id="about_us_paragraph"><?php echo $aboutData ? htmlspecialchars($aboutData['about_us_paragraph']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">About Us Video (YouTube URL):</label>
                                        <input class="form-control" type="url" name="about_us_video" id="about_us_video" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo htmlspecialchars($aboutData['about_us_video'] ?? ''); ?>">
                                        <small class="text-muted">Enter the full YouTube URL for the about us video</small>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- About Us Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">About Us Actions</h4>
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
    // Initialize Quill Editor for About Us Paragraph (safe init)
    (function() {
        var container = document.getElementById('about_us_paragraph_editor');
        if (!container) {
            console.error('Quill container not found: #about_us_paragraph_editor');
            return;
        }

        var aboutUsQuill = null;
        try {
            aboutUsQuill = new Quill(container, {
                theme: 'snow',
                placeholder: 'Enter about us paragraph'
            });
        } catch (err) {
            console.error('Failed to initialize Quill:', err);
            return;
        }

        // Preload content into editor if textarea has value
        var hiddenTextarea = document.getElementById('about_us_paragraph');
        if (hiddenTextarea && hiddenTextarea.value.trim() !== '') {
            // Set editor contents without escaping (assumes safe HTML from DB)
            aboutUsQuill.root.innerHTML = hiddenTextarea.value;
        }

        // Sync Quill content to hidden textarea on form submit with validation
        var form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            var text = aboutUsQuill.getText().trim();
            var html = aboutUsQuill.root.innerHTML.trim();

            // If editor is empty (no text), prevent submit and show message
            if (!text) {
                e.preventDefault();
                // Focus the editor for accessibility
                aboutUsQuill.focus();
                // Show a user-friendly alert (can be replaced with nicer UI)
                alert('Please enter the About Us content before saving.');
                return false;
            }

            // Put HTML into hidden textarea for server-side processing
            if (hiddenTextarea) {
                hiddenTextarea.value = html;
            }
        });
    })();
</script>

