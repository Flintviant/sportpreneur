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
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/slick/slick.min.js"></script>
	<script src="js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script src="vendor/parallax100/parallax100.js"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="vendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		function showToast(type, message, redirect = null) {
		    Swal.fire({
		        toast: true,
		        position: 'center',
		        icon: type,
		        title: message,
		        showConfirmButton: false,
		        timer: 3000,
		        timerProgressBar: true,
		        customClass: {
		            popup: 'big-toast',
		            title: 'big-toast-title'
		        }
		    }).then(() => {
		        if (redirect) {
		            window.location.href = redirect;
		        }
		    });
		}
	</script>

<!--===============================================================================================-->
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
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
	<script src="js/main.js"></script>

	<script>
		function renderCart() {
		    fetch('cart_render.php')
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

		document.querySelectorAll('.add-cart').forEach(btn => {
		    btn.onclick = e => {
		        e.preventDefault();

		        const kategori = btn.dataset.kategori;

		        if (kategori == 1) {
		            fetch('cart_add.php', {
		                method: 'POST',
		                headers: {'Content-Type': 'application/json'},
		                body: JSON.stringify({
		                    id: btn.dataset.id,
		                    nama: btn.dataset.nama,
		                    foto: btn.dataset.foto,
		                    harga: btn.dataset.harga
		                })
		            }).then(() => {
		                renderCart();
		                showToast('success', 'Produk ditambahkan ke cart');
		            });

		        } else {
		            fetch('kategori_action.php', {
		                method: 'POST',
		                headers: {'Content-Type': 'application/json'},
		                body: JSON.stringify({
		                    id: btn.dataset.id,
		                    kategori: kategori
		                })
		            })
		            .then(res => res.json())
		            .then(data => {
		                if (data.login === false) {
		                    showToast('warning', data.message, 'login.php');
		                    return;
		                }

		                // ✅ BERHASIL
		                if (data.status) {
		                    showToast('success', data.message, data.redirect);
		                } else {
		                    showToast('error', data.message);
		                }
		            });
		        }
		    };
		});

		function updateCart(id, type) {
		    fetch('cart_update.php', {
		        method: 'POST',
		        headers: {'Content-Type': 'application/json'},
		        body: JSON.stringify({id, type})
		    }).then(() => renderCart());
		}

		function updateCartBadge() {
		  fetch('cart_count.php')
		    .then(res => res.json())
		    .then(data => {
		      document.querySelectorAll('.icon-header-noti').forEach(el => {
		        el.setAttribute('data-notify', data.count);
		      });
		    });
		}

		// load awal
		renderCart();
	</script>

	<!-- <script>
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
    </script> -->

    <script>
    	document.addEventListener('click', function (e) {
		  const btn = e.target.closest('.icon-header-item');
		  if (!btn) return;

		  if (btn.id === 'signout') {
		    e.preventDefault();

		    // optional confirm
		    if (!confirm('Yakin ingin logout?')) return;

		    window.location.href = '/logout.php';
		  }
		});

    </script>

   <!--  <script>
	  const counters = document.querySelectorAll('.impact-card h4');

	  const runCounter = (counter) => {
	    const target = +counter.getAttribute('data-target');
	    const duration = 1800; // ms
	    const startTime = performance.now();

	    const update = (currentTime) => {
	      const elapsed = currentTime - startTime;
	      const progress = Math.min(elapsed / duration, 1);
	      const value = Math.floor(progress * target);

	      counter.textContent = value + '+';

	      if (progress < 1) {
	        requestAnimationFrame(update);
	      }
	    };

	    requestAnimationFrame(update);
	  };

	  const observer = new IntersectionObserver(
	    entries => {
	      entries.forEach(entry => {
	        if (entry.isIntersecting) {
	          runCounter(entry.target);
	          observer.unobserve(entry.target);
	        }
	      });
	    },
	    { threshold: 0.5 }
	  );

	  counters.forEach(counter => observer.observe(counter));
	</script> -->

	<script>
		document.addEventListener("scroll", () => {
		  document.querySelectorAll(".sport-item").forEach((el, i) => {
		    el.style.transform =
		      `translateY(${window.scrollY * (0.05 + i * 0.01)}px)`;
		  });
		});
	</script>

	<script>
		const profileForm = document.getElementById('profileForm');

		if (profileForm) {
		  profileForm.addEventListener('submit', function(e) {
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

		        setTimeout(() => {
		          window.location.href = 'profile.php';
		        }, 1500);
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
		}
	</script>

	<script>
		function openDetail(el) {
		  const id = el.getAttribute('data-id');
		  const judul = el.getAttribute('data-judul');

		  // SET DATA UNTUK DOWNLOAD
		  document.getElementById('proposalSponsorId').value = id;
		  document.getElementById('proposalSponsorNama').value = judul;

		  document.getElementById('detailModal').style.display = 'flex';
		  document.getElementById('modalContent').innerHTML = 'Memuat data...';

		  fetch('get_sponsor_detail.php?id=' + id)
		    .then(res => res.text())
		    .then(data => {
		      document.getElementById('modalContent').innerHTML = data;
		    });
		}
	</script>

	<script>
		function downloadProposal() {
		  const id = document.getElementById('proposalSponsorId').value;
		  const nama = document.getElementById('proposalSponsorNama').value;

		  if (!id) {
		    alert('Data program tidak ditemukan');
		    return;
		  }

		  window.location.href =
		    'download_proposal.php?id=' + id +
		    '&nama=' + encodeURIComponent(nama);
		}
	</script>

	<script>
		function openContactModal(el) {
		  document.getElementById('contactModal').style.display = 'flex';

		  document.getElementById('cm-id').value = el.dataset.id;
		  document.getElementById('cm-minat').value =
		    el.dataset.jenis + ' — ' + el.dataset.judul;
		}

		function closeContact() {
		  document.getElementById('contactModal').style.display = 'none';
		}

		function submitContact() {
		  const form = document.getElementById('contactForm');
		  const data = new FormData(form);

		  fetch('save_kontak.php', {
		    method: 'POST',
		    body: data
		  })
		  .then(res => res.json())
		  .then(res => {
		    if (res.status === 'ok') {
		      alert('Terima kasih! Tim kami akan menghubungi Anda.');
		      closeContact();
		      form.reset();
		    } else {
		      alert('Gagal menyimpan data.');
		    }
		  });
		}
	</script>

	<script>
		function sponsorEventSport(el) {
		  document.getElementById('sponsorEvent').style.display = 'flex';

		  document.getElementById('ev-ide').value = el.dataset.ide;
		  document.getElementById('ev-minat').value =
		    el.dataset.event + ' — ' + el.dataset.nmeo;
		}

		function closeEvent() {
		  document.getElementById('sponsorEvent').style.display = 'none';
		}

		function submitEvent() {
		  const form = document.getElementById('contactFormEvent');
		  const data = new FormData(form);

		  fetch('save_kontak_event.php', {
		    method: 'POST',
		    body: data
		  })
		  .then(res => res.json())
		  .then(res => {
		    if (res.status === 'ok') {
		      alert('Terima kasih! Tim kami akan menghubungi Anda.');
		      closeEvent();
		      form.reset();
		    } else {
		      alert('Gagal menyimpan data.');
		    }
		  });
		}
	</script>

	<script>
		function daftarPeserta(el) {
		  document.getElementById('pesertaEvent').style.display = 'flex';

		  document.getElementById('ps-id').value = el.dataset.idp;
		  document.getElementById('ps-event').value =
		    el.dataset.ev + ' — ' + el.dataset.eo;
		}

		function closePesertaEvent() {
		  document.getElementById('pesertaEvent').style.display = 'none';
		}

		function submitPesertaEvent() {
		  const form = document.getElementById('pesertaFormEvent');
		  const data = new FormData(form);

		  fetch('save_peserta_event.php', {
		    method: 'POST',
		    body: data
		  })
		  .then(res => res.json())
		  .then(res => {
		    if (res.status === 'ok') {
		      alert('Terima kasih! Tim kami akan menghubungi Anda.');
		      closePesertaEvent();
		      form.reset();
		    } else {
		      alert('Gagal menyimpan data.');
		    }
		  });
		}
	</script>

</body>
</html>