<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id = $_POST['id'];
    $tgl = mysqli_real_escape_string($koneksi, $_POST['tgl']);
    $berat = mysqli_real_escape_string($koneksi, $_POST['berat']);
    $tinggi = mysqli_real_escape_string($koneksi, $_POST['tinggi']);

    // Query untuk memperbarui data
    $sql = "UPDATE timbangan SET 
                tanggal = '$tgl',
                berat = '$berat',
                tinggi_badan = '$tinggi'
            WHERE nik_balita = '$id'";

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
