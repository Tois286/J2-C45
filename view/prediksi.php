<div class="card-home">
    <a href='modul/database/tambah.php?table=<?php echo $table_name; ?>' value="<?php echo $table_name; ?>" class='button-mining'>tambah</a>
    <a href='modul/database/deleteTabel.php?table=<?php echo $table_name; ?>' value="<?php echo $table_name; ?>" class='button-mining'>Hapus</a>
    <a href="modul/database/PrintPros.php?table=<?php echo $table_name; ?>" value="<?php echo $table_name; ?>" class="button-mining" onclick="printDocument('print')">Cetak Berkas Anda</a>
    <div class="table-container">
        <?php
        $host = 'localhost';
        $dbname = 'dbmining';
        $username = 'root';
        $password = '';

        if (isset($_GET['table'])) {
            $table_name = $_GET['table'];

            $conn = new mysqli($host, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Hitung jumlah total baris pada tabel
            $result = $conn->query("SELECT * FROM $table_name");

            if ($result) {
                if ($result->num_rows > 0) {
                    echo "<h3>$table_name</h3>";
                    echo "<br>";
                    echo "<table id='table-content'>";
                    echo "<tr>";
                    echo "<th>NO</th>"; // Kolom nomor urut

                    $fields = $result->fetch_fields();
                    $headerColumns = [];

                    foreach ($fields as $field) {
                        // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
                        if ($field->name != 'id' && $field->name != 'NO') {
                            $headerColumns[] = $field->name;
                            echo "<th>" . $field->name . "</th>";
                        }
                    }
                    echo "<th>Edit</th>"; // Kolom untuk edit
                    echo "<th>Delete</th>"; // Kolom untuk delete
                    echo "</tr>";

                    $counter = 1; // Counter untuk nomor urut

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>"; // Tampilkan nomor urut
                        $counter++;

                        foreach ($row as $key => $value) {
                            // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
                            if ($key != 'id' && $key != 'NO') {
                                echo "<td>$value</td>";
                            }
                        }

                        // Kolom edit dengan link ke halaman edit
                        echo "<td><a href='modul/database/edit.php?table=$table_name&id=" . $row['id'] . "'>Edit</a></td>";
                        // Kolom delete dengan link untuk menghapus
                        echo "<td><a href='modul/database/delete.php?table=$table_name&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
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