<?php
/**
 * API Configuration File
 * Contains database connection and API settings
 */

// Prevent direct access
if (!defined('API_ACCESS')) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'Direct access not allowed'
    ]));
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'warast');

// API Configuration
define('API_VERSION', '1.0.0');
define('API_TOKEN', 'wirasat_api_token_2024_secure_key_change_this'); // CHANGE THIS TO A SECURE TOKEN
define('API_TOKEN_HEADER', 'X-API-Token'); // Header name for token
define('API_ALLOWED_ORIGINS', '*'); // Set to specific domain in production

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

/**
 * Database Connection Class
 */
class ApiDatabase {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            // Return proper error response instead of throwing
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => API_VERSION
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>

