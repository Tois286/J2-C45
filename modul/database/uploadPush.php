<?php
// Include autoload.php dari PhpSpreadsheet
require '../../config/koneksi.php';
// Contoh penggunaan error_log di skrip PHP
$error_message = "Data tidak lengkap pada baris ke-" . $row_number;
error_log($error_message);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tableName"]) && isset($_POST["columns"])) {
    $tableName = $_POST["tableName"];
    $columns = $_POST["columns"];

    // Prepare INSERT INTO SQL statement
    $insertSql = "INSERT INTO `$tableName` (" . implode(', ', array_map(function ($col) {
        return "`" . preg_replace('/[^a-zA-Z0-9_]/', '_', $col) . "`";
    }, $columns)) . ") VALUES (:" . implode(', :', array_map(function ($col) {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $col);
    }, $columns)) . ");";

    $stmt = $pdo->prepare($insertSql);

    // Cek apakah kolom 'KETERANGAN' ada di tabel
    $checkColumnSql = "SHOW COLUMNS FROM `$tableName` LIKE 'KETERANGAN';";
    $columnExists = $pdo->query($checkColumnSql)->fetch();

    if (!$columnExists) {
        // Jika kolom 'KETERANGAN' tidak ada, tambahkan kolom 'Prediksi'
        $addColumnSql = "ALTER TABLE `$tableName` ADD `PREDIKSI` TEXT NOT NULL DEFAULT '';";
        try {
            $pdo->exec($addColumnSql);
        } catch (PDOException $e) {
            die("Error adding column: " . $e->getMessage());
        }
    }

    // Loop through posted data and insert into database
    $rowCount = count($_POST[$columns[0]]); // Assuming all columns have the same number of rows
    for ($i = 0; $i < $rowCount; $i++) {
        $data = [];
        foreach ($columns as $column) {
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
