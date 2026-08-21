<?php
/**
 * GenzNewz — Setting Model
 */

declare(strict_types=1);

class Setting extends Model {
    protected static string $table = 'settings';

    public static function get(string $key, ?string $default = null): ?string {
        return Helper::getSetting($key, $default);
    }

    public static function set(string $key, ?string $value): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM settings WHERE key_name = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();

        if ($row) {
            $upd = $db->prepare("UPDATE settings SET key_value = ?, updated_at = CURRENT_TIMESTAMP WHERE key_name = ?");
            $upd->execute([$value, $key]);
        } else {
            $ins = $db->prepare("INSERT INTO settings (key_name, key_value, created_at, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            $ins->execute([$key, $value]);
        }
    }
}
