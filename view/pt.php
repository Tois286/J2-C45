<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Training</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div>
        <h1>Pohon Testing</h1>
        <div class="card-home">
            <div class="card-tree" id="table-content">
                <div class="table-container">
                    <a href="c45/uji.php?table=<?php echo $table_name; ?>" id="loading" onclick="startLoading(event)" class="button-mining" value="<?php echo $table_name; ?>">Prediksi</a>
                    <a href='#proji' onclick="showContent('proji')" class='button-mining'>Proses Uji</a>
                    <a href='#lihas' onclick="showContent('lihas')" class='button-mining'>Lihat Hasil Uji</a>
                    <div class="card-home" id="content">
                        <?php
                        include 'config/koneksi.php';

                        if (isset($_GET['table'])) {
                            $table_name = $_GET['table'];

                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Hitung jumlah total baris pada tabel
                            $result = $conn->query("SELECT COUNT(*) AS total_rows FROM $table_name WHERE Keterangan='$lulus'");
                            $row = $result->fetch_assoc();
                            $total_rows = $row['total_rows'];

                            // Hitung jumlah baris yang ingin ditampilkan (70% dari total baris)
                            $limit = ceil(0.3 * $total_rows);

                            // Query untuk mengambil 70% data terbaru
                            $query = "SELECT * FROM $table_name  WHERE Keterangan='$lulus' ORDER BY id DESC LIMIT $limit";
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
                <div class="hidden">
                    <div class="card-home">
                    </div>
                </div>
                <div class="card-home">
                    <div id="lihas">
                        lihas
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>