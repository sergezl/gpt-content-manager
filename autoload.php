<?php

spl_autoload_register(static function (string $class): void {
    // The namespace prefix that this plugin handles
    $prefix = 'SZ\\';
    $base_dir = GCM_DIR . 'src/'; // folder with classes

    // Making sure that the class belongs to our namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Converting the class name to the file path
    $relative = substr($class, $len);
    $relative = ltrim($relative, '\\');
    $file = $base_dir . str_replace('\\', '/', $relative) . '.php';

    // If the file exists, connect
    if (is_readable($file)) {
        require_once $file;
    }
});