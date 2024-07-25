<!-- login.php -->
<link rel="stylesheet" href="src/css/style.css">

<body style="background-image: url('asset/cc.jpg')">
    <div class="container-login">
        <center>
            <h1>Login</h1>
        </center>
        <div class="box-login">
            <div class="img">
                <center>
                    <img src="asset/unipi.png" alt="image" style="width: 30%; height: auto;">
                </center>
            </div><br>
            <form action="modul/database/loginPros.php" method="POST">
                <div class="login-form" style="border-radius:10px 10px 0px 0px;">
                    <label for="username">Username :</label>
                    <input type="text" name="username" id="username" required placeholder="Username"> <br><br>
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="password" required placeholder="Password">
                </div>
                <div class="login-btn">
                    <input type="submit" value="Masuk" name="submit" class="button button1" style="width: 100%; margin: 0px; padding:5px;border-radius: 0px 0px 10px 10px;">
                </div>
            </form>
            <?php include 'modul/footer.php' ?>
        </div>
    </div>
</body>