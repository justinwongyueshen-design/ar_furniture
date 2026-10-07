<?php
require_once 'includes/db.php';
require_once 'includes/categories.php';
require_once 'includes/products.php';
$config = require 'config/config.php';

$category_slug = $_GET['category'] ?? null;
$selected_category = null;
$category_id = null;

if ($category_slug) {
    $selected_category = get_category_by_slug($category_slug);
    if ($selected_category) {
        $category_id = $selected_category['id'];
    }
}

$categories = get_all_categories(true);
$products = get_active_products($category_id);
$current_url = $config['app']['url'] . '/index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['app']['name']) ?> - Browse Furniture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php">
                <i class="fa-solid fa-cube text-primary me-2"></i><?= htmlspecialchars($config['app']['name']) ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Catalog</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn btn-outline-dark btn-sm" href="admin/login.php">Admin Login</a></li>
                </ul>
            </div>
        </div>
    </header>

    <section class="hero-section py-5 bg-light border-bottom">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill fw-semibold">Augmented Reality Experience</span>
                    <h1 class="display-4 fw-bold mb-3">See it in your space before you buy</h1>
                    <p class="lead text-muted mb-4">Explore our catalog of fine furniture and place them instantly inside your room using your mobile phone camera and AR.</p>
                    <a href="#catalog" class="btn btn-dark btn-lg px-4">Browse Catalog</a>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="card shadow-sm p-3 bg-white d-inline-block rounded-4">
                        <div id="qrcode" class="mb-2 d-flex justify-content-center"></div>
                        <small class="text-muted d-block">Scan to open on mobile & view in AR</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main id="catalog" class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2 class="h3 fw-bold mb-3 mb-md-0">Furniture Catalog</h2>
            <div class="category-filters d-flex flex-wrap gap-2">
                <a href="index.php" class="btn btn-sm <?= !$category_slug ? 'btn-dark' : 'btn-outline-dark' ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?category=<?= htmlspecialchars($cat['slug']) ?>" class="btn btn-sm <?= $category_slug === $cat['slug'] ? 'btn-dark' : 'btn-outline-dark' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <p class="text-muted lead">No products found in this category.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $prod): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card product-card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="position-relative bg-light ratio ratio-43">
                                <?php if (!empty($prod['thumb_path']) && file_exists($prod['thumb_path'])): ?>
                                    <img src="<?= htmlspecialchars($prod['thumb_path']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="object-fit-cover">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-image fa-2x"></i></div>
                                <?php endif; ?>
                                <span class="badge bg-white text-dark position-absolute top-0 start-0 m-3 shadow-sm"><?= htmlspecialchars($prod['category_name']) ?></span>
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <h3 class="h5 fw-bold mb-1"><?= htmlspecialchars($prod['name']) ?></h3>
                                <div class="text-primary fw-bold fs-5 mb-3"><?= format_price($prod['price'], $prod['currency']) ?></div>
                                <a href="product.php?slug=<?= htmlspecialchars($prod['slug']) ?>" class="btn btn-dark mt-auto w-100 rounded-pill">View in your space &rarr;</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-white border-top py-4 text-center text-muted">
        <div class="container">&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app']['name']) ?>. All rights reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="js/qr.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            generateQRCode(<?= json_encode($current_url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        });
    </script>
</body>
</html>