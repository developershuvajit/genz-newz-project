<?php
/**
 * GenzNewz — Activity Log Model
 */

declare(strict_types=1);

class ActivityLog extends Model {
    protected static string $table = 'activity_logs';

    public static function getRecent(int $limit = 30): array {
        return self::all('id DESC', $limit);
    }
}
