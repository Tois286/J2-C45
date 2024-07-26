<style>
    .form-search {
        float: right;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="submit"] {
        padding: 8px;
        margin-right: 10px;
    }
</style>
<h1>Prediksi</h1>
<div class="card-home">
    <div class="upload">
        <form action="modul/database/prediksiUpload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="excelFile" accept=".xlsx, .xls">
            <button type="submit" class="button button1">Upload</button>
        </form>
    </div>
</div>
<div class="card-home">
    <div class="card_tree">
        <div class="table-container">
            <?php
            include 'config/koneksi.php';
            $table_name = 'r_dataprediksi';
            $search_query = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

            // Cek apakah koneksi berhasil
            if ($koneksi) {
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

                    echo '<a href="modul/tambah.php?table=' . htmlspecialchars($table_name) . '" class="button-mining">Tambah</a>';
                    echo '<a href="modul/database/hapusPre.php" class="button-mining">Hapus</a>';
                    echo '<a href="c45/prediksi.php?table=' . htmlspecialchars($table_name) . '" class="button-mining">Lakukan Prediksi</a>';
                    echo '<a href="modul/database/PrintPros.php?table=' . htmlspecialchars($table_name) . '" class="button-mining" onclick="printDocument(\'print\')">Cetak Berkas Anda</a>';

                    echo "<form method='GET' action='' class='form-search'>";
                    echo "<input type='hidden' name='table' value='$table_name'>";
                    echo "<input type='text' name='search' value='" . htmlspecialchars($search_query) . "' placeholder='Search...'>";
                    echo "<input type='submit' class='button-mining' value='Search'>";
                    echo "</form>";

                    echo '<div class="styled-table" style="overflow-x: auto;">';
                    echo '<table style="width: 100%; border-collapse: collapse; font-size: 14px;">';
                    echo '<thead>';
                    echo '<tr>';

                    // Mengambil nama kolom dari hasil query
                    $field_info = mysqli_fetch_fields($sql);
                    foreach ($field_info as $val) {
                        echo '<th style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($val->name) . '</th>';
                    }
                    if ($role == 'admin') {
                        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Edit</th>";
                        echo "<th style='border: 1px solid #ddd; padding: 8px;'>Delete</th>";
                    }
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    // Menampilkan data dari tabel
                    while ($row = mysqli_fetch_assoc($sql)) {
                        echo '<tr>';
                        foreach ($row as $column) {
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($column) . '</td>';
                        }
                        if ($role == 'admin') {
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/edit.php?table=$table_name&id=" . $row['id'] . "'>Edit</a></td>";
                            echo "<td style='border: 1px solid #ddd; padding: 8px;'><a href='modul/database/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo 'Tidak ada data.';
                }
            } else {
                echo 'Koneksi ke database gagal.';
            }

            mysqli_close($koneksi);
            ?>
        </div>
    </div>
</div>