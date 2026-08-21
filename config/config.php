<?php
/**
 * GenzNewz — Global Configuration
 */

require_once __DIR__ . '/constants.php';

return [
    'app' => [
        'name' => APP_NAME,
        'title' => APP_TITLE,
        'tagline' => APP_TAGLINE,
        'timezone' => 'Asia/Kolkata',
        'debug' => true,
        'locale' => 'bn_IN',
    ],
    'database' => [
        'driver' => getenv('DB_DRIVER') ?: 'sqlite', // 'mysql' or 'sqlite'
        'mysql' => [
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'port' => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: 'genznewz',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') ?: '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        'sqlite' => [
            'path' => STORAGE_PATH . '/database.sqlite',
        ]
    ],
    'uploads' => [
        'max_file_size' => 20 * 1024 * 1024, // 20MB
        'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'],
        'allowed_pdf_types' => ['application/pdf'],
        'page_sizes' => [
            'thumb' => [300, 420],
            'medium' => [800, 1120],
            'original' => [1800, 2520],
        ]
    ],
    'auth' => [
        'session_lifetime' => 86400 * 7, // 7 days
        'password_min_length' => 6,
    ],
    'pagination' => [
        'per_page' => 12,
        'admin_per_page' => 15,
    ]
];
