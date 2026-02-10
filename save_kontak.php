<?php
include 'koneksi.php';

$stmt = $conn->prepare("
  INSERT INTO kontak_sponsor
  (id_sponsor, nama, lembaga, minat, tujuan, telp, email)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "issssss",
  $_POST['id_sponsor'],
  $_POST['nama'],
  $_POST['lembaga'],
  $_POST['minat'],
  $_POST['tujuan'],
  $_POST['telp'],
  $_POST['email']
);

echo json_encode([
  'status' => $stmt->execute() ? 'ok' : 'error'
]);
