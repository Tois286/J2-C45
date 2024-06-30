<?php
// Include autoload.php dari PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Database configuration
require '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];
    $fileSize = $_FILES['excelFile']['size'];
    $fileType = $_FILES['excelFile']['type'];

    // Pastikan file yang diunggah adalah file Excel
    $allowedExtensions = array("xlsx", "xls");
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        die("Error: Hanya file Excel (.xlsx, .xls) yang diizinkan.");
    }

    // Pindahkan file yang diunggah ke lokasi yang diinginkan
    $uploadDir = './uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Membuat direktori uploads jika belum ada
    }
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {

        // Load file Excel yang diunggah menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($destPath);
        $sheet = $spreadsheet->getActiveSheet();

        // Dapatkan kolom dari baris pertama (header)
        $columns = [];
        $firstRow = $sheet->getRowIterator()->current();
        $cellIterator = $firstRow->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE);

        foreach ($cellIterator as $cell) {
            // Sanitasi nama kolom
            $columnName = $cell->getValue();
            $sanitizedColumnName = preg_replace('/[^a-zA-Z0-9_]/', '_', $columnName); // Ganti karakter yang tidak valid dengan _

            // Pastikan nama kolom yang divalidasi tidak kosong
            if (!empty($sanitizedColumnName)) {
                $columns[] = $sanitizedColumnName;
            } else {
                die("Error: Nama kolom tidak valid.");
            }
        }

        // Sanitize file name to use as table name
        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '_', pathinfo($fileName, PATHINFO_FILENAME));

        // Construct CREATE TABLE SQL statement
        $createTableSql = "CREATE TABLE IF NOT EXISTS `$tableName` (id INT AUTO_INCREMENT PRIMARY KEY, ";
        foreach ($columns as $column) {
            $createTableSql .= "`$column` TEXT, "; // Gunakan nama kolom yang sudah divalidasi
        }
        $createTableSql = rtrim($createTableSql, ", ") . ");";

        try {
            $pdo->exec($createTableSql);
        } catch (PDOException $e) {
            die("Error creating table: " . $e->getMessage());
        }

        // Kemas data dari file Excel dalam bentuk form
        echo "<form class='excel-form' method='post' action='uploadPush.php'>";
        echo "<input type='hidden' name='tableName' value='$tableName'>";

        foreach ($columns as $column) {
            echo "<input type='hidden' name='columns[]' value='$column'>";
        }

        $rowCounter = 0;
        foreach ($sheet->getRowIterator() as $row) {
            // Skip the first row (header)
            if ($rowCounter === 0) {
                $rowCounter++;
                continue;
            }

            echo "<div class='form-row'>";
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            $columnCounter = 0;
            $columnProcessed = 0; // Counter for processed columns

            // Limit the number of columns to iterate through (assuming $columns is set accordingly)
            $maxColumns = min(9, count($columns)); // Limit to a maximum of 9 columns or the number of columns available

            foreach ($cellIterator as $cell) {
                if ($columnProcessed >= $maxColumns) {
                    break; // Stop iterating if we've processed the maximum allowed columns
                }

                $value = $cell->getValue();

                // Check if the cell has a value
                if (!empty($value)) {
                    $label = $columns[$columnCounter];
                    $id = "value" . $rowCounter; // Generate ID based on row number
                    echo "<div class='form-group'>";
                    echo "<label class='form-label'>{$label}</label>";
                    echo "<input type='text' class='form-input' name='{$label}[]' id='{$id}' value='{$value}'>";
                    echo "</div>";
                    $columnProcessed++;
                }

                $columnCounter++;
            }

            echo "</div>";
            $rowCounter++;
        }

        echo "<div class='card-alert'>";
        echo "<h1 class='coment'>File Excel berhasil di baca!</h1>";
        echo "<h3 class='coment'>Check Lagi File Kamu Sebelum Lanjut!</h3>";
        echo "<center>";
        echo "<button type='cancel' class='cancel-button' onclick='return confirmCancel()'>Cancel</button>";
        echo "<button type='submit' class='submit-button' onclick='return confirmSubmit()'>Lanjut</button>";
        echo "</center>";
        echo "</div>";
        echo "</form>";

        // Hapus file yang telah diunggah (opsional)
        unlink($destPath);
    } else {
        echo "Error: Gagal mengunggah file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            padding-top: 10%;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .excel-form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-row {
            display: flex;
            gap: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 300px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .result-section {
            margin-top: 20px;
        }

        .submit-button {
            padding: 10px 20px;
            background-color: #219ebc;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            align-self: flex-start;
            margin-right: 10px;
            /* Add space to the right of the submit button */
        }

        .cancel-button {
            padding: 10px 20px;
            background-color: #BC2121;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            align-self: flex-start;
        }

        .submit-button:hover {
            background-color: #117B96;
        }

        .cancel-button:hover {
            background-color: #961717;
        }

        .card-alert {
            border-radius: 10px;
            width: 60%;
            height: 100px;
            background-color: #219DBCB0;
            position: fixed;
            top: 0;
            /* Memposisikan bagian atas .card-alert tepat di tengah vertikal */
            left: 50%;
            /* Memposisikan bagian kiri .card-alert tepat di tengah horizontal */
            transform: translate(-50%, -50%);
            /* Menggeser .card-alert ke tengah dengan tepat */
            padding-top: 10%;
            z-index: 999;
            /* Pastikan .card-alert di atas konten lain */
        }

        .coment {
            margin: auto;
            text-align: center;
        }

        .submit-button {
            bottom: 20px;
            /* Atur jarak dari bawah layar sesuai kebutuhan */
            /* right: 20px; */
            /* Atur jarak dari sisi kanan layar sesuai kebutuhan */
            z-index: 999;
            /* Pastikan tombol di atas konten lainnya */
            padding: 10px 20px;
            background-color: #219ebc;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #117B96;
        }
    </style>
    <script>
        function confirmSubmit() {
            return confirm("Anda yakin ingin mengirim data?");
        }

        function confirmCancel() {
            if (confirm("Anda yakin ingin membatalkan?")) {
                window.location.href = '../../index.php';
            }
            return false; // Prevent the default form submission behavior
        }
    </script>
</head>