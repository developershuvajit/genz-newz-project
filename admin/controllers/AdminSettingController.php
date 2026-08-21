<?php
/**
 * GenzNewz — Admin Setting Controller
 */

declare(strict_types=1);

class AdminSettingController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $db = Database::getConnection();
        $stmt = $db->query("SELECT key_name, key_value FROM settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $this->view('admin/views/settings/index', [
            'pageTitle' => 'পোর্টাল সেটিংস — ' . APP_NAME,
            'settings' => $settings
        ]);
    }

    public function update(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $keys = [
            'site_name', 'site_title', 'site_tagline', 'seo_description',
            'contact_email', 'contact_phone', 'contact_address', 'footer_text',
            'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube',
            'breaking_news_enabled', 'epaper_download_enabled', 'maintenance_mode'
        ];

        foreach ($keys as $key) {
            if (isset($_POST[$key])) {
                Setting::set($key, trim($_POST[$key]));
            }
        }

        Auth::logActivity('SETTINGS_UPDATED', 'Updated website and portal configurations.');
        Session::setFlash('success', 'পোর্টাল সেটিংস সফলভাবে সংরক্ষিত হয়েছে।');
        $this->redirect('/admin/settings');
    }
}
