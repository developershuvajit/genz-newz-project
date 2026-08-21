<?php
/**
 * GenzNewz — Reporter Notification Controller
 */

declare(strict_types=1);

class ReporterNotificationController extends Controller {
    public function index(): void {
        Auth::requireReporter();

        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId, 'id DESC', 20);

        // Mark as read
        $db = Database::getConnection();
        $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$userId]);

        $this->view('reporter/views/notifications/index', [
            'pageTitle' => 'বিজ্ঞপ্তি ও নোটিফিকেশন',
            'notifications' => $notifications
        ]);
    }
}
