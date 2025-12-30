<?php 
include 'check_login.php';
require_once 'config/autoload.php';

$database = Database::getInstance();
$db = $database->getConnection();
$galleryPictures = new GalleryPictures($db);
$galleryVideos = new GalleryVideos($db);
$galleryController = new GalleryController($galleryPictures, $galleryVideos);

$stats = $galleryController->getStatistics();

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
                            <h4 class="page-title">Gallery Overview</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Content</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Gallery</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <!-- Gallery Statistics -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md rounded-circle bg-primary-subtle">
                                            <i class="las la-video font-48 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0 fw-bold"><?php echo $stats['total_videos']; ?></h4>
                                        <p class="text-muted mb-0">Total Videos</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="gallery_videos" class="btn btn-sm btn-primary">
                                            Manage Videos <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-md rounded-circle bg-success-subtle">
                                            <i class="las la-images font-48 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0 fw-bold"><?php echo $stats['total_pictures']; ?></h4>
                                        <p class="text-muted mb-0">Total Pictures</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="gallery_pictures" class="btn btn-sm btn-success">
                                            Manage Pictures <i class="las la-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <!-- Quick Access Cards -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Recent Videos</h4>
                                    <a href="gallery_videos" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Company Introduction Video</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>2024-01-15</td>
                                            </tr>
                                            <tr>
                                                <td>Project Tour Video</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>2024-01-20</td>
                                            </tr>
                                            <tr>
                                                <td>Customer Testimonials</td>
                                                <td><span class="badge bg-warning">Inactive</span></td>
                                                <td>2024-02-10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Recent Pictures</h4>
                                    <a href="gallery_pictures" class="btn btn-sm btn-outline-success">View All</a>
                                </div>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Office Building Exterior</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>2024-01-10</td>
                                            </tr>
                                            <tr>
                                                <td>Conference Room</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>2024-01-12</td>
                                            </tr>
                                            <tr>
                                                <td>Team Meeting</td>
                                                <td><span class="badge bg-success">Active</span></td>
                                                <td>2024-01-18</td>
                                            </tr>
                                            <tr>
                                                <td>Project Site View</td>
                                                <td><span class="badge bg-warning">Inactive</span></td>
                                                <td>2024-02-05</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
                                        
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

<!-- Add Video Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVideoModalLabel">Add Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Video ID:</label>
                        <input class="form-control" type="text" name="video_id" id="video_id" placeholder="Enter video ID" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video Title: <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="video_title" id="video_title" placeholder="Enter video title" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video URL/File:</label>
                        <input class="form-control" type="text" name="video_url" id="video_url" placeholder="Enter video URL (YouTube, Vimeo, etc.)">
                        <small class="text-muted">Or</small>
                        <input class="form-control mt-2" type="file" name="video_file" id="video_file" accept="video/*">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video Thumbnail:</label>
                        <input class="form-control" type="file" name="video_thumbnail" id="video_thumbnail" accept="image/*">
                        <small class="text-muted">Upload thumbnail image</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video Order:</label>
                        <input class="form-control" type="number" name="video_order" id="video_order" placeholder="Enter display order" min="1" value="1">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video Status:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="video_status" name="video_status" checked>
                            <label class="form-check-label" for="video_status">Active</label>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Video Date:</label>
                        <input class="form-control" type="date" name="video_date" id="video_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Video</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Picture Modal -->
<div class="modal fade" id="addPictureModal" tabindex="-1" aria-labelledby="addPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPictureModalLabel">Add Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Picture ID:</label>
                        <input class="form-control" type="text" name="pic_id" id="pic_id" placeholder="Enter picture ID" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture Title: <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="pic_title" id="pic_title" placeholder="Enter picture title" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture File: <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" name="pic_file" id="pic_file" accept="image/*" required>
                        <small class="text-muted">Upload image file (JPG, PNG, GIF, etc.)</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture Description:</label>
                        <textarea class="form-control" name="pic_description" id="pic_description" rows="3" placeholder="Enter picture description"></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture Order:</label>
                        <input class="form-control" type="number" name="pic_order" id="pic_order" placeholder="Enter display order" min="1" value="1">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture Status:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pic_status" name="pic_status" checked>
                            <label class="form-check-label" for="pic_status">Active</label>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Picture Date:</label>
                        <input class="form-control" type="date" name="pic_date" id="pic_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Picture</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'include/footer_cdn.php'; ?>

<script>
// Initialize DataTables if available
if (typeof $.fn.DataTable !== 'undefined') {
    $('#videosTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "language": {
            "search": "Search Videos:",
            "lengthMenu": "Show _MENU_ videos per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ videos",
            "infoEmpty": "No videos available",
            "emptyTable": "No videos in gallery"
        }
    });

    $('#picturesTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "language": {
            "search": "Search Pictures:",
            "lengthMenu": "Show _MENU_ pictures per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ pictures",
            "infoEmpty": "No pictures available",
            "emptyTable": "No pictures in gallery"
        }
    });
}
</script>

