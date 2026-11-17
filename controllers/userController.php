<?php
include("../config/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action == "register") {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $usia = $_POST['usia'];
        $tinggi = $_POST['tinggi_cm'];
        $gender = $_POST['gender'];
        $aktivitas = $_POST['aktivitas'];

        // Simpan ke database
        $sql = "INSERT INTO users (nama, email, password, usia, tinggi_cm, gender, aktivitas) 
                VALUES ('$nama', '$email', '$password', '$usia', '$tinggi', '$gender', '$aktivitas')";
        
        if ($conn->query($sql)) {
            header("Location: ../views/login.php?msg=registered");
            exit();
        } else {
            header("Location: ../views/register.php?error=duplicate");
            exit();
        }
    }

    if ($action == "login") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id_user'];
                $_SESSION['nama'] = $row['nama'];
                header("Location: ../views/dashboard.php");
                exit();
            } else {
                header("Location: ../views/login.php?error=wrongpass");
                exit();
            }
        } else {
            header("Location: ../views/login.php?error=notfound");
            exit();
        }
    }
} else {
    header("Location: ../views/login.php");
    exit();
}
?>
