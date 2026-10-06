<?php
return [
    'app' => [
        'name' => 'AR Furniture Catalog',
        'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$$_SERVER[HTTP_HOST]" . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\')
    ],
    'db' => [
        'host' => 'localhost',
        'name' => 'synergy1_justinwong_ar_furniture',
        'user' => 'synergy1_yenping',
        'pass' => 'R.zb0ZwEuGZ}*fW2'
    ]
];