<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM timbangan WHERE nik_balita='$id'");

    // Cek apakah query berhasil
    if (!$sql) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    // Ambil data
    $data = mysqli_fetch_array($sql);
    // Cek apakah data ditemukan
    if (!$data) {
        die("Data tidak ditemukan.");
    }
} else {
    header('Location: modul/jadwal.php');
    exit;
}
?>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Update Data Timbangan</h1>
            </center>
            <form action="database/updateTimbang.php?table=dataorangtua" method="POST" class="register-form">

                <label for="tgl">Tanggal Di timbang</label>
                <input type="text" name="tgl" id="tgl" required value="<?php echo htmlspecialchars($data['tanggal']); ?>">

                <label for="berat">Berat :</label>
                <input type="text" name="berat" id="berat" required value="<?php echo htmlspecialchars($data['berat']); ?>">

                <label for="tinggi">Tinggi :</label>
                <input type="text" name="tinggi" id="tinggi" required value="<?php echo htmlspecialchars($data['tinggi_badan']); ?>">

                <div class="register-btn">
                    <input type="submit" value="Update" name="submit" class="button button1">
                    <a href="../" class="button-cancel">Cancel</a>
                </div>
            </form>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>

</html>