<?php
$request_host = strtolower((string) parse_url('//' . ($_SERVER['HTTP_HOST'] ?? 'localhost'), PHP_URL_HOST));
$is_local_request = $request_host === 'localhost'
    || $request_host === '::1'
    || (filter_var($request_host, FILTER_VALIDATE_IP)
        && !filter_var($request_host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
$app_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (in_array($request_host, ['localhost', '127.0.0.1', '::1'], true)) {
    $lan_host = gethostbyname(gethostname());
    if (filter_var($lan_host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        && !filter_var($lan_host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        $port = parse_url('//' . $app_host, PHP_URL_PORT);
        $app_host = $lan_host . ($port ? ':' . $port : '');
    }
}

return [
    'app' => [
        'name' => 'AR Furniture Catalog',
        // Set APP_URL to the phone-accessible site URL when localhost is used on desktop.
        'url' => rtrim(getenv('APP_URL') ?: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $app_host . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\')), '/')
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: ($is_local_request ? 'ar_furniture' : 'synergy1_justinwong_ar_furniture'),
        'user' => getenv('DB_USER') ?: ($is_local_request ? 'root' : 'synergy1_yenping'),
        'pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : ($is_local_request ? '' : 'R.zb0ZwEuGZ}*w2')
    ]
];