<?php
require_once __DIR__ . '/head.php';

// Proteksi login
if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}

// Validasi input
if (!isset($_POST['id_modul'], $_POST['jawaban'])) {
    echo "Data tidak lengkap.";
    exit;
}

$id_member = $_SESSION['id_member'];
$id_modul  = (int) $_POST['id_modul'];
$jawaban   = $_POST['jawaban'];

$benar = 0;
$total = 5;

// Hitung jawaban benar
foreach ($jawaban as $id_soal => $jawab_user) {

    $id_soal = (int) $id_soal;

    $q = $conn->prepare("
        SELECT jawaban 
        FROM soal 
        WHERE id_soal = ? 
        AND id_modul = ?
        LIMIT 1
    ");
    $q->bind_param("ii", $id_soal, $id_modul);
    $q->execute();
    $res = $q->get_result();

    if ($row = $res->fetch_assoc()) {
        if ($row['jawaban'] === $jawab_user) {
            $benar++;
        }
    }
}

// Penentuan status
$status = ($benar >= 4) ? 'LULUS' : 'TIDAK LULUS';
$badge  = ($status === 'LULUS') ? 'badge_modul_'.$id_modul.'.png' : null;

// Cegah dobel submit (1 modul 1 hasil)
$cek = $conn->prepare("
    SELECT status 
    FROM hasil_modul 
    WHERE id_member = ? AND id_modul = ?
    ORDER BY id_hasil DESC
    LIMIT 1
");
$cek->bind_param("ii", $id_member, $id_modul);
$cek->execute();
$cekRes = $cek->get_result();

$pernahLulus = false;

if ($row = $cekRes->fetch_assoc()) {
    if ($row['status'] === 'LULUS') {
        $pernahLulus = true;
    }
}

$simpan = $conn->prepare("
    INSERT INTO hasil_modul
    (id_member, id_modul, nilai, status, badge)
    VALUES (?, ?, ?, ?, ?)
");
$simpan->bind_param(
    "iiiss",
    $id_member,
    $id_modul,
    $benar,
    $status,
    $badge
);
$simpan->execute();

?>

<section class="modul-wrapper">
  <div class="container text-center">

    <h2 class="mb-4">Hasil Quiz</h2>

    <h1 class="<?= ($status=='LULUS') ? 'text-success' : 'text-danger' ?>">
      <h1><?= $benar ?>/<?= $total ?></h1>
    </h1>

    <h4>Status:
      <span class="<?= ($status=='LULUS') ? 'text-success' : 'text-danger' ?>">
        <?= $status ?>
      </span>
    </h4>

    <?php if ($status == 'LULUS'): ?>
      <p class="mt-3">🎉 Selamat! Anda mendapatkan badge.</p>
      <img src="badge/<?= $badge ?>" width="120" class="mt-3">
    <?php else: ?>
      <p class="mt-3 text-muted">
        Nilai minimal kelulusan adalah <b>4</b>
      </p>
    <?php endif; ?>

    <div class="mt-5">
      <a href="modul.php" class="btn btn-secondary px-4">
        Kembali ke Modul
      </a>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>