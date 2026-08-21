<?php
/**
 * GenzNewz — Public Article Controller
 */

declare(strict_types=1);

class ArticleController extends Controller {
    public function show(string $slug): void {
        $article = Article::findBySlugWithDetails($slug);

        if (!$article || $article['status'] !== 'published') {
            $this->error404('অনুরোধ করা প্রতিবেদনটি খুঁজে পাওয়া যায়নি বা এটি এখনো প্রকাশিত হয়নি।');
            return;
        }

        // Related articles from the same category
        $relatedArticles = Article::query(
            "SELECT a.*, c.name as category_name, c.slug as category_slug 
             FROM articles a 
             JOIN categories c ON a.category_id = c.id 
             WHERE a.category_id = ? AND a.id != ? AND a.status = 'published' 
             ORDER BY a.published_at DESC LIMIT 4",
            [$article['category_id'], $article['id']]
        );

        $trendingArticles = Article::query(
            "SELECT a.*, c.name as category_name 
             FROM articles a 
             JOIN categories c ON a.category_id = c.id 
             WHERE a.status = 'published' 
             ORDER BY a.views_count DESC, a.published_at DESC LIMIT 5"
        );

        $this->view('frontend/article', [
            'pageTitle' => "{$article['title']} — GenzNewz",
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'trendingArticles' => $trendingArticles
        ]);
    }
}
