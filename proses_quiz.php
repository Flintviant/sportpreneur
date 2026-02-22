<?php
session_start();
include 'koneksi.php';
require_once __DIR__ . '/head.php';

// Proteksi login
if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}

// Validasi input
if (!isset($_POST['id_sub_modul'], $_POST['jawaban'])) {
    echo "Data tidak lengkap.";
    exit;
}

$id_member = $_SESSION['id_member'];
$id_sub_modul  = (int) $_POST['id_sub_modul'];
$jawaban   = $_POST['jawaban'];

$benar = 0;
$total = count($jawaban);

// Hitung jawaban benar
foreach ($jawaban as $id_soal => $jawab_user) {

    $id_soal = (int) $id_soal;

    $q = $conn->prepare("
        SELECT jawaban_benar 
        FROM soal_sub_modul 
        WHERE id_soal = ? 
        AND id_sub_modul = ?
        LIMIT 1
    ");
    $q->bind_param("ii", $id_soal, $id_sub_modul);
    $q->execute();
    $res = $q->get_result();

    if ($row = $res->fetch_assoc()) {
        if ($row['jawaban_benar'] === $jawab_user) {
            $benar++;
        }
    }
}

// Penentuan status
$skor = $total > 0 ? round(($benar / $total) * 100) : 0;
$status = ($skor >= 70) ? 'LULUS' : 'TIDAK LULUS';
$badge  = ($status === 'LULUS') ? 'sertifikat.jpeg' : null;

// Cegah dobel submit (1 modul 1 hasil)
$cek = $conn->prepare("
    SELECT status 
    FROM hasil_sub_modul 
    WHERE id_member = ? AND id_sub_modul = ?
    ORDER BY id DESC
    LIMIT 1
");
$cek->bind_param("ii", $id_member, $id_sub_modul);
$cek->execute();
$cekRes = $cek->get_result();

$pernahLulus = false;

if ($row = $cekRes->fetch_assoc()) {
    if ($row['status'] === 'LULUS') {
        $pernahLulus = true;
    }
}

$simpan = $conn->prepare("
    INSERT INTO hasil_sub_modul
    (id_member, id_sub_modul, skor, status, badge)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        skor = VALUES(skor),
        status = VALUES(status)
");
$simpan->bind_param(
    "iiiss",
    $id_member,
    $id_sub_modul,
    $skor,
    $status,
    $badge
);
$simpan->execute();

// ============================
// INSERT SERTIFIKAT JIKA LULUS
// ============================

if ($status === 'LULUS' && !$pernahLulus) {

    // generate kode unik
    $kode_sertifikat = 'INK-' . strtoupper(uniqid());

    // cek apakah sudah ada
    $cekSertifikat = $conn->prepare("
        SELECT id_sertifikat 
        FROM sertifikat 
        WHERE id_member = ? 
        AND id_sub_modul = ?
    ");
    $cekSertifikat->bind_param("ii", $id_member, $id_sub_modul);
    $cekSertifikat->execute();
    $resSertifikat = $cekSertifikat->get_result();

    if ($resSertifikat->num_rows == 0) {

        $insertSertifikat = $conn->prepare("
            INSERT INTO sertifikat
            (id_member, id_sub_modul, kode_sertifikat)
            VALUES (?, ?, ?)
        ");
        $insertSertifikat->bind_param(
            "iis",
            $id_member,
            $id_sub_modul,
            $kode_sertifikat
        );
        $insertSertifikat->execute();
    }
}

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
      <p class="mt-3">Selamat! Anda mendapatkan sertifikat. Klik Sertifikat Untuk Preview dan Download</p>
      <a href="sertifikat.php?id_sub_modul=<?=$id_sub_modul?>" target="_blank"><img src="images/<?= $badge ?>" width="120" class="mt-3"></a>
    <?php else: ?>
      <p class="mt-3 text-muted">
        Nilai minimal kelulusan adalah <b>4</b>
      </p>
    <?php endif; ?>

    <div class="mt-5">
      <a href="<?= $url_utama ?>sub_modul.php?id_modul=2" class="btn btn-secondary px-4">
        Kembali ke Modul
      </a>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>