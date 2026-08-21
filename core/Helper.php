<?php
/**
 * GenzNewz — Global Helper Functions
 */

declare(strict_types=1);

class Helper {
    private static array $bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    private static array $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    private static array $bengaliMonths = [
        'January' => 'জানুয়ারি',
        'February' => 'ফেব্রুয়ারি',
        'March' => 'মার্চ',
        'April' => 'এপ্রিল',
        'May' => 'মে',
        'June' => 'জুন',
        'July' => 'জুলাই',
        'August' => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর',
        'November' => 'নভেম্বর',
        'December' => 'ডিসেম্বর'
    ];

    private static array $bengaliDays = [
        'Sunday' => 'রবিবার',
        'Monday' => 'সোমবার',
        'Tuesday' => 'মঙ্গলবার',
        'Wednesday' => 'বুধবার',
        'Thursday' => 'বৃহস্পতিবার',
        'Friday' => 'শুক্রবার',
        'Saturday' => 'শনিবার'
    ];

    public static function e(mixed $value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    public static function formatBengaliNumber(null|int|float|string $number): string {
        return str_replace(self::$englishDigits, self::$bengaliDigits, (string)($number ?? 0));
    }

    public static function formatBengaliDate(string $dateString = 'now', string $format = 'j F Y, l'): string {
        $timestamp = is_numeric($dateString) ? (int)$dateString : strtotime($dateString);
        if (!$timestamp) {
            $timestamp = time();
        }

        $dayNum = date('j', $timestamp);
        $monthEn = date('F', $timestamp);
        $yearNum = date('Y', $timestamp);
        $dayNameEn = date('l', $timestamp);

        $dayBn = self::formatBengaliNumber($dayNum);
        $monthBn = self::$bengaliMonths[$monthEn] ?? $monthEn;
        $yearBn = self::formatBengaliNumber($yearNum);
        $dayNameBn = self::$bengaliDays[$dayNameEn] ?? $dayNameEn;

        return "{$dayBn} {$monthBn} {$yearBn}, {$dayNameBn}";
    }

    public static function formatBengaliDateShort(string $dateString): string {
        $timestamp = strtotime($dateString);
        if (!$timestamp) return $dateString;
        $dayBn = self::formatBengaliNumber(date('j', $timestamp));
        $monthBn = self::$bengaliMonths[date('F', $timestamp)] ?? date('M', $timestamp);
        $yearBn = self::formatBengaliNumber(date('Y', $timestamp));
        return "{$dayBn} {$monthBn} {$yearBn}";
    }

    public static function slugify(string $text): string {
        // Bengali & English slug generator
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = mb_strtolower($text, 'UTF-8');
        return empty($text) ? 'n-a-' . time() : $text;
    }

    public static function timeAgo(string $datetime): string {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'এইমাত্র';
        if ($diff < 3600) return self::formatBengaliNumber((int)floor($diff / 60)) . ' মিনিট আগে';
        if ($diff < 86400) return self::formatBengaliNumber((int)floor($diff / 3600)) . ' ঘণ্টা আগে';
        if ($diff < 2592000) return self::formatBengaliNumber((int)floor($diff / 86400)) . ' দিন আগে';
        return self::formatBengaliDateShort($datetime);
    }

    public static function excerpt(string $text, int $limit = 120): string {
        $clean = strip_tags($text);
        if (mb_strlen($clean, 'UTF-8') <= $limit) {
            return $clean;
        }
        return mb_substr($clean, 0, $limit, 'UTF-8') . '...';
    }

    public static function truncate(string $text, int $limit = 120): string {
        return self::excerpt($text, $limit);
    }

    public static function sanitize(string $data): string {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function getSetting(string $key, ?string $default = null): ?string {
        static $settingsCache = null;
        if ($settingsCache === null) {
            try {
                $db = Database::getConnection();
                $stmt = $db->query("SELECT key_name, key_value FROM settings");
                $settingsCache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (Exception $e) {
                $settingsCache = [];
            }
        }
        return $settingsCache[$key] ?? $default;
    }

    public static function asset(string $path): string {
        return ASSET_URL . '/' . ltrim($path, '/');
    }

    public static function storage(string $path): string {
        return STORAGE_URL . '/' . ltrim($path, '/');
    }
}
