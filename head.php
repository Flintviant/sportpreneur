<?php

	include 'koneksi.php'; 

	include 'session_modal.php';

	$current = $_SERVER['REQUEST_URI'];

	function activeMenu($path, $current) {
	    return ($current == $path || strpos($current, $path.'/') === 0) ? 'active-menu' : '';
	}

	$limit = 3;

	// ambil halaman sekarang (default 1)
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	if ($page < 1) $page = 1;

	// hitung offset
	$offset = ($page - 1) * $limit;

	// hitung total artikel
	$total_sql = "SELECT COUNT(*) as total FROM tb_artikel";
	$total_result = $conn->query($total_sql);
	$total_row = $total_result->fetch_assoc();
	$total_artikel = $total_row['total'];

	// hitung total halaman
	$total_pages = ceil($total_artikel / $limit);

	// Query jumlah artikel
	$sql = "SELECT * FROM tb_artikel ORDER BY id DESC LIMIT $limit OFFSET $offset";
	$result = $conn->query($sql);

	// buat data jadi array supaya bisa dipakai berkali-kali
	$artikels = [];
		if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
		  $artikels[] = $row;
		}
	}

	$sql_barang = $conn->query("SELECT * FROM barang ORDER BY id DESC LIMIT 4");

	if (!$sql_barang) {
	    die("Query Error: " . $conn->error);
	}

	$produk = [];
	while ($row = $sql_barang->fetch_assoc()) {
	    $produk[] = $row; // ambil semua kolom
	}

	$id_member = $_SESSION['id_member'] ?? null;

	if ($id_member) {
	    $stmt = $conn->prepare(
	        "SELECT nm_member, telepon, alamat_member, email FROM member WHERE id_member = ?"
	    );
	    $stmt->bind_param("i", $id_member);
	    $stmt->execute();
	    $result = $stmt->get_result();

	    if ($row = $result->fetch_assoc()) {
	        $phone = $row['telepon'];
	        $nama  = $row['nm_member'];
	        $address  = $row['alamat_member'];
	        $email	= $row['email'];
	    }
	}

	// $stmt = $conn->prepare("SELECT keyword, description FROM m_dataf");
	// $stmt->execute();
	// $result = $stmt->get_result();
	// $data = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/logo-sport.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>

<?php require_once __DIR__ . '/header.php'; ?>