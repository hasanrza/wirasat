<?php
/**
 * Gallery Pictures API Endpoint
 * GET /adminapi/gallery-pictures.php
 */

define('API_ACCESS', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/BaseApi.php';
require_once __DIR__ . '/../config/autoload.php';

// Set CORS headers
AuthMiddleware::setCorsHeaders();

// Validate token
if (!AuthMiddleware::validateToken()) {
    exit;
}

class GalleryPicturesApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all gallery pictures
     */
    public function getAll() {
        try {
            $activeOnly = isset($_GET['active_only']) && $_GET['active_only'] == '1';
            
            $query = "SELECT * FROM gallery_pictures";
            if ($activeOnly) {
                $query .= " WHERE status = 1";
            }
            $query .= " ORDER BY display_order ASC, created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $pictures = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['picture_file', 'picture_thumbnail'];
            $pictures = $this->formatFilePaths($pictures, $fileFields);
            
            $this->sendSuccess($pictures, 'Gallery pictures retrieved successfully');
        } catch (Exception $e) {
            error_log("Gallery Pictures API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve gallery pictures', 500);
        }
    }
    
    /**
     * Get picture by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM gallery_pictures WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $picture = $stmt->fetch();
            
            if (!$picture) {
                $this->sendError('Picture not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['picture_file', 'picture_thumbnail'];
            $picture = $this->formatFilePaths($picture, $fileFields);
            
            $this->sendSuccess($picture, 'Picture retrieved successfully');
        } catch (Exception $e) {
            error_log("Gallery Pictures API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve picture', 500);
        }
    }
    
    /**
     * Handle request
     */
    public function handleRequest() {
        $method = $this->getMethod();
        
        if ($method !== 'GET') {
            $this->sendError('Method not allowed', 405);
            return;
        }
        
        $id = $this->getId();
        
        if ($id) {
            $this->getById($id);
        } else {
            $this->getAll();
        }
    }
}

// Initialize and handle request
$api = new GalleryPicturesApi();
$api->handleRequest();
?>

