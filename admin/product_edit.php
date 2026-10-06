<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
$product = [
    'category_id' => '', 'slug' => '', 'name' => '', 'description' => '',
    'price' => '', 'currency' => 'RM', 'glb_path' => '', 'usdz_path' => '',
    'thumb_path' => '', 'width_cm' => '', 'height_cm' => '', 'depth_cm' => '',
    'is_active' => 1, 'sort_order' => 0
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch() ?: $product;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? 0;
    $currency = $_POST['currency'] ?? 'RM';
    $width_cm = $_POST['width_cm'] !== '' ? $_POST['width_cm'] : null;
    $height_cm = $_POST['height_cm'] !== '' ? $_POST['height_cm'] : null;
    $depth_cm = $_POST['depth_cm'] !== '' ? $_POST['depth_cm'] : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    $glb_path = $product['glb_path'];
    $usdz_path = $product['usdz_path'];
    $thumb_path = $product['thumb_path'];

    // File Upload handling
    if (!is_dir('../uploads')) mkdir('../uploads', 0755, true);
    if (!is_dir('../uploads/thumbs')) mkdir('../uploads/thumbs', 0755, true);
    if (!is_dir('../models')) mkdir('../models', 0755, true);

    if (isset($_FILES['glb_file']) && $_FILES['glb_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['glb_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'glb') {
            $filename = bin2hex(random_bytes(8)) . '.glb';
            move_uploaded_file($_FILES['glb_file']['tmp_name'], '../models/' . $filename);
            $glb_path = 'models/' . $filename;
        }
    }

    if (isset($_FILES['thumb_file']) && $_FILES['thumb_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['thumb_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['thumb_file']['tmp_name'], '../uploads/thumbs/' . $filename);
            $thumb_path = 'uploads/thumbs/' . $filename;
        }
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE products SET category_id=?, slug=?, name=?, description=?, price=?, currency=?, glb_path=?, usdz_path=?, thumb_path=?, width_cm=?, height_cm=?, depth_cm=?, is_active=?, sort_order=? WHERE id=?");
        $stmt->execute([$category_id, $slug, $name, $description, $price, $currency, $glb_path, $usdz_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active, $sort_order, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (category_id, slug, name, description, price, currency, glb_path, usdz_path, thumb_path, width_cm, height_cm, depth_cm, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $slug, $name, $description, $price, $currency, $glb_path, $usdz_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active, $sort_order]);
    }
    header('Location: index.php');
    exit;
}

render_admin_header($id ? "Edit Product" : "Add Product");
?>
<h1 class="h3 fw-bold mb-4"><?= $id ? "Edit Product" : "Add Product" ?></h1>
<form method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm rounded-4 p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold small">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">Slug (leave blank to auto-generate)</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($product['slug']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold small">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold small">Currency</label>
            <input type="text" name="currency" class="form-control" value="<?= htmlspecialchars($product['currency']) ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold small">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold small">Width (cm)</label>
            <input type="number" step="0.1" name="width_cm" class="form-control" value="<?= htmlspecialchars($product['width_cm']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold small">Height (cm)</label>
            <input type="number" step="0.1" name="height_cm" class="form-control" value="<?= htmlspecialchars($product['height_cm']) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold small">Depth (cm)</label>
            <input type="number" step="0.1" name="depth_cm" class="form-control" value="<?= htmlspecialchars($product['depth_cm']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">GLB 3D Model File</label>
            <input type="file" name="glb_file" class="form-control" accept=".glb">
            <?php if ($product['glb_path']): ?><small class="text-muted">Current: <?= $product['glb_path'] ?></small><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">Thumbnail Image</label>
            <input type="file" name="thumb_file" class="form-control" accept="image/*">
            <?php if ($product['thumb_path']): ?><small class="text-muted">Current: <?= $product['thumb_path'] ?></small><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($product['sort_order']) ?>">
        </div>
        <div class="col-md-6 d-flex align-items-center pt-4">
            <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" <?= $product['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold small" for="is_active">Active (Visible on Storefront)</label>
            </div>
        </div>
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-dark rounded-pill px-4">Save Product</button>
            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
        </div>
    </div>
</form>
<?php render_admin_footer(); ?>