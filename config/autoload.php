<?php
/**
 * Autoloader
 * Automatically loads classes when needed
 */

spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/../classes/',
        __DIR__ . '/../config/',
        __DIR__ . '/../controllers/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>

