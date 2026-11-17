<?php
// Aktifkan error report
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../views/login.php");
  exit();
}

include("../config/db.php");

// Tambah data makanan
if (isset($_POST['tambah'])) {
  $nama = trim($_POST['nama']);
  $kalori = (int)$_POST['kalori'];

  $stmt = $conn->prepare("INSERT INTO foods (nama_makanan, kalori_per_porsi) VALUES (?, ?)");
  $stmt->bind_param("si", $nama, $kalori);
  $stmt->execute();
}

// Update data makanan
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama = trim($_POST['nama']);
  $kalori = (int)$_POST['kalori'];

  $stmt = $conn->prepare("UPDATE foods SET nama_makanan=?, kalori_per_porsi=? WHERE id=?");
  $stmt->bind_param("sii", $nama, $kalori, $id);
  $stmt->execute();
}

// Hapus data makanan
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM foods WHERE id=$id");
}

// Ambil semua data makanan
$result = $conn->query("SELECT * FROM foods ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Makanan & Kalori - Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center mb-4">🍎 Kelola Makanan & Kalori</h2>

  <div class="card p-4 shadow">
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <!-- Form Tambah Makanan -->
    <form method="POST" class="row g-3 mb-4">
      <div class="col-md-6">
        <input type="text" name="nama" class="form-control" placeholder="Nama Makanan" required>
      </div>
      <div class="col-md-4">
        <input type="number" name="kalori" class="form-control" placeholder="Kalori per porsi" required>
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
      </div>
    </form>

    <!-- Tabel Data Makanan -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nama Makanan</th>
          <th>Kalori per Porsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['nama_makanan']) ?></td>
          <td><?= $row['kalori_per_porsi'] ?></td>
          <td>
            <!-- Tombol Edit -->
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Makanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form method="POST">
                <div class="modal-body">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <div class="mb-3">
                    <label>Nama Makanan</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($row['nama_makanan']) ?>" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label>Kalori per Porsi</label>
                    <input type="number" name="kalori" value="<?= $row['kalori_per_porsi'] ?>" class="form-control" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="update" class="btn btn-success">Simpan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
