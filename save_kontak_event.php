<?php
include 'koneksi.php';

$stmt = $conn->prepare("
  INSERT INTO sponsor_event
  (id_sponsor_event, nama_pic, lembaga_sponsor, minat_sponsor, tujuan_sponsor, telp_pic, email_pic)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "issssss",
  $_POST['id_sponsor_event'],
  $_POST['nama_pic'],
  $_POST['lembaga_sponsor'],
  $_POST['minat_sponsor'],
  $_POST['tujuan_sponsor'],
  $_POST['telp_pic'],
  $_POST['email_pic']
);

echo json_encode([
  'status' => $stmt->execute() ? 'ok' : 'error'
]);
