<?php
/**
 * GenzNewz — User Model
 */

class User extends Model {
    protected static string $table = 'users';

    public static function getReporters(string $status = 'all'): array {
        $db = Database::getConnection();
        $sql = "SELECT u.*, rp.reporter_id, rp.employee_code, rp.full_name, rp.designation, rp.assigned_area, rp.valid_until, rp.id_card_status, rp.phone as rep_phone 
                FROM users u 
                LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                WHERE u.role = 'reporter'";
        
        if ($status !== 'all') {
            $sql .= " AND u.status = :status";
            $stmt = $db->prepare($sql . " ORDER BY u.id DESC");
            $stmt->execute([':status' => $status]);
        } else {
            $stmt = $db->query($sql . " ORDER BY u.id DESC");
        }

        return $stmt->fetchAll();
    }

    public static function generateReporterId(): string {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT reporter_id FROM reporter_profiles ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch();

        if ($last && !empty($last['reporter_id'])) {
            if (preg_match('/GNZ-RPT-(\d+)/', $last['reporter_id'], $matches)) {
                $nextNum = (int)$matches[1] + 1;
                return sprintf('GNZ-RPT-%04d', $nextNum);
            }
        }

        return 'GNZ-RPT-0001';
    }
}
