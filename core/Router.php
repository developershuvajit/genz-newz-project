<?php
/**
 * GenzNewz — Central Request Router & Clean URL Engine
 */

declare(strict_types=1);

class Router {
    private static array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function get(string $path, string|callable $handler): void {
        self::$routes['GET'][$path] = $handler;
    }

    public static function post(string $path, string|callable $handler): void {
        self::$routes['POST'][$path] = $handler;
    }

    public static function any(string $path, string|callable $handler): void {
        self::$routes['GET'][$path] = $handler;
        self::$routes['POST'][$path] = $handler;
    }

    public static function dispatch(string $uri, string $method): void {
        // Strip query string and trailing slashes
        $cleanUri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $cleanUri = rtrim($cleanUri, '/');
        if ($cleanUri === '') {
            $cleanUri = '/';
        }

        // Redirection of old .php files
        if (str_ends_with($cleanUri, '.php')) {
            $newUri = preg_replace('~\.php$~i', '', $cleanUri);
            if ($newUri === '/index') $newUri = '/';
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$newUri}");
            exit;
        }

        $methodRoutes = self::$routes[$method] ?? [];

        // Direct exact match
        if (isset($methodRoutes[$cleanUri])) {
            self::executeHandler($methodRoutes[$cleanUri], []);
            return;
        }

        // Dynamic parameterized match (e.g., /edition/{slug}/page/{page})
        foreach ($methodRoutes as $routePattern => $handler) {
            $pattern = preg_replace('~\{([a-zA-Z0-9_]+)\}~', '(?P<$1>[^/]+)', $routePattern);
            $pattern = "~^" . $pattern . "$~u";

            if (preg_match($pattern, $cleanUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                self::executeHandler($handler, $params);
                return;
            }
        }

        // Route not found -> 404
        http_response_code(404);
        require_once ROOT_PATH . '/controllers/HomeController.php';
        $home = new HomeController();
        $home->error404();
    }

    private static function executeHandler(string|callable $handler, array $params): void {
        $positionalParams = array_values($params);

        if (is_callable($handler)) {
            call_user_func_array($handler, $positionalParams);
            return;
        }

        if (str_contains($handler, '@')) {
            [$controllerName, $action] = explode('@', $handler);

            // Locate controller file
            $controllerPath = ROOT_PATH . '/controllers/' . $controllerName . '.php';
            if (str_starts_with($controllerName, 'Admin')) {
                $controllerPath = ROOT_PATH . '/admin/controllers/' . $controllerName . '.php';
            } elseif (str_starts_with($controllerName, 'Reporter')) {
                $controllerPath = ROOT_PATH . '/reporter/controllers/' . $controllerName . '.php';
            }

            if (!file_exists($controllerPath)) {
                http_response_code(500);
                die("Controller {$controllerName} not found at {$controllerPath}");
            }

            require_once $controllerPath;
            $controller = new $controllerName();

            if (!method_exists($controller, $action)) {
                http_response_code(500);
                die("Action {$action} does not exist in {$controllerName}");
            }

            call_user_func_array([$controller, $action], $positionalParams);
            return;
        }

        http_response_code(500);
        die("Invalid route handler specified.");
    }
}
