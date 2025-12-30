<?php
/**
 * Authentication Controller
 * Handles login and authentication logic
 */
class AuthController {
    private $admin;
    private $formHandler;
    
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
     * Process login
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password) {
        // Validate inputs
        if (!$this->formHandler->validateRequired($email, 'Email')) return false;
        if (!$this->formHandler->validateRequired($password, 'Password')) return false;
        if (!$this->formHandler->validateEmail($email)) return false;
        
        // Attempt login
        $admin = $this->admin->login($email, $password);
        
        if ($admin) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_fname'] = $admin['fname'];
            $_SESSION['admin_lname'] = $admin['lname'];
            $_SESSION['admin_name'] = $admin['fname'] . ' ' . $admin['lname'];
            
            $this->formHandler->addSuccess("Login successful! Redirecting...");
            return true;
        } else {
            $this->formHandler->addError("Invalid email or password");
            return false;
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Destroy session
        session_destroy();
        
        // Clear session variables
        $_SESSION = array();
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
    }
    
    /**
     * Check if user is logged in
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }
    
    /**
     * Get error message
     * @return string|null
     */
    public function getErrorMessage() {
        return $this->formHandler->getFirstError();
    }
    
    /**
     * Get success message
     * @return string|null
     */
    public function getSuccessMessage() {
        return $this->formHandler->getFirstSuccess();
    }
}
?>

