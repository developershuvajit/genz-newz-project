<?php
/**
 * GenzNewz — Base Model Class
 */

abstract class Model {
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    public static function getTable(): string {
        return static::$table;
    }

    public static function all(string $orderBy = 'id DESC', ?int $limit = null): array {
        $db = Database::getConnection();
        $table = static::$table;
        $sql = "SELECT * FROM {$table} ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function find(int|string $id): ?array {
        $db = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE {$pk} = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findBy(string $column, mixed $value): ?array {
        $db = Database::getConnection();
        $table = static::$table;
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function where(string $column, mixed $value, string $orderBy = 'id DESC', ?int $limit = null): array {
        $db = Database::getConnection();
        $table = static::$table;
        $sql = "SELECT * FROM {$table} WHERE {$column} = ? ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public static function count(string $where = '1=1', array $params = []): int {
        $db = Database::getConnection();
        $table = static::$table;
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM {$table} WHERE {$where}");
        $stmt->execute($params);
        $res = $stmt->fetch();
        return (int)($res['total'] ?? 0);
    }

    public static function create(array $data): int {
        $db = Database::getConnection();
        $table = static::$table;
        
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return (int)$db->lastInsertId();
    }

    public static function update(int|string $id, array $data): bool {
        $db = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;
        
        $setParts = [];
        $values = [];
        foreach ($data as $col => $val) {
            $setParts[] = "{$col} = ?";
            $values[] = $val;
        }
        
        $values[] = $id;
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE {$pk} = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function delete(int|string $id): bool {
        $db = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$pk} = ?");
        return $stmt->execute([$id]);
    }

    public static function query(string $sql, array $params = []): array {
        $db = Database::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function paginate(int $page = 1, int $perPage = 12, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array {
        $offset = ($page - 1) * $perPage;
        $total = self::count($where, $params);
        $totalPages = ceil($total / $perPage);

        $db = Database::getConnection();
        $table = static::$table;
        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return [
            'data' => $items,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => max(1, (int)$totalPages),
            'has_more' => $page < $totalPages
        ];
    }
}
