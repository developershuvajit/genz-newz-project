<?php
/**
 * GenzNewz — Admin Backup Controller
 */

declare(strict_types=1);

class AdminBackupController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $this->view('admin/views/settings/backup', [
            'pageTitle' => 'ডেটাবেস ব্যাকআপ ও রিস্টোর — ' . APP_NAME
        ]);
    }

    public function download(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verify();
        }

        $db = Database::getConnection();
        $tables = [
            'users', 'reporter_profiles', 'edition_types', 'editions',
            'edition_pages', 'categories', 'articles', 'article_media',
            'notifications', 'activity_logs', 'settings', 'media_library'
        ];

        $backupContent = "-- GenzNewz Database Backup\n";
        $backupContent .= "-- Exported on " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $stmt = $db->query("SELECT * FROM {$table}");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                $backupContent .= "-- Table: {$table}\n";
                foreach ($rows as $row) {
                    $cols = array_keys($row);
                    $escapedVals = array_map(function($v) use ($db) {
                        return $v === null ? 'NULL' : $db->quote((string)$v);
                    }, array_values($row));

                    $backupContent .= "INSERT INTO `{$table}` (`" . implode('`, `', $cols) . "`) VALUES (" . implode(', ', $escapedVals) . ");\n";
                }
                $backupContent .= "\n";
            }
        }

        $filename = 'genznewz_backup_' . date('Y-m-d_His') . '.sql';

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($backupContent));

        Auth::logActivity('DATABASE_BACKUP_DOWNLOADED', 'Downloaded database backup SQL file.');
        echo $backupContent;
        exit;
    }
}
