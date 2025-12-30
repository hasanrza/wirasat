<?php
/**
 * About Us API Endpoint
 * GET /adminapi/about-us.php
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

class AboutUsApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get about us data
     */
    public function get() {
        try {
            $query = "SELECT * FROM about_us ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $aboutUs = $stmt->fetch();
            
            if (!$aboutUs) {
                $this->sendError('About us data not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['about_us_video'];
            $aboutUs = $this->formatFilePaths($aboutUs, $fileFields);
            
            $this->sendSuccess($aboutUs, 'About us data retrieved successfully');
        } catch (Exception $e) {
            error_log("About Us API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve about us data', 500);
        }
    }
    
    /**
     * Get about us by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM about_us WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $aboutUs = $stmt->fetch();
            
            if (!$aboutUs) {
                $this->sendError('About us data not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['about_us_video'];
            $aboutUs = $this->formatFilePaths($aboutUs, $fileFields);
            
            $this->sendSuccess($aboutUs, 'About us data retrieved successfully');
        } catch (Exception $e) {
            error_log("About Us API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve about us data', 500);
        }
    }
    
    /**
     * Get all about us records
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM about_us ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $records = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['about_us_video'];
            $records = $this->formatFilePaths($records, $fileFields);
            
            $this->sendSuccess($records, 'About us records retrieved successfully');
        } catch (Exception $e) {
            error_log("About Us API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve about us records', 500);
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
            $this->get();
        }
    }
}

// Initialize and handle request
$api = new AboutUsApi();
$api->handleRequest();
?>

