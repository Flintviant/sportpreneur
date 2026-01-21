<?php require_once __DIR__ . '/head.php'; ?>


	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/slide-03.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
			Blog
		</h2>
	</section>	

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="/" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Blog
			</span>
		</div>
	</div>

	<!-- Content page -->
	<section class="bg0 p-t-62 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-lg-9 p-b-80">
					<div class="p-r-45 p-r-0-lg">
						<!-- item blog -->
						<?php if (!empty($artikels)): ?>
			            	<?php foreach ($artikels as $artikel): ?>
								<div class="p-b-63">
								    <a href="<?= $url_utama ?>artikel/<?= urlencode($artikel['slug']) ?>" class="hov-img0 how-pos5-parent">
								        <img src="<?= $url_admin ?>uploads/<?= htmlspecialchars($artikel['gambar']) ?>"
								             alt="<?= htmlspecialchars($artikel['judul']) ?>">

								        <div class="flex-col-c-m size-123 bg9 how-pos5">
								            <span class="stext-109 cl3 txt-center">
								                <?= date("d M Y", strtotime($artikel['created_at'])) ?>
								            </span>
								        </div>
								    </a>

								    <div class="p-t-32">
								        <h4 class="p-b-15">
								            <a href="<?= $url_utama ?>artikel/<?= urlencode($artikel['slug']) ?>" class="ltext-108 cl2 hov-cl1 trans-04">
								                <?= htmlspecialchars($artikel['judul']) ?>
								            </a>
								        </h4>

								        <p class="stext-117 cl6">
								            <?= htmlspecialchars(substr(strip_tags($artikel['deskripsi']), 0, 180)) ?>...
								        </p>

								        <div class="flex-w flex-sb-m p-t-18">
								            <span class="stext-111 cl2">
								                <span class="cl4">By</span> Admin |
								                <?= htmlspecialchars($artikel['kategori']) ?>
								            </span>

								            <a href="<?= $url_utama ?>artikel/<?= urlencode($artikel['slug']) ?>" class="stext-101 cl2 hov-cl1 trans-04">
								                Continue Reading <i class="fa fa-long-arrow-right m-l-9"></i>
								            </a>
								        </div>
								    </div>
								</div>
							<?php endforeach; ?>

						<?php else: ?>
							<div class="col-12 text-center">
							  <p>Belum ada artikel.</p>
							</div>
						<?php endif; ?>

						<!-- Pagination -->
						<div class="flex-l-m flex-w w-full p-t-10 m-lr--7">
							<?php if ($page > 1): ?>

								<a href="?page=<?= $page - 1 ?>" class="flex-c-m how-pagination1 trans-04 m-all-7 previous-posts"><i class="fa fa-arrow-left"></i>
								</a>

							<?php endif; ?>

							<?php for ($i = 1; $i <= $total_pages; $i++): ?>
				                <a href="?page=<?= $i ?>" class="flex-c-m how-pagination1 trans-04 m-all-7 <?= ($i == $page) ? 'active' : '' ?>">
				                  <?= $i ?>
				                </a>
				            <?php endfor; ?>

				            <?php if ($page < $total_pages): ?>
				                <a href="?page=<?= $page + 1 ?>" class="flex-c-m how-pagination1 trans-04 m-all-7 next-posts"><i class="fa fa-arrow-right"></i></a>
				            <?php endif; ?>

						</div>

					</div>
				</div>

				<div class="col-md-4 col-lg-3 p-b-80">
					<div class="side-menu">
						<!-- <div class="bor17 of-hidden pos-relative">
							<input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search" placeholder="Search">

							<button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
								<i class="zmdi zmdi-search"></i>
							</button>
						</div> -->

						<!-- <div class="p-t-55">
							<h4 class="mtext-112 cl2 p-b-33">
								Kategori
							</h4>

							<ul>
								<?php foreach ($artikels as $artikel): ?>
									<li class="bor18">
										<a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
											<?=$artikel['kategori']?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div> -->

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
	<?php include 'footer.php';?>