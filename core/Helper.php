<?php
class Helper {
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
        // Simplified example
        $months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
        $ts = strtotime($date);
        $day = date('d', $ts);
        $month = $months[(int)date('m', $ts) - 1];
        $year = date('Y', $ts);
        return "{$day} {$month} {$year}";
    }
    
    public static function redirect($url) {
        header("Location: $url");
        exit;
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