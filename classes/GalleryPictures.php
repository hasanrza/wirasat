<?php

/**
 * GalleryPictures Class
 * Handles all gallery pictures database operations
 */
class GalleryPictures {
    
    private $conn;
    private $table_name = 'gallery_pictures';
    
    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get all pictures
     * @return array Array of all pictures
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY display_order ASC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get active pictures only
     * @return array Array of active pictures
     */
    public function getActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 1 ORDER BY display_order ASC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get picture by ID
     * @param int $id Picture ID
     * @return array Picture data
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get picture by picture_id
     * @param string $pictureId Picture ID
     * @return array Picture data
     */
    public function getByPictureId($pictureId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE picture_id = :picture_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':picture_id', $pictureId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get total count of pictures
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
     * Save picture (create or update)
     * @param array $data Picture data
     * @return bool Success status
     */
    public function save($data) {
        // Generate picture_id if not exists
        if (empty($data['picture_id'])) {
            $data['picture_id'] = 'pic_' . time() . '_' . rand(1000, 9999);
        }
        
        // Check if picture exists
        $existing = $this->getById($data['id'] ?? 0);
        
        if ($existing) {
            return $this->update($data);
        } else {
            return $this->create($data);
        }
    }
    
    /**
     * Create new picture
     * @param array $data Picture data
     * @return bool Success status
     */
    private function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (picture_id, picture_title, picture_description, picture_file, picture_thumbnail, display_order, status)
                  VALUES 
                  (:picture_id, :picture_title, :picture_description, :picture_file, :picture_thumbnail, :display_order, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':picture_id', $data['picture_id']);
        $stmt->bindParam(':picture_title', $data['picture_title']);
        $stmt->bindParam(':picture_description', $data['picture_description'] ?? null);
        $stmt->bindParam(':picture_file', $data['picture_file']);
        $stmt->bindParam(':picture_thumbnail', $data['picture_thumbnail'] ?? null);
        $stmt->bindParam(':display_order', $data['display_order'] ?? 0, PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'] ?? 1, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Update picture
     * @param array $data Picture data
     * @return bool Success status
     */
    private function update($data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET picture_title = :picture_title, 
                      picture_description = :picture_description, 
                      picture_file = :picture_file,
                      picture_thumbnail = :picture_thumbnail,
                      display_order = :display_order,
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':picture_title', $data['picture_title']);
        $stmt->bindParam(':picture_description', $data['picture_description'] ?? null);
        $stmt->bindParam(':picture_file', $data['picture_file']);
        $stmt->bindParam(':picture_thumbnail', $data['picture_thumbnail'] ?? null);
        $stmt->bindParam(':display_order', $data['display_order'] ?? 0, PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'] ?? 1, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Delete picture
     * @param int $id Picture ID
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
     * @param int $id Picture ID
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
