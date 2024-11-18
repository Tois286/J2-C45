<?php
include '../config/koneksi.php';

// Ambil ID dari URL
$id_ortu = isset($_GET['id']) ? $_GET['id'] : '';

// Query untuk mengambil data dari tabel jadwal
$sql = mysqli_query($koneksi, 'SELECT * FROM jadwal');
if (!$sql) {
    die('Query Error: ' . mysqli_error($koneksi));
}

// Simpan tanggal dari jadwal
$tanggal_jadwal = [];
while ($data = mysqli_fetch_array($sql)) {
    $tanggal_jadwal[] = $data['tanggal'];
}
?>

<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Daftarkan</h1>
            </center>
            <form action="database/tambahProses2.php?id=<?php echo htmlspecialchars($id_ortu); ?>" method="POST" class="register-form">
                <input type="text" name="tanggal_p" value="<?php echo htmlspecialchars(!empty($tanggal_jadwal) ? $tanggal_jadwal[0] : ''); ?>">

                <label for="id">ID Orang Tua Wali :</label>
                <input type="text" name="id" id="id" required placeholder="ID" value="<?php echo htmlspecialchars($id_ortu); ?>">

                <label for="nik_balita">NIK Balita :</label>
                <input type="text" name="nik_balita" id="nik_balita" required placeholder="nik balita">

                <label for="nama">Nama Balita :</label>
                <input type="text" name="nama" id="nama" required placeholder="Nama Lengkap Balita">

                <label for="tgl">Tanggal Lahir:</label>
                <input type="date" name="tgl" id="tgl" required placeholder="Tanggal"><br><br>

                <label for="ct">Catatan Kesehatan</label>
                <input type="text" name="ct" id="ct" required placeholder="Catatan Kesehatan atau Riwayat Penyakit">

                <label for="jenis_kelamin">Jenis Kelamin :</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required>
                    <option value="LAKI-LAKI">Laki-laki</option>
                    <option value="PEREMPUAN">Perempuan</option>
                </select>

                <div class="register-btn">
                    <input type="submit" value="Daftar" name="submit" class="button button1">
                    <a href="../index.php" class="button-cancel">Cancel</a>
                </div>
            </form>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>

</html>