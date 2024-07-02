<?php
// Include autoload.php dari PhpSpreadsheet
require '../../config/koneksi.php';

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
    // Nama tabel yang ingin Anda tambahkan kolomnya
    $tableName = $_POST["tableName"];

    // Query untuk menambahkan kolom Keterangan ke tabel
    $addColumnSql = "ALTER TABLE `$tableName` ADD `Keterangan` VARCHAR(255) NOT NULL DEFAULT '';";

    try {
        $pdo->exec($addColumnSql);
    } catch (PDOException $e) {
        die("Error adding column: " . $e->getMessage());
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
    header("Location: ../..//index.php");
    exit;
}
