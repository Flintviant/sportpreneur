<?php
	include 'koneksi.php';

	require_once __DIR__ . '/session_modal.php';

	$id_member = $_SESSION['id_member'] ?? null;

	if ($id_member) {

	    $stmt = $conn->prepare("
	        SELECT 
	            m.nm_member,
	            m.email,
	            m.telepon,
	            m.alamat_member,
	            m.kota_member,

	            m.id_role,
	            r.role_name,

	            a.olahraga,
	            a.tawarkan,
	            a.butuh,
	            a.dampak

	        FROM member m
	        LEFT JOIN akun a 
	            ON m.id_member = a.id_member
	        LEFT JOIN roles r
	            ON m.id_role = r.id_role
	        WHERE m.id_member = ?
	        LIMIT 1
	    ");

	    $stmt->bind_param('i', $id_member);
	    $stmt->execute();
	    $result = $stmt->get_result();

	    if ($row = $result->fetch_assoc()) {

	        // DATA MEMBER
	        $nama    = $row['nm_member'];
	        $email   = $row['email'];
	        $phone   = $row['telepon'];
	        $address = $row['alamat_member'];
	        $kota    = $row['kota_member'];

	        // ROLE
	        $role   = $row['id_role'];
	        $role_name = $row['role_name']; // ✅ SEKARANG AMAN

	        // DATA AKUN
	        $olahraga = $row['olahraga'];
	        $tawarkan = $row['tawarkan'];
	        $butuh    = $row['butuh'];
	        $dampak   = $row['dampak'];
	    }
	}

	// $sql_barang = $conn->query("
	//   SELECT 
	//     b.*, 
	//     k.nama_kategori
	//   FROM barang b
	//   JOIN kategori k 
	//     ON b.id_kategori = k.id_kategori
	//   ORDER BY b.id DESC
	//   LIMIT 4
	// ");

	// if (!$sql_barang) {
	//   die('Query Error: ' . $conn->error);
	// }

	// $produk = [];
	// while ($row = $sql_barang->fetch_assoc()) {
	//   $produk[] = $row;
	// }

	$kategori_btn = [];

	$query = $conn->query("
	    SELECT j.id_jenis, j.nama_jenis
	    FROM jenis j
	    JOIN barang b ON b.id_jenis = j.id_jenis
	    GROUP BY j.id_jenis
	");

	while ($row = $query->fetch_assoc()) {

	    switch ($row['id_jenis']) {

	        case 1:
	            $kategori_btn[$row['id_jenis']] = "Pesan";
	            break;

	        case 2:
	            $kategori_btn[$row['id_jenis']] = "Lihat Jasa";
	            break;

	        case 3:
	            $kategori_btn[$row['id_jenis']] = "Lihat Event";
	            break;

	        case 4:
	            $kategori_btn[$row['id_jenis']] = "Gabung Membership";
	            break;

	        case 5:
	            $kategori_btn[$row['id_jenis']] = "Hubungi Pelatih";
	            break;
	    }
	}

	// ambil kategori
	$kategori_search = [];
	$qKat = $conn->query("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
	while ($row = $qKat->fetch_assoc()) {
	    $kategori_search[] = $row;
	}

	// ambil lokasi unik
	$lokasi_list = [];
	$qLok = $conn->query("SELECT DISTINCT kota FROM barang WHERE kota IS NOT NULL AND kota != '' ORDER BY kota ASC");
	while ($row = $qLok->fetch_assoc()) {
	    $lokasi_list[] = $row['kota'];
	}

	$where = [];
	$params = [];
	$types = "";

	// SEARCH (nama / merk)
	if (!empty($_GET['q'])) {
	    $where[] = "(b.nama_barang LIKE ? OR merk LIKE ?)";
	    $q = '%' . $_GET['q'] . '%';
	    $params[] = $q;
	    $params[] = $q;
	    $types .= "ss";
	}

	// FILTER KATEGORI
	if (!empty($_GET['kategori'])) {
	    $where[] = "b.id_kategori = ?";
	    $params[] = (int)$_GET['kategori'];
	    $types .= "i";
	}

	// FILTER LOKASI
	if (!empty($_GET['lokasi'])) {
	    $where[] = "b.kota = ?";
	    $params[] = $_GET['lokasi'];
	    $types .= "s";
	}

	if (!empty($_GET['harga_min'])) {
    $where[] = "b.harga_jual >= ?";
    $params[] = (int)$_GET['harga_min'];
    $types .= "i";
	}

	// FILTER HARGA MAX
	if (!empty($_GET['harga_max'])) {
	    $where[] = "b.harga_jual <= ?";
	    $params[] = (int)$_GET['harga_max'];
	    $types .= "i";
	}

	// BASE QUERY
	$sql = "
	  SELECT 
	    b.*, 
	    k.nama_kategori
	  FROM barang b
	  JOIN kategori k 
	    ON b.id_kategori = k.id_kategori
	";

	if (!$sql) {
	  die('Query Error: ' . $conn->error);
	}

	// $produk = [];
	// while ($row = $sql_barang->fetch_assoc()) {
	//   $produk[] = $row;
	// }

	// WHERE
	if ($where) {
	    $sql .= " WHERE " . implode(" AND ", $where);
	}

	// SORTING
	switch ($_GET['sort'] ?? '') {
	    case 'terbaru':
	        $sql .= " ORDER BY b.id_barang DESC";
	        break;
	    case 'termurah':
	        $sql .= " ORDER BY b.harga_jual ASC";
	        break;
	    case 'termahal':
	        $sql .= " ORDER BY b.harga_jual DESC";
	        break;
	    case 'az':
	        $sql .= " ORDER BY b.nama_barang ASC";
	        break;
	    default:
	        $sql .= " ORDER BY b.id_barang DESC";
	}

	$stmt = $conn->prepare($sql);

	if ($params) {
	    $stmt->bind_param($types, ...$params);
	}

	$stmt->execute();
	$produk_filter = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Marketplace</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
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
<body class="animsition">
	
	<!-- Header -->
	<header class="header-v4">

	    <!-- ================= HEADER DESKTOP ================= -->
	    <div class="container-menu-desktop">
	        <div class="wrap-menu-desktop how-shadow1" style="background-color: #0F1426;">
	            <nav class="limiter-menu-desktop container">

	                <!-- Logo -->
	                <a href="<?= $url_utama ?>" class="logo">
	                    <img src="images/logo-sport-nav.png" alt="logo-sportpreneur">
	                </a>

	                <!-- Menu Desktop -->
	                <div class="menu-desktop">
	                    <ul class="main-menu">
	                        <li><a href="<?= $url_utama ?>">Home</a></li>
	                        <li><a href="/inkubator">Inkubator</a></li>
	                        <li class="active-menu"><a href="/product">Shop</a></li>
	                        <li><a href="/blog">Blog</a></li>
	                        <li><a href="/matchmaking">Matchmaking</a></li>
	                        <li><a href="/event">Event</a></li>
	                    </ul>
	                </div>

	                <!-- Icon Header -->
	                <div class="wrap-icon-header flex-w flex-r-m">
	                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
							<a href="/profile"><i class="zmdi zmdi-account"></i></a>
						</div>

	                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-cart">
	                        <span class="icon-header-noti" id="cart-count"></span>
	                        <i class="zmdi zmdi-shopping-cart"></i>
	                    </div>

	                    <a href="javascript:void(0)" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="signout">
	                        <i class="zmdi zmdi-power"></i>
	                    </a>
	                </div>

	            </nav>
	        </div>
	    </div>

	    <!-- ================= HEADER MOBILE ================= -->
	    <div class="wrap-header-mobile">

	        <!-- Logo Mobile -->
	        <div class="logo-mobile">
	            <a href="<?= $url_utama ?>">
	                <img src="images/logo-sport-nav.png" alt="logo-sportpreneur">
	            </a>
	        </div>

	        <!-- Icon Mobile -->
	        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
	            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
					<a href="/profile"><i class="zmdi zmdi-account"></i></a>
				</div>

	            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-cart">
	                <span class="icon-header-noti" id="cart-count"></span>
	                <i class="zmdi zmdi-shopping-cart"></i>
	            </div>

	            <a href="javascript:void(0)" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="signout">
	                <i class="zmdi zmdi-power"></i>
	            </a>
	        </div>

	        <!-- Button Menu Mobile -->
	        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
	            <span class="hamburger-box">
	                <span class="hamburger-inner"></span>
	            </span>
	        </div>

	    </div>

	    <!-- ================= MENU MOBILE ================= -->
	    <div class="menu-mobile">
	        <ul class="main-menu-m">
	            <li><a href="<?= $url_utama ?>">Home</a></li>
	            <li><a href="/inkubator">Inkubator</a></li>
	            <li><a href="/product">Shop</a></li>
	            <li><a href="/blog">Blog</a></li>
	            <li><a href="/matchmaking">Matchmaking</a></li>
	            <li><a href="/event">Event</a></li>
	        </ul>
	    </div>

	</header>


	<!-- Cart -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>

		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Keranjang Kamu
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>
			
			<div class="header-cart-content flex-w js-pscroll">
				<ul class="header-cart-wrapitem w-full" id="cart-items">
					
				</ul>
				
				<div class="w-full">
					<div class="header-cart-total w-full p-tb-40" id="cart-total">
						Total: 0
					</div>

					<div class="header-cart-buttons flex-w w-full">
						<a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Product -->
	<div class="bg0 p-b-140" style="background-color: #0F1426;">
		<div class="container">

			<div class="mtext-107 cl2 plh2 mb-5 text-center">
				<h2 class="ltext-103 redefine-title text-center text-white">
					Tempat Jual-Beli & Kolaborasi Ekonomi Olahraga
				</h2>
				<h6>
					Disini, produk dan jasa olahraga tidak sekadar dipajang, tapi menjadi <b> mesin transaksi, penyerap tenaga kerja, dan bukti nyata pertumbuhan ekonomi sport. </b>
				</h6>
			</div>

			<form method="get" class="row g-2 mb-4">

			  <!-- Search Nama -->
			  <div class="col-md-3">
			    <input type="text" name="q" class="form-control"
			           placeholder="Cari barang..."
			           value="<?= $_GET['q'] ?? '' ?>">
			  </div>

			  <!-- Kategori -->
			  <div class="col-md-2">
			    <select name="kategori" class="form-control">
			      <option value="">Semua Kategori</option>
			      <?php foreach ($kategori_search as $k): ?>
			        <option value="<?= $k['id_kategori'] ?>"
			          <?= ($_GET['kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '' ?>>
			          <?= $k['nama_kategori'] ?>
			        </option>
			      <?php endforeach ?>
			    </select>
			  </div>

			  <!-- Harga Min -->
			  <div class="col-md-2">
			    <input type="number" name="harga_min" class="form-control"
			           placeholder="Harga min"
			           value="<?= $_GET['harga_min'] ?? '' ?>">
			  </div>

			  <!-- Harga Max -->
			  <div class="col-md-2">
			    <input type="number" name="harga_max" class="form-control"
			           placeholder="Harga max"
			           value="<?= $_GET['harga_max'] ?? '' ?>">
			  </div>

			  <!-- Sort -->
			  <div class="col-md-2">
			    <select name="sort" class="form-control">
			      <option value="">Terbaru</option>
			      <option value="termurah">Termurah</option>
			      <option value="termahal">Termahal</option>
			      <option value="az">A - Z</option>
			    </select>
			  </div>

			  <div class="col-md-1">
			    <button class="btn btn-dark w-100">Cari</button>
			  </div>

			</form>


			<div class="row isotope-grid">
				<?php foreach ($produk_filter as $produk): ?>
				<div class="col-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
				  <div class="block2">

				    <!-- Gambar Produk -->
				    <div class="block2-pic hov-img0">
				    	<span class="block2-category">
					      <?= htmlspecialchars($produk['nama_kategori']) ?>
					    </span>
				      <img src="images/<?= htmlspecialchars($produk['foto_produk']) ?>" alt="<?= htmlspecialchars($produk['nama_barang']) ?>">
				    </div>

				    <!-- Info Produk -->
				    <div class="block2-txt p-t-14">

				      <a href="#" class="stext-104 cl4 hov-cl1 trans-04 d-block p-b-5">
				        <?= htmlspecialchars($produk['nama_barang']) ?>
				      </a>

				      <span class="stext-105 cl3 d-block p-b-10">
				        Rp <?= number_format($produk['harga_jual'], 0, ',', '.') ?>
				      </span>

				      <?php $btn_text = $kategori_btn[$produk['id_jenis']] ?? "Lihat Produk"; ?>

				      <!-- Button Add Cart -->
				      <a href="#"
				         class="btn-add-cart add-cart"
				         data-id="<?= $produk['id_barang'] ?>"
				         data-kategori="<?= $produk['id_jenis'] ?>"
				         data-nama="<?= htmlspecialchars($produk['nama_barang']) ?>"
				         data-foto="<?= htmlspecialchars($produk['foto_produk']) ?>"
				         data-harga="<?= $produk['harga_jual'] ?>">
				         <i class="zmdi zmdi-shopping-cart"></i>
				         <span><?= $btn_text ?></span>
				      </a>

				    </div>

				  </div>
				</div>
				<?php endforeach ?>
			</div>

		</div>
	</div>
		

	<!-- Footer -->
	<?php include 'footer.php';?>