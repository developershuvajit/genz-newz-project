<?php
/**
 * GenzNewz — Global Constants Definition
 */

// Application Info
defined('APP_NAME') || define('APP_NAME', 'GenzNewz');
defined('APP_TITLE') || define('APP_TITLE', 'GenzNewz — Latest News & ePaper');
defined('APP_TAGLINE') || define('APP_TAGLINE', 'Your News. Your Voice.');
defined('APP_VERSION') || define('APP_VERSION', '1.0.0');

// Environment & Paths
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__));
defined('STORAGE_PATH') || define('STORAGE_PATH', ROOT_PATH . '/storage');
defined('PUBLIC_PATH') || define('PUBLIC_PATH', ROOT_PATH . '/public');

// URLs
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:3000';
defined('BASE_URL') || define('BASE_URL', $protocol . $host);
defined('APP_URL') || define('APP_URL', BASE_URL);
defined('SITE_URL') || define('SITE_URL', BASE_URL);
defined('ASSET_URL') || define('ASSET_URL', BASE_URL . '/public/assets');
defined('STORAGE_URL') || define('STORAGE_URL', BASE_URL . '/storage');

// Brand Colors
defined('COLOR_PRIMARY_GREEN') || define('COLOR_PRIMARY_GREEN', '#0B6B3A');
defined('COLOR_DARK_GREEN') || define('COLOR_DARK_GREEN', '#064D2B');
defined('COLOR_DEEP_GREEN') || define('COLOR_DEEP_GREEN', '#03351E');
defined('COLOR_ACCENT_GREEN') || define('COLOR_ACCENT_GREEN', '#19A463');
defined('COLOR_LIGHT_GREEN') || define('COLOR_LIGHT_GREEN', '#EAF7EF');
defined('COLOR_WHITE') || define('COLOR_WHITE', '#FFFFFF');
defined('COLOR_TEXT_DARK') || define('COLOR_TEXT_DARK', '#1F2937');

// Statuses
defined('STATUS_DRAFT') || define('STATUS_DRAFT', 'draft');
defined('STATUS_SUBMITTED') || define('STATUS_SUBMITTED', 'submitted');
defined('STATUS_APPROVED') || define('STATUS_APPROVED', 'approved');
defined('STATUS_REJECTED') || define('STATUS_REJECTED', 'rejected');
defined('STATUS_PUBLISHED') || define('STATUS_PUBLISHED', 'published');
defined('STATUS_ARCHIVED') || define('STATUS_ARCHIVED', 'archived');
defined('STATUS_ACTIVE') || define('STATUS_ACTIVE', 'active');
defined('STATUS_INACTIVE') || define('STATUS_INACTIVE', 'inactive');

// User Roles
defined('ROLE_ADMIN') || define('ROLE_ADMIN', 'admin');
defined('ROLE_REPORTER') || define('ROLE_REPORTER', 'reporter');
