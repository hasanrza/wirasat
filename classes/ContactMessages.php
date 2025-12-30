<?php

/**
 * ContactMessages Class
 * Handles all contact form submissions and messages
 */
class ContactMessages {
    
    private $conn;
    private $table_name = 'contact_messages';
    
    /**
     * Constructor
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get all contact messages
     * @return array Array of all contact messages
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get unread messages only
     * @return array Array of unread messages
     */
    public function getUnread() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_read = 0 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get message by ID
     * @param int $id Message ID
     * @return array Message data
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get message by contact_id
     * @param string $contactId Contact ID
     * @return array Message data
     */
    public function getByContactId($contactId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE contact_id = :contact_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':contact_id', $contactId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get total count of messages
     * @return int Total count
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Get total count of unread messages
     * @return int Total unread count
     */
    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Save contact message
     * @param array $data Message data
     * @return bool|int Success status or inserted ID
     */
    public function save($data) {
        // Generate contact_id if not exists
        if (empty($data['contact_id'])) {
            $data['contact_id'] = 'contact_' . time() . '_' . rand(1000, 9999);
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (contact_id, full_name, email_address, phone_number, message_subject, message_body, is_read)
                  VALUES 
                  (:contact_id, :full_name, :email_address, :phone_number, :message_subject, :message_body, :is_read)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':contact_id', $data['contact_id']);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':email_address', $data['email_address']);
        $stmt->bindParam(':phone_number', $data['phone_number'] ?? null);
        $stmt->bindParam(':message_subject', $data['message_subject']);
        $stmt->bindParam(':message_body', $data['message_body']);
        $stmt->bindParam(':is_read', $data['is_read'] ?? 0, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    /**
     * Mark message as read
     * @param int $id Message ID
     * @return bool Success status
     */
    public function markAsRead($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_read = 1, updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Mark message as unread
     * @param int $id Message ID
     * @return bool Success status
     */
    public function markAsUnread($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_read = 0, updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Delete message
     * @param int $id Message ID
     * @return bool Success status
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Validate email format
     * @param string $email Email address
     * @return bool Validation result
     */
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Sanitize message data
     * @param array $data Raw message data
     * @return array Sanitized data
     */
    public function sanitize($data) {
        return [
            'full_name' => htmlspecialchars(strip_tags(trim($data['full_name'] ?? '')), ENT_QUOTES, 'UTF-8'),
            'email_address' => htmlspecialchars(trim($data['email_address'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'phone_number' => htmlspecialchars(trim($data['phone_number'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'message_subject' => htmlspecialchars(strip_tags(trim($data['message_subject'] ?? '')), ENT_QUOTES, 'UTF-8'),
            'message_body' => htmlspecialchars(trim($data['message_body'] ?? ''), ENT_QUOTES, 'UTF-8')
        ];
    }
}
