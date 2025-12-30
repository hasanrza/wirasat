<?php 
include 'check_login.php';
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
                            <h4 class="page-title">Gallery - Videos</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Gallery</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Videos</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <form method="POST" action="#" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Video Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Video Fields</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <input type="hidden" name="comp_id" value="999999">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video ID:</label>
                                        <input class="form-control" type="text" name="video_id" id="video_id" placeholder="Enter video ID">
                                        <small class="text-muted">Auto-generated if left empty</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Title: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="video_title" id="video_title" placeholder="Enter video title" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Description:</label>
                                        <div id="video_description_editor" style="height: 150px;"></div>
                                        <textarea class="form-control d-none" name="video_description" id="video_description"></textarea>
                                        <small class="text-muted">Enter video description or details</small>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Video Source</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video URL:</label>
                                        <input class="form-control" type="url" name="video_url" id="video_url" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
                                        <small class="text-muted">YouTube, Vimeo, or other video platform URL</small>
                                    </div>
                                    
                                    <div class="text-center my-3">
                                        <span class="badge bg-secondary">OR</span>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Upload Video File:</label>
                                        <input class="form-control" type="file" name="video_file" id="video_file" accept="video/*">
                                        <small class="text-muted">Upload video file (MP4, AVI, MOV, etc.)</small>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Video Thumbnail</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Thumbnail Image:</label>
                                        <input class="form-control" type="file" name="video_thumbnail" id="video_thumbnail" accept="image/*">
                                        <small class="text-muted">Upload thumbnail/preview image for the video</small>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Video Settings</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Order:</label>
                                        <input class="form-control" type="number" name="video_order" id="video_order" placeholder="Enter display order" min="1" value="1">
                                        <small class="text-muted">Order in which video appears in gallery</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Date:</label>
                                        <input class="form-control" type="date" name="video_date" id="video_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Category:</label>
                                        <select class="form-select" name="video_category" id="video_category">
                                            <option value="">Select Category</option>
                                            <option value="company">Company Overview</option>
                                            <option value="projects">Projects</option>
                                            <option value="testimonials">Testimonials</option>
                                            <option value="events">Events</option>
                                            <option value="tutorials">Tutorials</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Video Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Video Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Video Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="video_status" name="video_status" checked>
                                            <label class="form-check-label" for="video_status">Active</label>
                                        </div>
                                        <small class="text-muted">Active videos will be visible in gallery</small>
                                    </div>
                                    
                                    <hr class="my-3">
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                            <i class="las la-save"></i> Save Video
                                        </button>
                                        <button type="reset" class="btn btn-secondary w-100 mb-2">
                                            <i class="las la-redo"></i> Reset
                                        </button>
                                        <a href="gallery_videos" class="btn btn-danger w-100">
                                            <i class="las la-times"></i> Cancel
                                        </a>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                            
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Quick Links</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <a href="gallery_pictures" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="las la-images"></i> Manage Pictures
                                    </a>
                                    <a href="gallery" class="btn btn-outline-secondary w-100">
                                        <i class="las la-th"></i> View All Gallery
                                    </a>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-4-->
                    </div><!--end row-->
                </form>
                
                <!-- Videos List -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Videos List</h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0" id="videosTable">
                                        <thead>
                                            <tr>
                                                <th>Video ID</th>
                                                <th>Video Title</th>
                                                <th>Video Status</th>
                                                <th>Video Order</th>
                                                <th>Video Date</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Sample Data - Replace with dynamic data from database -->
                                            <tr>
                                                <td>VID001</td>
                                                <td>Company Introduction Video</td>
                                                <td>
                                                    <span class="badge bg-success">Active</span>
                                                </td>
                                                <td>1</td>
                                                <td>2024-01-15</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-soft-primary" title="Edit" onclick="editVideo('VID001')">
                                                        <i class="las la-pen"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-info" title="View" onclick="viewVideo('VID001')">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-danger" title="Delete" onclick="deleteVideo('VID001')">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>VID002</td>
                                                <td>Project Tour Video</td>
                                                <td>
                                                    <span class="badge bg-success">Active</span>
                                                </td>
                                                <td>2</td>
                                                <td>2024-01-20</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-soft-primary" title="Edit" onclick="editVideo('VID002')">
                                                        <i class="las la-pen"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-info" title="View" onclick="viewVideo('VID002')">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-danger" title="Delete" onclick="deleteVideo('VID002')">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>VID003</td>
                                                <td>Customer Testimonials</td>
                                                <td>
                                                    <span class="badge bg-warning">Inactive</span>
                                                </td>
                                                <td>3</td>
                                                <td>2024-02-10</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-soft-primary" title="Edit" onclick="editVideo('VID003')">
                                                        <i class="las la-pen"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-info" title="View" onclick="viewVideo('VID003')">
                                                        <i class="las la-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-danger" title="Delete" onclick="deleteVideo('VID003')">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                </td>
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

<?php include 'include/footer_cdn.php'; ?>

<script>
// Initialize Quill Editor for Video Description
var videoDescriptionQuill = new Quill('#video_description_editor', {
    theme: 'snow',
    placeholder: 'Enter video description or details'
});

// Sync Quill content to hidden textarea on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('video_description').value = videoDescriptionQuill.root.innerHTML;
});

// Initialize DataTables if available
if (typeof $.fn.DataTable !== 'undefined') {
    $('#videosTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "order": [[3, "asc"]], // Sort by order column
        "language": {
            "search": "Search Videos:",
            "lengthMenu": "Show _MENU_ videos per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ videos",
            "infoEmpty": "No videos available",
            "emptyTable": "No videos in gallery"
        }
    });
}

// Function to edit video
function editVideo(videoId) {
    // Implement edit functionality - load video data into form
    console.log('Edit video:', videoId);
    // You would typically make an AJAX call here to fetch video data
    // and populate the form fields
}

// Function to view video
function viewVideo(videoId) {
    // Implement view functionality
    console.log('View video:', videoId);
    // You could open a modal or redirect to a view page
}

// Function to delete video
function deleteVideo(videoId) {
    if (confirm('Are you sure you want to delete this video?')) {
        // Implement delete functionality
        console.log('Delete video:', videoId);
        // You would typically make an AJAX call here to delete the video
    }
}
</script>

