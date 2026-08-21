<?php
/**
 * GenzNewz — Reporter Profile Model
 */

class ReporterProfile extends Model {
    protected static string $table = 'reporter_profiles';

    public static function findByReporterId(string $reporterId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT rp.*, u.name as user_name, u.email as user_email, u.status as user_status, u.created_at as account_created 
                              FROM reporter_profiles rp 
                              JOIN users u ON rp.user_id = u.id 
                              WHERE rp.reporter_id = ? LIMIT 1");
        $stmt->execute([$reporterId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByUserId(int $userId): ?array {
        return self::findBy('user_id', $userId);
    }
}
