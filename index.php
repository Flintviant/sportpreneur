<?php
	include 'head.php';

	$kategori_btn = [
	    1 => "Pesan",
	    2 => "Lihat Jasa",
	    3 => "Lihat Event",
	    4 => "Gabung Membership",
	    5 => "Hubungi Pelatih"
	];
?>

	<section class="col-12 section-card">
	  <div class="card col-10 mx-auto item-slick1">

	    <div class="hero-content">
	      <a href="#program" class="hero-btn">
	        Mulai Bangun Profil
	      </a>
	    </div>

	  </div>
	</section>

	<section class="polygon-section">

	  <!-- Polygon Background -->
	    <div class="polygon-bg">
		    <span class="poly"></span>
		    <span class="poly"></span>
		    <span class="poly"></span>
		    <span class="poly"></span>
		    <span class="poly"></span>
	    </div>

		<!-- Product -->
		<section class="p-t-23 p-b-140 mt-5">
			<div class="container">
				<div class="p-b-10">
					<h3 class="ltext-103 redefine-title text-center">
						SPORTMARKET
					</h3>
				</div>

				<div class="row isotope-grid">
					<?php foreach ($produk as $produks): ?>
					<div class="col-sm-12 col-md-4 col-lg-3 p-b-35 isotope-item women">
					  	<div class="block2">

						  <!-- Gambar Produk -->
							<div class="block2-pic hov-img0">

							    <!-- KATEGORI -->
							    <span class="block2-category">
							      <?= htmlspecialchars($produks['nama_kategori']) ?>
							    </span>

							    <a href="<?=$url_utama?>product">
							      <img src="images/<?= htmlspecialchars($produks['foto_produk']) ?>" 
							           alt="<?= htmlspecialchars($produks['nama_barang']) ?>">
							    </a>

							    <?php $btn_text = $kategori_btn[$produks['id_kategori']] ?? "Lihat Produk"; ?>

							    <!-- BUTTON -->
							    <a href="<?=$url_utama?>product" class="block2-btn hero-btn">
								  <?= $btn_text ?>
								</a>

						  	</div>

						  <!-- Info Produk -->
							<div class="block2-txt p-t-14">
							    <a href="<?=$url_utama?>product" class="stext-104 cl4 hov-cl1 trans-04 d-block p-b-5">
							      <?= htmlspecialchars($produks['nama_barang']) ?> - <?= htmlspecialchars($produks['merk']) ?>
							    </a>

							    <span class="stext-105 cl3 d-block p-b-10">
							      Rp <?= number_format($produks['harga_jual'], 0, ',', '.') ?>
							    </span>
						  	</div>

						</div>

					</div>
					<?php endforeach ?>
				</div>
			</div>
		</section>

	</section>

	<section class="event-gallery">
	  	<div class="container">

		    <h3 class="event-title text-center">
		      EVENT <span>GALLERY</span>
		    </h3>

		    <div class="gallery-grid">

				<a href="#" class="gallery-item large championship">
				<span>Championship<br>2024</span>
				</a>

				<a href="#" class="gallery-item large workshop">
				<span>Workshop</span>
				</a>

				<a href="#" class="gallery-item large seminar">
				<span>Seminar</span>
				</a>

				<a href="#" class="gallery-item large bootcamp">
				<span>Bootcamp</span>
				</a>

				<a href="#" class="gallery-item large community">
				<span>Community</span>
				</a>

				<a href="#" class="gallery-item large exhibition">
				<span>Exhibition</span>
				</a>

				<a href="#" class="gallery-item large training">
				<span>Training</span>
				</a>

				<a href="#" class="gallery-item large networking">
				<span>Networking</span>
				</a>

				<a href="#" class="gallery-item large talkshow">
				<span>Upcoming Event</span>
				</a>

	    	</div>
	  	</div>
	</section>

		  <!-- ================= PARTNERS ================= -->
	<section class="partner-section p-t-60 p-b-90">
	    <div class="container">
	      <h3 class="ltext-103 redefine-title text-center mb-5">OUR PARTNERS</h3>
	      <div class="partner-slider">
	        <div class="partner-item"><span>NIKE<br>INDONESIA</span></div>
	        <div class="partner-item"><span>ADIDAS<br>SPORTS</span></div>
	        <div class="partner-item"><span>GOJEK<br>VENTURES</span></div>
	        <div class="partner-item"><span>TOKOPEDIA<br>CARE</span></div>
	        <div class="partner-item"><span>KEMENPORA<br>RI</span></div>
	        <div class="partner-item"><span>BCA<br>DIGITAL</span></div>
	      </div>
	    </div>
	</section>

	  <!-- ================= TEAM ================= -->
	<section class="team-section p-t-40 p-b-90">
	    <div class="container">

		    <h3 class="team-title text-center">
		      MEET OUR TEAM
		    </h3>

		    <div class="row justify-content-center team-grid">

		       <div class="col-md-6 col-lg-4 col-xl-3 p-b-30">
		        <div class="team-card">
		          <img src="<?=$url_utama?>images/team/ceo.png" alt="Team Member">

		          <div class="team-info">
		            <h5>BWS</h5>
		            <span>Founder & CEO</span>
		          </div>
		        </div>
		       </div>

		       <div class="col-md-6 col-lg-4 col-xl-3 p-b-30">
		        <div class="team-card">
		          <img src="<?=$url_utama?>images/team/managing.png" alt="Team Member">

		          <div class="team-info">
		            <h5>RR</h5>
		            <span>Managing Director</span>
		          </div>
		        </div>
		       </div>

		       <div class="col-md-6 col-lg-4 col-xl-3 p-b-30">
		        <div class="team-card">
		          <img src="<?=$url_utama?>images/team/program.png" alt="Team Member">

		          <div class="team-info">
		            <h5>EP</h5>
		            <span>Event Development Lead</span>
		          </div>
		        </div>
		       </div>

		    </div>
	    </div>
	</section>

	  <!-- ================= IMPACT ================= -->
	<section class="impact-section p-t-40 p-b-90">
		<div class="container">

		    <h3 class="team-title text-center mb-5">
		      OUR IMPACT
		    </h3>

		    <div class="row justify-content-center impact-grid">

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="134">134</h4>
			          <span>UMKM Sport Aktif</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="50">50</h4>
			          <span>Tenaga Kerja Terserap</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="1">1,2 M</h4>
			          <span>Omzet Anggota</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="39">39</h4>
			          <span>Event Terdaftar</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="300">300</h4>
			          <span>Pelatihan & Sertifikasi</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="450">450 Jt</h4>
			          <span>Kontribusi CSR/Partnership</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="37">37</h4>
			          <span>Brand & Sponsor Aktif</span>
			        </div>
		    	</div>

		    	<div class="col-md-6 col-lg-3 p-b-30">
			        <div class="impact-card">
			          <h4 data-target="25">25</h4>
			          <span>Mentor & Coach Bisnis</span>
			        </div>
		    	</div>

		    </div>
		</div>
	</section>

	<!-- <section class="sec-blog bg0 p-t-60 p-b-90">
		<div class="container">
			<div class="p-b-66">
				<h3 class="ltext-105 cl5 txt-center respon1">
					Our Blogs
				</h3>
			</div>

			<div class="row">
				<div class="col-sm-6 col-md-4 p-b-40">
					<div class="blog-item">
						<div class="hov-img0">
							<a href="blog-detail.html">
								<img src="images/blog-01.jpg" alt="IMG-BLOG">
							</a>
						</div>

						<div class="p-t-15">
							<div class="stext-107 flex-w p-b-14">
								<span class="m-r-3">
									<span class="cl4">
										By
									</span>

									<span class="cl5">
										Nancy Ward
									</span>
								</span>

								<span>
									<span class="cl4">
										on
									</span>

									<span class="cl5">
										July 22, 2017 
									</span>
								</span>
							</div>

							<h4 class="p-b-12">
								<a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
									8 Inspiring Ways to Wear Dresses in the Winter
								</a>
							</h4>

							<p class="stext-108 cl6">
								Duis ut velit gravida nibh bibendum commodo. Suspendisse pellentesque mattis augue id euismod. Interdum et male-suada fames
							</p>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-4 p-b-40">
					<div class="blog-item">
						<div class="hov-img0">
							<a href="blog-detail.html">
								<img src="images/blog-02.jpg" alt="IMG-BLOG">
							</a>
						</div>

						<div class="p-t-15">
							<div class="stext-107 flex-w p-b-14">
								<span class="m-r-3">
									<span class="cl4">
										By
									</span>

									<span class="cl5">
										Nancy Ward
									</span>
								</span>

								<span>
									<span class="cl4">
										on
									</span>

									<span class="cl5">
										July 18, 2017
									</span>
								</span>
							</div>

							<h4 class="p-b-12">
								<a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
									The Great Big List of Men’s Gifts for the Holidays
								</a>
							</h4>

							<p class="stext-108 cl6">
								Nullam scelerisque, lacus sed consequat laoreet, dui enim iaculis leo, eu viverra ex nulla in tellus. Nullam nec ornare tellus, ac fringilla lacus. Ut sit ame
							</p>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-4 p-b-40">
					<div class="blog-item">
						<div class="hov-img0">
							<a href="blog-detail.html">
								<img src="images/blog-03.jpg" alt="IMG-BLOG">
							</a>
						</div>

						<div class="p-t-15">
							<div class="stext-107 flex-w p-b-14">
								<span class="m-r-3">
									<span class="cl4">
										By
									</span>

									<span class="cl5">
										Nancy Ward
									</span>
								</span>

								<span>
									<span class="cl4">
										on
									</span>

									<span class="cl5">
										July 2, 2017 
									</span>
								</span>
							</div>

							<h4 class="p-b-12">
								<a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
									5 Winter-to-Spring Fashion Trends to Try Now
								</a>
							</h4>

							<p class="stext-108 cl6">
								Proin nec vehicula lorem, a efficitur ex. Nam vehicula nulla vel erat tincidunt, sed hendrerit ligula porttitor. Fusce sit amet maximus nunc
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->

	<?php include 'footer.php'; ?>