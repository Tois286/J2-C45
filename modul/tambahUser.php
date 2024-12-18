<style>
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

    .register-form input[type="password"],
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
// Ambil ID dari URL
$id_ortu = isset($_GET['id']) ? $_GET['id'] : '';
?>

<body>
    <div class="container-register">
        <div class="box-register">
            <center>
                <h1>Buat Akun</h1>
            </center>
            <form action="database/tambahUserPros.php?table=<?php echo htmlspecialchars($id_ortu); ?>" method="POST" class="register-form">
                <label for="id">ID Orang Tua Wali :</label>
                <input type="text" name="id" id="id" required placeholder="id" value="<?php echo htmlspecialchars($id_ortu); ?>">

                <label for="nama">Nama :</label>
                <input type="text" name="nama" id="nama" required placeholder="Nama">

                <label for="username">Username :</label>
                <input type="text" name="username" id="username" required placeholder="username">

                <label for="password">Password :</label>
                <input type="password" name="password" id="password" required placeholder="password">

                <div class="register-btn">
                    <input type="submit" value="Lanjutkan" name="submit" class="button button1">
                </div>
            </form>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>