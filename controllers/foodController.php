<?php
session_start();
include "../config/db.php";

// ==============================
// Ambil kalori otomatis
// ==============================
if (isset($_GET['action']) && $_GET['action'] == "get_calorie") {
    $makanan = strtolower($_GET['makanan']);
    $sql = "SELECT kalori_per_porsi FROM foods WHERE LOWER(nama_makanan) = '$makanan' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['kalori_per_porsi'];
    } else {
        echo "0"; // makanan belum ada di database
    }
    exit;
}

// ==============================
// Simpan makanan user
// ==============================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['action']) && $_GET['action'] == "save_food") {
    if (!isset($_SESSION['user_id'])) {
        die("Anda harus login terlebih dahulu.");
    }

    $user_id = $_SESSION['user_id'];
    $makanan = $_POST['makanan'];
    $porsi   = $_POST['porsi'];
    $kalori  = $_POST['kalori'];

    $sql = "INSERT INTO food_logs (user_id, makanan, porsi, kalori) 
            VALUES ('$user_id', '$makanan', '$porsi', '$kalori')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/dashboard.php?success=food_saved");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
