<?php
/**
 * Our Services API Endpoint
 * GET /adminapi/our-services.php
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

class OurServicesApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all services
     */
    public function getAll() {
        try {
            $activeOnly = isset($_GET['active_only']) && $_GET['active_only'] == '1';
            
            $query = "SELECT * FROM our_services";
            if ($activeOnly) {
                $query .= " WHERE status = 1";
            }
            $query .= " ORDER BY display_order ASC, id DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $services = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['service_image'];
            $services = $this->formatFilePaths($services, $fileFields);
            
            $this->sendSuccess($services, 'Services retrieved successfully');
        } catch (Exception $e) {
            error_log("Our Services API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve services', 500);
        }
    }
    
    /**
     * Get service by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM our_services WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $service = $stmt->fetch();
            
            if (!$service) {
                $this->sendError('Service not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['service_image'];
            $service = $this->formatFilePaths($service, $fileFields);
            
            $this->sendSuccess($service, 'Service retrieved successfully');
        } catch (Exception $e) {
            error_log("Our Services API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve service', 500);
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
$api = new OurServicesApi();
$api->handleRequest();
?>

