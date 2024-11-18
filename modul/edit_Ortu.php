<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM dataorangtua WHERE id_orangtua='$id'");

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
                <h1>Update Data Orang Tua Wali</h1>
            </center>
            <form action="database/updateOrtu.php?table=dataorangtua" method="POST" class="register-form">
                <input type="text" name="id" value="<?php echo htmlspecialchars($id); ?>">

                <label for="namaAyah">Nama Ayah:</label>
                <input type="text" name="namaAyah" id="namaAyah" required placeholder="Nama Ayah" value="<?php echo htmlspecialchars($data['nama_ayah']); ?>">

                <label for="nikAyah">NIK Ayah:</label>
                <input type="text" name="nikAyah" id="nikAyah" required placeholder="NIK Ayah" value="<?php echo htmlspecialchars($data['nik_ayah']); ?>">

                <label for="namaIbu">Nama Ibu:</label>
                <input type="text" name="namaIbu" id="namaIbu" required placeholder="Nama Ibu" value="<?php echo htmlspecialchars($data['nama_ibu']); ?>">

                <label for="nikIbu">NIK Ibu:</label>
                <input type="text" name="nikIbu" id="nikIbu" required placeholder="NIK Ibu" value="<?php echo htmlspecialchars($data['nik_ibu']); ?>">

                <label for="alamat">Alamat Sekarang:</label>
                <input type="text" name="alamat" id="alamat" required placeholder="Alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>">

                <label for="notpln">No. Telp Sekarang:</label>
                <input type="text" name="notpln" id="notpln" required placeholder="No. Telp" value="<?php echo htmlspecialchars($data['telepon']); ?>">

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