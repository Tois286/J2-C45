<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">My Dashboard</div>
        <ul class="navbar-nav">
            <li><a href="#home">Home</a></li>
            <li><a href="#logout">Logout</a></li>
        </ul>
    </nav>
    <!-- Main Content -->
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="#overview">Data siswa</a></li>
                <li class="dropdown" id="data-training">
                    <a href="#">Data Training</a>
                    <div class="dropdown-content" id="submenu-training">
                        <a href="#">Proses C4.5</a>
                        <a href="#">Pohon keputusan</a>
                    </div>
                </li>
                <li class="dropdown" id="data-testing">
                    <a href="#">Data Testing</a>
                    <div class="dropdown-content" id="submenu-testing">
                        <a href="#">Proses Testing</a>
                        <a href="#">Hasil Testing</a>
                    </div>
                </li>
                <li><a href="#analytics">Prediksi</a></li>
                <li><a href="#export">Cetak</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Welcome to the Dashboard</h1>
            <p>This is where your main content will go.</p>
        </div>
    </div>

    <script src="src/js/script.js"></script>
</body>

</html>