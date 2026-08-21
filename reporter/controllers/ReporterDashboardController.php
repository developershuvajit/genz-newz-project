<?php
/**
 * GenzNewz — Reporter Dashboard Controller
 */

declare(strict_types=1);

class ReporterDashboardController extends Controller {
    public function index(): void {
        Auth::requireReporter();

        $userId = Auth::id();
        $user = Auth::user();
        $profile = ReporterProfile::findByUserId($userId);

        $submittedCount = Article::count("reporter_id = ? AND status = 'submitted'", [$userId]);

        $stats = [
            'total_articles' => Article::count("reporter_id = ?", [$userId]),
            'draft_articles' => Article::count("reporter_id = ? AND status = 'draft'", [$userId]),
            'submitted_articles' => $submittedCount,
            'pending_articles' => $submittedCount,
            'approved_articles' => Article::count("reporter_id = ? AND status = 'approved'", [$userId]),
            'published_articles' => Article::count("reporter_id = ? AND status = 'published'", [$userId]),
            'rejected_articles' => Article::count("reporter_id = ? AND status = 'rejected'", [$userId]),
            'total_views' => (int)(Article::query("SELECT SUM(views_count) as total_views FROM articles WHERE reporter_id = ?", [$userId])[0]['total_views'] ?? 0)
        ];

        // Recent articles
        $recentArticles = Article::query(
            "SELECT a.*, c.name as category_name 
             FROM articles a 
             JOIN categories c ON a.category_id = c.id 
             WHERE a.reporter_id = ? 
             ORDER BY a.id DESC LIMIT 6",
            [$userId]
        );

        // Recent notifications
        $notifications = Notification::where('user_id', $userId, 'id DESC', 5);

        $this->view('reporter/views/dashboard', [
            'pageTitle' => 'রিপোর্টার ড্যাশবোর্ড — ' . ($user['name'] ?? 'সাংবাদিক'),
            'user' => $user,
            'profile' => $profile,
            'stats' => $stats,
            'recentArticles' => $recentArticles,
            'notifications' => $notifications
        ]);
    }
}
