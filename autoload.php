<?php
/**
 * PSR-4 Autoloader
 */

spl_autoload_register(function ($class) {
    // Namespace prefix => base directory mapping
    $prefixes = [
        'Core\\' => __DIR__ . '/core/',
        'Modules\\' => __DIR__ . '/modules/',
        'Shared\\' => __DIR__ . '/shared/',
        'Api\\' => __DIR__ . '/api/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        
        $relativeClass = substr($class, $len);
        // Klasör adlarını küçük harfe çevir (Linux case-sensitive)
        $parts = explode('\\', $relativeClass);
        $className = array_pop($parts); // Son eleman class adı, onu olduğu gibi bırak
        $path = implode('/', array_map('strtolower', $parts));
        
        $file = $baseDir . ($path ? $path . '/' : '') . $className . '.php';
        
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Error handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Exception handler
set_exception_handler(function ($e) {
    $app = \Core\App::getInstance();
    
    if ($app->isDebug()) {
        \Core\Response::json([
            'success' => false,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    } else {
        error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        \Core\Response::serverError();
    }
});

