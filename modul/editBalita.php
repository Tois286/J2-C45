<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id_ortu = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM balita WHERE id_orangtua='$id_ortu'");

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
                <h1>Daftarkan</h1>
            </center>
            <form action="database/updateBalita.php?id=<?php echo htmlspecialchars($id_ortu); ?>" method="POST" class="register-form">

                <label for="id">ID Orang Tua Wali :</label>
                <input type="text" name="id" id="id" required placeholder="ID" value="<?php echo htmlspecialchars($id_ortu); ?>">

                <label for="nik_balita">NIK Balita :</label>
                <input type="text" name="nik_balita" id="nik_balita" required placeholder="nik balita" value="<?php echo htmlspecialchars($data['nik_balita']); ?>">

                <label for="nama">Nama Balita :</label>
                <input type="text" name="nama" id="nama" required placeholder="Nama Lengkap Balita" value="<?php echo htmlspecialchars($data['nama']); ?>">

                <label for="tgl">Tanggal Lahir:</label>
                <input type="text" name="tgl" id="tgl" required placeholder="Tanggal" value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>"><br>

                <label for="ct">Catatan Kesehatan :</label>
                <input type="text" name="ct" id="ct" required value="<?php echo htmlspecialchars($data['catatan_kesehatan']); ?>">

                <label for="jenis_kelamin">Jenis Kelamin :</label>
                <input type="text" name="jenis_kelamin" id="jenis_kelamin" required value="<?php echo htmlspecialchars($data['jenis_kelamin']); ?>">

                <label for="tgl_imunisasi">Tanggal Imunisasi :</label>
                <input type="text" name="tgl_imunisasi" id="tgl_imunisasi" required value="<?php echo htmlspecialchars($data['tanggal_imunisasi']); ?>">

                <label for="tgl_daftar">Tanggal mendaftar :</label>
                <input type="text" name="tgl_daftar" id="tgl_daftar" required value="<?php echo htmlspecialchars($data['tgl_daftar']); ?>">

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