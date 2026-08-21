<?php
/**
 * GenzNewz — Admin Dashboard Controller
 */

declare(strict_types=1);

class AdminDashboardController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $stats = [
            'total_editions' => Edition::count(),
            'today_editions' => Edition::count("edition_date = ?", [date('Y-m-d')]),
            'total_pages' => EditionPage::count(),
            'total_articles' => Article::count(),
            'pending_articles' => Article::count("status = 'submitted'"),
            'published_articles' => Article::count("status = 'published'"),
            'total_reporters' => User::count("role = 'reporter'"),
            'active_reporters' => User::count("role = 'reporter' AND status = 'active'"),
            'expired_id_cards' => ReporterProfile::count("valid_until < ?", [date('Y-m-d')])
        ];

        // Recent pending articles
        $pendingArticles = Article::query(
            "SELECT a.*, c.name as category_name, u.name as reporter_name 
             FROM articles a 
             JOIN categories c ON a.category_id = c.id 
             JOIN users u ON a.reporter_id = u.id 
             WHERE a.status = 'submitted' 
             ORDER BY a.created_at DESC LIMIT 5"
        );

        // Recent editions
        $recentEditions = Edition::query(
            "SELECT e.*, et.name as edition_type_name,
             (SELECT COUNT(*) FROM edition_pages WHERE edition_id = e.id) as page_count 
             FROM editions e 
             JOIN edition_types et ON e.edition_type_id = et.id 
             ORDER BY e.edition_date DESC, e.id DESC LIMIT 5"
        );

        // Recent activity logs
        $activityLogs = ActivityLog::getRecent(6);

        // Monthly stats for chart
        $monthlyArticles = [
            'labels' => ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট'],
            'data' => [45, 62, 78, 95, 110, 140, 185, 230]
        ];

        $this->view('admin/views/dashboard', [
            'pageTitle' => 'সুপার অ্যাডমিন ড্যাশবোর্ড',
            'stats' => $stats,
            'pendingArticles' => $pendingArticles,
            'recentEditions' => $recentEditions,
            'activityLogs' => $activityLogs,
            'monthlyArticles' => $monthlyArticles
        ]);
    }
}
