<?php
require 'auth.php';
requireLogin();

require_once __DIR__ . '/session_modal.php';
$id_modul = $_GET['id_modul'];
$id_member = $_SESSION['id_member'];

// echo '<pre>';
// print_r($_SESSION);
// exit;

?>

<?php include 'head.php'; ?>

<section class="modul-wrapper">
  <div class="container">

    <h2 class="ltext-103 redefine-title text-center mb-5">Materi Pembelajaran <?= $id_modul ?></h2>

    <div class="video-wrapper text-center mb-4">
      <iframe
        id="videoMateri"
        src="https://www.youtube.com/embed/89QEZGxWYZc?enablejsapi=1"
        frameborder="0"
        allowfullscreen>
      </iframe>
    </div>

    <div class="text-center mt-4">
      <a href="quiz.php?id_modul=<?= $id_modul ?>" 
         id="btnQuiz"
         class="btn btn-success selesai1 text-white disabled"
         aria-disabled="true">
        Selesaikan Video untuk Melanjutkan
      </a>
    </div>

  </div>
</section>

<script src="https://www.youtube.com/iframe_api"></script>

<script>
let player;

function onYouTubeIframeAPIReady() {
  player = new YT.Player('videoMateri', {
    events: {
      'onStateChange': onPlayerStateChange
    }
  });
}

function onPlayerStateChange(event) {
  // 0 = video selesai
  if (event.data === 0) {
    const btn = document.getElementById('btnQuiz');
    btn.classList.remove('disabled');
    btn.removeAttribute('aria-disabled');
    btn.textContent = 'Saya Sudah Selesai → Kerjakan Soal';
  }
}
</script>

<?php include 'footer.php'; ?>