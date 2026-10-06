<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
if ($id) {
    // Check if products exist in category
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        die("Cannot delete category containing products. Please delete or reassign products first.");
    }
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: categories.php');
exit;