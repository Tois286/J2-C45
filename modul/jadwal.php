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
                <h1>Buat Jadwal</h1>
            </center>
            <form action="database/i_Jadwal.php?table=<?php echo $table_name; ?>" method="POST" class="register-form">
                <label for="nama">Tema Kegiatan :</label>
                <input type="text" name="nama" id="nama" required placeholder="nama">

                <label for="tempat">Tempat Kegiatan :</label>
                <input type="text" name="tempat" id="tempat" required placeholder="tempat">

                <label for="tanggal">Tanggal Kegiatan :</label>
                <input type="date" name="tanggal" id="tanggal" required placeholder="tanggal"><br><br>

                <label for="peserta">Jenis Vaksin Imunisasi:</label>
                <select name="peserta" id="peserta" required>
                    <option value="Bacillus Calmette-Guérin">BCG</option>
                    <option value="Hepatitis B ">HBV</option>
                    <option value="Difteria, Pertusis, Tetanus">DPT</option>
                    <option value="Polio">IPV</option>
                    <option value="Campak, Gondong, Rubella">MMR</option>
                    <option value="Pneumokokus">PCV</option>
                    <option value="Cacar Air">Varicella (Cacar Air)</option>
                    <option value="Influenza">Influenza</option>
                </select>

                <label for="penyelengara">Penyelengara :</label>
                <input type="text" name="penyelengara" id="penyelengara" required placeholder="penyelengara">

                <label for="dokter">dokter :</label>
                <input type="text" name="dokter" id="dokter" required placeholder="dokter">


                <label for="sumberDaya">Sumber Daya :</label>
                <input type="text" name="sumberDaya" id="sumberDaya" required placeholder="sumberDaya">

                <label for="tujuan">Tujuan Kegiatan :</label>
                <input type="text" name="tujuan" id="tujuan" required placeholder="tujuan">

                <label for="noted">noted :</label>
                <input type="text" name="noted" id="noted" required placeholder="noted">

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