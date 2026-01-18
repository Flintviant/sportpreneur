<?php
session_start();
include 'koneksi.php';

$id_member = $_SESSION['id_member'] ?? null;

$phone = '628000000000';
$nama  = 'Guest';
$postal = null;

if ($id_member) {
    $stmt = $conn->prepare(
        "SELECT nm_member, telepon, alamat_member, email, kota_member, kode_pos FROM member WHERE id_member = ?"
    );
    $stmt->bind_param("i", $id_member);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $phone = $row['telepon'];
        $nama  = $row['nm_member'];
        $address  = $row['alamat_member'];
        $email  = $row['email'];
        $city  = $row['kota_member'];
        $postal  = $row['kode_pos'];
    }
}

$phone = preg_replace('/[^0-9]/', '', $phone);
if (substr($phone, 0, 1) === '0') {
    $phone = '62' . substr($phone, 1);
}

require_once 'api/midtrans-php-master/REMOVED.php';

    $stmt = $conn->prepare("
        SELECT server_key, is_production, is_sanitized, is_3ds
        FROM payment_settings
        WHERE gateway = 'midtrans'
        LIMIT 1
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die('Konfigurasi REMOVED belum diset');
    }

    $config = $result->fetch_assoc();

    // set config midtrans
    \REMOVED\Config::$serverKey    = $config['server_key'];
    \REMOVED\Config::$isProduction = (bool)$config['is_production'];
    \REMOVED\Config::$isSanitized  = (bool)$config['is_sanitized'];
    \REMOVED\Config::$is3ds        = (bool)$config['is_3ds'];

if (empty($_SESSION['cart'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart kosong']);
    exit;
}

$order_id = 'ORDER-' . time();
$gross_amount = 0;
$items = [];

foreach ($_SESSION['cart'] as $id => $item) {
    $subtotal = $item['harga'] * $item['qty'];
    $gross_amount += $subtotal;

    $items[] = [
        'id'       => $id,
        'price'    => $item['harga'],
        'quantity' => $item['qty'],
        'name'     => $item['nama']
    ];
}

$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $gross_amount
    ],
    'item_details' => $items,
    'customer_details' => [
        'first_name' => $nama,
        'email'      => $email,
        'phone'      => $phone,

        'shipping_address' => [
            'first_name'   => $nama,
            'address'      => $address,
            'city'         => $city ?? 'Jakarta',
            'postal_code'  => $postal ?? '00000',
            'phone'        => $phone
        ],

        'billing_address' => [
            'first_name'   => $nama,
            'address'      => $address,
            'city'         => $city ?? 'Jakarta',
            'postal_code'  => $postal ?? '00000',
            'phone'        => $phone
        ]
    ]
];

$conn->begin_transaction();

try {

    // simpan ke orders
    $stmt = $conn->prepare("
        INSERT INTO orders 
        (order_id, id_member, nama, email, phone, address, total, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->bind_param(
        "sissssi",
        $order_id,
        $id_member,
        $nama,
        $email,
        $phone,
        $address,
        $gross_amount
    );

    $stmt->execute();

    // simpan item
    $stmtItem = $conn->prepare("
        INSERT INTO order_items
        (order_id, id_barang, product_name, price, qty, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['harga'] * $item['qty'];

        $stmtItem->bind_param(
            "sssiii",
            $order_id,
            $id,
            $item['nama'],
            $item['harga'],
            $item['qty'],
            $subtotal
        );

        $stmtItem->execute();
    }

    $conn->commit();
    unset($_SESSION['cart']);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Gagal simpan order']);
    exit;
}

try {
    $snapToken = \REMOVED\Snap::getSnapToken($params);
    echo json_encode([
        'token' => $snapToken,
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
