<?php
include 'koneksi.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM sponsor WHERE id_sponsor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()):
?>

<h4><?= htmlspecialchars($row['nama_kegiatan']) ?></h4>

<div class="modal-badges">
  <span class="badge"><?= $row['jenis_kegiatan'] ?></span>
  <span class="badge">📍 <?= $row['kota_kegiatan'] ?></span>
  <span class="badge"><?= $row['kategori'] ?></span>
  <span class="badge badge-open">Status: <?= $row['status'] ?></span>
</div>

<p><strong>Target audience:</strong><br>
  <?= nl2br($row['target']) ?>
</p>

<p><strong>Kebutuhan:</strong> <?= $row['kebutuhan'] ?></p>

<p><strong>Nilai kebutuhan dana:</strong><br>
  Rp <?= number_format($row['dana'],0,',','.') ?>
</p>

<p><strong>Timeline:</strong> <?= $row['timeline'] ?: '-' ?></p>

<div class="modal-section">
  <h6>Proposal ringkas:</h6>
  <p><?= nl2br($row['proposal'] ?? '-') ?></p>
</div>

<?php else: ?>
<p>Data tidak ditemukan.</p>
<?php endif; ?>
