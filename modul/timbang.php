<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM balita WHERE nik_balita='$id'");
    $data = mysqli_fetch_array($sql);
} else {
    header('Location: modul/jadwal.php');
    exit;
}
?>

<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<body>
    <div class="container-register">
        <div class="box-register">
            <h1>Edit Jadwal</h1>
            <form action="database/tambahProses3.php" method="POST" class="register-form">
                <input type="hidden" name="id" value="<?php echo $data['nik_balita']; ?>">

                <label for="id_ortu">ID Orang Tua :</label>
                <input type="text" id="id_ortu" name="id_ortu" value="<?php echo $data['id_orangtua']; ?>" required>

                <label for="nama">Nama Balita:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>

                <label for="berat">Berat :</label>
                <input type="text" name="berat" id="berat" required placeholder="berat">

                <label for="tinggi">Tinggi :</label>
                <input type="text" name="tinggi" id="tinggi" required placeholder="tinggi">

                <div class="register-btn">
                    <input type="submit" value="Edit" name="submit" class="button button1">
                    <a href="../" type="button" class="button-cancel">cancel</a>
                </div>
            </form>
        </div>
    </div>
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Your custom script -->
    <script src="src/js/script.js"></script>
</body>

</html>