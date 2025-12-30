<?php
/**
 * API Test/Debug File
 * Use this to test if the API is accessible and debug routing issues
 */

header('Content-Type: application/json');

$info = [
    'server_info' => [
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'Not set',
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'Not set',
        'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? 'Not set',
        'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'Not set',
        'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Not set',
    ],
    'path_info' => [
        'dirname_script' => dirname($_SERVER['SCRIPT_NAME'] ?? ''),
        'basename_script' => basename($_SERVER['SCRIPT_NAME'] ?? ''),
        'parsed_path' => parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH),
    ],
    'files' => [
        'index_exists' => file_exists(__DIR__ . '/index.php'),
        'config_exists' => file_exists(__DIR__ . '/config.php'),
        'company_profile_api_exists' => file_exists(__DIR__ . '/CompanyProfileApi.php'),
    ],
    'directory' => __DIR__,
    'message' => 'API test endpoint - If you see this, the API folder is accessible'
];

echo json_encode($info, JSON_PRETTY_PRINT);
?>

