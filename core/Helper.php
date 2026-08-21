<?php
class Helper {
    private static $baseUrl = null;
    
    /**
     * Get the base URL of the application
     */
    public static function getBaseUrl() {
        if (self::$baseUrl === null) {
            // Auto-detect base URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $scriptName = $_SERVER['SCRIPT_NAME'];
            
            // Remove public/index.php or index.php from script name
            $baseDir = dirname($scriptName);
            if ($baseDir == '/' || $baseDir == '\\') {
                $baseDir = '';
            }
            
            self::$baseUrl = $protocol . '://' . $host . $baseDir;
        }
        return self::$baseUrl;
    }
    
    /**
     * Generate a URL for a given path
     */
    public static function url($path = '') {
        $baseUrl = self::getBaseUrl();
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Generate a URL for an asset
     */
    public static function asset($path) {
        return self::url('assets/' . ltrim($path, '/'));
    }
    
    /**
     * Generate a URL for storage files
     */
    public static function storage($path) {
        return self::url('storage/' . ltrim($path, '/'));
    }
    
    /**
     * Redirect to a URL
     */
    public static function redirect($url) {
        header('Location: ' . self::url($url));
        exit;
    }
    
    // ... rest of existing methods ...
    
    public static function slugify($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
    
    public static function generateReporterId($lastId) {
        return 'GNZ-RPT-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }
    
    public static function formatBengaliDate($date) {
        $months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
        $ts = strtotime($date);
        $day = date('d', $ts);
        $month = $months[(int)date('m', $ts) - 1];
        $year = date('Y', $ts);
        return "{$day} {$month} {$year}";
    }
    
    public static function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    public static function getSetting($key) {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : null;
    }
}
?>