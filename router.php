<?php

declare(strict_types=1);

// Get current request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Normalize trailing slashes
if ($requestUri !== '/' && str_ends_with($requestUri, '/')) {
    $requestUri = rtrim($requestUri, '/');
}

$routes = [
    'GET' => [
        '/' => fn() => require APP_PATH . '/views/home.php',
        '/login' => 'AuthController@loginForm',
        '/logout' => 'AuthController@logout',
        '/register' => 'AuthController@registerForm',
        '/feed' => 'FeedController@index',
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
    ],
];

if (isset($routes[$requestMethod][$requestUri])) {
    $handler = $routes[$requestMethod][$requestUri];

    if (is_callable($handler)) {
        // Case 1: Direct closure
        $handler();
    } elseif (is_string($handler)) {
        // Case 2: Controller reference, e.g. "AuthController@login"
        [$controllerName, $method] = explode('@', $handler);
        $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    http_response_code(500);
                    echo "Error: Method '$method' not found in $controllerName.";
                }
            } else {
                http_response_code(500);
                echo "Error: Controller class '$controllerName' not found.";
            }
        } else {
            http_response_code(500);
            echo "Error: Controller file '$controllerFile' not found.";
        }
    }
} else {
    // No route found → 404
    http_response_code(404);
    require APP_PATH . '/views/404.php';
}

