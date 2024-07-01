<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/style.css">
    <title>Halaman Login</title>
</head>

<body>
    <h1>Login</h1>
    <div class="card-home">
        <div class="container-login">
            <div class="box-login">
                <div class="img">
                    <center>
                        <img src="asset/unipi.png" alt="image" style="width: 30%; height: auto;">
                    </center>
                </div><br>
                <form action="">
                    <div class="login-form">
                        <label for="username">Username :</label>
                        <input type="text" name="username" id="username" required placeholder="Username"> <br><br>
                        <label for="password">Password :</label>
                        <input type="password" name="password" id="password" required placeholder="Password">
                    </div>
                    <div class="login-btn">
                        <input type="submit" value="Masuk" name="submit" class="button button1">
                        <input type="submit" value="Daftar" name="submit" class="button button1">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include 'modul/footer.php' ?>

</html>