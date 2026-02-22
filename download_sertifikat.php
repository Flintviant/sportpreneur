<?php
require 'vendor/autoload.php';
include 'session_modal.php';
include 'koneksi.php';

use Dompdf\Dompdf;

$id_member = $_SESSION['id_member'];
$id_sub_modul = $_GET['id_sub_modul'] ?? 0;

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

$html = '
<style>
body { font-family: sans-serif; text-align:center; }
.wrapper { position: relative; width: 100%; }
.nama { font-size: 30px; font-weight: bold; color:#F7931E; margin-top:200px; }
.sub { margin-top:20px; font-size:18px; }
.tgl { margin-top:10px; font-size:14px; }
</style>

<div class="wrapper">
    <img src="'.$url_utama.'images/sertifikat.jpeg" width="100%">
    <div class="nama">'.strtoupper($data['nm_member']).'</div>
    <div class="sub">
        Successfully completed <b>'.$data['nama_sub_modul'].'</b>
    </div>
    <div class="tgl">
        '.date('d F Y', strtotime($data['tanggal'])).'
    </div>
    <div style="margin-top:30px;">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data='.urlencode($qr_link).'">
    </div>
</div>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Sertifikat.pdf", ["Attachment" => true]);
exit;