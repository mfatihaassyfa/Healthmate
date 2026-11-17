<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include("../config/db.php");

$user_id = $_SESSION['user_id'];
$msg = "";

// Ambil data user saat ini
$sql = "SELECT * FROM users WHERE id_user='$user_id'";
$user = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $tinggi = floatval($_POST['tinggi_cm']);
    $usia = intval($_POST['usia']);
    $gender = $_POST['gender'];
    $aktivitas = $_POST['aktivitas'];

    $update = $conn->prepare("UPDATE users SET nama=?, tinggi_cm=?, usia=?, gender=?, aktivitas=? WHERE id_user=?");
    $update->bind_param("sdissi", $nama, $tinggi, $usia, $gender, $aktivitas, $user_id);

    if ($update->execute()) {
        $_SESSION['nama'] = $nama;
        $msg = "<div class='alert alert-success'>✅ Profil berhasil diperbarui!</div>";
        // Ambil ulang data user
        $sql = "SELECT * FROM users WHERE id_user='$user_id'";
        $user = $conn->query($sql)->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-danger'>❌ Gagal memperbarui profil.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil - Health Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f4f7fb; font-family:'Poppins',sans-serif; display:flex; }
.sidebar { width:250px; background:linear-gradient(180deg,#0b2447,#19376d); color:white; height:100vh; position:fixed; }
.sidebar a { display:block; color:white; padding:10px 20px; text-decoration:none; border-radius:6px; margin-bottom:8px; }
.sidebar a.active,.sidebar a:hover{background:#4fc3f7;color:#000;}
.main { margin-left:260px; padding:30px; width:100%; }
</style>
</head>
<body>
<div class="sidebar">
  <h4 class="text-center mt-3 mb-4"><i class="bi bi-heart-pulse"></i> HealthMate</h4>
  <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="food_input.php"><i class="bi bi-egg-fried"></i> Input Makanan</a>
  <a href="input.php"><i class="bi bi-activity"></i> Input Aktivitas</a>
  <a href="#" class="active"><i class="bi bi-person-lines-fill"></i> Edit Profil</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main">
  <h3 class="mb-4">Edit Profil Kesehatan 👤</h3>
  <?= $msg ?>

  <form method="POST" class="card p-4 shadow-sm">
    <div class="row mb-3">
      <div class="col-md-6">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
      </div>
      <div class="col-md-3">
        <label>Tinggi Badan (cm)</label>
        <input type="number" name="tinggi_cm" class="form-control" value="<?= $user['tinggi_cm'] ?>" step="0.1" required>
      </div>
      <div class="col-md-3">
        <label>Usia</label>
        <input type="number" name="usia" class="form-control" value="<?= $user['usia'] ?>" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label>Jenis Kelamin</label>
        <select name="gender" class="form-select" required>
          <option value="Pria" <?= ($user['gender']=='Pria')?'selected':'' ?>>Pria</option>
          <option value="Wanita" <?= ($user['gender']=='Wanita')?'selected':'' ?>>Wanita</option>
        </select>
      </div>
      <div class="col-md-8">
        <label>Aktivitas Harian</label>
        <select name="aktivitas" class="form-select" required>
          <option value="Sedentary" <?= ($user['aktivitas']=='Sedentary')?'selected':'' ?>>Sedentary (Jarang bergerak)</option>
          <option value="Ringan" <?= ($user['aktivitas']=='Ringan')?'selected':'' ?>>Ringan (1–3x olahraga/minggu)</option>
          <option value="Sedang" <?= ($user['aktivitas']=='Sedang')?'selected':'' ?>>Sedang (3–5x olahraga/minggu)</option>
          <option value="Berat" <?= ($user['aktivitas']=='Berat')?'selected':'' ?>>Berat (6–7x olahraga/minggu)</option>
          <option value="Sangat Berat" <?= ($user['aktivitas']=='Sangat Berat')?'selected':'' ?>>Sangat Berat (aktivitas fisik intens setiap hari)</option>
        </select>
      </div>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Simpan Perubahan</button>
    </div>
  </form>
</div>
</body>
</html>
