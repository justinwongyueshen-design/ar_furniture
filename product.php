<?php
require_once 'includes/db.php';
require_once 'includes/products.php';
$config = require 'config/config.php';

$slug = $_GET['slug'] ?? '';
$product = get_product_by_slug($slug);

if (!$product || !$product['is_active']) {
    http_response_code(404);
    exit("Product not found.");
}
$ar_mode = ($_GET['ar'] ?? '') === '1';
$current_url = $config['app']['url'] . '/product.php?slug=' . urlencode($product['slug']);
$ar_url = $current_url . '&ar=1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - <?= htmlspecialchars($config['app']['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>
<body class="<?= $ar_mode ? 'ar-launch' : '' ?>">
    <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php">
                <i class="fa-solid fa-cube text-primary me-2"></i><?= htmlspecialchars($config['app']['name']) ?>
            </a>
            <?php if ($ar_mode): ?>
                <a href="product.php?slug=<?= rawurlencode($product['slug']) ?>" class="btn btn-outline-secondary btn-sm">Product details</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Catalog</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="<?= $ar_mode ? 'container-fluid py-2 ar-launch-page' : 'container py-5' ?>">
        <div class="row <?= $ar_mode ? 'g-0' : 'g-5' ?>">
            <div class="<?= $ar_mode ? 'col-12' : 'col-lg-7' ?>">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative <?= $ar_mode ? 'ar-launch-card' : '' ?>">
                    <model-viewer
                        src="<?= htmlspecialchars($product['glb_path']) ?>"
                        <?php if (!empty($product['usdz_path'])): ?>ios-src="<?= htmlspecialchars($product['usdz_path']) ?>"<?php endif; ?>
                        camera-controls
                        ar
                        ar-modes="webxr scene-viewer quick-look"
                        shadow-intensity="1"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                        class="w-100 h-100 bg-light"
                        style="height: <?= $ar_mode ? '100%' : '500px' ?>;"
                        id="ar-viewer">
                        <button slot="ar-button" class="btn btn-primary position-absolute bottom-0 start-50 translate-middle-x mb-4 px-4 rounded-pill shadow">
                            <i class="fa-solid fa-cube me-2"></i><?= $ar_mode ? 'Start AR Preview' : 'View in AR' ?>
                        </button>
                    </model-viewer>
                </div>
            </div>
            <?php if (!$ar_mode): ?>
            <div class="col-lg-5">
                <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill fw-semibold"><?= htmlspecialchars($product['category_name']) ?></span>
                <h1 class="h2 fw-bold mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                <div class="text-primary fw-bold fs-3 mb-3"><?= format_price($product['price'], $product['currency']) ?></div>
                <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <?php if ($product['width_cm'] || $product['height_cm'] || $product['depth_cm']): ?>
                    <div class="card bg-light border-0 p-3 rounded-4 mb-4">
                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-ruler-combined me-2 text-primary"></i> Dimensions</h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <?php if ($product['width_cm']): ?><li>Width: <strong><?= $product['width_cm'] ?> cm</strong></li><?php endif; ?>
                            <?php if ($product['height_cm']): ?><li>Height: <strong><?= $product['height_cm'] ?> cm</strong></li><?php endif; ?>
                            <?php if ($product['depth_cm']): ?><li>Depth: <strong><?= $product['depth_cm'] ?> cm</strong></li><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm p-3 rounded-4 text-center d-none d-lg-block">
                    <p class="small text-muted mb-2">Scan QR code to open on your phone for AR preview</p>
                    <div id="qrcode" class="d-flex justify-content-center"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if (!$ar_mode): ?>
    <footer class="bg-white border-top py-4 text-center text-muted mt-5">
        <div class="container">&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app']['name']) ?>. All rights reserved.</div>
    </footer>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="js/qr.js"></script>
    <script src="js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            generateQRCode(<?= json_encode($ar_url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        });
    </script>
</body>
</html>