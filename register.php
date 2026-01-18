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
        "INSERT INTO member (nm_member, telepon, alamat_member)
         VALUES (?, ?, ?)"
    );
    $stmt1->bind_param("sss", $username, $tlp, $address);
    $stmt1->execute();

    // ambil id_member BARU
    $id_member = $conn->insert_id;

    // insert login
    $stmt2 = $conn->prepare(
        "INSERT INTO login (user, pass, id_member, id_role)
         VALUES (?, ?, ?, 2)"
    );
    $stmt2->bind_param("ssi", $username, $hashed, $id_member);
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

      <form method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" required autofocus>
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
</body>
</html>