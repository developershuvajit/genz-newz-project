<?php
/**
 * GenzNewz — Media Library Model
 */

declare(strict_types=1);

class MediaLibrary extends Model {
    protected static string $table = 'media_library';

    public static function getRecent(int $limit = 50): array {
        return self::all('id DESC', $limit);
    }
}
