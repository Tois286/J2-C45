<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id = $_POST['id'];
    $namaAyah = mysqli_real_escape_string($koneksi, $_POST['namaAyah']);
    $nikAyah = mysqli_real_escape_string($koneksi, $_POST['nikAyah']);
    $namaIbu = mysqli_real_escape_string($koneksi, $_POST['namaIbu']);
    $nikIbu = mysqli_real_escape_string($koneksi, $_POST['nikIbu']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $notpln = mysqli_real_escape_string($koneksi, $_POST['notpln']);

    // Query untuk memperbarui data
    $sql = "UPDATE dataorangtua SET 
                nama_ayah = '$namaAyah',
                nik_ayah = '$nikAyah',
                nama_ibu = '$namaIbu',
                nik_ibu = '$nikIbu',
                alamat = '$alamat',
                telepon = '$notpln'
            WHERE id_orangtua = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data berhasil di Update');</script>";
        // Jika update berhasil, redirect ke halaman jadwal
        echo "<script>window.location.href='../../index.php';</script>";
    } else {
        // Jika terjadi kesalahan, redirect dengan pesan error
        header('Location: ../jadwal.php?status=error');
        exit;
    }
} else {
    // Jika tidak ada data POST, redirect ke jadwal
    header('Location: ../jadwal.php');
    exit;
}
