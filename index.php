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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="src/js/script.js"></script>
</head>
<?php
session_start(); // Memulai sesi

include 'config/koneksi.php'; // Menghubungkan ke database

// Memeriksa apakah user_id ada di sesi
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    // Query untuk mengambil data pengguna
    $sql = mysqli_query($koneksi, "SELECT * FROM users WHERE user_id='$id'");

    // Memeriksa hasil query
    if ($sql && mysqli_num_rows($sql) > 0) {
        $user = mysqli_fetch_assoc($sql);
        // Lakukan sesuatu dengan data pengguna
        // $pengguna = "Nama: " . htmlspecialchars($user['nama']); // Menghindari XSS
    }
}

// Menutup koneksi
mysqli_close($koneksi);
?>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <!-- <div class="navbar-brand">POSYANDU</div> -->
        <img src="asset/posyandu2.png" alt="" style="width:150px;padding:0;">
        <!-- <?php echo $pengguna; ?> -->
        <div>Welcome, <strong><?php echo htmlspecialchars($user['nama']); ?> </strong>!</div>
    </nav>
    <!-- Main Content -->
    <div>
        <div class="sidebar">
            <ul>
                <li><a href="#home" onclick="showContent('home')">Home</a></li>
                <?php if ($role == 'admin') : ?>
                    <li><a href="#jadwal" onclick="showContent('jadwal')">Jadwal</a></li>
                    <li><a href="#dataUser" onclick="showContent('dataUser')">Data Orang Tua</a></li>
                    <li><a href="#dataBalita" onclick="showContent('dataBalita')">Data Balita</a></li>
                    <li><a href="#dataTimbangan" onclick="showContent('dataTimbangan')">Data Penimbangan</a></li>
                    <li><a href="#dataImunisasi" onclick="showContent('dataImunisasi')">Imunisasi</a></li>
                    <li><a href="#lapor" onclick="showContent('lapor')">Pelaporan</a></li>
                    <li><a href="#cetak" onclick="showContent('cetak')">Cetak Kartu</a></li>
                <?php endif; ?>
                <?php if ($role == 'superUser') : ?>
                    <li><a href="#jadwal" onclick="showContent('jadwal')">Jadwal</a></li>
                    <li><a href="#dataBalita" onclick="showContent('dataBalita')">Data Balita</a></li>
                    <li><a href="#dataTimbangan" onclick="showContent('dataTimbangan')">Data Penimbangan</a></li>
                    <li><a href="#dataJenis" onclick="showContent('dataJenis')">Imunisasi</a></li>
                    <li><a href="#lapor" onclick="showContent('lapor')">Pelaporan</a></li>
                <?php endif; ?>
                <?php if ($role == 'user') : ?>
                    <li><a href="#jadwal" onclick="showContent('jadwal')">Jadwal</a></li>
                    <li><a href="#dataBalita" onclick="showContent('dataBalita')">Data Balita</a></li>
                    <li><a href="#dataTimbangan" onclick="showContent('dataTimbangan')">Data Penimbangan</a></li>
                    <li><a href="#dataJenis" onclick="showContent('dataJenis')">Imunisasi</a></li>
                    <li><a href="#cetak" onclick="showContent('cetak')">Cetak Kartu</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content" id="home" style="display: none;">
            <?php include 'view/home.php' ?>
        </div>
        <div class="content" id="dataUser" style="display: none;">
            <?php include 'view/dataUser.php' ?>
        </div>
        <div class="content" id="dataBalita" style="display: none;">
            <?php include 'view/dataBalita.php' ?>
        </div>
        <div class="content" id="dataTimbangan" style="display: none;">
            <?php include 'view/d_timbangan.php' ?>
        </div>
        <div class="content" id="dataJenis" style="display: none;">
            <?php include 'view/d_imunisasi.php' ?>
        </div>
        <div class="content" id="dataImunisasi" style="display: none;">
            <?php include 'view/d_imunisasi.php' ?>
        </div>
        <div class="content" id="dataMedis" style="display: none;">
            <?php include 'view/d_medis.php' ?>
        </div>
        <div class="content" id="lapor" style="display: none;">
            <?php include 'view/lapor.php' ?>
        </div>
        <div class="content" id="jadwal" style="display: none;">
            <?php include 'view/jadwal.php' ?>
        </div>
        <div class="content" id="cetak" style="display: none;">
            <?php include 'view/cetak.php' ?>
        </div>
    </div>
</body>

</html>