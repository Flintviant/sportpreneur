<?php
include 'koneksi.php';

$kode = $_GET['kode'] ?? '';

$data = $conn->query("
    SELECT m.nama, sm.nama_sub_modul, s.tanggal
    FROM sertifikat s
    JOIN member m ON s.id_member = m.id_member
    JOIN sub_modul sm ON s.id_sub_modul = sm.id_sub_modul
    WHERE s.kode_sertifikat = '$kode'
")->fetch_assoc();

if ($data):
?>

<h2>Sertifikat Valid</h2>
<p>Nama: <b><?= htmlspecialchars($data['nama']) ?></b></p>
<p>Sub Modul: <b><?= htmlspecialchars($data['nama_sub_modul']) ?></b></p>
<p>Tanggal: <?= $data['tanggal'] ?></p>

<?php else: ?>

<h2>Sertifikat Tidak Valid </h2>

<?php endif; ?>