<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  die("⚠️ Error: Sesi pengguna tidak ditemukan. Silakan login ulang.");
}

include("../config/db.php");
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $tanggal = $_POST['tanggal'] ?? date("Y-m-d"); // gunakan tanggal input
  $jam_tidur = $_POST['jam_tidur'];
  $durasi_olahraga = $_POST['durasi_olahraga'];
  $air_liter = $_POST['air_liter'];
  $kalori = $_POST['kalori'];
  $berat_kg = $_POST['berat_kg'];

  $stmt = $conn->prepare("INSERT INTO activities (id_user, tanggal, jam_tidur, durasi_olahraga, air_liter, kalori, berat_kg)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
  if (!$stmt) die("❌ Error prepare SQL: " . $conn->error);

  $stmt->bind_param("isddddd", $user_id, $tanggal, $jam_tidur, $durasi_olahraga, $air_liter, $kalori, $berat_kg);
  if (!$stmt->execute()) die("❌ Gagal simpan data: " . $stmt->error);

  echo "<div style='padding:20px;background:#d4edda;color:#155724;border-radius:8px;width:400px;margin:50px auto;text-align:center;'>
    ✅ Data aktivitas tanggal <b>$tanggal</b> berhasil disimpan.<br><br>
    <a href='dashboard.php' style='color:#155724;text-decoration:none;font-weight:bold;'>Kembali ke Dashboard</a>
  </div>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Aktivitas - HealthTracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">🏃 Input Aktivitas Harian</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Jam Tidur (jam)</label>
        <input type="number" name="jam_tidur" class="form-control" step="0.1" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Durasi Olahraga (menit)</label>
        <input type="number" name="durasi_olahraga" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Air Diminum (liter)</label>
        <input type="number" name="air_liter" class="form-control" step="0.1" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Kalori (kcal)</label>
        <input type="number" name="kalori" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Berat Badan (kg)</label>
        <input type="number" name="berat_kg" class="form-control" step="0.1" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Simpan Aktivitas</button>
      <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali ke Dashboard</a>
    </form>
  </div>
</div>
</body>
</html>
