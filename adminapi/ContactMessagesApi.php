<?php
/**
 * Contact Messages API Endpoint
 * GET /adminapi/contact-messages.php - Get contact messages
 * POST /adminapi/contact-messages.php - Create new contact message
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
     * Create a new contact message
     */
    public function create() {
        try {
            // Get POST data (JSON or form data)
            $input = $this->getPostData();
            
            // Validate required fields
            $requiredFields = ['full_name', 'email_address', 'message_subject', 'message_body'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($input[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $this->sendError('Missing required fields: ' . implode(', ', $missingFields), 400);
                return;
            }
            
            // Validate email format
            if (!filter_var($input['email_address'], FILTER_VALIDATE_EMAIL)) {
                $this->sendError('Invalid email address format', 400);
                return;
            }
            
            // Generate contact_id: contact_{timestamp}_{random}
            $timestamp = time();
            $random = substr(md5(uniqid(rand(), true)), 0, 4);
            $contactId = 'contact_' . $timestamp . '_' . $random;
            
            // Prepare data
            $fullName = $this->sanitize($input['full_name']);
            $emailAddress = filter_var($input['email_address'], FILTER_SANITIZE_EMAIL);
            $phoneNumber = isset($input['phone_number']) ? $this->sanitize($input['phone_number']) : null;
            $messageSubject = $this->sanitize($input['message_subject']);
            $messageBody = $this->sanitize($input['message_body']);
            $currentTime = date('Y-m-d H:i:s');
            
            // Insert into database
            $query = "INSERT INTO contact_messages 
                     (contact_id, full_name, email_address, phone_number, message_subject, message_body, is_read, created_at, updated_at) 
                     VALUES 
                     (:contact_id, :full_name, :email_address, :phone_number, :message_subject, :message_body, 0, :created_at, :updated_at)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':contact_id', $contactId);
            $stmt->bindParam(':full_name', $fullName);
            $stmt->bindParam(':email_address', $emailAddress);
            $stmt->bindParam(':phone_number', $phoneNumber);
            $stmt->bindParam(':message_subject', $messageSubject);
            $stmt->bindParam(':message_body', $messageBody);
            $stmt->bindParam(':created_at', $currentTime);
            $stmt->bindParam(':updated_at', $currentTime);
            
            $stmt->execute();
            
            // Get the newly created ID
            $newId = $this->db->lastInsertId();
            
            // Return success with the new ID
            $this->sendSuccess(
                ['id' => (int)$newId, 'contact_id' => $contactId],
                'Contact information saved successfully',
                201
            );
        } catch (PDOException $e) {
            error_log("Contact Messages API PDO Error: " . $e->getMessage());
            $this->sendError('Database error: ' . $e->getMessage(), 500);
        } catch (Exception $e) {
            error_log("Contact Messages API Error: " . $e->getMessage());
            $this->sendError('Failed to save contact message: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get POST data (handles both JSON and form data)
     * @return array
     */
    private function getPostData() {
        $input = [];
        
        // Check if request is JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            $input = json_decode($json, true) ?? [];
        } else {
            // Get from $_POST
            $input = $_POST;
        }
        
        // Remove token from input data if present (token should be in header, but remove from body for safety)
        if (isset($input['token'])) {
            unset($input['token']);
        }
        
        return $input;
    }
    
    /**
     * Handle request
     */
    public function handleRequest() {
        $method = $this->getMethod();
        
        if ($method === 'GET') {
            $id = $this->getId();
            
            if ($id) {
                $this->getById($id);
            } else {
                $this->getAll();
            }
        } elseif ($method === 'POST') {
            $this->create();
        } else {
            $this->sendError('Method not allowed', 405);
            return;
        }
    }
}

// Initialize and handle request
$api = new ContactMessagesApi();
$api->handleRequest();
?>

