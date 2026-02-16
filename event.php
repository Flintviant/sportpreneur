<?php
	include 'head.php';

	// Ambil data cabang olahraga
	$queryOlahraga = "SELECT * FROM cabang_olahraga ORDER BY nama_olahraga ASC";
	$resultOlahraga = $conn->query($queryOlahraga);

	// Ambil parameter filter
	$keyword = $_GET['keyword'] ?? '';
	$jenis   = $_GET['jenis'] ?? '';
	$tipe    = $_GET['tipe'] ?? '';
	$status  = $_GET['status'] ?? '';

	$sql = "SELECT * FROM list_event WHERE 1=1";

	$params = [];
	$types  = "";

	/* ===== Keyword ===== */
	if ($keyword != '') {
	    $sql .= " AND (nama_event LIKE ? OR nama_eo LIKE ? OR lokasi_kegiatan LIKE ?)";
	    $paramKeyword = "%$keyword%";

	    $params[] = $paramKeyword;
	    $params[] = $paramKeyword;
	    $params[] = $paramKeyword;

	    $types .= "sss";
	}

	/* ===== Cabang Olahraga ===== */
	if ($jenis != '') {
	    $sql .= " AND id_olahraga = ?";
	    $params[] = $jenis;
	    $types .= "i"; // kalau id_olahraga INT
	}

	/* ===== Tipe Event ===== */
	if ($tipe != '') {
	    $sql .= " AND tipe_event = ?";
	    $params[] = $tipe;
	    $types .= "s";
	}

	/* ===== Kota ===== */
	if ($kota != '') {
	    $sql .= " AND lokasi_kegiatan LIKE ?";
	    $params[] = "%$kota%";
	    $types .= "s";
	}

	/* ===== Status ===== */
	if ($status != '') {
	    $sql .= " AND status = ?";
	    $params[] = $status;
	    $types .= "s";
	}

	/* ===== Execute ===== */
	$stmt = $conn->prepare($sql);

	if (!empty($params)) {
	    $stmt->bind_param($types, ...$params);
	}

	$stmt->execute();
	$result = $stmt->get_result();

