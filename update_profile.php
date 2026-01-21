<?php
session_start();
include 'koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_member'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session berakhir, silakan login kembali'
    ]);
    exit;
}

$id_member = $_SESSION['id_member'];

$nama    = $_POST['nm_member'] ?? '';
$email   = $_POST['email'] ?? '';
$alamat  = $_POST['alamat_member'] ?? '';
$telepon = $_POST['telepon'] ?? '';

if (!$nama || !$email || !$alamat || !$telepon) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field wajib diisi'
    ]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE member 
    SET nm_member = ?, email = ?, alamat_member = ?, telepon = ?
    WHERE id_member = ?
");

$stmt->bind_param("ssssi", $nama, $email, $alamat, $telepon, $id_member);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal update data'
    ]);
}
