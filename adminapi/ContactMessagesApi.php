<?php
/**
 * Contact Messages API Endpoint
 * GET /adminapi/contact-messages.php
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

class ContactMessagesApi extends BaseApi {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all contact messages
     */
    public function getAll() {
        try {
            $unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] == '1';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            
            $query = "SELECT * FROM contact_messages";
            if ($unreadOnly) {
                $query .= " WHERE is_read = 0";
            }
            $query .= " ORDER BY created_at DESC";
            
            if ($limit && $limit > 0) {
                $query .= " LIMIT " . (int)$limit;
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $messages = $stmt->fetchAll();
            
            $this->sendSuccess($messages, 'Contact messages retrieved successfully');
        } catch (Exception $e) {
            error_log("Contact Messages API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve contact messages', 500);
        }
    }
    
    /**
     * Get message by ID
     * @param int $id
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM contact_messages WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $message = $stmt->fetch();
            
            if (!$message) {
                $this->sendError('Contact message not found', 404);
                return;
            }
            
            $this->sendSuccess($message, 'Contact message retrieved successfully');
        } catch (Exception $e) {
            error_log("Contact Messages API Error: " . $e->getMessage());
            $this->sendError('Failed to retrieve contact message', 500);
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
$api = new ContactMessagesApi();
$api->handleRequest();
?>

