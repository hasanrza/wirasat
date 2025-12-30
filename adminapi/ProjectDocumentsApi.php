<?php
/**
 * Project Documents API Endpoint
 * GET /adminapi/project-documents.php
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

class ProjectDocumentsApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all project documents
     */
    public function getAll() {
        try {
            $projectId = $_GET['project_id'] ?? null;
            
            $query = "SELECT * FROM project_documents WHERE 1=1";
            
            if ($projectId) {
                $query .= " AND project_id = :project_id";
            }
            
            $query .= " ORDER BY display_order ASC, id ASC";
            
            $stmt = $this->db->prepare($query);
            if ($projectId) {
                $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            }
            $stmt->execute();
            $documents = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['document_thumbnail', 'document_file'];
            $documents = $this->formatFilePaths($documents, $fileFields);
            
            $this->sendSuccess($documents, 'Project documents retrieved successfully');
        } catch (Exception $e) {
            error_log("Project Documents API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve project documents', 500);
        }
    }
    
    /**
     * Get document by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM project_documents WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $document = $stmt->fetch();
            
            if (!$document) {
                $this->sendError('Project document not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['document_thumbnail', 'document_file'];
            $document = $this->formatFilePaths($document, $fileFields);
            
            $this->sendSuccess($document, 'Project document retrieved successfully');
        } catch (Exception $e) {
            error_log("Project Documents API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve project document', 500);
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
$api = new ProjectDocumentsApi();
$api->handleRequest();
?>

