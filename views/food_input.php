<?php
session_start();
include("../config/db.php");
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Makanan - Health Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card p-4 shadow mx-auto" style="max-width:600px;">
    <h3 class="text-center mb-4">🍽️ Input Makanan</h3>

    <form method="POST" id="foodForm">
      <div class="mb-3">
        <label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="mb-3">
        <label for="makanan">Nama Makanan</label>
        <select id="makanan" name="makanan" class="form-select" required>
          <option value="">-- Pilih Makanan --</option>
          <?php
          $foods = $conn->query("SELECT * FROM foods ORDER BY nama_makanan ASC");
          while ($row = $foods->fetch_assoc()) {
            echo "<option value='{$row['nama_makanan']}' data-kalori='{$row['kalori_per_porsi']}'>
                    {$row['nama_makanan']} ({$row['kalori_per_porsi']} kcal/porsi)
                  </option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="porsi">Jumlah Porsi</label>
        <input type="number" id="porsi" name="porsi" class="form-control" min="1" value="1" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali ke Dashboard</a>
    </form>

    <!-- Tabel Total Kalori -->
    <div id="hasilKalori" class="mt-4" style="display:none;">
      <h5 class="text-center mb-3">🔥 Total Kalori</h5>
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th>Nama Makanan</th>
            <th>Kalori per Porsi</th>
            <th>Jumlah Porsi</th>
            <th>Total Kalori</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="hasilNama"></td>
            <td id="hasilKaloriPerPorsi"></td>
            <td id="hasilPorsi"></td>
            <td id="hasilTotalKalori" class="fw-bold text-danger"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const makananSelect = document.getElementById("makanan");
const porsiInput = document.getElementById("porsi");
const hasilDiv = document.getElementById("hasilKalori");

const hasilNama = document.getElementById("hasilNama");
const hasilKaloriPerPorsi = document.getElementById("hasilKaloriPerPorsi");
const hasilPorsi = document.getElementById("hasilPorsi");
const hasilTotalKalori = document.getElementById("hasilTotalKalori");

function hitungTotalKalori() {
  const selected = makananSelect.options[makananSelect.selectedIndex];
  const kaloriPerPorsi = parseInt(selected.getAttribute("data-kalori")) || 0;
  const jumlahPorsi = parseInt(porsiInput.value) || 0;

  if (kaloriPerPorsi > 0 && jumlahPorsi > 0) {
    const totalKalori = kaloriPerPorsi * jumlahPorsi;

    hasilNama.textContent = selected.value;
    hasilKaloriPerPorsi.textContent = kaloriPerPorsi + " kcal";
    hasilPorsi.textContent = jumlahPorsi;
    hasilTotalKalori.textContent = totalKalori + " kcal";

    hasilDiv.style.display = "block";
  } else {
    hasilDiv.style.display = "none";
  }
}

makananSelect.addEventListener("change", hitungTotalKalori);
porsiInput.addEventListener("input", hitungTotalKalori);
</script>

</body>
</html>
