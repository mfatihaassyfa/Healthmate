<?php
include("../config/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action == "add") {
        $id_user   = $_SESSION['user_id'];
        $tanggal   = $_POST['tanggal'];
        $jam_tidur = $_POST['jam_tidur'];
        $olahraga  = $_POST['durasi_olahraga'];
        $air       = $_POST['air_liter'];
        $makanan   = $_POST['makanan'];
        $kalori    = $_POST['kalori'];
        $berat     = $_POST['berat_kg'];

        $sql = "INSERT INTO activities 
                (id_user, tanggal, jam_tidur, durasi_olahraga, air_liter, makanan, kalori, berat_kg) 
                VALUES 
                ('$id_user', '$tanggal', '$jam_tidur', '$olahraga', '$air', '$makanan', '$kalori', '$berat')";

        if ($conn->query($sql)) {
            header("Location: ../views/dashboard.php?msg=success");
            exit();
        } else {
            // tampilkan error SQL supaya gampang debug
            echo "<h3>⚠️ Query Error</h3>";
            echo "<p>" . $conn->error . "</p>";
            echo "<p><b>SQL:</b> $sql</p>";
            exit();
        }
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>
