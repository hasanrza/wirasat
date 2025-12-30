<?php
/**
 * Admin Class
 * Handles all admin-related operations
 */
class Admin {
    private $conn;
    private $table_name = "admin";
    
    // Admin properties
    public $id;
    public $fname;
    public $lname;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor with database connection
     * @param PDO $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get admin by ID
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
     * Get admin by email
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Check if email exists for another admin
     * @param string $email
     * @param int $excludeId
     * @return bool
     */
    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        } else {
            $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
        }
        
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Update admin profile
     * @param int $id
     * @param string $fname
     * @param string $lname
     * @param string $email
     * @return bool
     */
    public function updateProfile($id, $fname, $lname, $email) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET fname = :fname, lname = :lname, email = :email 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitize and bind parameters
            $fname = htmlspecialchars(strip_tags($fname));
            $lname = htmlspecialchars(strip_tags($lname));
            $email = htmlspecialchars(strip_tags($email));
            
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update Profile Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update admin password
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($id, $newPassword) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET password = :password 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Hash the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update Password Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify password
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    /**
     * Validate email format
     * @param string $email
     * @return bool
     */
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate password strength
     * @param string $password
     * @param int $minLength
     * @return bool
     */
    public function validatePassword($password, $minLength = 6) {
        return strlen($password) >= $minLength;
    }
    
    /**
     * Login admin
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function login($email, $password) {
        $admin = $this->getByEmail($email);
        
        if ($admin && $this->verifyPassword($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }
}
?>

