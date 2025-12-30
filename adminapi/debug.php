<?php
/**
 * Debug Endpoint
 * Use this to test database connection and see what's happening
 */

define('API_ACCESS', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/AuthMiddleware.php';

// Set CORS headers
AuthMiddleware::setCorsHeaders();

// Validate token
if (!AuthMiddleware::validateToken()) {
    exit;
}

header('Content-Type: application/json');

$debug = [
    'database' => [
        'host' => DB_HOST,
        'database' => DB_NAME,
        'user' => DB_USER,
        'connected' => false,
        'error' => null
    ],
    'tables' => [],
    'company_profile_data' => []
];

try {
    $database = ApiDatabase::getInstance();
    $db = $database->getConnection();
    $debug['database']['connected'] = true;
    
    // Get all tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $debug['tables'] = $tables;
    
    // Check if company_profile table exists
    if (in_array('company_profile', $tables)) {
        // Get table structure
        $stmt = $db->query("DESCRIBE company_profile");
        $debug['company_profile']['structure'] = $stmt->fetchAll();
        
        // Get row count
        $stmt = $db->query("SELECT COUNT(*) as count FROM company_profile");
        $count = $stmt->fetch();
        $debug['company_profile']['row_count'] = $count['count'];
        
        // Get all data
        $stmt = $db->query("SELECT * FROM company_profile LIMIT 5");
        $debug['company_profile_data'] = $stmt->fetchAll();
    } else {
        $debug['company_profile']['error'] = 'Table does not exist';
    }
    
} catch (Exception $e) {
    $debug['database']['error'] = $e->getMessage();
    $debug['database']['connected'] = false;
}

echo json_encode([
    'success' => true,
    'message' => 'Debug information',
    'data' => $debug,
    'timestamp' => date('Y-m-d H:i:s')
], JSON_PRETTY_PRINT);
?>

