<?php
/**
 * GenzNewz — Admin Activity Log Controller
 */

declare(strict_types=1);

class AdminActivityLogController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $logs = ActivityLog::paginate($page, 30, '1=1', [], 'id DESC');

        $this->view('admin/views/activity_logs/index', [
            'pageTitle' => 'সিস্টেম অডিট ও সিকিউরিটি লগ — ' . APP_NAME,
            'logs' => $logs
        ]);
    }
}
