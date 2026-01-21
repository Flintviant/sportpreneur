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

$url_admin = 'https://sportpreneur.id/admin/';
$url_utama = 'https://sportpreneur.id/';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$current_url = htmlspecialchars($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
?>
