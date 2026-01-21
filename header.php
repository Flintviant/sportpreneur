<body class="animsition">
	
	<!-- Header -->
	<header>
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<!-- Topbar -->
			<!-- <div class="top-bar">
				<div class="content-topbar flex-sb-m h-full container">
					<div class="left-top-bar">
						Free shipping for standard order over $100
					</div>

					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m trans-04 p-lr-25">
							Help & FAQs
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							My Account
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							EN
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							USD
						</a>
					</div>
				</div>
			</div> -->

			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">
					
					<!-- Logo desktop -->		
					<a href="<?=$url_utama?>" class="logo">
						<img src="images/logo-sport-nav.png" alt="logo-sportpreneur">
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
					    <ul class="main-menu">
					        <li class="<?= activeMenu('/', $current) ?>">
					            <a href="<?= $url_utama ?>">Home</a>
					        </li>
					        <li class="<?= activeMenu('/product', $current) ?>">
					            <a href="/product">Shop</a>
					        </li>
					        <li class="<?= activeMenu('/blog', $current) ?>">
					            <a href="/blog">Blog</a>
					        </li>
					        <!-- <li class="<?= activeMenu('/about', $current) ?>">
					            <a href="/about">About</a>
					        </li> -->
					        <li class="<?= activeMenu('/contact', $current) ?>">
					            <a href="/contact">Contact</a>
					        </li>
					    </ul>
					</div>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-account"></i>
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

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->		
			<div class="logo-mobile">
				<a href="<?=$url_utama?>"><img src="images/logo-sport-nav.png" alt="logo-sportpreneur"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-account"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-cart">
					<span class="icon-header-noti" id="cart-count"></span>
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<a class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="signout">
	              <i class="zmdi zmdi-power"></i>
	            </a>
			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<!-- <ul class="topbar-mobile">
				<li>
					<div class="left-top-bar">
						Free shipping for standard order over $100
					</div>
				</li>

				<li>
					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m p-lr-10 trans-04">
							Help & FAQs
						</a>

						<a href="#" class="flex-c-m p-lr-10 trans-04">
							My Account
						</a>

						<a href="#" class="flex-c-m p-lr-10 trans-04">
							EN
						</a>

						<a href="#" class="flex-c-m p-lr-10 trans-04">
							USD
						</a>
					</div>
				</li>
			</ul> -->

			<ul class="main-menu-m">
				<li>
					<a href="<?=$url_utama?>">Home</a>
				</li>

				<li>
					<a href="/product">Shop</a>
				</li>

				<li>
					<a href="/blog">Blog</a>
				</li>

				<!-- <li>
					<a href="/about">About</a>
				</li> -->

				<li>
					<a href="/contact">Contact</a>
				</li>
			</ul>
		</div>

		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
		  <div class="container-search-header">

		    <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
		      <img src="images/icons/icon-close2.png" alt="CLOSE">
		    </button>

		    <?php 
		    	$isLogin = isset($_SESSION['id_member']);
		     	if ($isLogin): 
		    ?>
			    <!-- ================= USER SUDAH LOGIN ================= -->
			    <form id="profileForm" class="profile-form elegant-form">

				 	<input type="hidden" name="id_member" value="<?= $_SESSION['id_member'] ?>">

					<div class="form-group">
				    <label>Nama</label>
				    <input type="text" name="nm_member" value="<?= htmlspecialchars($nama) ?>" required>
					</div>

					<div class="form-group">
				    <label>Email</label>
				    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
					</div>

					<div class="form-group">
				    <label>Alamat</label>
				    <textarea name="alamat_member" rows="3" required><?= htmlspecialchars($address) ?></textarea>
					</div>

					<div class="form-group">
				    	<label>Nomor Handphone</label>
				    	<input type="text" name="telepon" value="<?= htmlspecialchars($phone) ?>" required>
				  	</div>

					<button type="submit" class="btn-update">
				    	Update Data
				  	</button>

				  	<div id="profileMsg" class="form-message"></div>

				</form>

		    <?php else: ?>
		    <!-- ================= USER BELUM LOGIN ================= -->
		    <div class="text-center p-4">

		      <h4 class="mb-3">Login Diperlukan</h4>
		      <p class="text-muted">
		        Silakan login terlebih dahulu untuk melihat dan mengubah profil Anda.
		      </p>

		      <a href="login.php" class="btn btn-dark w-100 mb-2">
		        Login
		      </a>

		      <a href="register.php" class="btn btn-outline-dark w-100">
		        Daftar Akun
		      </a>

		    </div>
		    <?php endif; ?>

		  </div>
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

	