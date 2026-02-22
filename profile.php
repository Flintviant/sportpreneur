<?php
	include 'head.php';
?>

	<section class="profile-section" style="background-color: #0F1426;">
	  	<div class="container">
		    <div class="profile-card">

		      <?php if (isset($_SESSION['id_member'])): ?>

		      <form id="profileForm" class="profile-form">

		        <input type="hidden" name="id_member" value="<?= $_SESSION['id_member'] ?>">

		        <div class="form-group">
		          <label>Nama</label>
		          <input type="text" name="nm_member" value="<?= htmlspecialchars($nama) ?>" required>
		        </div>

		        <div class="form-group">
		          <label>Role</label>
		          <input type="text" name="role" value="<?= htmlspecialchars($role_name) ?>" readonly>
		        </div>

		        <div class="form-group">
		          <label>Kota</label>
		          <input type="text" name="kota" value="<?= htmlspecialchars($kota) ?>" required>
		        </div>

		        <?php if ($role == '4') {?>
			        <div class="form-group">
			          <label>Cabang Olahraga Utama</label>
			          <input type="text" name="olahraga" value="<?= htmlspecialchars($olahraga) ?>" required>
			        </div>

			        <div class="form-group">
			          <label>Apa yang anda tawarkan?</label>
			          <textarea name="tawarkan" rows="3" required><?= htmlspecialchars($tawarkan) ?></textarea>
			        </div>

			        <div class="form-group">
			          <label>Apa yang anda butuhkan untuk naik level?</label>
			          <textarea name="butuh" rows="3" required><?= htmlspecialchars($butuh) ?></textarea>
			        </div>
			    <?php }elseif ($role == '3') { ?>
			    	<div class="form-group">
			          <label>Target Dampak yang Diinginkan</label>
			          <textarea name="dampak" rows="3" required><?= htmlspecialchars($dampak) ?></textarea>
			        </div>
			    <?php } ?>

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

		      <div class="text-center">
		        <h4>Login Diperlukan</h4>
		        <p>Silakan login untuk mengakses profil.</p>
		        <a href="login.php" class="btn-update">Login</a>
		      </div>

		      <?php endif; ?>

		    </div>
	  	</div>
	</section>


<!-- Footer -->
<?php include 'footer.php';?>