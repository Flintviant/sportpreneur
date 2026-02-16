<?php
	include 'head.php';

	// ================= FILTER =================
	$keyword   = $_GET['keyword'] ?? '';
	$jenis     = $_GET['jenis'] ?? '';
	$kota      = $_GET['kota'] ?? '';
	$kebutuhan = $_GET['kebutuhan'] ?? '';

	$sql = "SELECT * FROM sponsor WHERE 1=1";

	if ($keyword != '') {
	    $sql .= " AND nama_kegiatan LIKE '%$keyword%'";
	}
	if ($jenis != '') {
	    $sql .= " AND jenis_kegiatan = '$jenis'";
	}
	if ($kota != '') {
	    $sql .= " AND kota_kegiatan LIKE '%$kota%'";
	}
	if ($kebutuhan != '') {
	    $sql .= " AND kebutuhan LIKE '%$kebutuhan%'";
	}

	$result = $conn->query($sql);
?>

	<div class="sponsor container mt-4 mb-5">

	    <!-- ===== FILTER ===== -->
	    <form method="GET" class="filter-box">
	        <div class="row g-3">
	            <div class="col-md-3">
	                <label>Cari judul / kata kunci</label>
	                <input type="text" name="keyword" placeholder="Contoh: beasiswa, event" value="<?= htmlspecialchars($keyword) ?>">
	            </div>
	            <div class="col-md-2">
	                <label>Tipe Program</label>
	                <select name="jenis">
	                    <option value="">Semua</option>
	                    <option value="Pelatihan" <?= $jenis=='Pelatihan'?'selected':'' ?>>Pelatihan</option>
	                    <option value="Pengadaan" <?= $jenis=='Pengadaan'?'selected':'' ?>>Pengadaan</option>
	                    <option value="Beasiswa" <?= $jenis=='Beasiswa'?'selected':'' ?>>Beasiswa</option>
	                </select>
	            </div>
	            <div class="col-md-3">
	                <label>Kota</label>
	                <input type="text" name="kota" placeholder="Contoh: Jayapura" value="<?= htmlspecialchars($kota) ?>">
	            </div>
	            <div class="col-md-2">
	                <label>Kebutuhan</label>
	                <select name="kebutuhan">
	                    <option value="">Semua</option>
	                    <option value="CSR/Hibah">CSR / Hibah</option>
	                    <option value="Sponsor">Sponsor</option>
	                    <option value="In-kind">In-kind</option>
	                </select>
	            </div>
	           <div class="col-md-2 d-flex align-items-end filter-action gap-2">
	                <button type="submit" class="btn btn-primary w-100">
	                    Terapkan
	                </button>
	                <a href="?" class="btn btn-outline w-100 text-dark ml-2">
	                    Reset
	                </a>
	            </div>
	        </div>
	    </form>

	    <!-- ===== LIST ===== -->
	    <div class="sponsor-grid">
	        <?php if ($result->num_rows > 0): ?>
	            <?php while ($row = $result->fetch_assoc()): ?>
	                <div class="sponsor-card">
	                    <div>
	                        <span class="badge"><?= $row['jenis_kegiatan'] ?></span>
	                        <h4><?= htmlspecialchars($row['nama_kegiatan']) ?></h4>
	                        <div class="sponsor-meta">
	                            <?= $row['kota_kegiatan'] ?> • <?= $row['kategori'] ?>
	                        </div>

	                        <span class="badge"><?= $row['kebutuhan'] ?></span>

	                        <p><strong>Target Dampak:</strong><br><?= nl2br($row['target']) ?></p>
	                    </div>

	                    <div class="card-actions">
	                        <button
							  class="btn-outline"
							  data-id="<?= $row['id_sponsor'] ?>"
							  data-judul="<?= htmlspecialchars($row['nama_kegiatan']) ?>"
							  onclick="openDetail(this)">
							  Lihat Detail
							</button>
	                        <button
	                        	class="btn-primary" 
	                        	data-id="<?= $row['id_sponsor'] ?>" 
	                        	onclick="openContactModal(this)"
	                        	data-judul="<?= htmlspecialchars($row['nama_kegiatan']) ?>"
	                        	data-jenis="<?= $row['jenis_kegiatan'] ?>"
	                        	>
	                        	Hubungi Kami
	                    	</button>
	                    </div>
	                </div>
	            <?php endwhile; ?>
	        <?php else: ?>
	            <p>Tidak ada program sponsor ditemukan.</p>
	        <?php endif; ?>
	    </div>

	    <div class="mt-3 text-muted">
	        <?= $result->num_rows ?> program ditemukan.
	    </div>
	</div>

	<div id="detailModal" class="modal-overlay">
	  <div class="modal-box">

	    <div class="modal-header">
	      <h5>Detail Program</h5>
	      <button class="modal-close" onclick="closeDetail()">Tutup</button>
	    </div>

	    <div class="modal-body" id="modalContent">
	      <p>Memuat data...</p>
	    </div>

		<div class="modal-footer">
		  <button
		    type="button"
		    class="btn-primary"
		    onclick="downloadProposal()">
		    Download Proposal Template
		  </button>

		  <input type="hidden" id="proposalSponsorId">
		  <input type="hidden" id="proposalSponsorNama">
		</div>

	  </div>
	</div>

	<div id="contactModal" class="modal-overlay">
	  <div class="modal-box modal-lg">

	    <div class="modal-header">
	      <h5>Hubungi Kami</h5>
	      <button class="modal-close" onclick="closeContact()">Tutup</button>
	    </div>

	    <form id="contactForm" class="modal-body">

	      <input type="hidden" name="id_sponsor" id="cm-id">

	      <p class="text-muted">
	        Isi data di bawah. Sistem akan menyimpan ke database:
	        <b>nama, lembaga, minat program, tujuan kerjasama, no telpon/WA, email</b>.
	      </p>

	      <div class="form-grid">
	        <div>
	          <label>Nama</label>
	          <input type="text" name="nama" placeholder="Nama PIC" required>
	        </div>

	        <div>
	          <label>Lembaga</label>
	          <input type="text" name="lembaga" placeholder="Nama perusahaan/brand/CSR/investor" required>
	        </div>
	      </div>

	      <label>Minat program</label>
	      <input type="text" name="minat" id="cm-minat" readonly>

	      <label>Tujuan kerjasama</label>
	      <textarea name="tujuan" rows="4"
	        placeholder="Contoh: ingin sponsor aktivasi brand, CSR pengadaan, investasi fasilitas, partnership event..."
	        required></textarea>

	      <div class="form-grid">
	        <div>
	          <label>No Telp / WhatsApp</label>
	          <input type="text" name="telp" placeholder="08xxxxxxxxxx" required>
	        </div>

	        <div>
	          <label>Email</label>
	          <input type="email" name="email" placeholder="nama@lembaga.com" required>
	        </div>
	      </div>

	    </form>

	    <div class="modal-footer">
	      <button class="btn-outline" onclick="closeContact()">Kembali</button>
	      <button class="btn-primary" onclick="submitContact()">Kirim</button>
	    </div>

	  </div>
	</div>

<?php include 'footer.php' ?>