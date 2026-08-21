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
        $uri = trim($uri, '/');
        
        // Handle static assets
        if (strpos($uri, 'assets/') === 0) {
            return false;
        }
        
        // Find matching route
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([a-z]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->params = $matches;
                $this->currentRoute = $route;
                return $this->handle($handler);
            }
        }
        
        // 404 Not Found
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
            
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                if (class_exists($controllerClass)) {
                    $obj = new $controllerClass();
                    return call_user_func_array([$obj, $method], $this->params);
                }
            }
        }
        
        $this->handle404();
    }
    
    private function handle404() {
        header("HTTP/1.0 404 Not Found");
        require_once VIEWS_PATH . '/errors/404.php';
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