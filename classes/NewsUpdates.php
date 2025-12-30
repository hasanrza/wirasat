<?php

/**
 * NewsUpdates Class
 * Handles all news and updates database operations
 */
class NewsUpdates {
    
    private $conn;
    private $table_name = 'news_updates';
    private $upload_dir = 'uploads/news/';
    
    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }
    
    /**
     * Get all news updates
     * @param bool $activeOnly
     * @return array Array of all news updates
     */
    public function getAll($activeOnly = false) {
        $query = "SELECT * FROM " . $this->table_name;
        
        if ($activeOnly) {
            $query .= " WHERE status = 1";
        }
        
        $query .= " ORDER BY news_date DESC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get active news updates only
     * @return array Array of active news updates
     */
    public function getActive() {
        return $this->getAll(true);
    }
    
    /**
     * Get news update by ID
     * @param int $id News ID
     * @return array|false News data
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get news update by news_id
     * @param string $newsId News ID
     * @return array News data
     */
    public function getByNewsId($newsId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE news_id = :news_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':news_id', $newsId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get total count of news updates
     * @return int Total count
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Create new news update
     * @param array $data News data
     * @return int|false Insert ID or false
     */
    public function create($data) {
        try {
            // Generate news_id if not exists
            if (empty($data['news_id'])) {
                $data['news_id'] = 'news_' . time() . '_' . rand(1000, 9999);
            }
            
            $query = "INSERT INTO " . $this->table_name . " 
                      (news_id, news_text, news_image, news_video, youtube_link, status)
                      VALUES 
                      (:news_id, :news_text, :news_image, :news_video, :youtube_link, :status)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':news_id', $data['news_id']);
            $stmt->bindParam(':news_text', $data['news_text']);
            $stmt->bindParam(':news_image', $data['news_image']);
            $stmt->bindParam(':news_video', $data['news_video']);
            $youtubeLink = $data['youtube_link'] ?? null;
            $stmt->bindParam(':youtube_link', $youtubeLink);
            $status = $data['status'] ?? 1;
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Create News Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update news update
     * @param int $id
     * @param array $data News data
     * @return bool Success status
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET news_text = :news_text, 
                          news_image = :news_image, 
                          news_video = :news_video,
                          youtube_link = :youtube_link,
                          status = :status,
                          updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':news_text', $data['news_text']);
            $stmt->bindParam(':news_image', $data['news_image']);
            $stmt->bindParam(':news_video', $data['news_video']);
            $youtubeLink = $data['youtube_link'] ?? null;
            $stmt->bindParam(':youtube_link', $youtubeLink);
            $status = $data['status'] ?? 1;
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update News Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete news update
     * @param int $id News ID
     * @return bool Success status
     */
    public function delete($id) {
        try {
            // Get news to delete associated files
            $news = $this->getById($id);
            
            if ($news) {
                // Delete image
                if (!empty($news['news_image'])) {
                    $filePath = $this->upload_dir . $news['news_image'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                // Delete video
                if (!empty($news['news_video'])) {
                    $filePath = $this->upload_dir . $news['news_video'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Delete News Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle status
     * @param int $id News ID
     * @return bool Success status
     */
    public function toggleStatus($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = IF(status = 1, 0, 1),
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
    
    /**
     * Get upload directory
     * @return string
     */
    public function getUploadDir() {
        return $this->upload_dir;
    }
}
