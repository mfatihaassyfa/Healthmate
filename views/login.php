<?php
session_start();
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // ambil data user berdasarkan email dan password
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=? LIMIT 1");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    // simpan ke session
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // arahkan berdasarkan role
    if ($user['role'] == 'admin') {
      header("Location: ../admin/dashboard.php");
    } else {
      header("Location: ../views/dashboard.php");
    }
    exit();
  } else {
    $error = "Email atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - HealthTracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card p-4 shadow mx-auto" style="max-width:400px;">
    <h3 class="text-center mb-4">🔑 Login</h3>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <a href="register.php" class="btn btn-link w-100 mt-2">Daftar Akun</a>
    </form>
  </div>
</div>
</body>
</html>
