<body class="animsition">
	
	<!-- Header -->
	<header>
		<!-- Header desktop -->
		<div class="container-menu-desktop">
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
					        <li class="<?= activeMenu('/inkubator', $current) ?>">
					            <a href="/inkubator">Inkubator</a>
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

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->		
			<div class="logo-mobile">
				<a href="<?=$url_utama?>"><img src="images/logo-sport-nav.png" alt="logo-sportpreneur"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
					<a href="/profile"><i class="zmdi zmdi-account"></i></a>
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
			<ul class="main-menu-m">
				<li>
					<a href="<?=$url_utama?>">Home</a>
				</li>

				<li>
					<a href="/inkubator">Inkubator</a>
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

	