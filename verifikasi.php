<?php
include 'koneksi.php';

$kode = $_GET['kode'] ?? '';

$stmt = $conn->prepare("
    SELECT m.nm_member, sm.nama_sub_modul, s.tanggal
    FROM sertifikat s
    JOIN member m ON s.id_member = m.id_member
    JOIN sub_modul sm ON s.id_sub_modul = sm.id_sub_modul
    WHERE s.kode_sertifikat = ?
");
$stmt->bind_param("s", $kode);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Verifikasi Sertifikat</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #0F1426, #1c2541);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CARD */
.verifikasi-card {
    background: #ffffff;
    width: 420px;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    text-align: center;
}

/* ICON */
.icon-valid {
    font-size: 60px;
    color: #28a745;
    margin-bottom: 15px;
}

.icon-invalid {
    font-size: 60px;
    color: #dc3545;
    margin-bottom: 15px;
}

h2 {
    margin-bottom: 20px;
}

.data-row {
    margin: 10px 0;
    font-size: 16px;
}

.data-label {
    color: #888;
}

.data-value {
    font-weight: bold;
    color: #222;
}

.tanggal {
    font-size: 14px;
    color: #777;
    margin-top: 15px;
}

.footer {
    margin-top: 25px;
    font-size: 13px;
    color: #999;
}
</style>

</head>
<body>

<div class="verifikasi-card">

<?php if ($data): ?>

    <div class="icon-valid">✔</div>
    <h2>Sertifikat Valid</h2>

    <div class="data-row">
        <div class="data-label">Nama Peserta</div>
        <div class="data-value"><?= htmlspecialchars($data['nm_member']) ?></div>
    </div>

    <div class="data-row">
        <div class="data-label">Sub Modul</div>
        <div class="data-value"><?= htmlspecialchars($data['nama_sub_modul']) ?></div>
    </div>

    <div class="tanggal">
        Diterbitkan pada <?= date('d F Y', strtotime($data['tanggal'])) ?>
    </div>

    <div class="footer">
        Indonesia Sportpreneur Foundation
    </div>

<?php else: ?>

    <div class="icon-invalid">✖</div>
    <h2>Sertifikat Tidak Valid</h2>
    <p>Kode sertifikat tidak ditemukan atau tidak terdaftar.</p>

<?php endif; ?>

</div>

</body>
</html>