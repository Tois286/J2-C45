<?php
// Include database connection file
include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Retrieve form data
    $nama = $_POST['nama'] ?? null;
    $npm = $_POST['npm'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $ips1 = $_POST['ips1'] ?? null;
    $ips2 = $_POST['ips2'] ?? null;
    $ips3 = $_POST['ips3'] ?? null;
    $ips4 = $_POST['ips4'] ?? null;

    // Validate form data
    if ($nama && $npm && $jenis_kelamin && $ips1 && $ips2 && $ips3 && $ips4) {
        // Prepare an SQL statement
        $sql = "INSERT INTO $table_name (nama, npm, jenis_kelamin, ips1, ips2, ips3, ips4) 
                VALUES (:nama, :npm, :jenis_kelamin, :ips1, :ips2, :ips3, :ips4)";

        // Execute the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':npm', $npm);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':ips1', $ips1);
        $stmt->bindParam(':ips2', $ips2);
        $stmt->bindParam(':ips3', $ips3);
        $stmt->bindParam(':ips4', $ips4);

        // Execute and check if successful
        if ($stmt->execute()) {
            header('Location: ../index.php');
            exit();
        } else {
            echo "Terjadi kesalahan saat menambahkan data.";
        }
    } else {
        echo "Semua kolom harus diisi!";
    }
} else {
    echo "Metode request tidak valid atau parameter table tidak ada.";
}
