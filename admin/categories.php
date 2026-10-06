<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, name ASC")->fetchAll();

render_admin_header("Manage Categories");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">Categories</h1>
    <a href="category_edit.php" class="btn btn-dark rounded-pill"><i class="fa-solid fa-plus me-1"></i> Add Category</a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No categories found.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['slug']) ?></td>
                            <td><?= $c['sort_order'] ?></td>
                            <td>
                                <span class="badge <?= $c['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $c['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="category_edit.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-pen"></i></a>
                                <a href="category_delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php render_admin_footer(); ?>