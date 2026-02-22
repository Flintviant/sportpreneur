<?php
include 'head.php';
include 'koneksi.php';

$id_member = $_SESSION['id_member'] ?? 0;
$id_modul  = (int) $_GET['id_modul'];

// ambil semua sub modul sesuai modul
$sub = $conn->query("
    SELECT * FROM sub_modul
    WHERE id_modul = $id_modul
    AND status = 'aktif'
    ORDER BY urutan ASC
");
?>

<section class="modul-wrapper">
<div class="container">

<h2 class="text-center mb-5">Daftar Sub Modul</h2>

<div class="row">

<?php
$index = 0;
while ($s = $sub->fetch_assoc()):

    $index++;

    // cek apakah sudah lulus
    $cek = $conn->query("
        SELECT 1 FROM hasil_sub_modul
        WHERE id_member = $id_member
        AND id_sub_modul = {$s['id_sub_modul']}
        AND status = 'LULUS'
        LIMIT 1
    ");

    $lulus = $cek->num_rows > 0;

    // cek sub sebelumnya
    $unlock = false;

    if ($index == 1) {
        $unlock = true; // sub pertama selalu terbuka
    } else {
        $prev = $conn->query("
            SELECT id_sub_modul FROM sub_modul
            WHERE id_modul = $id_modul
            AND urutan = " . ($s['urutan'] - 1)
        )->fetch_assoc();

        $cek_prev = $conn->query("
            SELECT 1 FROM hasil_sub_modul
            WHERE id_member = $id_member
            AND id_sub_modul = {$prev['id_sub_modul']}
            AND status = 'LULUS'
            LIMIT 1
        ");

        $unlock = $cek_prev->num_rows > 0;
    }
?>

<div class="col-md-4 mb-4">
    <div class="card shadow-sm p-4 text-center">

        <h5><?= htmlspecialchars($s['nama_sub_modul']) ?></h5>
        <p><?= htmlspecialchars($s['deskripsi']) ?></p>

        <?php if ($unlock): ?>
            <a href="materi.php?id_sub_modul=<?= $s['id_sub_modul'] ?>" 
               class="btn btn-primary">
               <?= $lulus ? 'Ulangi' : 'Mulai' ?>
            </a>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>
                Terkunci
            </button>
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>

</div>
</div>
</section>

<?php include 'footer.php'; ?>