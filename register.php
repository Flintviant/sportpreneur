<?php
session_start();
include 'koneksi.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);
    $tlp      = trim($_POST['tlp']);
    $address  = trim($_POST['address']);
    $role  = $_POST['role'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $message = "Semua field wajib diisi!";
    } elseif ($password !== $confirm) {
        $message = "Password dan konfirmasi password tidak sama!";
    } else {

        // cek username
        $stmt = $conn->prepare("SELECT id_login FROM login WHERE user = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Username sudah digunakan!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // mulai transaction
            $conn->begin_transaction();

            try {
                // insert member
                $stmt1 = $conn->prepare(
                    "INSERT INTO member (nm_member, telepon, alamat_member, id_role)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt1->bind_param("sssi", $username, $tlp, $address, $role);
                $stmt1->execute();

                // ambil id_member BARU
                $id_member = $conn->insert_id;

                // insert login
                $stmt2 = $conn->prepare(
                    "INSERT INTO login (user, pass, id_member, id_role)
                     VALUES (?, ?, ?, ?)"
                );
                $stmt2->bind_param("ssii", $username, $hashed, $id_member, $role);
                $stmt2->execute();

                $conn->commit();

                header("Location: login.php");
                exit;

            } catch (mysqli_sql_exception $e) {
                $conn->rollback();
                die("Error: " . $e->getMessage());
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="max-width:400px; width:100%;">
      <h3 class="text-center mb-3">Registrasi User Baru</h3>

      <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="post" id="registerForm">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>

            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle w-100"
                        type="button"
                        id="drop_daftar"
                        data-bs-toggle="dropdown">
                    Daftar Sebagai?
                </button>

                <ul class="dropdown-menu w-100">
                    <li>
                        <button class="dropdown-item" type="button" data-value="4">
                            Sportpreneur
                        </button>
                    </li>
                    <li>
                      <button class="dropdown-item" type="button" data-value="3">
                            Partnership / Sponsor
                      </button>
                    </li>
                    <li>
                      <button class="dropdown-item" type="button" data-value="2">
                        Anggota Biasa
                      </button>
                    </li>
                </ul>
            </div>

            <!-- VALUE YANG DIKIRIM -->
            <input type="hidden" name="role" id="role" required>

            <!-- ERROR MESSAGE -->
            <small class="text-danger d-none" id="roleError">
              Silakan pilih role terlebih dahulu
            </small>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="confirm" class="form-label">Konfirmasi Password</label>
          <input type="password" name="confirm" id="confirm" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="tlp" class="form-label">Nomor WhatsApp</label>
          <input type="number" name="tlp" id="tlp" class="form-control" required>
        </div>
        <div class="mb-3 form-floating">
          <textarea class="form-control" placeholder="Alamat Lengkap Anda" id="address" name="address"></textarea>
          <label for="address">Alamat</label>
        </div>
        <button type="submit" class="btn btn-success w-100">Daftar</button>
        <a href="login.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
      </form>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.dropdown-item').forEach(item => {
          item.addEventListener('click', function () {

            // ubah teks button
            document.getElementById('drop_daftar').innerText = this.innerText;

            // simpan value ke hidden input
            document.getElementById('role').value = this.dataset.value;

          });
        });
    </script>

    <script>
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        const roleInput = document.getElementById('role');
        const dropButton = document.getElementById('drop_daftar');
        const roleError = document.getElementById('roleError');
        const form = document.getElementById('registerForm');

        // klik pilihan
        dropdownItems.forEach(item => {
          item.addEventListener('click', function () {
            dropButton.innerText = this.innerText;
            roleInput.value = this.dataset.value;

            roleError.classList.add('d-none');
            dropButton.classList.remove('btn-danger');
            dropButton.classList.add('btn-dark');
          });
        });

        // validasi submit
        form.addEventListener('submit', function (e) {
          if (!roleInput.value) {
            e.preventDefault();

            roleError.classList.remove('d-none');
            dropButton.classList.remove('btn-success', 'btn-dark');
            dropButton.classList.add('btn-danger');
          }
        });
    </script>

</body>
</html>