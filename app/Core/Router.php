<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($route, $action) {
        // Convert route to regex
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $action;
    }

    public function dispatch($url) {
        // Ensure leading slash for matching
        if (empty($url)) {
            $url = '/';
        } else {
            $url = '/' . ltrim($url, '/');
        }
        
        // Strip trailing slash
        $url = rtrim($url, '/');
        
        // Strip .php extension for backward compatibility with old URLs
        $url = preg_replace('/\.php$/i', '', $url);
        
        // Root path fallback
        if ($url === '') {
            $url = '/';
        }

        foreach ($this->routes as $route => $action) {
            if (preg_match($route, $url, $matches)) {
                $parts = explode('@', $action);
                $controllerName = "App\\Controllers\\" . $parts[0];
                $method = $parts[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $method)) {
                        call_user_func_array([$controller, $method], []);
                        return;
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}