<?php
/**
 * GENZNEWZ — CORE PHP ePAPER & NEWS MANAGEMENT SYSTEM
 * Primary Entry Point & Application Bootstrap
 */

declare(strict_types=1);

// Error reporting for development (clean in production)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

// Define project root path
defined('ROOT_PATH') || define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

// Load Core Framework
require_once ROOT_PATH . '/core/Session.php';
require_once ROOT_PATH . '/core/CSRF.php';
require_once ROOT_PATH . '/core/Helper.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Validator.php';
require_once ROOT_PATH . '/core/Upload.php';
require_once ROOT_PATH . '/core/ImageManager.php';
require_once ROOT_PATH . '/core/PDFManager.php';
require_once ROOT_PATH . '/core/Router.php';

// Start session
Session::start();

// Load Application Models
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/models/ReporterProfile.php';
require_once ROOT_PATH . '/models/Edition.php';
require_once ROOT_PATH . '/models/Article.php';
require_once ROOT_PATH . '/models/Notification.php';
require_once ROOT_PATH . '/models/Category.php';
require_once ROOT_PATH . '/models/ActivityLog.php';
require_once ROOT_PATH . '/models/Setting.php';
require_once ROOT_PATH . '/models/MediaLibrary.php';

// Load Web Routes
require_once ROOT_PATH . '/routes/web.php';

// Dispatch Request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

Router::dispatch($requestUri, $requestMethod);
