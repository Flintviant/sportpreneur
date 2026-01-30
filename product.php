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

	$sql_barang = $conn->query("
	  SELECT 
	    b.*, 
	    k.nama_kategori
	  FROM barang b
	  JOIN kategori k 
	    ON b.id_kategori = k.id_kategori
	  ORDER BY b.id DESC
	  LIMIT 4
	");

	if (!$sql_barang) {
	  die('Query Error: ' . $conn->error);
	}

	$produk = [];
	while ($row = $sql_barang->fetch_assoc()) {
	  $produk[] = $row;
	}

	$kategori_btn = [
	    1 => "Pesan",
	    2 => "Lihat Jasa",
	    3 => "Lihat Event",
	    4 => "Gabung Membership",
	    5 => "Hubungi Pelatih"
	];
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Product</title>
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
	        <div class="wrap-menu-desktop how-shadow1">
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
	                        <!-- <li><a href="/about">About</a></li> -->
	                        <li><a href="/contact">Contact</a></li>
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
	            <!-- <li><a href="/about">About</a></li> -->
	            <li><a href="/contact">Contact</a></li>
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
						<!-- <a href="shoping-cart.html" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							View Cart
						</a> -->

						<a href="shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Product -->
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52">
				<img src="<?=$url_utama?>images/banner-shop.png" style="width: 100%;border-radius: 10%;">
			</div>

			<div class="mtext-107 cl2 size-114 plh2 mb-5 text-center">
				<h2 class="ltext-103 redefine-title text-center">
					Produk Kami
				</h2>	
			</div>

			<div class="row isotope-grid">
				<?php foreach ($produk as $produk): ?>
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

				      <?php $btn_text = $kategori_btn[$produk['id_kategori']] ?? "Lihat Produk"; ?>

				      <!-- Button Add Cart -->
				      <a href="#"
				         class="btn-add-cart add-cart"
				         data-id="<?= $produk['id_barang'] ?>"
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

			<!-- Load more -->
			<!-- <div class="flex-c-m flex-w w-full p-t-45">
				<a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
					Load More
				</a>
			</div> -->
		</div>
	</div>
		

	<!-- Footer -->
	<?php include 'footer.php';?>