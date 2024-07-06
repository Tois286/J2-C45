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
                <a href='#miningTree' onclick="showContent('miningTree')" class='button-mining'>Proses Uji</a>
                <div class="table-container">
                    <div class="card-home" id="content">
                        <?php
                        include 'config/koneksi.php';

                        if (isset($_GET['table'])) {
                            $table_name = $_GET['table'];

                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $query = "SELECT * FROM $table_name";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                echo "<table id='table-content'>";
                                echo "<tr>";

                                $fields = $result->fetch_fields();
                                foreach ($fields as $field) {
                                    echo "<th>" . $field->name . "</th>";
                                }
                                echo "</tr>";

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";

                                    foreach ($row as $value) {
                                        echo "<td>$value</td>";
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

</body>