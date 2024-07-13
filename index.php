<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <li><a href="#edit" onclick="showContent('edit')">Edit</a></li>
                <li><a href="#dataSiswa" onclick="showContent('dataSiswa')">Data Siswa</a></li>
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

        <div class="content" id="edit" style="display: none;">
            <?php include 'view/edit.php' ?>
        </div>

    </div>
    <script src="src/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>