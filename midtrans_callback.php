<?php
require_once __DIR__ . '/api/midtrans-php-master/Midtrans.php';
require_once __DIR__ . '/koneksi.php';

/* ======================
   LOG RAW CALLBACK
====================== */
$raw = file_get_contents('php://input');
file_put_contents(__DIR__ . '/midtrans_raw.log', $raw . PHP_EOL, FILE_APPEND);

$notif = json_decode($raw, true);

if (!is_array($notif) || empty($notif['order_id'])) {
    http_response_code(200);
    exit;
}

$order_id = $notif['order_id'];
$transaction_status = $notif['transaction_status'] ?? '';
$fraud_status       = $notif['fraud_status'] ?? '';

/* ======================
   MAPPING STATUS
====================== */
$status = 'pending';

if (
    ($transaction_status === 'capture' && $fraud_status === 'accept') ||
    $transaction_status === 'settlement'
) {
    $status = 'paid';
} elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
    $status = 'failed';
}

/* ======================
   CEK STATUS SEBELUMNYA
   (ANTI DOUBLE CALLBACK)
====================== */
$stmt = $conn->prepare("
    SELECT status 
    FROM orders 
    WHERE order_id = ?
    LIMIT 1
");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(200);
    exit;
}

$current = $res->fetch_assoc()['status'];

// kalau sudah paid → STOP
if ($current === 'paid') {
    http_response_code(200);
    echo 'OK';
    exit;
}

/* ======================
   TRANSACTION DB
====================== */
$conn->begin_transaction();

try {

    // update status order
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = ? 
        WHERE order_id = ?
    ");
    $stmt->bind_param("ss", $status, $order_id);
    $stmt->execute();

    /* ======================
       POTONG STOK
       HANYA JIKA PAID
    ====================== */
    if ($status === 'paid') {

        // ambil item order
        $stmt = $conn->prepare("
            SELECT id_barang, qty 
            FROM order_items 
            WHERE order_id = ?
        ");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $items = $stmt->get_result();

        while ($item = $items->fetch_assoc()) {
            $id_barang = $item['id_barang'];
            $qty       = (int)$item['qty'];

            // potong stok
            $stmtStock = $conn->prepare("
                UPDATE barang 
                SET stok = stok - ?
                WHERE id_barang = ?
            ");
            $stmtStock->bind_param("is", $qty, $id_barang);
            $stmtStock->execute();
        }
    }

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    exit;
}

http_response_code(200);
echo 'OK';