?>

	<div class="sponsor container mt-4" style="background-color: #0F1426;padding-bottom: 100px;">

	    <!-- ===== FILTER ===== -->
	    <form method="GET" class="filter-box">
	        <div class="row g-3">

	            <!-- Cari -->
	            <div class="col-md-4">
	                <label>Cari (nama/EO/kota)</label>
	                <input type="text" 
	                       name="keyword" 
	                       class="form-control"
	                       placeholder="Contoh: marathon, futsal, Bandung"
	                       value="<?= htmlspecialchars($keyword) ?>">
	            </div>

	            <!-- Cabang Olahraga -->
	            <div class="col-md-2">
	                <label>Cabang olahraga</label>
	                <select name="jenis" class="form-select">
	                    <option value="">Semua</option>
	                    <?php if ($resultOlahraga && $resultOlahraga->num_rows > 0): ?>
	                        <?php while ($row = $resultOlahraga->fetch_assoc()): ?>
	                            <option value="<?= $row['id_olahraga'] ?>" 
	                                <?= ($jenis == $row['id_olahraga']) ? 'selected' : '' ?>>
	                                <?= htmlspecialchars($row['nama_olahraga']) ?>
	                            </option>
	                        <?php endwhile; ?>
	                    <?php endif; ?>
	                </select>
	            </div>

	            <!-- Tipe Event -->
	            <div class="col-md-2">
	                <label>Tipe event</label>
	                <select name="tipe" class="form-select">
	                    <option value="">Semua</option>
	                    <option value="Kompetisi" <?= $tipe=='Kompetisi'?'selected':'' ?>>Kompetisi</option>
	                    <option value="Pelatihan" <?= $tipe=='Pelatihan'?'selected':'' ?>>Pelatihan</option>
	                    <option value="Komunitas" <?= $tipe=='Komunitas'?'selected':'' ?>>Komunitas</option>
	                    <option value="Seleksi" <?= $tipe=='Seleksi'?'selected':'' ?>>Seleksi</option>
	                </select>
	            </div>

	            <!-- Status -->
	            <div class="col-md-2">
	                <label>Status</label>
	                <select name="status" class="form-select">
	                    <option value="">Semua</option>
	                    <option value="Open" <?= $status=='Open'?'selected':'' ?>>Open</option>
	                    <option value="Closed" <?= $status=='Closed'?'selected':'' ?>>Closed</option>
	                    <option value="Coming Soon" <?= $status=='Coming Soon'?'selected':'' ?>>Coming Soon</option>
	                </select>
	            </div>

	            <!-- Button -->
	            <div class="col-md-2 d-flex align-items-end gap-2">
	                <button type="submit" class="btn btn-primary w-100">
	                    Terapkan
	                </button>
	                <a href="?" class="btn btn-outline w-100 text-dark ml-2">
	                    Reset
	                </a>
	            </div>

	        </div>
	    </form>

	    <div class="sponsor-grid">
	        <?php if ($result->num_rows > 0): ?>
	            <?php while ($row = $result->fetch_assoc()): ?>
	                <div class="sponsor-card">
	                    <div>
	                        <span class="badge"><?= $row['tipe_event'] ?></span>
	                        <h4><?= htmlspecialchars($row['nama_event']) ?></h4>
	                        <div class="sponsor-meta">
	                            <?= $row['lokasi_kegiatan'] ?> • <?= $row['id_olahraga'] ?>
	                        </div>

	                        <span class="badge"><?= $row['nama_eo'] ?></span>

	                    </div>

	                    <div class="card-actions">
	                        <button
							  class="btn-outline"
							  data-idp="<?= $row['id_event'] ?>"
							  data-eo="<?= htmlspecialchars($row['nama_eo']) ?>"
							  data-ev="<?= $row['nama_event'] ?>"
							  onclick="daftarPeserta(this)">
							  Daftar Peserta
							</button>
	                        <button
	                        	class="btn-primary"
	                        	data-ide="<?= $row['id_event'] ?>" 
	                        	onclick="sponsorEventSport(this)"
	                        	data-nmeo="<?= htmlspecialchars($row['nama_eo']) ?>"
	                        	data-event="<?= $row['nama_event'] ?>"
	                        	>
	                        	Ambil Slot Sponsor
	                    	</button>
	                    </div>
	                </div>
	            <?php endwhile; ?>
	        <?php else: ?>
	            <p>Tidak ada program sponsor ditemukan.</p>
	        <?php endif; ?>
	    </div>

	</div>

	<div id="sponsorEvent" class="modal-overlay">
	  <div class="modal-box modal-lg">

	    <div class="modal-header">
	      <h5>Hubungi Kami</h5>
	      <button class="modal-close" onclick="closeEvent()">Tutup</button>
	    </div>

	    <form id="contactFormEvent" class="modal-body">

	      <input type="hidden" name="id_sponsor_event" id="ev-ide">

	      <p class="text-muted">
	        Isi data di bawah. Sistem akan menyimpan ke database:
	        <b>nama, lembaga, minat program, tujuan kerjasama, no telpon/WA, email</b>.
	      </p>

	      <div class="form-grid">
	        <div>
	          <label>Nama</label>
	          <input type="text" name="nama_pic" placeholder="Nama PIC" required>
	        </div>

	        <div>
	          <label>Lembaga</label>
	          <input type="text" name="lembaga_sponsor" placeholder="Nama perusahaan/brand/CSR/investor" required>
	        </div>
	      </div>

	      <label>Minat program</label>
	      <input type="text" name="minat_sponsor" id="ev-minat" readonly>

	      <label>Tujuan kerjasama</label>
	      <textarea name="tujuan_sponsor" rows="4"
	        placeholder="Contoh: ingin sponsor aktivasi brand, CSR pengadaan, investasi fasilitas, partnership event..."
	        required></textarea>

	      <div class="form-grid">
	        <div>
	          <label>No Telp / WhatsApp</label>
	          <input type="text" name="telp_pic" placeholder="08xxxxxxxxxx" required>
	        </div>

	        <div>
	          <label>Email</label>
	          <input type="email" name="email_pic" placeholder="nama@lembaga.com" required>
	        </div>
	      </div>

	    </form>

	    <div class="modal-footer">
	      <button class="btn-outline" onclick="closeEvent()">Kembali</button>
	      <button class="btn-primary" onclick="submitEvent()">Kirim</button>
	    </div>

	  </div>
	</div>

	<div id="pesertaEvent" class="modal-overlay">
	  <div class="modal-box modal-lg">

	    <div class="modal-header">
	      <h5>Daftar Sekarang di Event</h5>
	      <button class="modal-close" onclick="closePesertaEvent()">Tutup</button>
	    </div>

	    <form id="pesertaFormEvent" class="modal-body">

	      	<input type="hidden" name="id_peserta_event" id="ps-id">

	      	<p class="text-muted">
	        	Isi data di bawah. Sistem akan menyimpan ke database:
	        	<b>nama, alamat, no telpon/WA, email</b>.
	      	</p>

	      	<div class="form-grid">
	        	<div>
		          	<label>Nama</label>
		          	<input type="text" name="nama_peserta" placeholder="Nama Lengkap" required>
	        	</div>
	      	</div>
	      	
	      	<label>Event</label>
	      	<input type="text" name="peserta_event" id="ps-event" readonly>

	      	<label>Alamat</label>
	      	<textarea
	      		name="alamat_peserta" rows="2" placeholder="Contoh: Jln. Event Bersama nomor 2" required
	        ></textarea>

	      	<div class="form-grid">
		        <div>
		          <label>No Telp / WhatsApp</label>
		          <input type="text" name="telp_peserta" placeholder="08xxxxxxxxxx" required>
		        </div>

		        <div>
		          <label>Email</label>
		          <input type="email" name="email_peserta" placeholder="nama@lembaga.com" required>
		        </div>
	      	</div>

	    </form>

	    <div class="modal-footer">
	      <button class="btn-outline" onclick="closePesertaEvent()">Kembali</button>
	      <button class="btn-primary" onclick="submitPesertaEvent()">Kirim</button>
	    </div>

	  </div>
	</div>

<?php include 'footer.php' ?>