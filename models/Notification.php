<?php
/**
 * GenzNewz — Notification Model
 */

declare(strict_types=1);

class Notification extends Model {
    protected static string $table = 'notifications';

    public static function getUnreadForUser(int $userId): array {
        return self::query("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY id DESC LIMIT 20", [$userId]);
    }

    public static function createNotification(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): int {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'is_read' => 0
        ]);
    }
}
