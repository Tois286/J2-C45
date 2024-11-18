<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $id = $_POST['id']; // ID orang tua
    $nik_balita = mysqli_real_escape_string($koneksi, $_POST['nik_balita']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tgl = mysqli_real_escape_string($koneksi, $_POST['tgl']);
    $ct = mysqli_real_escape_string($koneksi, $_POST['ct']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tgl_imunisasi = mysqli_real_escape_string($koneksi, $_POST['tgl_imunisasi']);
    $tgl_daftar = mysqli_real_escape_string($koneksi, $_POST['tgl_daftar']);

    // Query untuk memperbarui data di tabel balita
    $sql_balita = "UPDATE balita SET 
                    nik_balita = '$nik_balita',
                    nama = '$nama',
                    tanggal_lahir = '$tgl',
                    jenis_kelamin = '$jenis_kelamin',
                    catatan_kesehatan = '$ct',
                    tanggal_imunisasi = '$tgl_imunisasi',
                    tgl_daftar = '$tgl_daftar'
                  WHERE id_orangtua = '$id'";

    // Eksekusi update di tabel balita
    if (!mysqli_query($koneksi, $sql_balita)) {
        die("Error updating balita: " . mysqli_error($koneksi));
    }

    // Query untuk memperbarui data di tabel imunisasi
    $sql_imunisasi = "UPDATE imunisasi SET 
                        nik_balita ='$nik_balita',
                        nama = '$nama'
                      WHERE id_orangtua = '$id'"; // Asumsi nik_balita digunakan sebagai foreign key

    // Eksekusi update di tabel imunisasi
    if (!mysqli_query($koneksi, $sql_imunisasi)) {
        die("Error updating imunisasi: " . mysqli_error($koneksi));
    }

    // Query untuk memperbarui data di tabel timbangan
    $sql_timbangan = "UPDATE timbangan SET 
                        nik_balita = '$nik_balita',
                        nama_balita = '$nama'
                      WHERE id_orangtua = '$id'"; // Asumsi nik_balita digunakan sebagai foreign key

    // Eksekusi update di tabel timbangan
    if (!mysqli_query($koneksi, $sql_timbangan)) {
        die("Error updating timbangan: " . mysqli_error($koneksi));
    }

    // Jika semua query berhasil
    echo "<script>alert('Data berhasil di Update');</script>";
    echo "<script>window.location.href='../../index.php';</script>";
} else {
    // Jika tidak ada data POST, redirect ke jadwal
    header('Location: ../jadwal.php');
    exit;
}
