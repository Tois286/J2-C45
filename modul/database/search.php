<?php
include '../../config/koneksi.php';

$table_name = $_GET['table'];
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$sql_query = "SELECT * FROM $table_name";
if (!empty($search_query)) {
    $fields_result = mysqli_query($koneksi, "SHOW COLUMNS FROM $table_name");
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

        echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/edit.php?table=$table_name&id=" . $row['id'] . "'>Edit</a></td>";
        echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";

        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'Tidak ada data.';
}

mysqli_close($koneksi);
