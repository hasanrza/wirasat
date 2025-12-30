<?php
/**
 * Profile Controller
 * Handles profile page logic
 */
class ProfileController {
    private $admin;
    private $formHandler;
    private $adminData;
    
    /**
     * Constructor
     * @param Admin $admin
     * @param FormHandler $formHandler
     */
    public function __construct($admin, $formHandler) {
        $this->admin = $admin;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load admin data
     * @param int $adminId
     */
    public function loadAdminData($adminId) {
        $this->adminData = $this->admin->getById($adminId);
        return $this->adminData;
    }
    
    /**
     * Get admin data
     * @return array
     */
    public function getAdminData() {
        return $this->adminData;
    }
    
    /**
     * Process form submissions
     * @param int $adminId
     */
    public function processRequest($adminId) {
        // Handle profile update
        if (isset($_POST['update_profile'])) {
            $success = $this->formHandler->handleProfileUpdate($this->admin, $_POST, $adminId);
            if ($success) {
                // Reload admin data after update
                $this->loadAdminData($adminId);
                // Update session
                $_SESSION['admin_name'] = $this->adminData['fname'] . ' ' . $this->adminData['lname'];
            }
        }
        
        // Handle password update
        if (isset($_POST['update_password'])) {
            $this->formHandler->handlePasswordUpdate(
                $this->admin, 
                $_POST, 
                $adminId, 
                $this->adminData['password']
            );
        }
    }
    
    /**
     * Get success message
     * @return string|null
     */
    public function getSuccessMessage() {
        return $this->formHandler->getFirstSuccess();
    }
    
    /**
     * Get error message
     * @return string|null
     */
    public function getErrorMessage() {
        return $this->formHandler->getFirstError();
    }
}
?>

