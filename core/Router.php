<?php
class Router {
    private $routes = [];
    private $params = [];
    private $currentRoute = '';
    
    public function add($route, $handler, $method = 'GET') {
        $route = trim($route, '/');
        $this->routes[$method][$route] = $handler;
        return $this;
    }
    
    public function get($route, $handler) {
        return $this->add($route, $handler, 'GET');
    }
    
    public function post($route, $handler) {
        return $this->add($route, $handler, 'POST');
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Debug: Log the URI
        error_log("Request URI: " . $uri);
        
        // Remove base path for MAMP
        $basePath = '/2026/news';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        $uri = trim($uri, '/');
        
        // Debug: Log the cleaned URI
        error_log("Cleaned URI: " . $uri);
        
        // Handle static assets
        if (strpos($uri, 'assets/') === 0) {
            return false;
        }
        
        // Debug: Log all routes
        error_log("Available GET routes: " . print_r(array_keys($this->routes['GET'] ?? []), true));
        
        // Find matching route
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([a-z]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            // Debug: Check if route matches
            error_log("Checking route: $route with pattern: $pattern against URI: $uri");
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->params = $matches;
                $this->currentRoute = $route;
                error_log("Route matched: $route");
                return $this->handle($handler);
            }
        }
        
        // 404 Not Found
        error_log("No route found for URI: $uri");
        $this->handle404();
    }
    
    private function handle($handler) {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $this->params);
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $method) = explode('@', $handler);
            $controllerClass = $controller . 'Controller';
            $controllerPath = ROOT_PATH . '/controllers/' . $controllerClass . '.php';
            
            error_log("Looking for controller: $controllerPath");
            
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                if (class_exists($controllerClass)) {
                    $obj = new $controllerClass();
                    return call_user_func_array([$obj, $method], $this->params);
                } else {
                    error_log("Class $controllerClass not found");
                }
            } else {
                error_log("Controller file not found: $controllerPath");
            }
        }
        
        $this->handle404();
    }
    
    private function handle404() {
        header("HTTP/1.0 404 Not Found");
        $errorView = VIEWS_PATH . '/errors/404.php';
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            echo "404 - Page Not Found (Error view not found)";
        }
        exit;
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function getCurrentRoute() {
        return $this->currentRoute;
    }
}
?>