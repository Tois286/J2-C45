<?php
// Include autoload.php dari PhpSpreadsheet
require '../../config/koneksi.php';
// Contoh penggunaan error_log di skrip PHP
$error_message = "Data tidak lengkap pada baris ke-" . $row_number;
error_log($error_message);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tableName"]) && isset($_POST["columns"])) {
    $tableName = $_POST["tableName"];
    $columns = $_POST["columns"];

    // Tentukan kolom yang akan diinput
    $allowedColumns = ['NPM', 'NAMA', 'JENIS_KELAMIN', 'IPS1', 'IPS2', 'IPS3', 'IPS4']; // Ganti dengan nama kolom yang sesuai

    // Filter kolom yang akan digunakan dari input
    $filteredColumns = array_intersect($columns, $allowedColumns);

    if (count($filteredColumns) !== 7) {
        die("Harap pastikan ada tepat 7 kolom yang valid.");
    }

    // Prepare INSERT INTO SQL statement
    $insertSql = "INSERT INTO `$tableName` (" . implode(', ', array_map(function ($col) {
        return "`" . preg_replace('/[^a-zA-Z0-9_]/', '_', $col) . "`";
    }, $filteredColumns)) . ") VALUES (:" . implode(', :', array_map(function ($col) {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $col);
    }, $filteredColumns)) . ");";

    $stmt = $pdo->prepare($insertSql);

    // Loop through posted data and insert into database
    $rowCount = count($_POST[$filteredColumns[0]]); // Assuming all columns have the same number of rows
    for ($i = 0; $i < $rowCount; $i++) {
        $data = [];
        foreach ($filteredColumns as $column) {
            $sanitizedColumn = preg_replace('/[^a-zA-Z0-9_]/', '_', $column);
            $data[$sanitizedColumn] = $_POST[$column][$i];
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
