<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

$id_barang = $data['id'];       // id_barang dari DB
$nama      = $data['nama'];
$foto      = $data['foto'];
$harga     = (int)$data['harga'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id_barang])) {
    $_SESSION['cart'][$id_barang]['qty']++;
} else {
    $_SESSION['cart'][$id_barang] = [
        'id_barang' => $id_barang,
        'nama'      => $nama,
        'harga'     => $harga,
        'foto'     => $foto,
        'qty'       => 1
    ];
}

echo json_encode(['status' => 'ok']);
