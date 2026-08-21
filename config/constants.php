<?php
// Application Paths
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEWS_PATH', ROOT_PATH . '/views');

// Storage Sub-directories
define('PAGES_ORIGINAL', STORAGE_PATH . '/pages/original/');
define('PAGES_MEDIUM', STORAGE_PATH . '/pages/medium/');
define('PAGES_THUMB', STORAGE_PATH . '/pages/thumb/');
define('UPLOADS_PATH', STORAGE_PATH . '/uploads/');
define('PDF_PATH', STORAGE_PATH . '/pdf/');
define('LOGS_PATH', STORAGE_PATH . '/logs/');

// Default Settings
define('SITE_NAME', 'GenzNewz');
define('SITE_TAGLINE', 'Your News. Your Voice.');

// Security
define('CSRF_TOKEN_LENGTH', 32);
define('PASSWORD_MIN_LENGTH', 8);

// File Upload Limits
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'pdf']);

// Pagination
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Session
define('SESSION_NAME', 'genznewz_session');

// Timezone
date_default_timezone_set('Asia/Kolkata');
?>