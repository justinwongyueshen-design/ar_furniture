<?php
require_once __DIR__ . '/db.php';

function get_all_categories(bool $active_only = true): array {
    global $pdo;
    $sql = "SELECT * FROM categories";
    if ($active_only) {
        $sql .= " WHERE is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC, name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function get_category_by_slug(string $slug): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}