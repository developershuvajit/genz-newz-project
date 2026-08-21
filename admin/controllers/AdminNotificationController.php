<?php
/**
 * GenzNewz — Admin Notification Controller
 */

declare(strict_types=1);

class AdminNotificationController extends Controller {
    public function index(): void {
        Auth::requireAdmin();
        $notifications = Notification::where('user_id', Auth::id(), 'id DESC', 30);

        // Mark all read
        $db = Database::getConnection();
        $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([Auth::id()]);

        $this->view('admin/views/notifications/index', [
            'pageTitle' => 'বিজ্ঞপ্তি ও নোটিফিকেশন',
            'notifications' => $notifications
        ]);
    }
}
