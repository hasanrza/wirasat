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
     * Get category for file field
     * @param string $field
     * @return string
     */
    private function getFileCategory($field) {
        // Map file fields to their upload categories
        $categoryMap = [
            // CEO category
            'ceo_picture_1' => 'ceo',
            'ceo_picture_2' => 'ceo',
            
            // Company category
            'company_logo' => 'company',
            'company_background' => 'company',
            'footer_image' => 'company',
            
            // News category
            'news_image' => 'news',
            'news_video' => 'news',
            
            // Projects category
            'project_map_thumbnail' => 'projects',
            'project_map_full' => 'projects',
            'project_payment_plan' => 'projects',
            'project_amenities_image' => 'projects',
            'document_thumbnail' => 'projects',
            'document_file' => 'projects',
            
            // Services category
            'service_image' => 'services',
            
            // Gallery category (if needed)
            'picture_file' => 'gallery',
            'picture_thumbnail' => 'gallery',
            'video_thumbnail' => 'gallery',
            
            // About Us category (if needed)
            'about_us_video' => 'about',
        ];
        
        return $categoryMap[$field] ?? 'uploads';
    }
    
    /**
     * Extract category and filename from path
     * @param string $path
     * @return array ['category' => string, 'filename' => string]
     */
    private function extractCategoryFromPath($path) {
        // Check if path already contains dashboard/uploads/{category}/ pattern
        if (preg_match('#dashboard/uploads/([^/]+)/([^/]+)$#', $path, $matches)) {
            return ['category' => $matches[1], 'filename' => $matches[2]];
        }
        
        // Check if path contains uploads/{category}/ pattern
        if (preg_match('#uploads/([^/]+)/([^/]+)$#', $path, $matches)) {
            return ['category' => $matches[1], 'filename' => $matches[2]];
        }
        
        return null;
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
                        $pathInfo = $this->extractCategoryFromPath($data[$field]);
                        
                        if ($pathInfo) {
                            // Use category from path if available
                            $category = $pathInfo['category'];
                            $filename = $pathInfo['filename'];
                        } else {
                            // Use field-based category mapping
                            $category = $this->getFileCategory($field);
                            $filename = basename($data[$field]);
                        }
                        
                        // Construct full URL: https://cms.wirasat.com/dashboard/uploads/{category}/{filename}
                        $data[$field] = $baseUrl . '/dashboard/uploads/' . $category . '/' . $filename;
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
                                $pathInfo = $this->extractCategoryFromPath($record[$field]);
                                
                                if ($pathInfo) {
                                    // Use category from path if available
                                    $category = $pathInfo['category'];
                                    $filename = $pathInfo['filename'];
                                } else {
                                    // Use field-based category mapping
                                    $category = $this->getFileCategory($field);
                                    $filename = basename($record[$field]);
                                }
                                
                                // Construct full URL: https://cms.wirasat.com/dashboard/uploads/{category}/{filename}
                                $record[$field] = $baseUrl . '/dashboard/uploads/' . $category . '/' . $filename;
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
        // Use the CMS base URL
        return 'https://cms.wirasat.com';
    }
}
?>

