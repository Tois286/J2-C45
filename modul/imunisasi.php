<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>
<?php
include '../config/koneksi.php';

// Ambil ID dari URL
$nik_balita = isset($_GET['id']) ? $_GET['id'] : '';

// Query untuk mengambil data dari tabel jadwal
$sql = mysqli_query($koneksi, 'SELECT * FROM jadwal');
$sql2 = mysqli_query($koneksi, "SELECT * FROM balita WHERE nik_balita='$nik_balita'");
if (!$sql) {
    die('Query Error: ' . mysqli_error($koneksi));
}
// Simpan tanggal dari jadwal
$nama = '';
$id_ortu = '';
while ($data1 = mysqli_fetch_array($sql2)) {
    $nama = $data1['nama'];
    $id_ortu = $data1['id_orangtua'];
}
// Simpan tanggal dari jadwal
$dokter = [];
while ($data = mysqli_fetch_array($sql)) {
    $dokter[] = $data['dokter'];
}
?>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Buat Jadwal</h1>
            </center>
            <form action="database/i_imunisasi.php?table=<?php echo $nik_balita; ?>" method="POST" class="register-form">
                <input type="text" name="id_ortu" id="id_ortu" required placeholder="id" value="<?php echo htmlspecialchars($id_ortu); ?>">

                <label for="id">NIK Balita :</label>
                <input type="text" name="id" id="id" required placeholder="id" value="<?php echo htmlspecialchars($nik_balita); ?>">

                <label for="nama">Nama Balita:</label>
                <input type="text" name="nama" id="nama" required placeholder="nama" value="<?php echo htmlspecialchars($nama); ?>">


                <label for="vaksin">Jenis Vaksin Imunisasi :</label>
                <select name="vaksin" id="vaksin" required>
                    <option value="Bacillus Calmette-Guérin">BCG</option>
                    <option value="Hepatitis B ">HBV</option>
                    <option value="Difteria, Pertusis, Tetanus">DPT</option>
                    <option value="Polio">IPV</option>
                    <option value="Campak, Gondong, Rubella">MMR</option>
                    <option value="Pneumokokus">PCV</option>
                    <option value="Cacar Air">Varicella (Cacar Air)</option>
                    <option value="Influenza">Influenza</option>
                </select>

                <label for="dokter">Dokter :</label>
                <input type="text" name="dokter" id="dokter" required placeholder="dokter">

                <label for="status">Status :</label>
                <select name="status" id="status" required>
                    <option value="Sudah">Sudah Menerima Imunisasi</option>
                    <option value="Belum">Tidak Menerima Imunisasi</option>
                </select>

                <label for="keterangan">Keterangan :</label>
                <input type="text" name="keterangan" id="keterangan" required placeholder="keterangan">

                <div class="register-btn">
                    <input type="submit" value="Submit" name="submit" class="button button1">
                    <a href="../" type="button" class="button-cancel">cancel</a>
                </div>
            </form>
        </div>
        <?php include 'footer.php' ?>
    </div>
</body>

</html>