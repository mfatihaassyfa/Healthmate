<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username   = "root";     // default user XAMPP/Laragon
$password   = "";         // kosongkan jika tanpa password
$database   = "health_tracker_modern";  // nama database kamu

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Aktifkan UTF-8 biar karakter (emoji & bahasa Indonesia) aman
$conn->set_charset("utf8mb4");
?>
