<?php
/**
 * CEO Message API Endpoint
 * GET /adminapi/ceo-message.php
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

class CeoMessageApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get CEO message data
     */
    public function get() {
        try {
            $query = "SELECT * FROM ceo_message ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $ceoMessage = $stmt->fetch();
            
            if (!$ceoMessage) {
                $this->sendError('CEO message not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['ceo_picture_1', 'ceo_picture_2'];
            $ceoMessage = $this->formatFilePaths($ceoMessage, $fileFields);
            
            $this->sendSuccess($ceoMessage, 'CEO message retrieved successfully');
        } catch (Exception $e) {
            error_log("CEO Message API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve CEO message', 500);
        }
    }
    
    /**
     * Get CEO message by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM ceo_message WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $ceoMessage = $stmt->fetch();
            
            if (!$ceoMessage) {
                $this->sendError('CEO message not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['ceo_picture_1', 'ceo_picture_2'];
            $ceoMessage = $this->formatFilePaths($ceoMessage, $fileFields);
            
            $this->sendSuccess($ceoMessage, 'CEO message retrieved successfully');
        } catch (Exception $e) {
            error_log("CEO Message API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve CEO message', 500);
        }
    }
    
    /**
     * Get all CEO message records
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM ceo_message ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $records = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['ceo_picture_1', 'ceo_picture_2'];
            $records = $this->formatFilePaths($records, $fileFields);
            
            $this->sendSuccess($records, 'CEO message records retrieved successfully');
        } catch (Exception $e) {
            error_log("CEO Message API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve CEO message records', 500);
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
$api = new CeoMessageApi();
$api->handleRequest();
?>

