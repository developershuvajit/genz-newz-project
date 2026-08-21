<?php
/**
 * GenzNewz — Category Model
 */

declare(strict_types=1);

class Category extends Model
{
    protected static string $table = 'categories';

    public static function getActive(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM " . static::$table . " WHERE status = 'active' ORDER BY sort_order ASC, name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM " . static::$table . " ORDER BY sort_order ASC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function getBySlug(string $slug): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE slug = :slug LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::getBySlug($slug);
    }

    public static function create(array $data): int
    {
        if (empty($data['slug'])) {
            $data['slug'] = Helper::slugify($data['name_en'] ?? $data['name'] ?? 'category');
        }
        $fields = ['name', 'name_en', 'slug', 'description', 'status', 'sort_order'];
        $insertData = [];
        foreach ($fields as $f) {
            if (isset($data[$f])) {
                $insertData[$f] = $data[$f];
            }
        }
        $insertData['created_at'] = date('Y-m-d H:i:s');

        $cols = implode(', ', array_keys($insertData));
        $placeholders = ':' . implode(', :', array_keys($insertData));

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO " . static::$table . " ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($insertData);
        return (int) $db->lastInsertId();
    }
}
