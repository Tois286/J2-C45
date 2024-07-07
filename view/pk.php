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
        <h1>Pohon Keputusan</h1>
        <div class="card-home">

            <div class="card-tree">
                <a href='#miningTree' onclick="showContent('miningTree')" class='button-mining'>Proses Training</a>
                <a href='#stepTree' onclick="showContent('stepTree')" class='button-mining'>Step Tree</a>
                <div class="table-container">
                    <div class="card-home">
                        <?php
                        include 'config/koneksi.php';

                        if (isset($_GET['table'])) {
                            $table_name = $_GET['table'];

                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $lulus = 'TEPAT WAKTU';
                            // Hitung jumlah total baris pada tabel
                            $result = $conn->query("SELECT COUNT(*) AS total_rows FROM $table_name WHERE Keterangan='$lulus'");
                            $row = $result->fetch_assoc();
                            $total_rows = $row['total_rows'];

                            // Hitung jumlah baris yang ingin ditampilkan (70% dari total baris)
                            $limit = ceil(0.7 * $total_rows);

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
            </div>
            <div id="miningTree" class="hidden">
                <div class="card-home">
                    <div class="table-container">
                        <div class="card-table" style="background-color:black; padding:40px; color:white; ">
                            <div id="table-content-container"></div>
                            <?php include 'c45/prediksi.php' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="stepTree" class="hidden">
                <div class="card-home" style="color:black;">
                    <p>Ini adalah konten untuk Step Tree.</p>

                    <?php
                    // Contoh data $data

                    $values = [];
                    foreach ($data as $row) {
                        // Pastikan semua kunci ada dalam array $row dan konversi nilai menjadi numerik
                        if (isset($row['ips1'], $row['ips2'], $row['ips3'], $row['ips4'])) {
                            // Tentukan rata-rata berdasarkan kategori
                            $categories = ['KURANG' => 1, 'CUKUP' => 2, 'BAIK' => 3, 'SANGAT BAIK' => 4];
                            $average = ($categories[$row['ips1']] + $categories[$row['ips2']] + $categories[$row['ips3']] + $categories[$row['ips4']]) / 4;

                            $values[] = [
                                'id' => $row['id'],
                                'ips1' => $row['ips1'],
                                'ips2' => $row['ips2'],
                                'ips3' => $row['ips3'],
                                'ips4' => $row['ips4'],
                                'lulus' => $average >= 2.5 ? 'TEPAT WAKTU' : 'TERLAMBAT',
                            ];
                        }
                    }
                    ?>

                    <style>
                        /* CSS untuk gaya tabel */
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                            font-family: Arial, sans-serif;
                        }

                        th,
                        td {
                            border: 1px solid #ddd;
                            padding: 10px;
                            text-align: left;
                        }

                        th {
                            background-color: #f2f2f2;
                        }

                        tbody tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                    </style>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Steps</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($values as $value) : ?>
                                <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td>
                                        IPS 1:(<?php echo $value['ips1']; ?>,) =>
                                        IPS 2: (<?php echo $value['ips2']; ?>,) =>
                                        IPS 3: (<?php echo $value['ips3']; ?>,) =>
                                        IPS 4: (<?php echo $value['ips4']; ?>,) =>
                                        Status: (<?php echo $value['lulus']; ?>)
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>

</body>