<?php

declare(strict_types=1);

// Get current request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Normalize trailing slashes (optional, for consistency)
if ($requestUri !== '/' && str_ends_with($requestUri, '/')) {
    $requestUri = rtrim($requestUri, '/');
}

// Define routes
$routes = [
    'GET' => [
        '/' => fn() => require APP_PATH . '/views/home.php',
        '/login' => fn() => require APP_PATH . '/views/login.php',
        '/register' => fn() => require APP_PATH . '/views/register.php',
        '/feed' => fn() => require APP_PATH . '/views/feed.php',
    ],
    'POST' => [
    
    '/login' => function() { echo "Handle login submission"; },
    '/register' => function() { echo "Handle registration submission"; },
],
];

// Check if route exists
if (isset($routes[$requestMethod][$requestUri])) {
    $handler = $routes[$requestMethod][$requestUri];
    $handler();
} else {
    // Route not found → show 404
    http_response_code(404);
    require APP_PATH . '/views/404.php';
}

