<?php
/**
 * GenzNewz — Category, Article, and ArticleMedia Models
 */

class Category extends Model {
    protected static string $table = 'categories';

    public static function getActive(): array {
        return self::where('status', 'active', 'sort_order ASC, name ASC');
    }

    public static function findBySlug(string $slug): ?array {
        return self::findBy('slug', $slug);
    }
}

class Article extends Model {
    protected static string $table = 'articles';

    public static function getPublished(int $limit = 10, ?int $categoryId = null): array {
        $db = Database::getConnection();
        $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug, u.name as reporter_name, rp.reporter_id 
                FROM articles a 
                JOIN categories c ON a.category_id = c.id 
                JOIN users u ON a.reporter_id = u.id 
                LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                WHERE a.status = 'published'";

        $params = [];
        if ($categoryId !== null) {
            $sql .= " AND a.category_id = :catId";
            $params[':catId'] = $categoryId;
        }

        $sql .= " ORDER BY a.published_at DESC, a.id DESC LIMIT " . (int)$limit;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getBreakingNews(int $limit = 5): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT a.id, a.title, a.slug, a.published_at 
                              FROM articles a 
                              WHERE a.status = 'published' AND a.is_breaking = 1 
                              ORDER BY a.published_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public static function getFeaturedStory(): ?array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT a.*, c.name as category_name, c.slug as category_slug, u.name as reporter_name, rp.reporter_id 
                            FROM articles a 
                            JOIN categories c ON a.category_id = c.id 
                            JOIN users u ON a.reporter_id = u.id 
                            LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                            WHERE a.status = 'published' AND a.is_featured = 1 
                            ORDER BY a.published_at DESC LIMIT 1");
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getTopStories(int $limit = 4): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT a.*, c.name as category_name, c.slug as category_slug 
                              FROM articles a 
                              JOIN categories c ON a.category_id = c.id 
                              WHERE a.status = 'published' AND a.is_top_story = 1 
                              ORDER BY a.published_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public static function findBySlugWithDetails(string $slug): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT a.*, c.name as category_name, c.slug as category_slug, u.name as reporter_name, rp.reporter_id, rp.designation as reporter_designation, rp.profile_photo as reporter_photo 
                              FROM articles a 
                              JOIN categories c ON a.category_id = c.id 
                              JOIN users u ON a.reporter_id = u.id 
                              LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                              WHERE a.slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        if ($row) {
            // Increment view count
            $upd = $db->prepare("UPDATE articles SET views_count = views_count + 1 WHERE id = ?");
            $upd->execute([$row['id']]);
        }

        return $row ?: null;
    }

    public static function searchArticles(string $query, int $page = 1, int $perPage = 10): array {
        $db = Database::getConnection();
        $term = "%{$query}%";
        
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM articles a WHERE a.status = 'published' AND (a.title LIKE ? OR a.short_description LIKE ? OR a.content LIKE ?)");
        $countStmt->execute([$term, $term, $term]);
        $total = (int)($countStmt->fetch()['total'] ?? 0);

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug, u.name as reporter_name 
                FROM articles a 
                JOIN categories c ON a.category_id = c.id 
                JOIN users u ON a.reporter_id = u.id 
                WHERE a.status = 'published' AND (a.title LIKE ? OR a.short_description LIKE ? OR a.content LIKE ?) 
                ORDER BY a.published_at DESC LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $db->prepare($sql);
        $stmt->execute([$term, $term, $term]);
        $items = $stmt->fetchAll();

        return [
            'data' => $items,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int)ceil($total / $perPage))
        ];
    }
}
