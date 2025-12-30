<?php 
include 'check_login.php';
require_once 'config/autoload.php';

// Initialize database and objects
$database = Database::getInstance();
$db = $database->getConnection();

$newsUpdates = new NewsUpdates($db);
$formHandler = new FormHandler();
$controller = new NewsUpdatesController($newsUpdates, $formHandler);

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $newsId = (int)$_GET['id'];
    $controller->deleteNews($newsId);
    header('Location: news_list');
    exit;
}

// Get all news
$allNews = $controller->getAllNews();

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
                            <h4 class="page-title">News Updates</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Settings</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">News Updates</li>
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
                                <h4 class="card-title">All News Updates</h4>
                                <a href="news_add" class="btn btn-primary btn-sm">
                                    <i class="las la-plus"></i> Add New News
                                </a>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <?php if (empty($allNews)): ?>
                                <div class="alert alert-info" role="alert">
                                    <strong>No news found.</strong> <a href="news_add">Create your first news update</a>
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Media</th>
                                                <th class="align-middle">News Text</th>
                                                <th class="align-middle">Created</th>
                                                <th class="align-middle">Status</th>
                                                <th class="align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($allNews as $index => $news): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if (!empty($news['news_image']) && file_exists('uploads/news/' . $news['news_image'])): ?>
                                                        <img src="uploads/news/<?php echo htmlspecialchars($news['news_image']); ?>" alt="News Image" style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                                                        <?php endif; ?>
                                                        <?php if (!empty($news['news_video']) && file_exists('uploads/news/' . $news['news_video'])): ?>
                                                        <span class="badge bg-info"><i class="las la-video"></i> Video</span>
                                                        <?php endif; ?>
                                                        <?php if (empty($news['news_image']) && empty($news['news_video'])): ?>
                                                        <span class="badge bg-light text-dark">No Media</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="m-0 text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?php echo htmlspecialchars(substr(strip_tags($news['news_text']), 0, 80)) . '...'; ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($news['created_at'])); ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($news['status'] == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="news_edit?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="las la-edit"></i> Edit
                                                        </a>
                                                        <a href="news_list?action=delete&id=<?php echo $news['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this news? This action cannot be undone.');" title="Delete">
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

                <!-- News Stats Cards -->
                <?php if (!empty($allNews)): ?>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                <i class="las la-newspaper"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total News</p>
                                        <h4 class="mb-0"><?php echo count($allNews); ?></h4>
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
                                        <p class="text-muted fw-medium mb-2">Active News</p>
                                        <h4 class="mb-0"><?php echo count(array_filter($allNews, function($n) { return $n['status'] == 1; })); ?></h4>
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
                                        <p class="text-muted fw-medium mb-2">Inactive News</p>
                                        <h4 class="mb-0"><?php echo count(array_filter($allNews, function($n) { return $n['status'] == 0; })); ?></h4>
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

