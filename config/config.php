<?php
require_once 'constants.php';
require_once 'database.php';

// Error Reporting (Production: 0)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
session_name(SESSION_NAME);
session_start();

// Include Core Files
require_once ROOT_PATH . '/core/Session.php';
require_once ROOT_PATH . '/core/Helper.php';
require_once ROOT_PATH . '/core/Validator.php';
require_once ROOT_PATH . '/core/CSRF.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Router.php';

// Initialize Router FIRST
$router = new Router();

// Then load routes
require_once ROOT_PATH . '/routes/web.php';

// Dispatch router
$router->dispatch();
?>