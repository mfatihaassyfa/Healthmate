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
  <title>Dashboard Admin - Health Tracker</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4 text-center">👨‍⚕️ Dashboard Admin - HealthMate</h2>

    <div class="card p-4 shadow">
      <h4>Selamat datang, <?php echo $_SESSION['nama']; ?>!</h4>
      <p>Anda login sebagai <strong>Admin</strong>.</p>

      <hr>
      <h5>📊 Daftar Semua Pengguna</h5>

      <?php
      $result = $conn->query("SELECT id_user, nama, email, gender, usia, aktivitas, target_kalori, role FROM users ORDER BY id_user ASC");

      if ($result->num_rows > 0) {
        echo '<table class="table table-bordered table-striped mt-3">';
        echo '<thead><tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Usia</th>
                <th>Aktivitas</th>
                <th>Target Kalori</th>
                <th>Role</th>
              </tr></thead><tbody>';
        while ($row = $result->fetch_assoc()) {
          echo '<tr>
                  <td>'.$row['id_user'].'</td>
                  <td>'.$row['nama'].'</td>
                  <td>'.$row['email'].'</td>
                  <td>'.$row['gender'].'</td>
                  <td>'.$row['usia'].'</td>
                  <td>'.$row['aktivitas'].'</td>
                  <td>'.$row['target_kalori'].'</td>
                  <td>'.$row['role'].'</td>
                </tr>';
        }
        echo '</tbody></table>';
      } else {
        echo "<p class='text-muted'>Belum ada data pengguna.</p>";
      }
      ?>


      <a href="activities.php" class="btn btn-primary mt-3">Lihat Aktivitas Pengguna</a>
      <a href="foods.php" class="btn btn-success mt-3">Kelola Makanan & Kalori</a>
      <a href="../controllers/logout.php" class="btn btn-danger mt-3">Logout</a>

    </div>
  </div>
</body>
</html>
