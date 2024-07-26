<?php
require '../../config/koneksi.php';

// Contoh penggunaan error_log di skrip PHP
$error_message = "Data tidak lengkap pada baris ke-" . $row_number;
error_log($error_message);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tableName"]) && isset($_POST["columns"])) {
    $tableName = $_POST["tableName"];
    $columns = $_POST["columns"];

    // Mendapatkan tanggal saat ini
    $tgl = date('Y-m-d');

    // Tentukan kolom yang akan diinput
    $allowedColumns = ['tgl_prediksi', 'NPM', 'NAMA', 'JENIS_KELAMIN', 'IPS1', 'IPS2', 'IPS3', 'IPS4'];

    // Filter kolom yang akan digunakan dari input
    $filteredColumns = array_intersect($columns, $allowedColumns);

    // Pastikan ada tepat 7 kolom yang valid
    if (count($filteredColumns) !== 7) {
        die("Harap pastikan ada tepat 7 kolom yang valid.");
    }

    // Tambahkan 'tgl_prediksi' ke dalam daftar kolom dan nilai
    $filteredColumns[] = 'tgl_prediksi'; // Tambahkan kolom tanggal
    $placeholders = array_map(function ($col) {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $col);
    }, $filteredColumns);

    // Prepare INSERT INTO SQL statement
    $insertSql = "INSERT INTO `$tableName` (" . implode(', ', array_map(function ($col) {
        return "`" . preg_replace('/[^a-zA-Z0-9_]/', '_', $col) . "`";
    }, $filteredColumns)) . ") VALUES (:" . implode(', :', $placeholders) . ");";

    $stmt = $pdo->prepare($insertSql);

    // Loop through posted data and insert into database
    $rowCount = count($_POST[$filteredColumns[0]]); // Assuming all columns have the same number of rows
    for ($i = 0; $i < $rowCount; $i++) {
        $data = [];
        foreach ($filteredColumns as $column) {
            $sanitizedColumn = preg_replace('/[^a-zA-Z0-9_]/', '_', $column);
            // Set data untuk kolom tanggal prediksi jika ada
            if ($column === 'tgl_prediksi') {
                $data[$sanitizedColumn] = $tgl; // Set tanggal prediksi
            } else {
                $data[$sanitizedColumn] = $_POST[$column][$i];
            }
        }

        try {
            $stmt->execute($data);
        } catch (PDOException $e) {
            die("Error inserting data: " . $e->getMessage());
        }
    }
    header("Location: ../../index.php");
    exit;
}
