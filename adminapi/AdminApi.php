<?php
/**
 * Admin API Endpoint
 * GET /adminapi/admin.php
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

class AdminApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all admins
     */
    public function getAll() {
        try {
            $query = "SELECT id, fname, lname, email, created_at, updated_at 
                     FROM admin 
                     ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $admins = $stmt->fetchAll();
            
            // Remove sensitive data
            foreach ($admins as &$admin) {
                unset($admin['password']);
            }
            
            $this->sendSuccess($admins, 'Admins retrieved successfully');
        } catch (Exception $e) {
            error_log("Admin API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve admins', 500);
        }
    }
    
    /**
     * Get admin by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT id, fname, lname, email, created_at, updated_at 
                     FROM admin 
                     WHERE id = :id 
                     LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $admin = $stmt->fetch();
            
            if (!$admin) {
                $this->sendError('Admin not found', 404);
                return;
            }
            
            // Remove sensitive data
            unset($admin['password']);
            
            $this->sendSuccess($admin, 'Admin retrieved successfully');
        } catch (Exception $e) {
            error_log("Admin API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve admin', 500);
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
$api = new AdminApi();
$api->handleRequest();
?>

