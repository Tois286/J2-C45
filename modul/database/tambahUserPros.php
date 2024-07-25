<?php
include '../../config/koneksi.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];

$role = 'user';

$stmt = $koneksi->prepare("INSERT INTO users (nama,username,password,role)VALUES(?,?,?,?)");

if ($stmt == false) {
    die('tidak terkirim') . htmlspecialchars($koneksi->error);
}

$stmt->bind_param('ssss', $nama, $username, $password, $role);

if ($stmt->execute()) {
    header('Location: ../../index.php');
    exit();
} else {
    echo 'data tidak tersimpan' . htmlspecialchars($stmt->error);
}

$stmt->close();
$koneksi->close();
