<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id='$id'");
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
            <form action="database/updateJadwal.php" method="POST" class="register-form">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <label for="nama_kegiatan">Nama Kegiatan:</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?php echo $data['nama_kegiatan']; ?>" required>

                <label for="tempat">Tempat:</label>
                <input type="text" id="tempat" name="tempat" value="<?php echo $data['tempat']; ?>" required>

                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $data['tanggal']; ?>" required>

                <label for="peserta">Peserta:</label>
                <input type="text" id="peserta" name="peserta" value="<?php echo $data['peserta']; ?>" required>

                <label for="penyelengara">Penyelengara:</label>
                <input type="text" id="penyelengara" name="penyelengara" value="<?php echo $data['penyelengara']; ?>" required>

                <label for="dokter">Dokter:</label>
                <input type="text" id="dokter" name="dokter" value="<?php echo $data['dokter']; ?>" required>

                <label for="sumber_daya">Sumber Daya:</label>
                <input type="text" id="sumber_daya" name="sumber_daya" value="<?php echo $data['sumber_daya']; ?>" required>

                <label for="keterangan">Tujuan:</label>
                <input type="text" id="keterangan" name="keterangan" value="<?php echo $data['keterangan']; ?>" required>

                <label for="noted">Noted:</label>
                <input type="text" id="noted" name="noted" value="<?php echo $data['noted']; ?>" required>

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