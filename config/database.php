<?php
/**
 * GenzNewz — Database PDO Wrapper & Bootstrap
 */

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/config.php';
            $dbConfig = $config['database'];

            if ($dbConfig['driver'] === 'mysql') {
                try {
                    $dsn = "mysql:host={$dbConfig['mysql']['host']};port={$dbConfig['mysql']['port']};dbname={$dbConfig['mysql']['database']};charset={$dbConfig['mysql']['charset']}";
                    self::$instance = new PDO($dsn, $dbConfig['mysql']['username'], $dbConfig['mysql']['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$dbConfig['mysql']['charset']} COLLATE {$dbConfig['mysql']['collation']}"
                    ]);
                } catch (PDOException $e) {
                    // Fall back gracefully to SQLite if MySQL is not reachable
                    error_log("MySQL connection failed: " . $e->getMessage() . ". Falling back to SQLite.");
                    self::$instance = self::createSqliteConnection($dbConfig['sqlite']['path']);
                }
            } else {
                self::$instance = self::createSqliteConnection($dbConfig['sqlite']['path']);
            }

            self::ensureTables();
        }

        return self::$instance;
    }

    private static function createSqliteConnection(string $path): PDO {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $pdo = new PDO("sqlite:" . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        $pdo->exec("PRAGMA foreign_keys = ON; PRAGMA journal_mode = WAL;");
        return $pdo;
    }

    public static function ensureTables(): void {
        $pdo = self::$instance;
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        // Check if users table exists
        try {
            $check = $pdo->query("SELECT 1 FROM users LIMIT 1");
        } catch (Exception $e) {
            self::initSchemaAndSeed($driver);
        }
    }

    public static function initSchemaAndSeed(string $driver): void {
        $pdo = self::$instance;

        // Create table schemas
        $isSqlite = ($driver === 'sqlite');
        $pk = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
        $timestamp = $isSqlite ? 'DATETIME DEFAULT CURRENT_TIMESTAMP' : 'DATETIME DEFAULT CURRENT_TIMESTAMP';

        $queries = [
            // Users table
            "CREATE TABLE IF NOT EXISTS users (
                id $pk,
                role VARCHAR(20) NOT NULL DEFAULT 'reporter',
                name VARCHAR(100) NOT NULL,
                email VARCHAR(150) UNIQUE NOT NULL,
                phone VARCHAR(30) NULL,
                password VARCHAR(255) NOT NULL,
                profile_image VARCHAR(255) NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'active',
                last_login DATETIME NULL,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Reporter Profiles table
            "CREATE TABLE IF NOT EXISTS reporter_profiles (
                id $pk,
                user_id INT NOT NULL,
                reporter_id VARCHAR(50) UNIQUE NOT NULL,
                employee_code VARCHAR(50) NULL,
                full_name VARCHAR(100) NOT NULL,
                father_name VARCHAR(100) NULL,
                date_of_birth DATE NULL,
                blood_group VARCHAR(10) NULL,
                phone VARCHAR(30) NOT NULL,
                email VARCHAR(150) NOT NULL,
                address TEXT NULL,
                city VARCHAR(80) NULL,
                state VARCHAR(80) DEFAULT 'West Bengal',
                pin_code VARCHAR(20) NULL,
                profile_photo VARCHAR(255) NULL,
                designation VARCHAR(80) DEFAULT 'Staff Reporter',
                joining_date DATE NULL,
                valid_until DATE NULL,
                assigned_area VARCHAR(100) DEFAULT 'Kolkata Bureau',
                emergency_contact VARCHAR(30) NULL,
                id_card_status VARCHAR(20) DEFAULT 'active',
                authorized_signature VARCHAR(255) NULL,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Edition Types table
            "CREATE TABLE IF NOT EXISTS edition_types (
                id $pk,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) UNIQUE NOT NULL,
                status VARCHAR(20) DEFAULT 'active',
                sort_order INT DEFAULT 0,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Editions table
            "CREATE TABLE IF NOT EXISTS editions (
                id $pk,
                title VARCHAR(200) NOT NULL,
                slug VARCHAR(200) UNIQUE NOT NULL,
                edition_date DATE NOT NULL,
                edition_type_id INT NOT NULL,
                description TEXT NULL,
                cover_image VARCHAR(255) NULL,
                pdf_file VARCHAR(255) NULL,
                status VARCHAR(20) DEFAULT 'published',
                is_featured INT DEFAULT 0,
                created_by INT NULL,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Edition Pages table
            "CREATE TABLE IF NOT EXISTS edition_pages (
                id $pk,
                edition_id INT NOT NULL,
                page_number INT NOT NULL,
                page_title VARCHAR(150) NULL,
                page_image VARCHAR(255) NOT NULL,
                thumbnail VARCHAR(255) NULL,
                medium_image VARCHAR(255) NULL,
                pdf_page VARCHAR(255) NULL,
                status VARCHAR(20) DEFAULT 'active',
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Categories table
            "CREATE TABLE IF NOT EXISTS categories (
                id $pk,
                name VARCHAR(100) NOT NULL,
                name_en VARCHAR(100) NULL,
                slug VARCHAR(100) UNIQUE NOT NULL,
                description TEXT NULL,
                status VARCHAR(20) DEFAULT 'active',
                sort_order INT DEFAULT 0,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Articles table
            "CREATE TABLE IF NOT EXISTS articles (
                id $pk,
                reporter_id INT NOT NULL,
                category_id INT NOT NULL,
                edition_id INT NULL,
                title VARCHAR(255) NOT NULL,
                subheadline VARCHAR(255) NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                short_description TEXT NULL,
                content TEXT NOT NULL,
                featured_image VARCHAR(255) NULL,
                author_name VARCHAR(100) NULL,
                location VARCHAR(100) DEFAULT 'কলকাতা',
                status VARCHAR(20) DEFAULT 'published',
                rejection_reason TEXT NULL,
                is_breaking INT DEFAULT 0,
                is_featured INT DEFAULT 0,
                is_top_story INT DEFAULT 0,
                views_count INT DEFAULT 0,
                published_at DATETIME NULL,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Article Media
            "CREATE TABLE IF NOT EXISTS article_media (
                id $pk,
                article_id INT NOT NULL,
                media_type VARCHAR(20) DEFAULT 'image',
                file_path VARCHAR(255) NOT NULL,
                caption VARCHAR(255) NULL,
                sort_order INT DEFAULT 0,
                created_at $timestamp
            )",

            // Notifications
            "CREATE TABLE IF NOT EXISTS notifications (
                id $pk,
                user_id INT NOT NULL,
                title VARCHAR(150) NOT NULL,
                message TEXT NOT NULL,
                type VARCHAR(30) DEFAULT 'info',
                link VARCHAR(255) NULL,
                is_read INT DEFAULT 0,
                created_at $timestamp
            )",

            // Activity Logs
            "CREATE TABLE IF NOT EXISTS activity_logs (
                id $pk,
                user_id INT NULL,
                user_name VARCHAR(100) NULL,
                action VARCHAR(100) NOT NULL,
                details TEXT NULL,
                ip_address VARCHAR(45) NULL,
                created_at $timestamp
            )",

            // Settings
            "CREATE TABLE IF NOT EXISTS settings (
                id $pk,
                key_name VARCHAR(80) UNIQUE NOT NULL,
                key_value TEXT NULL,
                created_at $timestamp,
                updated_at $timestamp
            )",

            // Media Library
            "CREATE TABLE IF NOT EXISTS media_library (
                id $pk,
                file_name VARCHAR(255) NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                file_type VARCHAR(50) NULL,
                file_size INT NULL,
                uploaded_by INT NULL,
                created_at $timestamp
            )"
        ];

        foreach ($queries as $sql) {
            $pdo->exec($sql);
        }

        // Seed initial data
        require_once ROOT_PATH . '/database/seed.php';
        seedDatabase($pdo);
    }
}
