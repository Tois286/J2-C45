<?php include 'modul/header.php' ?>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">My Dashboard</div>
        <ul class="navbar-nav">
            <li><a href="#login" onclick="showContent('login')">login</a></li>
        </ul>
    </nav>
    <!-- Main Content -->
    <div>
        <div class="sidebar">
            <ul>
                <li><a href="#home" onclick="showContent('home')">Home</a></li>
                <li><a href="#dataSiswa" onclick="showContent('dataSiswa')">Data Siswa</a></li>
                <li class="dropdown" id="data-training">
                    <a href="#">Data Training</a>
                    <div class="dropdown-content" id="submenu-training">
                        <a href="#" onclick="showContent('prosesC45')">Proses C4.5</a>
                        <a href="#" onclick="showContent('pohonKeputusan')">Pohon Keputusan</a>
                    </div>
                </li>
                <li class="dropdown" id="data-testing">
                    <a href="#">Data Testing</a>
                    <div class="dropdown-content" id="submenu-testing">
                        <a href="#" onclick="showContent('prosesTesting')">Proses Testing</a>
                        <a href="#" onclick="showContent('hasilTesting')">Hasil Testing</a>
                    </div>
                </li>
                <li><a href="#analytics" onclick="showContent('analytics')">Prediksi</a></li>
                <li><a href="#export" onclick="showContent('export')">Cetak</a></li>

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

        <div class="content" id="hasilTesting" style="display: none;">
            <?php include 'view/ht.php' ?>
        </div>

        <div class="content" id="analytics" style="display: none;">
            <?php include 'view/prediksi.php' ?>
        </div>

        <div class="content" id="export" style="display: none;">
            <?php include 'view/print.php' ?>
        </div>
        <div class="content" id="login" style="display: none;">
            <?php include 'public/login.php' ?>
        </div>
    </div>
    <script src="src/js/script.js"></script>
</body>

</html>