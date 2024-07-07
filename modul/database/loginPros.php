<?php
session_start();
include "../../config/koneksi.php";
$username =$_POST['username'];
$password =$_POST['password'];

// var_dump($koneksi);
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

$result = mysqli_query($koneksi, $sql);
if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_row($result)) {
    $_SESSION['id'] =  $row[0];
    $_SESSION['username'] = $row[1];
    $_SESSION['password'] = $password;
      }
      mysqli_free_result($result);
    echo "<script>alert('Anda Berhasil Login')</script>";
    $_SESSION['login_status'] = true;
    echo "<meta http-equiv='refresh' content='0; URL=../../index.php'>";
}else{
// header("Location: ../../index.php");
    echo "<script>alert('Anda Tidak Berhasil Login');</script>";
    $_SESSION['login_status'] = false;
    echo "<meta http-equiv='refresh' content='0; URL=../../index.php'>";
}
?>
