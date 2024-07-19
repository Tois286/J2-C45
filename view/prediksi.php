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
    <div class="table-container">
        <?php
        // Kode koneksi database dan pengambilan data tabel sudah ada di sini

        if (isset($_GET['table'])) {
            $table_name = $_GET['table'];
            $search_query = isset($_GET['search']) ? $_GET['search'] : '';

            $conn = new mysqli($host, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Menyiapkan query pencarian
            $sql = "SELECT * FROM $table_name";
            if ($search_query) {
                $search_query = $conn->real_escape_string($search_query);
                $sql .= " WHERE ";
                $fields_result = $conn->query("SHOW COLUMNS FROM $table_name");
                $conditions = [];
                while ($field = $fields_result->fetch_assoc()) {
                    $field_name = $field['Field'];
                    $conditions[] = "$field_name LIKE '%$search_query%'";
                }
                $sql .= implode(' OR ', $conditions);
            }

            $result = $conn->query($sql);
            echo '<a href="modul/tambah.php?table=' . htmlspecialchars($table_name) . '" class="button-mining">tambah</a>';
            echo '<a href="modul/database/PrintPros.php?table=' . htmlspecialchars($table_name) . '" class="button-mining" onclick="printDocument(\'print\')">Cetak Berkas Anda</a>';
            // Menampilkan form pencarian
            echo "<form method='GET' action='' class='form-search'>";
            echo "<input type='hidden' name='table' value='$table_name'>";
            echo "<input type='text' name='search' value='" . htmlspecialchars($search_query) . "' placeholder='Search...'>";
            echo "<input type='submit'class='button-mining' value='Search'>";
            echo "</form>";

            if ($result) {
                if ($result->num_rows > 0) {
                    echo "<br>";
                    echo "<table id='table-content'>";
                    echo "<tr>";
                    echo "<th>NO</th>"; // Kolom nomor urut

                    $fields = $result->fetch_fields();
                    $headerColumns = [];

                    foreach ($fields as $field) {
                        if ($field->name != 'id' && $field->name != 'NO') {
                            $headerColumns[] = $field->name;
                            echo "<th>" . $field->name . "</th>";
                        }
                    }
                    if ($role == 'admin') {
                        echo "<th>Edit</th>"; // Kolom untuk edit
                        echo "<th>Delete</th>";
                    } // Kolom untuk delete
                    echo "</tr>";

                    $counter = 1; // Counter untuk nomor urut

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>"; // Tampilkan nomor urut
                        $counter++;

                        foreach ($row as $key => $value) {
                            if ($key != 'id' && $key != 'NO') {
                                echo "<td>$value</td>";
                            }
                        }
                        if ($role == 'admin') {
                            echo "<td><a href='modul/database/edit.php?table=$table_name&id=" . $row['id'] . "'>Edit</a></td>";
                            echo "<td><a href='modul/database/delete.php?table=$table_name&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No data found</p>";
                }
            } else {
                echo "<p>Error executing query: " . $conn->error . "</p>";
            }

            $conn->close();
        } else {
            echo "<p>Silakan pilih tabel dari dropdown di atas dan lakukan prediksi</p>";
        }
        ?>

    </div>
</div>