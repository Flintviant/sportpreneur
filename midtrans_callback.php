<?php
require_once __DIR__.'/api/midtrans-php-master/REMOVED.php';
require_once __DIR__.'/koneksi.php';

// log raw input
$raw = file_get_contents('php://input');
file_put_contents(__DIR__.'/midtrans_raw.log', $raw.PHP_EOL, FILE_APPEND);

$notif = json_decode($raw, true);

if (!is_array($notif) || empty($notif['order_id'])) {
    http_response_code(200); // JANGAN 400
    exit;
}

$order_id = $notif['order_id'];
$transaction_status = $notif['transaction_status'] ?? '';
$fraud_status = $notif['fraud_status'] ?? '';

// mapping status
$status = 'pending';

if ($transaction_status === 'capture' && $fraud_status === 'accept') {
    $status = 'paid';
} elseif ($transaction_status === 'settlement') {
    $status = 'paid';
} elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
    $status = 'failed';
}

// update DB
$stmt = $conn->prepare("
    UPDATE orders 
    SET status = ? 
    WHERE order_id = ?
");
$stmt->bind_param("ss", $status, $order_id);
$stmt->execute();

http_response_code(200);
echo 'OK';