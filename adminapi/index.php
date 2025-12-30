<?php
/**
 * API Router/Index
 * Main entry point for API requests
 * Routes requests to appropriate API endpoints
 */

define('API_ACCESS', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/BaseApi.php';

// Set CORS headers
AuthMiddleware::setCorsHeaders();

// Validate token
if (!AuthMiddleware::validateToken()) {
    exit;
}

// Get the endpoint from URL - improved path detection
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// Get the directory where index.php is located
$scriptDir = dirname($scriptName);
if ($scriptDir === '/' || $scriptDir === '.') {
    $scriptDir = '';
}

// Remove the script directory from request URI
$path = $requestUri;
if ($scriptDir && strpos($path, $scriptDir) === 0) {
    $path = substr($path, strlen($scriptDir));
}
$path = trim($path, '/');

// Extract endpoint from path
// Handle cases like: /dashboard/adminapi/company-profile or /adminapi/company-profile
$pathParts = explode('/', $path);
$endpoint = '';

// Find 'adminapi' in path and get what comes after it
$adminapiIndex = array_search('adminapi', $pathParts);
if ($adminapiIndex !== false && isset($pathParts[$adminapiIndex + 1])) {
    $endpoint = $pathParts[$adminapiIndex + 1];
} else {
    // Fallback: get the last part of the path
    $endpoint = $pathParts[count($pathParts) - 1] ?? '';
}

// If endpoint is empty or 'index.php', try to get from path directly
if (empty($endpoint) || $endpoint === 'index.php') {
    // Try to extract from the full path
    if (preg_match('/adminapi\/([^\/\?]+)/', $requestUri, $matches)) {
        $endpoint = $matches[1];
    } elseif (!empty($pathParts)) {
        $endpoint = end($pathParts);
    }
}

// Remove query string
$endpoint = strtok($endpoint, '?');

// Debug: Uncomment to see what's being parsed (useful for troubleshooting)
// error_log("Request URI: " . $requestUri);
// error_log("Script Name: " . $scriptName);
// error_log("Script Dir: " . $scriptDir);
// error_log("Path: " . $path);
// error_log("Path Parts: " . print_r($pathParts, true));
// error_log("Endpoint: " . $endpoint);

// Map endpoints to API files
$endpoints = [
    'admin' => 'AdminApi.php',
    'company-profile' => 'CompanyProfileApi.php',
    'company_profile' => 'CompanyProfileApi.php',
    'about-us' => 'AboutUsApi.php',
    'about_us' => 'AboutUsApi.php',
    'ceo-message' => 'CeoMessageApi.php',
    'ceo_message' => 'CeoMessageApi.php',
    'our-services' => 'OurServicesApi.php',
    'our_services' => 'OurServicesApi.php',
    'services' => 'OurServicesApi.php',
    'projects' => 'ProjectsApi.php',
    'project-documents' => 'ProjectDocumentsApi.php',
    'project_documents' => 'ProjectDocumentsApi.php',
    'gallery-pictures' => 'GalleryPicturesApi.php',
    'gallery_pictures' => 'GalleryPicturesApi.php',
    'pictures' => 'GalleryPicturesApi.php',
    'gallery-videos' => 'GalleryVideosApi.php',
    'gallery_videos' => 'GalleryVideosApi.php',
    'videos' => 'GalleryVideosApi.php',
    'news-updates' => 'NewsUpdatesApi.php',
    'news_updates' => 'NewsUpdatesApi.php',
    'news' => 'NewsUpdatesApi.php',
    'contact-messages' => 'ContactMessagesApi.php',
    'contact_messages' => 'ContactMessagesApi.php',
    'messages' => 'ContactMessagesApi.php',
    'contacts' => 'ContactMessagesApi.php'
];

// If no endpoint specified, show API info
if (empty($endpoint) || $endpoint === 'index.php' || $endpoint === 'adminapi') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Wirasat API v' . API_VERSION,
        'endpoints' => array_keys($endpoints),
        'documentation' => 'See README.md for API documentation',
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    exit;
}

// Check if endpoint exists
if (!isset($endpoints[$endpoint])) {
    http_response_code(404);
    header('Content-Type: application/json');
    
    // Debug information (remove in production)
    $debugInfo = [
        'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
        'script_name' => $_SERVER['SCRIPT_NAME'] ?? '',
        'parsed_endpoint' => $endpoint,
        'path_parts' => $pathParts ?? []
    ];
    
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint not found: "' . $endpoint . '"',
        'available_endpoints' => array_keys($endpoints),
        'debug' => $debugInfo, // Remove this in production
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    exit;
}

// Include and execute the API endpoint
$apiFile = __DIR__ . '/' . $endpoints[$endpoint];
if (file_exists($apiFile)) {
    require_once $apiFile;
} else {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'API endpoint file not found',
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    exit;
}
?>

