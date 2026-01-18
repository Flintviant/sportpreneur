<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// $servername = "localhost";
// $username   = "root";
// $password   = "";
// $dbname     = "db_toko";

$servername = "localhost";
$username   = "u872414524_sport";
$password   = "Sportpreneur01!";
$dbname     = "u872414524_sport";

// buat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// set charset
$conn->set_charset("utf8mb4");

// config url
// $url_admin = 'http://sportshop.test/admin/';
// $url_utama = 'http://sportshop.test/';

$url_admin = 'http://sportpreneur.id/admin/';
$url_utama = 'http://sportpreneur.id/';
?>
