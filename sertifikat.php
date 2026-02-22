<?php
include 'session_modal.php';
include 'koneksi.php';

$id_member = $_SESSION['id_member'];
$id_sub_modul = $_GET['id_sub_modul'] ?? 0;

// ambil data sertifikat
$stmt = $conn->prepare("
    SELECT s.kode_sertifikat, m.nm_member, sm.nama_sub_modul, s.tanggal
    FROM sertifikat s
    JOIN member m ON s.id_member = m.id_member
    JOIN sub_modul sm ON s.id_sub_modul = sm.id_sub_modul
    WHERE s.id_member = ?
    AND s.id_sub_modul = ?
");
$stmt->bind_param("ii", $id_member, $id_sub_modul);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Sertifikat tidak ditemukan");
}

$qr_link = $url_utama . "verifikasi.php?kode=" . $data['kode_sertifikat'];
?>

<style>
.sertifikat-wrapper {
    position: relative;
    width: 1000px;
    margin: 40px auto;
}

.bg-sertifikat {
    width: 100%;
}

.nama-member {
    position: absolute;
    top: 260px;
    width: 100%;
    text-align: center;
    font-size: 42px;
    font-weight: bold;
    color: #F7931E;
}

.nama-submodul {
    position: absolute;
    top: 450px;
    width: 100%;
    text-align: center;
    font-size: 20px;
}

.tanggal {
    position: absolute;
    top: 490px;
    width: 100%;
    text-align: center;
    font-size: 16px;
}

.qr {
    position: absolute;
    bottom: 80px;
    right: 120px;
}
</style>

<div class="sertifikat-wrapper">

    <img src="images/sertifikat.jpeg" class="bg-sertifikat">

    <div class="nama-member">
        <?= strtoupper($data['nm_member']) ?>
    </div>

    <div class="nama-submodul">
        Successfully completed <b><?= $data['nama_sub_modul'] ?></b>
    </div>

    <div class="tanggal">
        <?= date('d F Y', strtotime($data['tanggal'])) ?>
    </div>

    <div class="qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=<?= urlencode($qr_link) ?>">
    </div>

</div>