<?php
/**
 * Our Services Class
 * Handles all services-related operations
 */
class OurServices {
    private $conn;
    private $table_name = "our_services";
    private $upload_dir = "uploads/services/";
    
    // Service properties
    public $id;
    public $service_title;
    public $service_description;
    public $service_icon;
    public $service_image;
    public $display_order;
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
     * Get all services
     * @param bool $activeOnly
     * @return array
     */
    public function getAll($activeOnly = false) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($activeOnly) {
            $query .= " WHERE status = 1";
        }
        
        $query .= " ORDER BY display_order ASC, id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get service by ID
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
     * Create new service
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                     SET service_title = :service_title,
                         service_description = :service_description,
                         service_icon = :service_icon,
                         service_image = :service_image,
                         display_order = :display_order,
                         status = :status";
            
            $stmt = $this->conn->prepare($query);
            
            if ($this->bindParams($stmt, $data)) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch(PDOException $e) {
            error_log("Create Service Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update existing service
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET service_title = :service_title,
                         service_description = :service_description,
                         service_icon = :service_icon,
                         service_image = :service_image,
                         display_order = :display_order,
                         status = :status
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $this->bindParams($stmt, $data);
        } catch(PDOException $e) {
            error_log("Update Service Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Bind parameters to statement
     * @param PDOStatement $stmt
     * @param array $data
     * @return bool
     */
    private function bindParams($stmt, $data) {
        $stmt->bindParam(':service_title', $data['service_title']);
        $stmt->bindParam(':service_description', $data['service_description']);
        $stmt->bindParam(':service_icon', $data['service_icon']);
        $stmt->bindParam(':service_image', $data['service_image']);
        $stmt->bindParam(':display_order', $data['display_order'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Delete service
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            // Get service to delete associated files
            $service = $this->getById($id);
            
            if ($service && $service['service_image']) {
                $filePath = $this->upload_dir . $service['service_image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Delete Service Error: " . $e->getMessage());
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
}
?>


