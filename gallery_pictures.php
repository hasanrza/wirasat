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
                            <h4 class="page-title">Gallery - Pictures</h4>
                            <div class="">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index">Home</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item"><a href="#">Gallery</a>
                                    </li><!--end nav-item-->
                                    <li class="breadcrumb-item active">Pictures</li>
                                </ol>
                            </div>                                
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                
                <form method="POST" action="#" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Picture Fields - 8 columns -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Picture Fields</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <p class="mb-3">
                                        <span class="text-danger">Note:</span>
                                        <span class="text-muted">Fields marked as (*) are required fields.</span>
                                    </p>
                                    
                                    <input type="hidden" name="comp_id" value="999999">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture ID:</label>
                                        <input class="form-control" type="text" name="pic_id" id="pic_id" placeholder="Enter picture ID">
                                        <small class="text-muted">Auto-generated if left empty</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Title: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="pic_title" id="pic_title" placeholder="Enter picture title" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Description:</label>
                                        <div id="pic_description_editor" style="height: 150px;"></div>
                                        <textarea class="form-control d-none" name="pic_description" id="pic_description"></textarea>
                                        <small class="text-muted">Enter picture description or caption</small>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Picture Upload</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Upload Picture File: <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="pic_file" id="pic_file" accept="image/*" required>
                                        <small class="text-muted">Upload image file (JPG, PNG, GIF, WebP, etc.)</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Alt Text:</label>
                                        <input class="form-control" type="text" name="pic_alt_text" id="pic_alt_text" placeholder="Enter alt text for SEO">
                                        <small class="text-muted">Alternative text for image (important for SEO and accessibility)</small>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h5 class="mb-3">Picture Settings</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Order:</label>
                                        <input class="form-control" type="number" name="pic_order" id="pic_order" placeholder="Enter display order" min="1" value="1">
                                        <small class="text-muted">Order in which picture appears in gallery</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Date:</label>
                                        <input class="form-control" type="date" name="pic_date" id="pic_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Category:</label>
                                        <select class="form-select" name="pic_category" id="pic_category">
                                            <option value="">Select Category</option>
                                            <option value="office">Office</option>
                                            <option value="projects">Projects</option>
                                            <option value="team">Team</option>
                                            <option value="events">Events</option>
                                            <option value="products">Products</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tags:</label>
                                        <input class="form-control" type="text" name="pic_tags" id="pic_tags" placeholder="Enter tags separated by commas">
                                        <small class="text-muted">E.g., office, building, exterior, modern</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Photographer/Credit:</label>
                                        <input class="form-control" type="text" name="pic_credit" id="pic_credit" placeholder="Enter photographer name or credit">
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-lg-8-->
                        
                        <!-- Picture Actions - 4 columns -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Picture Actions</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Picture Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="pic_status" name="pic_status" checked>
                                            <label class="form-check-label" for="pic_status">Active</label>
                                        </div>
                                        <small class="text-muted">Active pictures will be visible in gallery</small>
                                    </div>
                                    
                                    <hr class="my-3">
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                            <i class="las la-save"></i> Save Picture
                                        </button>
                                        <button type="reset" class="btn btn-secondary w-100 mb-2">
                                            <i class="las la-redo"></i> Reset
                                        </button>
                                        <a href="gallery_pictures" class="btn btn-danger w-100">
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
                                    <a href="gallery_videos" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="las la-video"></i> Manage Videos
                                    </a>
                                    <a href="gallery" class="btn btn-outline-secondary w-100">
                                        <i class="las la-th"></i> View All Gallery
                                    </a>
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
// Initialize Quill Editor for Picture Description
var picDescriptionQuill = new Quill('#pic_description_editor', {
    theme: 'snow',
    placeholder: 'Enter picture description or caption'
});

// Sync Quill content to hidden textarea on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('pic_description').value = picDescriptionQuill.root.innerHTML;
});

// Initialize DataTables if available
if (typeof $.fn.DataTable !== 'undefined') {
    $('#picturesTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "order": [[3, "asc"]], // Sort by order column
        "language": {
            "search": "Search Pictures:",
            "lengthMenu": "Show _MENU_ pictures per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ pictures",
            "infoEmpty": "No pictures available",
            "emptyTable": "No pictures in gallery"
        }
    });
}

// Function to edit picture
function editPicture(picId) {
    // Implement edit functionality - load picture data into form
    console.log('Edit picture:', picId);
    // You would typically make an AJAX call here to fetch picture data
    // and populate the form fields
}

// Function to view picture
function viewPicture(picId) {
    // Implement view functionality
    console.log('View picture:', picId);
    // You could open a modal or redirect to a view page
}

// Function to delete picture
function deletePicture(picId) {
    if (confirm('Are you sure you want to delete this picture?')) {
        // Implement delete functionality
        console.log('Delete picture:', picId);
        // You would typically make an AJAX call here to delete the picture
    }
}
</script>

