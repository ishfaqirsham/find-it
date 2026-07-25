<?php
// ==========================================
// ItemRepository
// Handles queries for BOTH lost_items and found_items. Which table
// and which class (LostItem/FoundItem) it uses depends on the
// $type parameter passed in - the calling code never needs an
// if/else, it just gets the right kind of object back.
// ==========================================
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/LostItem.php';
require_once __DIR__ . '/FoundItem.php';

class ItemRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Turns "lost" -> lost_items / LostItem, "found" -> found_items / FoundItem
    private function resolveTable(string $type): string
    {
        return $type === 'found' ? 'found_items' : 'lost_items';
    }

    private function buildItem(string $type, array $row): Item
    {
        return $type === 'found' ? new FoundItem($row) : new LostItem($row);
    }

    public function getRecent(string $type, int $limit = 4): array
    {
        $table = $this->resolveTable($type);
        // $limit is cast to int and never comes from user input here, so it's
        // safe to inline directly - LIMIT placeholders can misbehave under
        // PDO's emulated prepared statements.
        $rows = $this->db->fetchAll(
            "SELECT t.*, u.full_name FROM {$table} t 
             JOIN users u ON t.user_id = u.id 
             ORDER BY t.created_at DESC LIMIT " . (int) $limit
        );
        return array_map(fn($row) => $this->buildItem($type, $row), $rows);
    }

    public function search(string $type, string $keyword = '', string $category = ''): array
    {
        $table = $this->resolveTable($type);
        $sql = "SELECT t.*, u.full_name FROM {$table} t 
                JOIN users u ON t.user_id = u.id 
                WHERE t.status = 'pending'";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (t.item_name LIKE ? OR t.description LIKE ? OR t.location LIKE ?)";
            $like = "%{$keyword}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($category !== '') {
            $sql .= " AND t.category = ?";
            $params[] = $category;
        }

        $sql .= " ORDER BY t.created_at DESC";

        $rows = $this->db->fetchAll($sql, $params);
        return array_map(fn($row) => $this->buildItem($type, $row), $rows);
    }

    public function findById(string $type, int $id): ?Item
    {
        $table = $this->resolveTable($type);
        $row = $this->db->fetchOne(
            "SELECT t.*, u.full_name FROM {$table} t 
             JOIN users u ON t.user_id = u.id 
             WHERE t.id = ?",
            [$id]
        );
        return $row ? $this->buildItem($type, $row) : null;
    }

    public function getAll(string $type): array
    {
        $table = $this->resolveTable($type);
        $rows = $this->db->fetchAll(
            "SELECT t.*, u.full_name FROM {$table} t 
             JOIN users u ON t.user_id = u.id 
             ORDER BY t.created_at DESC"
        );
        return array_map(fn($row) => $this->buildItem($type, $row), $rows);
    }

    public function create(string $type, int $userId, string $itemName, string $category, string $description, string $location, string $itemDate, ?string $image, string $contactDetails): bool
    {
        $table = $this->resolveTable($type);
        return $this->db->execute(
            "INSERT INTO {$table} (user_id, item_name, category, description, location, item_date, image, contact_details) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$userId, $itemName, $category, $description, $location, $itemDate, $image, $contactDetails]
        );
    }

    // Returns the image filename (if any) so the caller can delete the file too
    public function getImageName(string $type, int $id): ?string
    {
        $table = $this->resolveTable($type);
        $row = $this->db->fetchOne("SELECT image FROM {$table} WHERE id = ?", [$id]);
        return $row['image'] ?? null;
    }

    public function delete(string $type, int $id): bool
    {
        $table = $this->resolveTable($type);
        return $this->db->execute("DELETE FROM {$table} WHERE id = ?", [$id]);
    }
}
