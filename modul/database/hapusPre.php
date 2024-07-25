<?php
// Koneksi ke database
include '../../config/koneksi.php';

// Nama tabel yang ingin dihapus datanya
$table_name = 'r_dataprediksi';

// Perintah SQL untuk menghapus seluruh data di tabel
$sql = "TRUNCATE TABLE $table_name";

// Menjalankan query
if (mysqli_query($koneksi, $sql)) {
    echo "<script>alert('Data berhasil di Update');</script>";
    echo "<script>window.location.href='../../index.php';</script>";
} else {
    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
}

// Menutup koneksi
mysqli_close($koneksi);
