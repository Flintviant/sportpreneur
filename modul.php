<?php
include 'head.php';
include 'koneksi.php';

$modul = $conn->query("
    SELECT id_modul, nama_modul, deskripsi, gambar 
    FROM modul 
    WHERE status = 'aktif'
    ORDER BY id_modul ASC
");
?>

<section class="modul-wrapper" style="background-color: #0F1426;">
  <div class="container">

    <h2 class="ltext-103 redefine-title text-center mb-5 text-white">
      Modul Pembelajaran
    </h2>

    <div class="row">

      <?php if ($modul->num_rows > 0): ?>
        <?php while ($m = $modul->fetch_assoc()): ?>
          
          <div class="col-md-4 mb-4">
            <a href="sub_modul.php?id_modul=<?= $m['id_modul'] ?>" 
               class="modul-card"
               style="background-image:url('images/modul/<?= htmlspecialchars($m['gambar']) ?>')">

              <div class="modul-overlay"></div>

              <div class="modul-content">
                <h4><?= htmlspecialchars($m['nama_modul']) ?></h4>
                <p><?= htmlspecialchars($m['deskripsi']) ?></p>
                <span class="btn-modul">Mulai</span>
              </div>

            </a>
          </div>

        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-white">
          <p>Belum ada program inkubator tersedia.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php include 'footer.php'; ?>