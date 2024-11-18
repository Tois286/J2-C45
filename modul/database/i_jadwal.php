<?php
// Include database connection file
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $tempat = $koneksi->real_escape_string($_POST['tempat']);
    $tanggal = $koneksi->real_escape_string($_POST['tanggal']);
    $peserta = $koneksi->real_escape_string($_POST['peserta']);
    $penyelengara = $koneksi->real_escape_string($_POST['penyelengara']);
    $dokter = $koneksi->real_escape_string($_POST['dokter']);
    $sumberDaya = $koneksi->real_escape_string($_POST['sumberDaya']);
    $tujuan = $koneksi->real_escape_string($_POST['tujuan']);
    $noted = $koneksi->real_escape_string($_POST['noted']);
    // Siapkan SQL untuk menyimpan data
    $sql = "INSERT INTO jadwal (nama_kegiatan, tempat, tanggal, peserta, penyelengara,dokter, sumber_daya,keterangan,noted)
            VALUES ('$nama', '$tempat', '$tanggal', '$peserta', '$penyelengara','$dokter', '$sumberDaya','$tujuan','$noted')";

    // Eksekusi query
    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil di Update');</script>";
        echo "<script>window.location.href='../../index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
    $koneksi->close();
}
