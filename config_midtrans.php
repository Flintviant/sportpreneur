<?php
require_once 'koneksi.php';
require_once 'api/midtrans-php-master/REMOVED.php';

$stmt = $conn->prepare("
    SELECT server_key, is_production, is_sanitized, is_3ds
    FROM payment_settings
    WHERE gateway = 'midtrans'
    LIMIT 1
");
$stmt->execute();
$config = $stmt->get_result()->fetch_assoc();

\REMOVED\Config::$serverKey     = $config['server_key'];
\REMOVED\Config::$isProduction = (bool)$config['is_production'];
\REMOVED\Config::$isSanitized  = (bool)$config['is_sanitized'];
\REMOVED\Config::$is3ds        = (bool)$config['is_3ds';
