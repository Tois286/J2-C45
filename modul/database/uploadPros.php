<?php
// Include autoload.php dari PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        echo "<h3>File Excel berhasil diunggah!</h3>";

        // Load file Excel yang diunggah menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($destPath);
        $sheet = $spreadsheet->getActiveSheet();

        // Tampilkan data dari Excel dalam tabel
        echo "<table class='table'>";
        $rowNum = 1; // Nomor baris untuk tabel
        foreach ($sheet->getRowIterator() as $row) {
            echo "<tr>";
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);

            // Ambil nilai sel dan tambahkan ke dalam <td> di tabel
            foreach ($cellIterator as $cell) {
                echo "<td>" . $cell->getValue() . "</td>";
            }
            echo "</tr>";

            $rowNum++;
        }
        echo "</table>";

        // Hapus file yang telah diunggah (opsional)
        unlink($destPath);
    } else {
        echo "Error: Gagal mengunggah file.";
    }
}
