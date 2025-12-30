<?php
/**
 * Company Profile API Endpoint
 * GET /adminapi/company-profile.php
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

class CompanyProfileApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all company profiles
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM company_profile ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $profiles = $stmt->fetchAll();
            
            // If no profiles found, return empty array with success message
            if (empty($profiles)) {
                $this->sendSuccess([], 'No company profiles found');
                return;
            }
            
            // Format file paths
            $fileFields = ['company_logo', 'company_background', 'footer_image'];
            $profiles = $this->formatFilePaths($profiles, $fileFields);
            
            $this->sendSuccess($profiles, 'Company profiles retrieved successfully');
        } catch (PDOException $e) {
            error_log("Company Profile API PDO Error: " . $e->getMessage());
            $this->sendError('Database error: ' . $e->getMessage(), 500);
        } catch (Exception $e) {
            error_log("Company Profile API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve company profiles: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get company profile by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM company_profile WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $profile = $stmt->fetch();
            
            if (!$profile) {
                $this->sendError('Company profile not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['company_logo', 'company_background', 'footer_image'];
            $profile = $this->formatFilePaths($profile, $fileFields);
            
            $this->sendSuccess($profile, 'Company profile retrieved successfully');
        } catch (Exception $e) {
            error_log("Company Profile API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve company profile', 500);
        }
    }
    
    /**
     * Get company profile by comp_id
     * @param string $compId
     */
    public function getByCompId($compId = '999999') {
        try {
            $query = "SELECT * FROM company_profile WHERE comp_id = :comp_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':comp_id', $compId);
            $stmt->execute();
            $profile = $stmt->fetch();
            
            if (!$profile) {
                $this->sendError('Company profile not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['company_logo', 'company_background', 'footer_image'];
            $profile = $this->formatFilePaths($profile, $fileFields);
            
            $this->sendSuccess($profile, 'Company profile retrieved successfully');
        } catch (Exception $e) {
            error_log("Company Profile API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve company profile', 500);
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
        $compId = $_GET['comp_id'] ?? null;
        
        if ($id) {
            $this->getById($id);
        } elseif ($compId) {
            $this->getByCompId($compId);
        } else {
            $this->getAll();
        }
    }
}

// Initialize and handle request
$api = new CompanyProfileApi();
$api->handleRequest();
?>

