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

$nama    = trim($_POST['nm_member'] ?? '');
$email   = trim($_POST['email'] ?? '');
$alamat  = trim($_POST['alamat_member'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$kota    = trim($_POST['kota'] ?? '');

$olahraga = $_POST['olahraga'] ?? '';
$tawarkan = $_POST['tawarkan'] ?? '';
$butuh    = $_POST['butuh'] ?? '';
$dampak   = $_POST['dampak'] ?? '';

if (!$nama || !$email || !$alamat || !$telepon || !$kota) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field wajib diisi'
    ]);
    exit;
}

$conn->begin_transaction();

try {

    // UPDATE MEMBER
    $stmt1 = $conn->prepare("
        UPDATE member 
        SET nm_member = ?, email = ?, alamat_member = ?, telepon = ?, kota_member = ?
        WHERE id_member = ?
    ");
    $stmt1->bind_param(
        "sssssi",
        $nama,
        $email,
        $alamat,
        $telepon,
        $kota,
        $id_member
    );
    $stmt1->execute();

    // UPDATE AKUN
    $stmt2 = $conn->prepare("
        UPDATE akun 
        SET olahraga = ?, tawarkan = ?, butuh = ?, dampak = ?
        WHERE id_member = ?
    ");
    $stmt2->bind_param(
        "ssssi",
        $olahraga,
        $tawarkan,
        $butuh,
        $dampak,
        $id_member
    );
    $stmt2->execute();

    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Profil berhasil diperbarui'
    ]);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem'
    ]);
    exit;
}
