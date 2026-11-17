<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tracker</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
        }
        .hero {
            height: 100vh;
        }
        .hero h1 {
            font-weight: bold;
            color: #333;
        }
        .hero p {
            font-size: 1.2rem;
            color: #444;
        }
        .btn-custom {
            width: 150px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid hero d-flex flex-column justify-content-center align-items-center text-center">
        <h1 class="mb-3">Health Tracker</h1>
        <p class="mb-4">Pantau pola hidup sehatmu dengan mudah.<br>Catat aktivitas harian dan lihat progresmu.</p>
        <div>
            <a href="views/login.php" class="btn btn-primary btn-lg btn-custom me-2">Login</a>
            <a href="views/register.php" class="btn btn-success btn-lg btn-custom">Daftar</a>
        </div>
    </div>

    <footer class="text-center py-3 bg-white shadow-sm">
        <p class="mb-0">&copy; <?= date("Y"); ?> Health Tracker | Dibuat dengan ❤️</p>
    </footer>
</body>
</html>
