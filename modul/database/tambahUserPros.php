<?php
include '../../config/koneksi.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$id_ortu = $_POST['id'];

$role = 'user';

$stmt = $koneksi->prepare("INSERT INTO users (nama,username,password,role,id_orangtua)VALUES(?,?,?,?,?)");

if ($stmt == false) {
    die('tidak terkirim') . htmlspecialchars($koneksi->error);
}

$stmt->bind_param('sssss', $nama, $username, $password, $role, $id_ortu);

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil Diinput! Silahkan Lanjut isi Form Balita');</script>";
    echo "<script>window.location.href='../tambah_balita.php?id=" . $id_ortu . "';</script>";
    exit();
} else {
    echo 'data tidak tersimpan' . htmlspecialchars($stmt->error);
}

$stmt->close();
$koneksi->close();
