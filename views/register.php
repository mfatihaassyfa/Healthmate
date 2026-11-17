<?php
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $usia = $_POST['usia'];
  $gender = $_POST['gender'];
  $tinggi = $_POST['tinggi_cm'];
  $aktivitas = $_POST['aktivitas'];

  $stmt = $conn->prepare("INSERT INTO users (nama, email, password, usia, gender, tinggi_cm, aktivitas) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssisss", $nama, $email, $password, $usia, $gender, $tinggi, $aktivitas);
  $stmt->execute();

  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register - HealthTracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card p-4 shadow mx-auto" style="max-width:450px;">
    <h3 class="text-center mb-4">🧍 Daftar Akun Baru</h3>
    <form method="POST">
      <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
      <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
      <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="mb-3"><label>Usia</label><input type="number" name="usia" class="form-control" required></div>
      <div class="mb-3"><label>Gender</label>
        <select name="gender" class="form-select">
          <option>Pria</option>
          <option>Wanita</option>
        </select>
      </div>
      <div class="mb-3"><label>Tinggi Badan (cm)</label><input type="number" name="tinggi_cm" class="form-control" required></div>
      <div class="mb-3"><label>Aktivitas Harian</label>
        <select name="aktivitas" class="form-select">
          <option value="Sedentary">Sedentary</option>
          <option value="Ringan">Ringan</option>
          <option value="Sedang">Sedang</option>
          <option value="Berat">Berat</option>
          <option value="Sangat Berat">Sangat Berat</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success w-100">Daftar</button>
      <a href="login.php" class="btn btn-link w-100 mt-2">Sudah punya akun? Login</a>
    </form>
  </div>
</div>
</body>
</html>
