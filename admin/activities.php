<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../views/login.php");
  exit();
}

include("../config/db.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aktivitas Pengguna - Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center mb-4">📋 Aktivitas Semua Pengguna</h2>

  <div class="card p-4 shadow">
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <?php
    // join tabel users dan activities untuk menampilkan nama user di setiap aktivitas
    $sql = "SELECT a.id_activity, u.nama, a.tanggal, a.air, a.tidur, a.makanan, a.berat_kg 
            FROM activities a
            JOIN users u ON a.id_user = u.id_user
            ORDER BY a.tanggal DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo '<table class="table table-striped table-bordered">';
      echo '<thead class="table-dark"><tr>
              <th>No</th>
              <th>Nama Pengguna</th>
              <th>Tanggal</th>
              <th>Air (ml)</th>
              <th>Tidur (jam)</th>
              <th>Makanan</th>
              <th>Berat (kg)</th>
            </tr></thead><tbody>';
      $no = 1;
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['tanggal']}</td>
                <td>{$row['air']}</td>
                <td>{$row['tidur']}</td>
                <td>{$row['makanan']}</td>
                <td>{$row['berat_kg']}</td>
              </tr>";
        $no++;
      }
      echo "</tbody></table>";
    } else {
      echo "<p class='text-muted'>Belum ada aktivitas pengguna.</p>";
    }
    ?>
  </div>
</div>
</body>
</html>
