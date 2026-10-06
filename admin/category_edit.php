<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
$category = ['name' => '', 'slug' => '', 'sort_order' => 0, 'is_active' => 1];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch() ?: $category;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
    $sort_order = $_POST['sort_order'] ?? 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$name, $slug, $sort_order, $is_active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, sort_order, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $sort_order, $is_active]);
    }
    header('Location: categories.php');
    exit;
}

render_admin_header($id ? "Edit Category" : "Add Category");
?>
<h1 class="h3 fw-bold mb-4"><?= $id ? "Edit Category" : "Add Category" ?></h1>
<form method="POST" class="card border-0 shadow-sm rounded-4 p-4 max-w-md">
    <div class="mb-3">
        <label class="form-label fw-bold small">Category Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold small">Slug (leave blank to auto-generate)</label>
        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($category['slug']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold small">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($category['sort_order']) ?>">
    </div>
    <div class="mb-4 form-check">
        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?>>
        <label class="form-check-label fw-bold small" for="is_active">Active</label>
    </div>
    <button type="submit" class="btn btn-dark rounded-pill px-4">Save Category</button>
    <a href="categories.php" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
</form>
<?php render_admin_footer(); ?>