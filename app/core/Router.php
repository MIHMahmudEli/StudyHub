<?php

class Router {
    protected $routes = [];

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Trim slashes
        $uri = trim($uri, '/');

        foreach ($this->routes as $route) {
            $routePath = trim($route['path'], '/');
            
            if ($route['method'] === $method && $routePath === $uri) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                require_once "../app/controllers/{$controllerName}.php";
                
                $controller = new $controllerName();
                $controller->$actionName();
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
