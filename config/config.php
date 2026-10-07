<?php
return [
    'app' => [
        'name' => 'AR Furniture Catalog',
        // Set APP_URL to the phone-accessible site URL when localhost is used on desktop.
        'url' => rtrim(getenv('APP_URL') ?: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\')), '/')
    ],
    'db' => [
        'host' => 'localhost',
        'name' => 'synergy1_justinwong_ar_furniture',
        'user' => 'synergy1_yenping',
        'pass' => 'R.zb0ZwEuGZ}*fW2'
    ]
];