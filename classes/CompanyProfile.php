<?php
/**
 * Company Profile Class
 * Handles all company profile-related operations
 */
class CompanyProfile {
    private $conn;
    private $table_name = "company_profile";
    private $upload_dir = "uploads/company/";
    
    // Company Profile properties
    public $id;
    public $comp_id;
    public $company_name;
    public $company_logo;
    public $company_background;
    public $footer_image;
    public $company_address;
    public $facebook_link;
    public $youtube_link;
    public $twitter_link;
    public $instagram_link;
    public $website_url;
    public $email_address;
    public $uan;
    public $mobile_1;
    public $mobile_2;
    public $ptcl_number;
    public $longitude;
    public $latitude;
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
     * Get company profile by comp_id
     * @param string $compId
     * @return array|false
     */
    public function getByCompId($compId = '999999') {
        $query = "SELECT * FROM " . $this->table_name . " WHERE comp_id = :comp_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comp_id', $compId);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Get company profile by ID
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
     * Create or update company profile
     * @param array $data
     * @return bool
     */
    public function save($data) {
        try {
            // Check if company profile exists
            $existing = $this->getByCompId($data['comp_id']);
            
            if ($existing) {
                return $this->update($existing['id'], $data);
            } else {
                return $this->create($data);
            }
        } catch(PDOException $e) {
            error_log("Save Company Profile Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new company profile
     * @param array $data
     * @return bool
     */
    private function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET comp_id = :comp_id,
                     company_name = :company_name,
                     company_logo = :company_logo,
                     company_background = :company_background,
                     footer_image = :footer_image,
                     company_address = :company_address,
                     facebook_link = :facebook_link,
                     youtube_link = :youtube_link,
                     twitter_link = :twitter_link,
                     instagram_link = :instagram_link,
                     website_url = :website_url,
                     email_address = :email_address,
                     uan = :uan,
                     mobile_1 = :mobile_1,
                     mobile_2 = :mobile_2,
                     ptcl_number = :ptcl_number,
                     longitude = :longitude,
                     latitude = :latitude,
                     status = :status";
        
        $stmt = $this->conn->prepare($query);
        return $this->bindParams($stmt, $data);
    }
    
    /**
     * Update existing company profile
     * @param int $id
     * @param array $data
     * @return bool
     */
    private function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                 SET company_name = :company_name,
                     company_logo = :company_logo,
                     company_background = :company_background,
                     footer_image = :footer_image,
                     company_address = :company_address,
                     facebook_link = :facebook_link,
                     youtube_link = :youtube_link,
                     twitter_link = :twitter_link,
                     instagram_link = :instagram_link,
                     website_url = :website_url,
                     email_address = :email_address,
                     uan = :uan,
                     mobile_1 = :mobile_1,
                     mobile_2 = :mobile_2,
                     ptcl_number = :ptcl_number,
                     longitude = :longitude,
                     latitude = :latitude,
                     status = :status
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $this->bindParams($stmt, $data, false);
    }
    
    /**
     * Bind parameters to statement
     * @param PDOStatement $stmt
     * @param array $data
     * @param bool $isCreate
     * @return bool
     */
    private function bindParams($stmt, $data, $isCreate = true) {
        if ($isCreate) {
            $stmt->bindParam(':comp_id', $data['comp_id']);
        }
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->bindParam(':company_logo', $data['company_logo']);
        $stmt->bindParam(':company_background', $data['company_background']);
        $stmt->bindParam(':footer_image', $data['footer_image']);
        $stmt->bindParam(':company_address', $data['company_address']);
        $stmt->bindParam(':facebook_link', $data['facebook_link']);
        $stmt->bindParam(':youtube_link', $data['youtube_link']);
        $stmt->bindParam(':twitter_link', $data['twitter_link']);
        $stmt->bindParam(':instagram_link', $data['instagram_link']);
        $stmt->bindParam(':website_url', $data['website_url']);
        $stmt->bindParam(':email_address', $data['email_address']);
        $stmt->bindParam(':uan', $data['uan']);
        $stmt->bindParam(':mobile_1', $data['mobile_1']);
        $stmt->bindParam(':mobile_2', $data['mobile_2']);
        $stmt->bindParam(':ptcl_number', $data['ptcl_number']);
        $stmt->bindParam(':longitude', $data['longitude']);
        $stmt->bindParam(':latitude', $data['latitude']);
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
    
    /**
     * Validate email format
     * @param string $email
     * @return bool
     */
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
?>


