<style>
    /* Gaya umum untuk seluruh halaman */
    body {
        background-image: url('../asset/cc.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Container utama untuk pendaftaran */
    .container-register {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 400px;
        max-width: 100%;
    }

    /* Gaya untuk judul pendaftaran */
    .container-register h1 {
        margin: 0;
        padding: 0;
        font-size: 24px;
        color: #333;
    }

    /* Gaya untuk form pendaftaran */
    .register-form {
        margin-top: 20px;
        max-height: 60vh;
        /* Tentukan tinggi maksimum yang Anda inginkan */
        overflow-y: auto;
        /* Aktifkan scrolling vertikal jika konten terlalu panjang */
    }

    /* Gaya untuk label dan input */
    .register-form label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }

    .register-form input[type="text"],
    .register-form select {
        width: calc(98% - 20px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Gaya untuk tombol */
    .register-btn {
        text-align: center;
    }

    .register-btn input[type="submit"] {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin: 5px;
        transition: background-color 0.3s;
    }

    .register-btn input[type="submit"]:hover {
        background-color: #45a049;
    }

    .button-cancel {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #AF4C4C;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin: 5px;
        transition: background-color 0.3s;
        text-decoration: none;
    }

    .button-cancel:hover {
        background-color: #A11111;
    }
</style>
<?php
// Contoh mendapatkan nama tabel dari parameter GET atau set default
$table_name = isset($_GET['table']) ? $_GET['table'] : 'default_table_name';
?>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Daftarkan</h1>
            </center>
            <form action="database/tambahPros.php?table=<?php echo $table_name; ?>" method="POST" class="register-form">
                <label for="nama">Nama :</label>
                <input type="text" name="nama" id="nama" required placeholder="Nama">

                <label for="npm">NPM :</label>
                <input type="text" name="npm" id="npm" required placeholder="NPM">

                <label for="jenis_kelamin">Jenis Kelamin :</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required>
                    <option value="LAKI-LAKI">Laki-laki</option>
                    <option value="PEREMPUAN">Perempuan</option>
                </select>

                <label for="ips1">IPS1 :</label>
                <input type="text" name="ips1" id="ips1" required placeholder="IPS1" pattern="\d+(\.\d{1,2})?" title="Masukkan angka desimal dengan titik sebagai pemisah desimal">

                <label for="ips2">IPS2 :</label>
                <input type="text" name="ips2" id="ips2" required placeholder="IPS2" pattern="\d+(\.\d{1,2})?" title="Masukkan angka desimal dengan titik sebagai pemisah desimal">

                <label for="ips3">IPS3 :</label>
                <input type="text" name="ips3" id="ips3" required placeholder="IPS3" pattern="\d+(\.\d{1,2})?" title="Masukkan angka desimal dengan titik sebagai pemisah desimal">

                <label for="ips4">IPS4 :</label>
                <input type="text" name="ips4" id="ips4" required placeholder="IPS4" pattern="\d+(\.\d{1,2})?" title="Masukkan angka desimal dengan titik sebagai pemisah desimal">


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