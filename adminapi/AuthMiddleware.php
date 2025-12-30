<?php
/**
 * Authentication Middleware
 * Handles token-based authentication for API access
 */

// Prevent direct access
if (!defined('API_ACCESS')) {
    http_response_code(403);
    die(json_encode([
        'success' => false,
        'message' => 'Direct access not allowed'
    ]));
}

class AuthMiddleware {
    
    /**
     * Validate API token
     * @return bool
     */
    public static function validateToken() {
        // Get token from header
        $headers = getallheaders();
        $token = null;
        
        // Check for token in various header formats
        if (isset($headers[API_TOKEN_HEADER])) {
            $token = $headers[API_TOKEN_HEADER];
        } elseif (isset($headers['X-Api-Token'])) {
            $token = $headers['X-Api-Token'];
        } elseif (isset($headers['x-api-token'])) {
            $token = $headers['x-api-token'];
        } elseif (isset($_SERVER['HTTP_X_API_TOKEN'])) {
            $token = $_SERVER['HTTP_X_API_TOKEN'];
        } elseif (isset($_GET['token'])) {
            $token = $_GET['token'];
        }
        
        // Validate token
        if (empty($token)) {
            self::sendUnauthorized('API token is required');
            return false;
        }
        
        if ($token !== API_TOKEN) {
            self::sendUnauthorized('Invalid API token');
            return false;
        }
        
        return true;
    }
    
    /**
     * Send unauthorized response
     * @param string $message
     */
    private static function sendUnauthorized($message = 'Unauthorized access') {
        http_response_code(401);
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => false,
            'message' => $message,
            'error' => 'UNAUTHORIZED',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
    
    /**
     * Set CORS headers
     */
    public static function setCorsHeaders() {
        $origin = API_ALLOWED_ORIGINS;
        
        if ($origin === '*') {
            header('Access-Control-Allow-Origin: *');
        } else {
            $requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if (in_array($requestOrigin, explode(',', $origin))) {
                header("Access-Control-Allow-Origin: $requestOrigin");
            }
        }
        
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, ' . API_TOKEN_HEADER);
        header('Access-Control-Max-Age: 3600');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}
?>

