<?php

	// require_once __DIR__ . '/session_modal.php';

	require_once __DIR__ . '/koneksi.php';

	// Validasi slug
	if (empty($_GET['slug'])) {
	    http_response_code(404);
	    exit('Artikel tidak ditemukan');
	}

	$slug = trim($_GET['slug']);

	// Query artikel
	$stmt = $conn->prepare("SELECT * FROM tb_artikel WHERE slug = ? LIMIT 1");
	$stmt->bind_param("s", $slug);
	$stmt->execute();

	$result  = $stmt->get_result();
	$artikel = $result->fetch_assoc();

	if (!$artikel) {
	    http_response_code(404);
	    exit('Artikel tidak ditemukan');
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($artikel['judul']) ?> - Sportpreneur</title>

	<meta name="description" content="<?= htmlspecialchars(substr(strip_tags($artikel['deskripsi']), 0, 160)) ?>">
	<meta name="keywords" content="<?= htmlspecialchars($artikel['keywords']) ?>">

	<!-- Open Graph -->
	<meta property="og:title" content="<?= htmlspecialchars($artikel['judul']) ?>">
	<meta property="og:description" content="<?= htmlspecialchars(substr(strip_tags($artikel['deskripsi']), 0, 160)) ?>">
	<meta property="og:image" content="<?= $url_admin ?>uploads/<?= htmlspecialchars($artikel['gambar']) ?>">
	<meta property="og:url" content="<?= htmlspecialchars($current_url) ?>">
	<meta property="og:type" content="article">

	<link rel="canonical" href="<?= htmlspecialchars($current_url) ?>">

    <link rel="icon" type="image/x-icon" href="<?= $url_utama?>assets/images/logo_fimatha.png">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?= $url_utama?>images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>fonts/linearicons-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>css/util.css">
	<link rel="stylesheet" type="text/css" href="<?= $url_utama?>css/main.css">
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
	                    <img src="<?=$url_utama?>images/logo-sport-nav.png" alt="IMG-LOGO">
	                </a>

	                <!-- Menu Desktop -->
	                <div class="menu-desktop">
	                    <ul class="main-menu">
	                        <li><a href="<?= $url_utama ?>">Home</a></li>
	                        <li><a href="/inkubator">Inkubator</a></li>
	                        <li><a href="/product">Shop</a></li>
	                        <li class="active-menu"><a href="/blog">Blog</a></li>
	                        <li><a href="/matchmaking">Matchmaking</a></li>
	                        <li><a href="/event">Event</a></li>
	                    </ul>
	                </div>

	                <!-- Icon header -->
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
	                <img src="<?=$url_utama?>images/logo-sport-nav.png" alt="IMG-LOGO">
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

	            <a href="javascript:void(0)" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="signout2">
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
						<!-- <a href="shoping-cart.html" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							View Cart
						</a> -->

						<a href="<?=$url_utama?>shoping-cart.php" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?=$url_utama?>" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="<?=$url_utama?>blog" class="stext-109 cl8 hov-cl1 trans-04">
				Blog
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				<?= htmlspecialchars($artikel['judul']) ?>
			</span>
		</div>
	</div>


	<!-- Content page -->
	<section class="bg0 p-t-52 p-b-20" style="background-color: #0F1426;">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-lg-9 p-b-80">
					<div class="p-r-45 p-r-0-lg">
						<!--  -->
						<div class="wrap-pic-w how-pos5-parent">
							<img src="<?= $url_utama ?>images/banner-08.jpg" 
     							alt="<?= htmlspecialchars($artikel['judul']) ?>">

							<div class="flex-col-c-m size-123 bg9 how-pos5">
								<!-- <span class="ltext-107 cl2 txt-center">
									22
								</span> -->

								<span class="stext-109 cl3 txt-center">
									<?= date("d M Y", strtotime($artikel['created_at'])) ?>
								</span>
							</div>
						</div>

						<div class="p-t-32">
							<span class="flex-w flex-m stext-111 cl2 p-b-19">
								<span>
									<span class="cl4">By</span> Admin  
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<span>
									<?= date("d M Y", strtotime($artikel['created_at'])) ?>
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<span>
									<?= htmlspecialchars($artikel['kategori']) ?>
									<span class="cl12 m-l-4 m-r-6">|</span>
								</span>

								<!-- <span>
									8 Comments
								</span> -->
							</span>

							<h4 class="ltext-109 cl2 p-b-28">
								<?= htmlspecialchars($artikel['judul']) ?>
							</h4>

							<div class="article-content stext-117 cl6">
							    <?= nl2br($artikel['isi']) ?>
							</div>

							<!-- <p class="stext-117 cl6 p-b-26">
								<?= nl2br($artikel['isi']) ?>
							</p> -->
						</div>

						<div class="flex-w flex-t p-t-16">
							<span class="size-216 stext-116 cl8 p-t-4">
								Tags
							</span>

							<div class="flex-w size-217">
							    <?php
							    $keywords = array_filter(array_map('trim', explode(',', $artikel['keywords'])));

							    foreach ($keywords as $tag):
							    ?>
							        <a href="<?= $url_utama ?>tag/<?= urlencode($tag) ?>"
							           class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
							            <?= htmlspecialchars($tag) ?>
							        </a>
							    <?php endforeach; ?>
							</div>

						</div>

						<!--  -->
						<!-- <div class="p-t-40">
							<h5 class="mtext-113 cl2 p-b-12">
								Leave a Comment
							</h5>

							<p class="stext-107 cl6 p-b-40">
								Your email address will not be published. Required fields are marked *
							</p>

							<form>
								<div class="bor19 m-b-20">
									<textarea class="stext-111 cl2 plh3 size-124 p-lr-18 p-tb-15" name="cmt" placeholder="Comment..."></textarea>
								</div>

								<div class="bor19 size-218 m-b-20">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="name" placeholder="Name *">
								</div>

								<div class="bor19 size-218 m-b-20">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="email" placeholder="Email *">
								</div>

								<div class="bor19 size-218 m-b-30">
									<input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="web" placeholder="Website">
								</div>

								<button class="flex-c-m stext-101 cl0 size-125 bg3 bor2 hov-btn3 p-lr-15 trans-04">
									Post Comment
								</button>
							</form>
						</div> -->
					</div>
				</div>

				<div class="col-md-4 col-lg-3 p-b-80">
					<div class="side-menu">

						<div class="p-t-65">
							<h4 class="mtext-112 cl2 p-b-33">
								Produk
							</h4>

							<ul>
								<?php foreach ($produk as $produk): ?>
									<li class="flex-w flex-t p-b-30">
										<a href="<?=$url_utama?>product" class="wrao-pic-w small-thumb hov-ovelay1 m-r-20">
											<img src="<?=$url_utama?>images/<?=$produk['foto_produk']?>" alt="PRODUCT">
										</a>

										<div class="size-215 flex-col-t p-t-8">
											<a href="<?=$url_utama?>product" class="stext-116 cl8 hov-cl1 trans-04">
												<?=$produk['nama_barang']?>
											</a>

											<span class="stext-116 cl6 p-t-20">
												<?=$produk['harga_jual']?>
											</span>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>	

	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
	  	<div class="container">
		    <div class="row footer-row">

		      <div class="col-sm-6 col-lg-3 footer-col">
		        <img src="<?=$url_utama?>images/logo-sport.png" class="footer-logo">
		      </div>

		      <div class="col-sm-6 col-lg-3 footer-col">
		        <h4 class="stext-301 cl0 p-b-20">
		          GET IN TOUCH
		        </h4>

		        <p class="stext-107 cl7">
		          Any questions? call us on <br> (+62) 812-3456-789
		        </p>

		        <div class="p-t-20">
		          <a href="https://facebook.com/sportpreneurid/" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
		            <i class="fa fa-facebook"></i>
		          </a>

		          <a href="https://instagram.com/sportpreneurid/" class="fs-18 cl7 hov-cl1 trans-04">
		            <i class="fa fa-instagram"></i>
		          </a>
		        </div>
		      </div>

		      <div class="col-sm-12 col-lg-6 footer-col footer-copy">
		        <p class="stext-107 cl6">
		          Copyright &copy;
		          <script>document.write(new Date().getFullYear());</script>
		          All rights reserved | Made with
		          <i class="fa fa-heart-o"></i>
		          by <br> <a href="https://sportpreneur.id" target="_blank">Sportpreneur Indonesia Berdampak</a>
		        </p>
		      </div>

		    </div>
		</div>
	</footer>

	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

<!--===============================================================================================-->	
	<script src="<?= $url_utama?>vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>vendor/bootstrap/js/popper.js"></script>
	<script src="<?= $url_utama?>vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
<!--===============================================================================================-->
	<script src="<?= $url_utama?>js/main.js"></script>

	<script>
		document.getElementById("signout").addEventListener("click", function() {
			if (confirm("Yakin ingin keluar dari akun?")) {
			  window.location.href = "logout.php"; // arahkan ke file logout PHP
			}
		});

	    document.getElementById("signout2").addEventListener("click", function() {
	        if (confirm("Yakin ingin keluar dari akun?")) {
	          window.location.href = "logout.php"; // arahkan ke file logout PHP
	        }
	   	});
    </script>

    <script>

		function renderCart() {
		    fetch('<?=$url_utama?>cart_render.php')
		        .then(res => res.json())
		        .then(data => {

		            // isi cart popup / table
		            document.getElementById('cart-items').innerHTML = data.html;
		            document.getElementById('cart-total').innerHTML =
		                'Total: Rp ' + data.total;

		            bindButtons();
		            updateCartBadge();
		        });
		}

		function bindButtons() {
		    document.querySelectorAll('.qty-btn').forEach(btn => {
		        btn.onclick = () => updateCart(btn.dataset.id, btn.dataset.type);
		    });

		    document.querySelectorAll('.remove-cart').forEach(btn => {
		        btn.onclick = () => updateCart(btn.dataset.id, 'remove');
		    });
		}

		function updateCart(id, type) {
		    fetch('<?=$url_utama?>cart_update.php', {
		        method: 'POST',
		        headers: {'Content-Type': 'application/json'},
		        body: JSON.stringify({id, type})
		    }).then(() => {
		        renderCart();        // popup
		        renderCartTable();   // table
		        updateCartBadge();   // badge
		    });
		}


		function updateCartBadge() {
		  fetch('<?=$url_utama?>cart_count.php')
		    .then(res => res.json())
		    .then(data => {
		      document.querySelectorAll('.icon-header-noti').forEach(el => {
		        el.setAttribute('data-notify', data.count);
		      });
		    });
		}

		renderCart();
		renderCartTable();
		updateCartBadge();
	</script>

    <!-- <script>
		document.getElementById('profileForm').addEventListener('submit', function(e) {
		  e.preventDefault();

		  const form = this;
		  const msg  = document.getElementById('profileMsg');
		  const btn  = form.querySelector('button');

		  btn.disabled = true;
		  btn.innerText = 'Updating...';

		  fetch('update_profile.php', {
		    method: 'POST',
		    body: new FormData(form)
		  })
		  .then(res => res.json())
		  .then(data => {
		    if (data.status === 'success') {
		      msg.innerHTML = `
		        <div class="alert alert-success">
		          ✅ Profil berhasil diperbarui
		        </div>
		      `;
		    } else {
		      msg.innerHTML = `
		        <div class="alert alert-danger">
		          ❌ ${data.message}
		        </div>
		      `;
		    }
		  })
		  .catch(() => {
		    msg.innerHTML = `
		      <div class="alert alert-danger">
		        ❌ Terjadi kesalahan
		      </div>
		    `;
		  })
		  .finally(() => {
		    btn.disabled = false;
		    btn.innerText = 'Update Data';
		  });
		});
	</script> -->

</body>
</html>