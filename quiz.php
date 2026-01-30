<?php
require_once __DIR__ . '/session_modal.php';
require_once __DIR__ . '/koneksi.php';

// proteksi login
if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id_modul'])) {
    echo "Modul tidak ditemukan.";
    exit;
}

$id_member = $_SESSION['id_member'];
$id_modul  = (int) $_GET['id_modul'];

// cek lulus
$cek = $conn->prepare("
    SELECT 1 FROM hasil_modul
    WHERE id_member = ? AND id_modul = ?
    AND status = 'LULUS'
    LIMIT 1
");
$cek->bind_param("ii", $id_member, $id_modul);
$cek->execute();

if ($cek->get_result()->num_rows > 0) {
    $_SESSION['notif'] = 'Anda sudah lulus modul ini';
    header("Location: modul.php");
    exit;
}

// ambil soal
$stmt = $conn->prepare("
    SELECT * FROM soal
    WHERE id_modul = ?
    ORDER BY RAND()
    LIMIT 5
");
$stmt->bind_param("i", $id_modul);
$stmt->execute();
$soal = $stmt->get_result();

if ($soal->num_rows < 5) {
    echo "Soal untuk modul ini belum lengkap.";
    exit;
}

include 'head.php';
?>


<style>
.card {
    border-radius: 16px;
}
.form-check-input,
.form-check-label {
    cursor: pointer;
}
.btn-lg {
    border-radius: 40px;
}
</style>

<section class="modul-wrapper">
  <div class="container">

    <h2 class="text-center mb-3">Quiz Modul</h2>
    <p class="text-center text-muted mb-5">
      Jawab minimal <b>4 dari 5 soal</b> untuk lulus
    </p>

    <form action="proses_quiz.php" method="post">

      <input type="hidden" name="id_modul" value="<?= $id_modul ?>">

      <?php 
      $no = 1;
      while ($s = $soal->fetch_assoc()) { 
      ?>
        <div class="card mb-4 shadow-sm">
          <div class="card-body">

            <h6 class="mb-3">
              <?= $no++ ?>. <?= htmlspecialchars($s['pertanyaan']) ?>
            </h6>

            <?php foreach (['a','b','c','d'] as $opsi): 
              $field = 'opsi_' . strtolower($opsi);
            ?>
              <div class="form-check mb-2">
                <input
                  class="form-check-input"
                  type="radio"
                  name="jawaban[<?= $s['id_soal'] ?>]"
                  value="<?= $opsi ?>"
                  required
                >
                <label class="form-check-label">
                  <?= htmlspecialchars($s[$field]) ?>
                </label>
              </div>
            <?php endforeach; ?>

          </div>
        </div>
      <?php } ?>

      <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary btn-lg px-5">
          Kirim Jawaban
        </button>
      </div>

    </form>

  </div>
</section>

<?php include 'footer.php'; ?>
