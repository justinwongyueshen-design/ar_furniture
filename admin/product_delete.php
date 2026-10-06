<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    if ($p) {
        if (!empty($p['glb_path']) && file_exists('../' . $p['glb_path'])) @unlink('../' . $p['glb_path']);
        if (!empty($p['thumb_path']) && file_exists('../' . $p['thumb_path'])) @unlink('../' . $p['thumb_path']);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
}
header('Location: index.php');
exit;