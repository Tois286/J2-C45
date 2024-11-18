<!-- login.php -->
<link rel="stylesheet" href="src/css/style.css">

<body style="
    background-image: url('asset/unpamG.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    height: 100vh;
    color: white; /* Optional: Warna teks agar kontras */
">
    <div class="container-login">
        <div class="box-login">
            <h1>Login</h1>
            <div class="img">
                <center>
                    <img src="asset/posyandu1.png" alt="image" style="width: 30%; height: auto;">
                </center>
            </div><br>
            <form action="modul/database/loginPros.php" method="POST">
                <div class="login-form" style="border-radius:10px 10px 0px 0px;">
                    <label for="username">Username :</label>
                    <input type="text" name="username" id="username" required placeholder="Username"><br>
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="password" required placeholder="Password">
                    <div class="login-btn">
                        <input type="submit" value="Masuk" name="submit" class="button button1" style="width: 100%; margin: 0px;">
                    </div>
                    <div class="login-btn">
                        <!-- <input type="submit" value="Daftar" name="submit" class="button button1" style="width: 100%; margin: 0px; "> -->
                        <a href="modul/tambah_Ortu.php" class="button button1" style="width: 100%; margin: 0px;"> Daftar</a>
                    </div>
                    <style>
                        .copy {
                            padding: 10px;
                        }

                        p {
                            font-weight: bold;
                        }
                    </style>

                </div>
            </form>
            <center>
                <footer class="copy" style="color:#007a8c;">
                    <p>Copy&#9400; | by</p>
                    <p>Universitas Pamulang Indonesia</p>
                </footer>
            </center>
        </div>
    </div>
</body>