<?php
include '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data orang tua dan data terkait
    $sqlDelete = "
        DELETE d, b, i, t, u
        FROM dataorangtua d
        LEFT JOIN balita b ON d.id_orangtua = b.id_orangtua
        LEFT JOIN users u ON d.id_orangtua = u.id_orangtua
        LEFT JOIN imunisasi i ON b.nik_balita = i.nik_balita
        LEFT JOIN timbangan t ON b.nik_balita = t.nik_balita
        WHERE d.id_orangtua = ?";

    // Persiapkan statement
    $stmt = $koneksi->prepare($sqlDelete);

    // Ubah 's' untuk string
    $stmt->bind_param("s", $id);

    // Eksekusi dan periksa hasil
    if ($stmt->execute()) {
        header("Location: ../../index.php");
        exit(); // Pastikan untuk menghentikan script setelah redirect
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$koneksi->close();
