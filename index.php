<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/js/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dapatkan URL saat ini
            let currentUrl = window.location.href;

            // Cari posisi dari tanda '?' (jika ada)
            let queryPosition = currentUrl.indexOf('?');

            // Ambil bagian URL sebelum '?' untuk mendapatkan base URL
            let baseUrl = (queryPosition !== -1) ? currentUrl.substring(0, queryPosition) : currentUrl;

            // Ubah URL tanpa query parameters menggunakan history.replaceState
            history.replaceState(null, '', baseUrl);
        });
    </script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">My Dashboard</div>
    </nav>
    <!-- Main Content -->
    <div>
        <div class="sidebar">
            <ul>
                <li><a href="#home" onclick="showContent('home')">Home</a></li>
                <?php if ($role == 'admin') : ?>
                    <li class="dropdown" id="data-training">
                        <a href="#">Data Training</a>
                        <div class="dropdown-content" id="submenu-training">
                            <a href="#" onclick="showContent('prosesC45')">Proses Training</a>
                            <a href="#" onclick="showContent('pohonKeputusan')">Pohon Keputusan</a>
                        </div>
                    </li>
                    <li class="dropdown" id="data-testing">
                        <a href="#">Data Testing</a>
                        <div class="dropdown-content" id="submenu-testing">
                            <a href="#" onclick="showContent('prosesTesting')">Proses Testing</a>
                            <a href="#" onclick="showContent('export')">Cetak Data</a>
                        </div>
                    </li>
                    <li><a href="#dataSiswa" onclick="showContent('dataSiswa')">Data Pengguna</a></li>
                <?php endif; ?>
                <li><a href="#analytics" onclick="showContent('analytics')">Prediksi</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content" id="home" style="display: none;">
            <?php include 'view/home.php' ?>
        </div>

        <div class="content" id="dataSiswa" style="display: none;">
            <?php include 'view/dataSiswa.php' ?>
        </div>

        <div class="content" id="prosesC45" style="display: none;">
            <?php include 'view/c45.php' ?>
        </div>

        <div class="content" id="pohonKeputusan" style="display: none;">
            <?php include 'view/pk.php' ?>
        </div>

        <div class="content" id="prosesTesting" style="display: none;">
            <?php include 'view/pt.php' ?>
        </div>

        <div class="content" id="export" style="display: none;">
            <?php include 'view/print.php' ?>
        </div>

        <div class="content" id="analytics" style="display: none;">
            <?php include 'view/prediksi.php' ?>
        </div>

    </div>
</body>

</html>