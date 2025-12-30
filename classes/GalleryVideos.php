<?php

/**
 * GalleryVideos Class
 * Handles all gallery videos database operations
 */
class GalleryVideos {
    
    private $conn;
    private $table_name = 'gallery_videos';
    
    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get all videos
     * @return array Array of all videos
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY display_order ASC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get active videos only
     * @return array Array of active videos
     */
    public function getActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 1 ORDER BY display_order ASC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get video by ID
     * @param int $id Video ID
     * @return array Video data
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get video by video_id
     * @param string $videoId Video ID
     * @return array Video data
     */
    public function getByVideoId($videoId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE video_id = :video_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':video_id', $videoId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get total count of videos
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
     * Save video (create or update)
     * @param array $data Video data
     * @return bool Success status
     */
    public function save($data) {
        // Generate video_id if not exists
        if (empty($data['video_id'])) {
            $data['video_id'] = 'vid_' . time() . '_' . rand(1000, 9999);
        }
        
        // Check if video exists
        $existing = $this->getById($data['id'] ?? 0);
        
        if ($existing) {
            return $this->update($data);
        } else {
            return $this->create($data);
        }
    }
    
    /**
     * Create new video
     * @param array $data Video data
     * @return bool Success status
     */
    private function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (video_id, video_title, video_description, video_url, video_thumbnail, video_embed_code, display_order, status)
                  VALUES 
                  (:video_id, :video_title, :video_description, :video_url, :video_thumbnail, :video_embed_code, :display_order, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':video_id', $data['video_id']);
        $stmt->bindParam(':video_title', $data['video_title']);
        $stmt->bindParam(':video_description', $data['video_description'] ?? null);
        $stmt->bindParam(':video_url', $data['video_url']);
        $stmt->bindParam(':video_thumbnail', $data['video_thumbnail'] ?? null);
        $stmt->bindParam(':video_embed_code', $data['video_embed_code'] ?? null);
        $stmt->bindParam(':display_order', $data['display_order'] ?? 0, PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'] ?? 1, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Update video
     * @param array $data Video data
     * @return bool Success status
     */
    private function update($data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET video_title = :video_title, 
                      video_description = :video_description, 
                      video_url = :video_url,
                      video_thumbnail = :video_thumbnail,
                      video_embed_code = :video_embed_code,
                      display_order = :display_order,
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':video_title', $data['video_title']);
        $stmt->bindParam(':video_description', $data['video_description'] ?? null);
        $stmt->bindParam(':video_url', $data['video_url']);
        $stmt->bindParam(':video_thumbnail', $data['video_thumbnail'] ?? null);
        $stmt->bindParam(':video_embed_code', $data['video_embed_code'] ?? null);
        $stmt->bindParam(':display_order', $data['display_order'] ?? 0, PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'] ?? 1, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Delete video
     * @param int $id Video ID
     * @return bool Success status
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Toggle status
     * @param int $id Video ID
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
     * Update display order
     * @param array $orders Array of id => order pairs
     * @return bool Success status
     */
    public function updateOrder($orders) {
        foreach ($orders as $id => $order) {
            $query = "UPDATE " . $this->table_name . " 
                      SET display_order = :order, updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':order', $order, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }
}
