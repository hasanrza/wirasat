<?php
/**
 * Projects API Endpoint
 * GET /adminapi/projects.php
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

class ProjectsApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all projects
     */
    public function getAll() {
        try {
            $activeOnly = isset($_GET['active_only']) && $_GET['active_only'] == '1';
            $compId = $_GET['comp_id'] ?? null;
            
            $query = "SELECT * FROM projects WHERE 1=1";
            
            if ($activeOnly) {
                $query .= " AND status = 1";
            }
            
            if ($compId) {
                $query .= " AND comp_id = :comp_id";
            }
            
            $query .= " ORDER BY id DESC";
            
            $stmt = $this->db->prepare($query);
            if ($compId) {
                $stmt->bindParam(':comp_id', $compId);
            }
            $stmt->execute();
            $projects = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['project_map_thumbnail', 'project_map_full', 'project_payment_plan', 'project_amenities_image'];
            $projects = $this->formatFilePaths($projects, $fileFields);
            
            // Get documents for each project
            foreach ($projects as &$project) {
                $project['documents'] = $this->getProjectDocuments($project['id']);
            }
            
            $this->sendSuccess($projects, 'Projects retrieved successfully');
        } catch (Exception $e) {
            error_log("Projects API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve projects', 500);
        }
    }
    
    /**
     * Get project by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM projects WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $project = $stmt->fetch();
            
            if (!$project) {
                $this->sendError('Project not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['project_map_thumbnail', 'project_map_full', 'project_payment_plan', 'project_amenities_image'];
            $project = $this->formatFilePaths($project, $fileFields);
            
            // Get documents
            $project['documents'] = $this->getProjectDocuments($id);
            
            $this->sendSuccess($project, 'Project retrieved successfully');
        } catch (Exception $e) {
            error_log("Projects API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve project', 500);
        }
    }
    
    /**
     * Get project documents
     * @param int $projectId
     * @return array
     */
    private function getProjectDocuments($projectId) {
        try {
            $query = "SELECT * FROM project_documents 
                     WHERE project_id = :project_id 
                     ORDER BY display_order ASC, id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            $documents = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['document_thumbnail', 'document_file'];
            $documents = $this->formatFilePaths($documents, $fileFields);
            
            return $documents;
        } catch (Exception $e) {
            error_log("Get Project Documents Error: " . $e->getMessage());
            return [];
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
$api = new ProjectsApi();
$api->handleRequest();
?>

