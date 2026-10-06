<?php
require_once __DIR__ . '/auth.php';
require_admin();

function render_admin_header(string $title): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title) ?> - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="index.php">AR Admin</a>
                <div class="navbar-nav ms-auto flex-row gap-3">
                    <a class="nav-link text-white" href="index.php">Products</a>
                    <a class="nav-link text-white" href="categories.php">Categories</a>
                    <a class="nav-link text-white" href="../index.php" target="_blank">Storefront</a>
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        <div class="container py-4">
    <?php
}

function render_admin_footer(): void {
    ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}