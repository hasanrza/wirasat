<?php
/**
 * Form Handler Class
 * Handles form submissions and validations
 */
class FormHandler {
    private $errors = [];
    private $success = [];
    
    /**
     * Add error message
     * @param string $message
     */
    public function addError($message) {
        $this->errors[] = $message;
    }
    
    /**
     * Add success message
     * @param string $message
     */
    public function addSuccess($message) {
        $this->success[] = $message;
    }
    
    /**
     * Check if there are errors
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Get all errors
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     * @return string|null
     */
    public function getFirstError() {
        return !empty($this->errors) ? $this->errors[0] : null;
    }
    
    /**
     * Get all success messages
     * @return array
     */
    public function getSuccessMessages() {
        return $this->success;
    }
    
    /**
     * Get first success message
     * @return string|null
     */
    public function getFirstSuccess() {
        return !empty($this->success) ? $this->success[0] : null;
    }
    
    /**
     * Clear all messages
     */
    public function clear() {
        $this->errors = [];
        $this->success = [];
    }
    
    /**
     * Sanitize input
     * @param string $data
     * @return string
     */
    public function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    /**
     * Validate required field
     * @param string $value
     * @param string $fieldName
     * @return bool
     */
    public function validateRequired($value, $fieldName) {
        if (empty(trim($value))) {
            $this->addError("$fieldName is required");
            return false;
        }
        return true;
    }
    
    /**
     * Validate email
     * @param string $email
     * @return bool
     */
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Invalid email format");
            return false;
        }
        return true;
    }
    
    /**
     * Validate minimum length
     * @param string $value
     * @param int $minLength
     * @param string $fieldName
     * @return bool
     */
    public function validateMinLength($value, $minLength, $fieldName) {
        if (strlen($value) < $minLength) {
            $this->addError("$fieldName must be at least $minLength characters long");
            return false;
        }
        return true;
    }
    
    /**
     * Validate password match
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    public function validatePasswordMatch($password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            $this->addError("Passwords do not match");
            return false;
        }
        return true;
    }
    
    /**
     * Handle profile update
     * @param Admin $adminObj
     * @param array $postData
     * @param int $adminId
     * @return bool
     */
    public function handleProfileUpdate($adminObj, $postData, $adminId) {
        // Sanitize inputs
        $fname = $this->sanitize($postData['first_name']);
        $lname = $this->sanitize($postData['last_name']);
        $email = $this->sanitize($postData['email']);
        
        // Validate inputs
        if (!$this->validateRequired($fname, 'First name')) return false;
        if (!$this->validateRequired($lname, 'Last name')) return false;
        if (!$this->validateRequired($email, 'Email')) return false;
        if (!$this->validateEmail($email)) return false;
        
        // Check if email exists for another user
        if ($adminObj->emailExists($email, $adminId)) {
            $this->addError("Email address already exists");
            return false;
        }
        
        // Update profile
        if ($adminObj->updateProfile($adminId, $fname, $lname, $email)) {
            $this->addSuccess("Profile updated successfully!");
            return true;
        } else {
            $this->addError("Error updating profile. Please try again.");
            return false;
        }
    }
    
    /**
     * Handle password update
     * @param Admin $adminObj
     * @param array $postData
     * @param int $adminId
     * @param string $currentHashedPassword
     * @return bool
     */
    public function handlePasswordUpdate($adminObj, $postData, $adminId, $currentHashedPassword) {
        // Get form data
        $currentPassword = $postData['current_password'];
        $newPassword = $postData['new_password'];
        $confirmPassword = $postData['confirm_password'];
        
        // Validate inputs
        if (!$this->validateRequired($currentPassword, 'Current password')) return false;
        if (!$this->validateRequired($newPassword, 'New password')) return false;
        if (!$this->validateRequired($confirmPassword, 'Confirm password')) return false;
        
        // Verify current password
        if (!$adminObj->verifyPassword($currentPassword, $currentHashedPassword)) {
            $this->addError("Current password is incorrect");
            return false;
        }
        
        // Validate new password
        if (!$this->validateMinLength($newPassword, 6, 'New password')) return false;
        if (!$this->validatePasswordMatch($newPassword, $confirmPassword)) return false;
        
        // Update password
        if ($adminObj->updatePassword($adminId, $newPassword)) {
            $this->addSuccess("Password updated successfully!");
            return true;
        } else {
            $this->addError("Error updating password. Please try again.");
            return false;
        }
    }
}
?>

