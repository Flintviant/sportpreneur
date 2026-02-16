<?php
include 'koneksi.php';

$stmt = $conn->prepare("
  INSERT INTO peserta_event
  (id_event, nama_peserta, alamat_peserta, peserta_event, telp_peserta, email_peserta)
  VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "isssss",
  $_POST['id_peserta_event'],
  $_POST['nama_peserta'],
  $_POST['alamat_peserta'],
  $_POST['peserta_event'],
  $_POST['telp_peserta'],
  $_POST['email_peserta']
);

echo json_encode([
  'status' => $stmt->execute() ? 'ok' : 'error'
]);
