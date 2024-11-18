<?php
include '../../config/koneksi.php'; // Pastikan jalur ini benar
date_default_timezone_set('Asia/Jakarta');

// Memeriksa apakah semua data POST tersedia
if (isset($_POST['id'], $_POST['nama'], $_POST['berat'], $_POST['tinggi'], $_POST['id_ortu'])) {
    // Ambil data dari POST
    $nik_balita = $_POST['id'];
    $id_ortu = $_POST['id_ortu'];
    $nama_balita = $_POST['nama'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];
    $tgl = date('Y-m-d'); // Format tanggal yang benar


    // Menyiapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO timbangan (nama_balita,nik_balita, tanggal,berat, tinggi_badan,id_orangtua) VALUES (?,?,?,?,?,?)";

    // Memastikan koneksi berhasil
    if ($koneksi) {
        if ($stmt = $koneksi->prepare($sql)) {
            // Mengikat parameter
            $stmt->bind_param("ssssss", $nama_balita, $nik_balita,  $tgl, $berat, $tinggi, $id_ortu);

            // Menjalankan pernyataan
            if ($stmt->execute()) {
                echo "<script>alert('Data Berhasil Diinput! Silahkan anda Login');</script>";
                echo "<script>window.location.href='../../index.php?id=" . htmlspecialchars($id_ortu) . "';</script>";
            } else {
                echo "Error executing statement: " . $stmt->error;
            }
            // Menutup pernyataan
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $koneksi->error;
        }
    } else {
        echo "Koneksi database gagal.";
    }

    // Menutup koneksi
    $koneksi->close();
} else {
    echo "Data tidak lengkap.";
    exit;
}
