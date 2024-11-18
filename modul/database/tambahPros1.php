<?php
include '../../config/koneksi.php';

// Fungsi untuk mendapatkan ID ortu berikutnya
function getNextId($koneksi)
{
    $prefix = 'OTW';
    $query = "SELECT MAX(id_orangtua) AS last_id FROM dataorangtua WHERE id_orangtua LIKE '$prefix%'";
    $result = $koneksi->query($query);
    $row = $result->fetch_assoc();

    if ($row['last_id']) {
        // Mengambil angka terakhir dari ID
        $lastId = substr($row['last_id'], strlen($prefix)); // Menghapus prefix
        $nextId = intval($lastId) + 1; // Menambah satu
    } else {
        // Jika belum ada data, mulai dari 1
        $nextId = 1;
    }

    // Membuat ID baru dengan format yang diinginkan
    return $prefix . str_pad($nextId, 3, '0', STR_PAD_LEFT); // Misalnya OTW001
}

// Mendapatkan ID ortu baru
$id_ortu = getNextId($koneksi);

// Mengambil data dari POST
$namaAyah = $_POST['namaAyah'];
$namaIbu = $_POST['namaIbu'];
$nikAyah = $_POST['nikAyah'];
$nikIbu = $_POST['nikIbu'];
$notlpn = $_POST['notpln'];
$alamat = $_POST['alamat'];

// Menyiapkan pernyataan SQL untuk menyimpan data
$sql = "INSERT INTO dataorangtua (id_orangtua, nama_ayah, nama_ibu, nik_ayah, nik_ibu, alamat, telepon) VALUES (?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $koneksi->prepare($sql)) {
    // Mengikat parameter
    $stmt->bind_param("sssssss", $id_ortu, $namaAyah, $namaIbu, $nikAyah, $nikIbu, $alamat, $notlpn);

    // Menjalankan pernyataan
    if ($stmt->execute()) {
        echo "<script>alert('Data Berhasil Diinput! Silahkan Lanjut isi Form Akun');</script>";
        echo "<script>window.location.href='../tambahUser.php?id=" . $id_ortu . "';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    // Menutup pernyataan
    $stmt->close();
} else {
    echo "Error: " . $koneksi->error;
}

// Menutup koneksi
$koneksi->close();
