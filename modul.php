<?php
include 'head.php';
?>

<?php if (!empty($_SESSION['notif'])): ?>
<div id="notif-top" class="notif-top alert alert-success text-center">
    <?= $_SESSION['notif'] ?>
</div>
<?php unset($_SESSION['notif']); endif; ?>

<section class="modul-wrapper" style="background-color: #0F1426;">
  <div class="container">

    <h2 class="ltext-103 redefine-title text-center mb-5 text-white">Modul Pembelajaran</h2>

    <div class="row">

      <!-- Modul Bisnis -->
      <div class="col-md-4 mb-4">
        <a href="materi.php?id_modul=1" class="modul-card" 
           style="background-image:url('images/modul/bisnis.jpg')">
          <div class="modul-overlay"></div>
          <div class="modul-content">
            <h4>Bisnis</h4>
            <p>Materi dasar bisnis & manajemen</p>
            <span class="btn-modul">Mulai</span>
          </div>
        </a>
      </div>

      <!-- Modul Olahraga -->
      <div class="col-md-4 mb-4">
        <a href="materi.php?id_modul=2" class="modul-card" 
           style="background-image:url('images/modul/olahraga.jpg')">
          <div class="modul-overlay"></div>
          <div class="modul-content">
            <h4>Olahraga</h4>
            <p>Manajemen olahraga & prestasi</p>
            <span class="btn-modul">Mulai</span>
          </div>
        </a>
      </div>

      <!-- Modul Sportpreneur -->
      <div class="col-md-4 mb-4">
        <a href="materi.php?id_modul=3" class="modul-card" 
           style="background-image:url('images/modul/sportpreneur.jpg')">
          <div class="modul-overlay"></div>
          <div class="modul-content">
            <h4>Sportpreneur</h4>
            <p>Bisnis berbasis industri olahraga</p>
            <span class="btn-modul">Mulai</span>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>

<?php include 'footer.php'; ?>