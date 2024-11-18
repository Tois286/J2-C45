<?php
// Include database connection file
include '../../config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $id = $koneksi->real_escape_string($_POST['id']);
    $id_ortu = $koneksi->real_escape_string($_POST['id_ortu']);
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $vaksin = $koneksi->real_escape_string($_POST['vaksin']);
    $dokter = $koneksi->real_escape_string($_POST['dokter']);
    $status = $koneksi->real_escape_string($_POST['status']);
    $keterangan = $koneksi->real_escape_string($_POST['keterangan']);

    $tanggal = date('Y-m-d'); // Format tanggal yang benar

    // Siapkan SQL untuk menyimpan data
    $sql = "INSERT INTO imunisasi (nik_balita,nama, jenis_imunisasi, tanggal_imunisasi, dokter, status, keterangan,id_orangtua)
            VALUES ('$id','$nama', '$vaksin', '$tanggal','$dokter', '$status','$keterangan','$id_ortu')";

    // Eksekusi query
    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil di Update');</script>";
        echo "<script>window.location.href='../../index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
    $koneksi->close();
}
