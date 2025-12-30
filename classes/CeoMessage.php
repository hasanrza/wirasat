<?php
/**
 * CEO Message Class
 * Handles all CEO message-related operations
 */
class CeoMessage {
    private $conn;
    private $table_name = "ceo_message";
    private $upload_dir = "uploads/ceo/";
    
    // CEO Message properties
    public $id;
    public $ceo_picture_1;
    public $ceo_picture_2;
    public $ceo_message_paragraph_1;
    public $ceo_message_paragraph_2;
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
     * Get CEO message record (usually only one)
     * @return array|false
     */
    public function get() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Get by ID
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
     * Create or update CEO message
     * @param array $data
     * @return bool
     */
    public function save($data) {
        try {
            // Check if CEO message exists
            $existing = $this->get();
            
            if ($existing) {
                return $this->update($existing['id'], $data);
            } else {
                return $this->create($data);
            }
        } catch(PDOException $e) {
            error_log("Save CEO Message Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new CEO message record
     * @param array $data
     * @return bool
     */
    private function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET ceo_picture_1 = :ceo_picture_1,
                     ceo_picture_2 = :ceo_picture_2,
                     ceo_message_paragraph_1 = :ceo_message_paragraph_1,
                     ceo_message_paragraph_2 = :ceo_message_paragraph_2,
                     status = :status";
        
        $stmt = $this->conn->prepare($query);
        return $this->bindParams($stmt, $data);
    }
    
    /**
     * Update existing CEO message record
     * @param int $id
     * @param array $data
     * @return bool
     */
    private function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                 SET ceo_picture_1 = :ceo_picture_1,
                     ceo_picture_2 = :ceo_picture_2,
                     ceo_message_paragraph_1 = :ceo_message_paragraph_1,
                     ceo_message_paragraph_2 = :ceo_message_paragraph_2,
                     status = :status
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $this->bindParams($stmt, $data);
    }
    
    /**
     * Bind parameters to statement
     * @param PDOStatement $stmt
     * @param array $data
     * @return bool
     */
    private function bindParams($stmt, $data) {
        $stmt->bindParam(':ceo_picture_1', $data['ceo_picture_1']);
        $stmt->bindParam(':ceo_picture_2', $data['ceo_picture_2']);
        $stmt->bindParam(':ceo_message_paragraph_1', $data['ceo_message_paragraph_1']);
        $stmt->bindParam(':ceo_message_paragraph_2', $data['ceo_message_paragraph_2']);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        
        return $stmt->execute();
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


