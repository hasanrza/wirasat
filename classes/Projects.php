<?php
/**
 * Projects Class
 * Handles all projects-related operations
 */
class Projects {
    private $conn;
    private $table_name = "projects";
    private $documents_table = "project_documents";
    private $upload_dir = "uploads/projects/";
    
    // Project properties
    public $id;
    public $comp_id;
    public $project_id;
    public $project_name;
    public $project_map_thumbnail;
    public $project_map_full;
    public $project_payment_plan;
    public $project_amenities;
    public $project_amenities_image;
    public $status;
    
    /**
     * Constructor with database connection
     * @param PDO $db
     */
    public function __construct($db) {
        $this->conn = $db;
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }
    
    /**
     * Get all projects
     * @param bool $activeOnly
     * @return array
     */
    public function getAll($activeOnly = false) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($activeOnly) {
            $query .= " WHERE status = 1";
        }
        
        $query .= " ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get project by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Get projects by comp_id
     * @param string $compId
     * @return array
     */
    public function getByCompId($compId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE comp_id = :comp_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comp_id', $compId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Create new project
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                     SET comp_id = :comp_id,
                         project_id = :project_id,
                         project_name = :project_name,
                         project_map_thumbnail = :project_map_thumbnail,
                         project_map_full = :project_map_full,
                         project_payment_plan = :project_payment_plan,
                         project_amenities = :project_amenities,
                         project_amenities_image = :project_amenities_image,
                         status = :status";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':comp_id', $data['comp_id']);
            $stmt->bindParam(':project_id', $data['project_id']);
            $stmt->bindParam(':project_name', $data['project_name']);
            $stmt->bindParam(':project_map_thumbnail', $data['project_map_thumbnail']);
            $stmt->bindParam(':project_map_full', $data['project_map_full']);
            $stmt->bindParam(':project_payment_plan', $data['project_payment_plan']);
            $stmt->bindParam(':project_amenities', $data['project_amenities']);
            $stmt->bindParam(':project_amenities_image', $data['project_amenities_image']);
            $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch(PDOException $e) {
            error_log("Create Project Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update existing project
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET comp_id = :comp_id,
                         project_id = :project_id,
                         project_name = :project_name,
                         project_map_thumbnail = :project_map_thumbnail,
                         project_map_full = :project_map_full,
                         project_payment_plan = :project_payment_plan,
                         project_amenities = :project_amenities,
                         project_amenities_image = :project_amenities_image,
                         status = :status
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':comp_id', $data['comp_id']);
            $stmt->bindParam(':project_id', $data['project_id']);
            $stmt->bindParam(':project_name', $data['project_name']);
            $stmt->bindParam(':project_map_thumbnail', $data['project_map_thumbnail']);
            $stmt->bindParam(':project_map_full', $data['project_map_full']);
            $stmt->bindParam(':project_payment_plan', $data['project_payment_plan']);
            $stmt->bindParam(':project_amenities', $data['project_amenities']);
            $stmt->bindParam(':project_amenities_image', $data['project_amenities_image']);
            $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update Project Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete project
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            // Get project to delete associated files
            $project = $this->getById($id);
            
            if ($project) {
                // Delete project images
                $imageFields = ['project_map_thumbnail', 'project_map_full', 'project_payment_plan', 'project_amenities_image'];
                foreach ($imageFields as $field) {
                    if (!empty($project[$field])) {
                        $filePath = $this->upload_dir . $project[$field];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
                
                // Delete document thumbnails
                $documents = $this->getDocuments($id);
                foreach ($documents as $doc) {
                    if (!empty($doc['document_thumbnail'])) {
                        $filePath = $this->upload_dir . $doc['document_thumbnail'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    if (!empty($doc['document_file'])) {
                        $filePath = $this->upload_dir . $doc['document_file'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Delete Project Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Handle file upload
     * @param array $file
     * @param string $fieldName
     * @param string|null $oldFile
     * @return string|null
     */
    public function handleFileUpload($file, $fieldName, $oldFile = null) {
        if (!isset($file['name']) || empty($file['name'])) {
            return $oldFile;
        }
        
        // Delete old file if exists
        if ($oldFile && file_exists($this->upload_dir . $oldFile)) {
            unlink($this->upload_dir . $oldFile);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $fieldName . '_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $this->upload_dir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        
        return $oldFile;
    }
    
    /**
     * Get project documents
     * @param int $projectId
     * @return array
     */
    public function getDocuments($projectId) {
        $query = "SELECT * FROM " . $this->documents_table . " WHERE project_id = :project_id ORDER BY display_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Add project document
     * @param int $projectId
     * @param array $data
     * @return int|false
     */
    public function addDocument($projectId, $data) {
        try {
            $query = "INSERT INTO " . $this->documents_table . " 
                     SET project_id = :project_id,
                         doc_id = :doc_id,
                         document_thumbnail = :document_thumbnail,
                         document_name = :document_name,
                         document_file = :document_file,
                         display_order = :display_order";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->bindParam(':doc_id', $data['doc_id']);
            $stmt->bindParam(':document_thumbnail', $data['document_thumbnail']);
            $stmt->bindParam(':document_name', $data['document_name']);
            $stmt->bindParam(':document_file', $data['document_file']);
            $stmt->bindParam(':display_order', $data['display_order'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch(PDOException $e) {
            error_log("Add Project Document Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update project document
     * @param int $docId
     * @param array $data
     * @return bool
     */
    public function updateDocument($docId, $data) {
        try {
            $query = "UPDATE " . $this->documents_table . " 
                     SET doc_id = :doc_id,
                         document_thumbnail = :document_thumbnail,
                         document_name = :document_name,
                         document_file = :document_file,
                         display_order = :display_order
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $docId, PDO::PARAM_INT);
            $stmt->bindParam(':doc_id', $data['doc_id']);
            $stmt->bindParam(':document_thumbnail', $data['document_thumbnail']);
            $stmt->bindParam(':document_name', $data['document_name']);
            $stmt->bindParam(':document_file', $data['document_file']);
            $stmt->bindParam(':display_order', $data['display_order'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update Project Document Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete project document
     * @param int $docId
     * @return bool
     */
    public function deleteDocument($docId) {
        try {
            // Get document to delete files
            $query = "SELECT * FROM " . $this->documents_table . " WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $docId, PDO::PARAM_INT);
            $stmt->execute();
            $doc = $stmt->fetch();
            
            if ($doc) {
                if (!empty($doc['document_thumbnail'])) {
                    $filePath = $this->upload_dir . $doc['document_thumbnail'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                if (!empty($doc['document_file'])) {
                    $filePath = $this->upload_dir . $doc['document_file'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            $query = "DELETE FROM " . $this->documents_table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $docId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Delete Project Document Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete all documents for a project
     * @param int $projectId
     * @return bool
     */
    public function deleteAllDocuments($projectId) {
        try {
            // Get all documents to delete files
            $documents = $this->getDocuments($projectId);
            foreach ($documents as $doc) {
                if (!empty($doc['document_thumbnail'])) {
                    $filePath = $this->upload_dir . $doc['document_thumbnail'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                if (!empty($doc['document_file'])) {
                    $filePath = $this->upload_dir . $doc['document_file'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            $query = "DELETE FROM " . $this->documents_table . " WHERE project_id = :project_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Delete All Project Documents Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
