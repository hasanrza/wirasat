<?php
/**
 * News Updates API Endpoint
 * GET /adminapi/news-updates.php
 */

define('API_ACCESS', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/BaseApi.php';
require_once __DIR__ . '/../config/autoload.php';

// Set CORS headers
AuthMiddleware::setCorsHeaders();

// Validate token
if (!AuthMiddleware::validateToken()) {
    exit;
}

class NewsUpdatesApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all news updates
     */
    public function getAll() {
        try {
            $activeOnly = isset($_GET['active_only']) && $_GET['active_only'] == '1';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            
            $query = "SELECT * FROM news_updates";
            if ($activeOnly) {
                $query .= " WHERE status = 1";
            }
            $query .= " ORDER BY news_date DESC, created_at DESC";
            
            if ($limit && $limit > 0) {
                $query .= " LIMIT " . (int)$limit;
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $news = $stmt->fetchAll();
            
            // Format file paths
            $fileFields = ['news_image', 'news_video'];
            $news = $this->formatFilePaths($news, $fileFields);
            
            $this->sendSuccess($news, 'News updates retrieved successfully');
        } catch (Exception $e) {
            error_log("News Updates API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve news updates', 500);
        }
    }
    
    /**
     * Get news by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM news_updates WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $news = $stmt->fetch();
            
            if (!$news) {
                $this->sendError('News update not found', 404);
                return;
            }
            
            // Format file paths
            $fileFields = ['news_image', 'news_video'];
            $news = $this->formatFilePaths($news, $fileFields);
            
            $this->sendSuccess($news, 'News update retrieved successfully');
        } catch (Exception $e) {
            error_log("News Updates API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve news update', 500);
        }
    }
    
    /**
     * Handle request
     */
    public function handleRequest() {
        $method = $this->getMethod();
        
        if ($method !== 'GET') {
            $this->sendError('Method not allowed', 405);
            return;
        }
        
        $id = $this->getId();
        
        if ($id) {
            $this->getById($id);
        } else {
            $this->getAll();
        }
    }
}

// Initialize and handle request
$api = new NewsUpdatesApi();
$api->handleRequest();
?>

