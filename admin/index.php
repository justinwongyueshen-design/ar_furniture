<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.sort_order ASC, p.id DESC");
$products = $stmt->fetchAll();

render_admin_header("Manage Products");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Products</h1>
    <a href="product_edit.php" class="btn btn-dark rounded-pill"><i class="fa-solid fa-plus me-1"></i> Add Product</a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Thumbnail</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No products found.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if (!empty($p['thumb_path']) && file_exists('../' . $p['thumb_path'])): ?>
                                    <img src="../<?= htmlspecialchars($p['thumb_path']) ?>" width="50" height="50" class="object-fit-cover rounded">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['category_name']) ?></td>
                            <td><?= htmlspecialchars($p['currency'] . ' ' . number_format($p['price'], 2)) ?></td>
                            <td>
                                <span class="badge <?= $p['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $p['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-pen"></i></a>
                                <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php render_admin_footer(); ?>