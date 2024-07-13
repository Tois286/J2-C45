<style>
    .hidden {
        display: none;
    }
</style>

<div>
    <h1>Proses Testing</h1>
    <div class="card-home">
        <div id="table-content">
            <a href="c45/uji.php?table=<?php echo $table_name; ?>" id="loading" class="button-mining" value="<?php echo $table_name; ?>">Prediksi</a>
            <!-- onclick="startLoading(event)" -->
            <a href='#proji' onclick="showContent('proji')" class='button-mining'>Proses Uji </a>
            Dari <span style="display: inline; font-size: 2em; font-weight: bold; margin: 0;">30%</span> data
            <div class="table-container">
                <div class="card-home" id="content">
                    <div id="table-content-container">
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
                            $result = $conn->query("SELECT COUNT(*) AS total_rows FROM $table_name ");
                            $row = $result->fetch_assoc();
                            $total_rows = $row['total_rows'];

                            // Hitung jumlah baris yang ingin ditampilkan (30% dari total baris)
                            $limit = ceil(0.3 * $total_rows);

                            // Query untuk mengambil 30% data terbaru
                            $query = "SELECT * FROM $table_name   ORDER BY id DESC LIMIT $limit";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {

                                echo "<table id='table-content'>";
                                echo "<tr>";

                                // Tambahkan kolom NO sebagai header pertama
                                echo "<th>NO</th>";

                                $fields = $result->fetch_fields();
                                $headerColumns = [];

                                foreach ($fields as $field) {
                                    // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
                                    if ($field->name != 'id' && $field->name != 'NO') {
                                        $headerColumns[] = $field->name;
                                        echo "<th>" . $field->name . "</th>";
                                    }
                                }
                                echo "</tr>";

                                $counter = 1; // Counter untuk nomor urut

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";

                                    // Tampilkan nomor urut (NO) di bagian pertama
                                    echo "<td>" . $counter . "</td>";
                                    $counter++; // Increment counter untuk nomor urut

                                    foreach ($row as $key => $value) {
                                        // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
                                        if (
                                            $key != 'id' && $key != 'NO'
                                        ) {
                                            echo "<td>$value</td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";

                                // Tambahkan tombol Mining di luar loop while
                            } else {
                                echo "<p>No data found</p>";
                            }
                            $conn->close();
                        } else {
                            echo "<p>Silakan pilih tabel dari dropdown di atas.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="proji" class="hidden">
        <div class="card-home">
            <p>Menampilkan <span style="display: inline; font-size: 2em; font-weight: bold; margin: 0;">100%</span> data</p>
            <?php include 'c45/uji.php' ?>
        </div>
    </div>
</div>