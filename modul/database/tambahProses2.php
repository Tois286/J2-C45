<?php
include '../../config/koneksi.php'; // Pastikan jalur ini benar
date_default_timezone_set('Asia/Jakarta');

// Memeriksa apakah semua data POST tersedia
if (isset($_POST['id'], $_POST['nik_balita'], $_POST['nama'], $_POST['tgl'], $_POST['tanggal_p'], $_POST['jenis_kelamin'], $_POST['ct'])) {
    // Ambil data dari POST
    $id_ortu = $_POST['id'];
    $nik_anak = $_POST['nik_balita']; // Pastikan ini ada di form
    $nama_balita = $_POST['nama'];
    $tgl_lahir = $_POST['tgl'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $ct = $_POST['ct'];
    $tgl = $_POST['tanggal_p']; // Format tanggal yang benar
    $tgl_daftar = date('Y-m-d'); // Ubah format menjadi Y-m-d

    // Debug: Cek nilai yang diterima
    // var_dump($_POST); // Hapus atau ganti dengan logging jika tidak diperlukan

    // Menyiapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO balita (nik_balita, nama, tanggal_lahir, jenis_kelamin, catatan_kesehatan, tanggal_imunisasi, tgl_daftar, id_orangtua) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Memastikan koneksi berhasil
    if ($koneksi) {
        if ($stmt = $koneksi->prepare($sql)) {
            // Mengikat parameter
            $stmt->bind_param("ssssssss", $nik_anak, $nama_balita, $tgl_lahir, $jenis_kelamin, $ct, $tgl, $tgl_daftar, $id_ortu);

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
