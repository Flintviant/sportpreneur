<?php
session_start();
include 'koneksi.php'; // file koneksi database

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_login'])) {
    header("Location: shoping-cart.php");
    exit();
}

// pesan dari redirect
$info = "";
if (isset($_GET['msg']) && $_GET['msg'] === 'login_required') {
    $info = "Silakan login terlebih dahulu untuk checkout";
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("
        SELECT 
            l.id_login,
            l.user,
            l.pass,
            l.id_member,
            r.id_role,
            r.role_name
        FROM login l
        JOIN roles r ON l.id_role = r.id_role
        WHERE l.user = ?
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['pass'])) {

            session_regenerate_id(true);

            $_SESSION['id_login'] = $user['id_login'];
            $_SESSION['user']     = $user['user'];
            $_SESSION['role']     = $user['role_name'];
            $_SESSION['id_role']  = $user['id_role'];
            $_SESSION['id_member']  = $user['id_member'];

            header("Location: shoping-cart.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="max-width:400px; width:100%;">
      <h3 class="text-center mb-3">Login Admin</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <a href="register.php" class="btn btn-warning w-100 mt-2">Registrasi</a>
      </form>
    </div>
  </div>
</body>
</html>