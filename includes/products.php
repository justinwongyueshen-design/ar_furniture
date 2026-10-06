<?php
require_once __DIR__ . '/db.php';

function get_active_products(?int $category_id = null): array {
    global $pdo;
    $sql = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 AND c.is_active = 1";
    $params = [];
    
    if ($category_id !== null) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    
    $sql .= " ORDER BY p.sort_order ASC, p.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_product_by_slug(string $slug): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function format_price(float $price, string $currency = 'RM'): string {
    return $currency . ' ' . number_format($price, 2);
}