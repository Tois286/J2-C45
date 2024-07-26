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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="src/js/script.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">C45 by Giri</div>
    </nav>
    <!-- Main Content -->
    <div>
        <div class="sidebar">
            <ul>
                <li><a href="#home" onclick="showContent('home')">Home</a></li>
                <?php if ($role == 'admin') : ?>
                    <li><a href="#dataSiswa" onclick="showContent('dataSiswa')">Data Pengguna</a></li>
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
            <?php include "view/prediksi.php"; ?>
        </div>

    </div>
</body>

</html>