<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id'];
$type = $data['type'];

if (!isset($_SESSION['cart'][$id])) exit;

if ($type === 'plus') {
    $_SESSION['cart'][$id]['qty']++;
}

if ($type === 'minus') {
    $_SESSION['cart'][$id]['qty']--;
    if ($_SESSION['cart'][$id]['qty'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
}

if ($type === 'remove') {
    unset($_SESSION['cart'][$id]);
}

echo json_encode(['status' => 'ok']);
