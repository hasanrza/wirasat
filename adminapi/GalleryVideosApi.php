<?php
/**
 * Gallery Videos API Endpoint
 * GET /adminapi/gallery-videos.php
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

class GalleryVideosApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all gallery videos
     */
    public function getAll() {
        try {
            $activeOnly = isset($_GET['active_only']) && $_GET['active_only'] == '1';
            
            $query = "SELECT * FROM gallery_videos";
            if ($activeOnly) {
                $query .= " WHERE status = 1";
            }
            $query .= " ORDER BY display_order ASC, created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $videos = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['video_thumbnail'];
            $videos = $this->formatFilePaths($videos, $fileFields);
            
            $this->sendSuccess($videos, 'Gallery videos retrieved successfully');
        } catch (Exception $e) {
            error_log("Gallery Videos API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve gallery videos', 500);
        }
    }
    
    /**
     * Get video by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM gallery_videos WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $video = $stmt->fetch();
            
            if (!$video) {
                $this->sendError('Video not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['video_thumbnail'];
            $video = $this->formatFilePaths($video, $fileFields);
            
            $this->sendSuccess($video, 'Video retrieved successfully');
        } catch (Exception $e) {
            error_log("Gallery Videos API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve video', 500);
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
$api = new GalleryVideosApi();
$api->handleRequest();
?>

