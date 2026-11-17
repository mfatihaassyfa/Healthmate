<?php
session_start();
session_unset();  // hapus semua variabel session
session_destroy();  // hancurkan session

header("Location: ../views/login.php");
exit();
?>
