<?php
/**
 * GenzNewz — Public Category Controller
 */

declare(strict_types=1);

class CategoryController extends Controller {
    public function show(string $slug): void {
        $category = Category::findBySlug($slug);

        if (!$category) {
            $this->error404('অনুরোধ করা ক্যাটাগরিটি খুঁজে পাওয়া যায়নি।');
            return;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $db = Database::getConnection();
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM articles WHERE category_id = ? AND status = 'published'");
        $countStmt->execute([$category['id']]);
        $total = (int)($countStmt->fetch()['total'] ?? 0);

        $stmt = $db->prepare(
            "SELECT a.*, u.name as reporter_name, rp.reporter_id 
             FROM articles a 
             JOIN users u ON a.reporter_id = u.id 
             LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
             WHERE a.category_id = ? AND a.status = 'published' 
             ORDER BY a.published_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->execute([$category['id'], $perPage, $offset]);
        $articles = $stmt->fetchAll();

        $allCategories = Category::getActive();

        $this->view('frontend/category', [
            'pageTitle' => "{$category['name']} ({$category['name_en']}) সংবাদ — " . APP_NAME,
            'category' => $category,
            'articles' => $articles,
            'allCategories' => $allCategories,
            'current_page' => $page,
            'total_pages' => max(1, (int)ceil($total / $perPage)),
            'total' => $total
        ]);
    }
}
