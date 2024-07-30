<?php
// Include database connection file
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Retrieve form data
    $tgl = date('Y-m-d');
    $nama = $_POST['nama'] ?? null;
    $npm = $_POST['npm'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $ips1 = $_POST['ips1'] ?? null;
    $ips2 = $_POST['ips2'] ?? null;
    $ips3 = $_POST['ips3'] ?? null;
    $ips4 = $_POST['ips4'] ?? null;

    // Fungsi untuk menentukan kategori berdasarkan nilai IPS
    //konversi nilai ips1
    if ($ips1 < 2.4) {
        $ips1_prediksi = 'KURANG';
    } elseif ($ips1 >= 2.5 && $ips1 < 3.0) {
        $ips1_prediksi = 'CUKUP';
    } elseif ($ips1 >= 3.0 && $ips1 < 3.5) {
        $ips1_prediksi = 'BAIK';
    } elseif ($ips1 >= 3.5) {
        $ips1_prediksi = 'SANGAT BAIK';
    }
    //konversi nilai ips2
    if ($ips2 < 2.4) {
        $ips2_prediksi = 'KURANG';
    } elseif ($ips2 >= 2.5 && $ips2 < 3.0) {
        $ips2_prediksi = 'CUKUP';
    } elseif ($ips2 >= 3.0 && $ips2 < 3.5) {
        $ips2_prediksi = 'BAIK';
    } elseif ($ips2 >= 3.5) {
        $ips2_prediksi = 'SANGAT BAIK';
    }
    //konversi nilai ips3
    if ($ips3 < 2.4) {
        $ips3_prediksi = 'KURANG';
    } elseif ($ips3 >= 2.5 && $ips3 < 3.0) {
        $ips3_prediksi = 'CUKUP';
    } elseif ($ips3 >= 3.0 && $ips3 < 3.5) {
        $ips3_prediksi = 'BAIK';
    } elseif ($ips3 >= 3.5) {
        $ips3_prediksi = 'SANGAT BAIK';
    }
    //konversi nilai ips4
    if ($ips4 < 2.4) {
        $ips4_prediksi = 'KURANG';
    } elseif ($ips4 >= 2.5 && $ips4 < 3.0) {
        $ips4_prediksi = 'CUKUP';
    } elseif ($ips4 >= 3.0 && $ips4 < 3.5) {
        $ips4_prediksi = 'BAIK';
    } elseif ($ips4 >= 3.5) {
        $ips4_prediksi = 'SANGAT BAIK';
    }

    // Validate form data
    if ($nama && $npm && $jenis_kelamin && $ips1 && $ips2 && $ips3 && $ips4) {
        // Check if NPM already exists
        $checkNpmSql = "SELECT COUNT(*) FROM $table_name WHERE npm = :npm";
        $stmt = $pdo->prepare($checkNpmSql);
        $stmt->bindParam(':npm', $npm);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // NPM already exists
            echo "<script>alert('NPM ini sudah dimiliki seseorang.'); window.history.back();</script>";
        } else {
            // Prepare an SQL statement
            $sql = "INSERT INTO $table_name (nama, npm, jenis_kelamin, ips1, ips2, ips3, ips4) 
                    VALUES (:nama, :npm, :jenis_kelamin, :ips1, :ips2, :ips3, :ips4)";

            // Execute the SQL statement
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':npm', $npm);
            $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
            $stmt->bindParam(':ips1', $ips1_prediksi);
            $stmt->bindParam(':ips2', $ips2_prediksi);
            $stmt->bindParam(':ips3', $ips3_prediksi);
            $stmt->bindParam(':ips4', $ips4_prediksi);

            // Execute and check if successful
            if ($stmt->execute()) {
                // Redirect or notify success
                echo "<script>alert('Data berhasil ditambahkan.'); window.location.href = '../../index.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan data.'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Metode request tidak valid atau parameter table tidak ada.'); window.history.back();</script>";
}
