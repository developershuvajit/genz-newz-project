<?php
/**
 * GenzNewz — Edition Types & Editions Models
 */

declare(strict_types=1);

class EditionType extends Model {
    protected static string $table = 'edition_types';

    public static function getActive(): array {
        return self::where('status', 'active', 'sort_order ASC, name ASC');
    }
}

class Edition extends Model {
    protected static string $table = 'editions';

    public static function findBySlug(string $slug): ?array {
        return self::findBy('slug', $slug);
    }

    public static function getPages(int|string $editionId): array {
        return EditionPage::getPagesForEdition((int)$editionId);
    }

    public static function getTodayEdition(?int $editionTypeId = null): ?array {
        $db = Database::getConnection();
        $sql = "SELECT e.*, et.name as edition_type_name, et.slug as edition_type_slug,
                (SELECT COUNT(*) FROM edition_pages WHERE edition_id = e.id AND status = 'active') as page_count
                FROM editions e 
                JOIN edition_types et ON e.edition_type_id = et.id 
                WHERE e.status = 'published'";
        
        if ($editionTypeId !== null) {
            $sql .= " AND e.edition_type_id = :typeId";
        }
        
        $sql .= " ORDER BY e.is_featured DESC, e.edition_date DESC LIMIT 1";
        
        $stmt = $db->prepare($sql);
        if ($editionTypeId !== null) {
            $stmt->execute([':typeId' => $editionTypeId]);
        } else {
            $stmt->execute();
        }
        
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findWithDetails(string $slug): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT e.*, et.name as edition_type_name, et.slug as edition_type_slug,
                              (SELECT COUNT(*) FROM edition_pages WHERE edition_id = e.id AND status = 'active') as page_count
                              FROM editions e 
                              JOIN edition_types et ON e.edition_type_id = et.id 
                              WHERE e.slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getArchiveList(array $filters = [], int $page = 1, int $perPage = 12): array {
        $db = Database::getConnection();
        $where = ["e.status IN ('published', 'archived')"];
        $params = [];

        if (!empty($filters['date'])) {
            $where[] = "e.edition_date = :date";
            $params[':date'] = $filters['date'];
        }

        if (!empty($filters['month']) && !empty($filters['year'])) {
            $where[] = "strftime('%m', e.edition_date) = :month AND strftime('%Y', e.edition_date) = :year";
            $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'mysql') {
                $where[count($where) - 1] = "MONTH(e.edition_date) = :month AND YEAR(e.edition_date) = :year";
            }
            $params[':month'] = sprintf('%02d', (int)$filters['month']);
            $params[':year'] = (string)$filters['year'];
        } elseif (!empty($filters['year'])) {
            $where[] = "strftime('%Y', e.edition_date) = :year";
            $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'mysql') {
                $where[count($where) - 1] = "YEAR(e.edition_date) = :year";
            }
            $params[':year'] = (string)$filters['year'];
        }

        if (!empty($filters['type_id'])) {
            $where[] = "e.edition_type_id = :type_id";
            $params[':type_id'] = (int)$filters['type_id'];
        }

        $whereClause = implode(' AND ', $where);

        // Count total
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM editions e WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int)($countStmt->fetch()['total'] ?? 0);

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT e.*, et.name as edition_type_name,
                (SELECT COUNT(*) FROM edition_pages WHERE edition_id = e.id AND status = 'active') as page_count
                FROM editions e 
                JOIN edition_types et ON e.edition_type_id = et.id 
                WHERE {$whereClause} 
                ORDER BY e.edition_date DESC, e.id DESC 
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
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

class EditionPage extends Model {
    protected static string $table = 'edition_pages';

    public static function getPagesForEdition(int $editionId): array {
        return self::where('edition_id', $editionId, 'page_number ASC');
    }

    public static function getPage(int $editionId, int $pageNumber): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM edition_pages WHERE edition_id = ? AND page_number = ? LIMIT 1");
        $stmt->execute([$editionId, $pageNumber]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
