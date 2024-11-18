<head>
    <link rel="stylesheet" href="../src/css/form.css">
</head>
<?php
// Contoh mendapatkan nama tabel dari parameter GET atau set default
$table_name = isset($_GET['table']) ? $_GET['table'] : 'default_table_name';
?>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Form Orang Tua Wali</h1>
            </center>
            <form action="database/tambahPros1.php?table=<?php echo $table_name; ?>" method="POST" class="register-form">
                <label for="namaAyah">Nama Ayah:</label>
                <input type="text" name="namaAyah" id="namaAyah" required placeholder="namaAyah">

                <label for="nikAyah">NIK Ayah:</label>
                <input type="text" name="nikAyah" id="nikAyah" required placeholder="nikAyah">

                <label for="namaIbu">Nama Ibu:</label>
                <input type="text" name="namaIbu" id="namaIbu" required placeholder="namaIbu">

                <label for="nikIbu">NIK Ibu:</label>
                <input type="text" name="nikIbu" id="nikIbu" required placeholder="nikIbu">

                <label for="alamat">Alamat Sekarang</label>
                <input type="text" name="alamat" id="alamat" required placeholder="alamat">

                <label for="notpln">No.tpln Sekarang</label>
                <input type="text" name="notpln" id="notpln" required placeholder="notpln">


                <!-- <label for="jenis_kelamin">Jenis Kelamin :</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required>
                    <option value="LAKI-LAKI">Laki-laki</option>
                    <option value="PEREMPUAN">Perempuan</option>
                </select> -->

                <div class="register-btn">
                    <input type="submit" value="Daftar" name="submit" class="button button1">
                    <a href="../" type="button" class="button-cancel">cancel</a>
                </div>
            </form>
        </div>
        <?php include 'footer.php' ?>
    </div>
</body>

</html>