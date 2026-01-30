<?php
session_start();
require_once 'koneksi.php';

header('Content-Type: application/json');

// ================== CEK SESSION ==================
if (!isset($_SESSION['id_member'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Session berakhir, silakan login kembali'
    ]);
    exit;
}

$id_member = (int) $_SESSION['id_member'];

// ================== AMBIL DATA ==================
$nama    = trim($_POST['nm_member'] ?? '');
$email   = trim($_POST['email'] ?? '');
$alamat  = trim($_POST['alamat_member'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$kota    = trim($_POST['kota'] ?? '');

// data akun (boleh null tergantung role)
$olahraga = $_POST['olahraga'] ?? '';
$tawarkan = $_POST['tawarkan'] ?? '';
$butuh    = $_POST['butuh'] ?? '';
$dampak   = $_POST['dampak'] ?? '';

// ================== VALIDASI ==================
if (!$nama || !$email || !$alamat || !$telepon || !$kota) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Semua field wajib diisi'
    ]);
    exit;
}

// ================== TRANSACTION ==================
$conn->begin_transaction();

try {

    // ==================================================
    // UPDATE MEMBER
    // ==================================================
    $stmt = $conn->prepare("
        UPDATE member
        SET 
            nm_member = ?,
            email = ?,
            alamat_member = ?,
            telepon = ?,
            kota_member = ?
        WHERE id_member = ?
    ");
    $stmt->bind_param(
        "sssssi",
        $nama,
        $email,
        $alamat,
        $telepon,
        $kota,
        $id_member
    );
    $stmt->execute();

    // ==================================================
    // CEK AKUN SUDAH ADA ATAU BELUM
    // ==================================================
    $cek = $conn->prepare("SELECT id_member FROM akun WHERE id_member = ?");
    $cek->bind_param("i", $id_member);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {

        // ------------------ UPDATE AKUN ------------------
        $stmt2 = $conn->prepare("
            UPDATE akun
            SET 
                olahraga = ?,
                tawarkan = ?,
                butuh = ?,
                dampak = ?
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

    } else {

        // ------------------ INSERT AKUN ------------------
        $stmt2 = $conn->prepare("
            INSERT INTO akun 
                (id_member, olahraga, tawarkan, butuh, dampak)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            "issss",
            $id_member,
            $olahraga,
            $tawarkan,
            $butuh,
            $dampak
        );
        $stmt2->execute();
    }

    // ==================================================
    // COMMIT
    // ==================================================
    $conn->commit();

    echo json_encode([
        'status'  => 'success',
        'message' => 'Profil berhasil diperbarui'
    ]);
    exit;

} catch (Throwable $e) {

    $conn->rollback();

    echo json_encode([
        'status'  => 'error',
        'message' => 'Terjadi kesalahan sistem'
        // 'debug' => $e->getMessage() // aktifkan kalau mau debug
    ]);
    exit;
}
