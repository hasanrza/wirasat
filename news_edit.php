<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and classes
$database = Database::getInstance();
$db = $database->getConnection();

$newsUpdates = new NewsUpdates($db);
$formHandler = new FormHandler();
$controller = new NewsUpdatesController($newsUpdates, $formHandler);

// Get news ID from URL parameter - required for edit page
$newsId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Redirect to list if no ID provided
if (!$newsId) {
    header('Location: news_list');
    exit;
}

// Load existing data
$controller->loadNewsData($newsId);
$newsData = $controller->getNewsData();

// Redirect if news not found
if (!$newsData) {
    header('Location: news_list');
    exit;
}

// Process form submissions (update only)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->processRequest($newsId)) {
        // Redirect to list after successful update
        header('Location: news_list');
        exit;
    }
    // Reload data after failed submission to show errors
    $controller->loadNewsData($newsId);
    $newsData = $controller->getNewsData();
}

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
                            <h4 class="page-title">Edit News</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="news_list">News Updates</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Edit News</li>
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
                        <!-- News Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Edit News Update</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">News Text: <span class="text-danger">*</span></label>
                                        <div id="news_text_editor" style="height: 300px;"></div>
                                        <textarea class="form-control d-none" name="news_text" id="news_text"></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">News Image:</label>
                                        <input class="form-control" type="file" name="news_image" id="news_image" accept="image/*">
                                        <small class="text-muted">Upload news image (JPG, PNG, GIF)</small>
                                        <?php if (!empty($newsData['news_image']) && file_exists('uploads/news/' . $newsData['news_image'])): ?>
                                        <div class="mt-2">
                                            <img src="uploads/news/<?php echo htmlspecialchars($newsData['news_image']); ?>" alt="News Image" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted small mb-0 mt-1">Current image (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">News Video:</label>
                                        <input class="form-control" type="file" name="news_video" id="news_video" accept="video/*">
                                        <small class="text-muted">Upload news video (MP4, WebM, AVI)</small>
                                        <?php if (!empty($newsData['news_video']) && file_exists('uploads/news/' . $newsData['news_video'])): ?>
                                        <div class="mt-2">
                                            <video controls style="max-width: 300px;">
                                                <source src="uploads/news/<?php echo htmlspecialchars($newsData['news_video']); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <p class="text-muted small mb-0 mt-1">Current video (leave empty to keep)</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">YouTube Video Link:</label>
                                        <input class="form-control" type="url" name="youtube_link" id="youtube_link" 
                                               value="<?php echo htmlspecialchars($newsData['youtube_link'] ?? ''); ?>"
                                               placeholder="https://www.youtube.com/watch?v=...">
                                        <small class="text-muted">Enter YouTube video URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)</small>
                                        <?php if (!empty($newsData['youtube_link'])): ?>
                                        <div class="mt-2">
                                            <a href="<?php echo htmlspecialchars($newsData['youtube_link']); ?>" target="_blank" class="btn btn-sm btn-danger">
                                                <i class="fab fa-youtube me-1"></i> View Current Video
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- News Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">News Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status" name="status" <?php echo ($newsData['status'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="status">Active</label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Update News</button>
                                    </div>
                                    <div class="form-group">
                                        <a href="news_list" class="btn btn-danger w-100">Cancel</a>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">News Info</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-2"><strong>ID:</strong> <?php echo $newsId; ?></p>
                                    <p class="mb-2"><strong>News ID:</strong> <?php echo htmlspecialchars($newsData['news_id'] ?? 'N/A'); ?></p>
                                    <?php if (!empty($newsData['created_at'])): ?>
                                    <p class="mb-2"><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($newsData['created_at'])); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($newsData['updated_at'])): ?>
                                    <p class="mb-0"><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($newsData['updated_at'])); ?></p>
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
// Initialize Quill Editor for News Text
var newsTextQuill = new Quill('#news_text_editor', {
    theme: 'snow',
    placeholder: 'Enter news text content...'
});

// Load existing content
<?php if (!empty($newsData['news_text'])): ?>
newsTextQuill.root.innerHTML = <?php echo json_encode($newsData['news_text']); ?>;
<?php endif; ?>

// Sync Quill content to hidden textarea on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    // Get the content and sync to textarea
    var content = newsTextQuill.root.innerHTML;
    document.getElementById('news_text').value = content;
    
    // Validate - check if content is empty (just empty tags)
    var textContent = newsTextQuill.getText().trim();
    if (textContent.length === 0) {
        e.preventDefault();
        alert('News text is required. Please enter some content.');
        return false;
    }
});
</script>

