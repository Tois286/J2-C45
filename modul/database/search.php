<?php
include '../../config/koneksi.php';

$nik_balita = $_GET['nik_balita'];
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$sql_query = "SELECT * FROM $nik_balita";
if (!empty($search_query)) {
    $fields_result = mysqli_query($koneksi, "SHOW COLUMNS FROM $nik_balita");
    $conditions = [];
    while ($field = mysqli_fetch_assoc($fields_result)) {
        $field_name = $field['Field'];
        $conditions[] = "$field_name LIKE '%$search_query%'";
    }
    $sql_query .= " WHERE " . implode(' OR ', $conditions);
}

$sql = mysqli_query($koneksi, $sql_query);

if ($sql && mysqli_num_rows($sql) > 0) {
    echo '<table style="width: 100%; border-collapse: collapse; font-size: 14px;">';
    echo '<thead>';
    echo '<tr>';

    // Mengambil nama kolom dari hasil query
    $field_info = mysqli_fetch_fields($sql);
    foreach ($field_info as $val) {
        echo '<th style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($val->name) . '</th>';
    }

    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Edit</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Prediksi</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Delete</th>";
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Menampilkan data dari tabel
    while ($row = mysqli_fetch_assoc($sql)) {
        echo '<tr>';
        foreach ($row as $column) {
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($column) . '</td>';
        }

        echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/edit.php?table=$nik_balita&id=" . $row['id'] . "'>Edit</a></td>";
        echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='c45/prediksiOne.php?table=$nik_balita&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Prediksi</a></td>";
        echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";

        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'Tidak ada data.';
}

mysqli_close($koneksi);
