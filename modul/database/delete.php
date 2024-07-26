<?php
include '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL untuk menghapus data
    $sql = "DELETE FROM r_dataprediksi WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {
        echo "User berhasil dihapus.";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    // Redirect kembali ke halaman utama atau halaman sebelumnya
    header("Location: ../../index.php");
    exit();
}
