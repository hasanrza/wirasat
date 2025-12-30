<?php
/**
 * Base API Class
 * Provides common functionality for all API endpoints
 */

// Prevent direct access
if (!defined('API_ACCESS')) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'Direct access not allowed'
    ]));
}

class BaseApi {
    protected $db;
    protected $response;
    
    /**
     * Constructor
     */
    public function __construct() {
        try {
            $database = ApiDatabase::getInstance();
            $this->db = $database->getConnection();
        } catch (Exception $e) {
            // If database connection fails, send error immediately
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
        
        $this->response = [
            'success' => false,
            'message' => '',
            'data' => null,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => API_VERSION
        ];
    }
    
    /**
     * Send success response
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     */
    protected function sendSuccess($data = null, $message = 'Success', $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        $this->response['success'] = true;
        $this->response['message'] = $message;
        $this->response['data'] = $data;
        
        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Send error response
     * @param string $message
     * @param int $statusCode
     * @param mixed $data
     */
    protected function sendError($message = 'An error occurred', $statusCode = 400, $data = null) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        $this->response['success'] = false;
        $this->response['message'] = $message;
        $this->response['data'] = $data;
        
        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Get request method
     * @return string
     */
    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
    
    /**
     * Get ID from URL or request
     * @return int|null
     */
    protected function getId() {
        $id = $_GET['id'] ?? null;
        return $id ? (int)$id : null;
    }
    
    /**
     * Sanitize data
     * @param mixed $data
     * @return mixed
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Format file paths to full URLs
     * @param array $data
     * @param array $fileFields
     * @return array
     */
    protected function formatFilePaths($data, $fileFields = []) {
        if (empty($data) || empty($fileFields)) {
            return $data;
        }
        
        $baseUrl = $this->getBaseUrl();
        
        // If single record
        if (isset($data['id']) && !isset($data[0])) {
            foreach ($fileFields as $field) {
                if (!empty($data[$field]) && is_string($data[$field])) {
                    // If path doesn't start with http, prepend base URL
                    if (strpos($data[$field], 'http') !== 0) {
                        // Ensure path starts with / if it doesn't already
                        $path = $data[$field];
                        if (strpos($path, '/') !== 0) {
                            $path = '/' . $path;
                        }
                        $data[$field] = $baseUrl . $path;
                    }
                }
            }
        } else {
            // If array of records
            foreach ($data as &$record) {
                if (is_array($record)) {
                    foreach ($fileFields as $field) {
                        if (!empty($record[$field]) && is_string($record[$field])) {
                            // If path doesn't start with http, prepend base URL
                            if (strpos($record[$field], 'http') !== 0) {
                                // Ensure path starts with / if it doesn't already
                                $path = $record[$field];
                                if (strpos($path, '/') !== 0) {
                                    $path = '/' . $path;
                                }
                                $record[$field] = $baseUrl . $path;
                            }
                        }
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Get base URL
     * @return string
     */
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = str_replace('/adminapi', '', $script);
        return $protocol . '://' . $host . $basePath;
    }
}
?>

