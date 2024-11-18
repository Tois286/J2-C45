<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id = $_POST['id'];
    $nama_kegiatan = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $tempat = mysqli_real_escape_string($koneksi, $_POST['tempat']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $peserta = mysqli_real_escape_string($koneksi, $_POST['peserta']);
    $penyelengara = mysqli_real_escape_string($koneksi, $_POST['penyelengara']);
    $dokter = mysqli_real_escape_string($koneksi, $_POST['dokter']);
    $sumber_daya = mysqli_real_escape_string($koneksi, $_POST['sumber_daya']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $noted = mysqli_real_escape_string($koneksi, $_POST['noted']);

    // Query untuk memperbarui data
    $sql = "UPDATE jadwal SET 
                nama_kegiatan = '$nama_kegiatan',
                tempat = '$tempat',
                tanggal = '$tanggal',
                peserta = '$peserta',
                penyelengara = '$penyelengara',
                dokter = '$dokter',
                sumber_daya = '$sumber_daya',
                keterangan = '$keterangan',
                noted = '$noted'
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data berhasil di Update');</script>";
        // Jika update berhasil, redirect ke halaman jadwal
        echo "<script>window.location.href='../../index.php';</script>";
    } else {
        // Jika terjadi kesalahan, redirect dengan pesan error
        header('Location: ../jadwal.php?status=error');
    }
} else {
    // Jika tidak ada data POST, redirect ke jadwal
    header('Location: ../jadwal.php');
}
