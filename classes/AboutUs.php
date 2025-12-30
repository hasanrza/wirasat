<?php
/**
 * About Us Class
 * Handles all about us-related operations
 */
class AboutUs {
    private $conn;
    private $table_name = "about_us";
    
    // About Us properties
    public $id;
    public $about_us_paragraph;
    public $about_us_video;
    public $status;
    
    /**
     * Constructor with database connection
     * @param PDO $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get about us record (usually only one)
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
     * Create or update about us
     * @param array $data
     * @return bool
     */
    public function save($data) {
        try {
            // Check if about us exists
            $existing = $this->get();
            
            if ($existing) {
                return $this->update($existing['id'], $data);
            } else {
                return $this->create($data);
            }
        } catch(PDOException $e) {
            error_log("Save About Us Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new about us record
     * @param array $data
     * @return bool
     */
    private function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET about_us_paragraph = :about_us_paragraph,
                     about_us_video = :about_us_video,
                     status = :status";
        
        $stmt = $this->conn->prepare($query);
        return $this->bindParams($stmt, $data);
    }
    
    /**
     * Update existing about us record
     * @param int $id
     * @param array $data
     * @return bool
     */
    private function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                 SET about_us_paragraph = :about_us_paragraph,
                     about_us_video = :about_us_video,
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
        $stmt->bindParam(':about_us_paragraph', $data['about_us_paragraph']);
        $stmt->bindParam(':about_us_video', $data['about_us_video']);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Validate YouTube URL
     * @param string $url
     * @return bool
     */
    public function validateYouTubeUrl($url) {
        if (empty($url)) {
            return true; // Optional field
        }
        
        $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/';
        return preg_match($pattern, $url);
    }
}
?>


