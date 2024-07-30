<style>
    .styled-table {
        width: 80%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 1em;
        font-family: sans-serif;
        min-width: 300px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr:hover {
        background-color: #dddddd;
    }
</style>
<center>
    <div class="img">
        <center>
            <img src="../../asset/unipi.png" alt="image" style="width: 5%; height: auto;">
            <h1>Data Prediksi Kelulusan Mahasiswa</h1>
        </center>
    </div>
    <div class="card-tree">
        <div class="table-container">
            <?php

            include '../../config/koneksi.php'; // Sesuaikan dengan path koneksi Anda

            if (isset($_GET['table'])) {
                $table_name = $_GET['table'];
                $lulus = "TEPAT WAKTU"; // Kategori positif
                $tidak_lulus = "TERLAMBAT"; // Kategori negatif

                // Koneksi ke Database
                $koneksi1 = new mysqli($host, $username, $password, $dbname);
                if ($koneksi1->connect_error) {
                    die("Connection failed: " . $koneksi1->connect_error);
                }

                // Query untuk mengambil data dari tabel
                $query = "SELECT * FROM $table_name";
                $result = $koneksi1->query($query);

                if ($result->num_rows > 0) {
                    echo "<div class='table-container'>";
                    echo "<table class='styled-table'>";
                    echo "<tr>";
                    echo "<th>NO</th>";

                    $fields = $result->fetch_fields();
                    $headerColumns = [];

                    foreach ($fields as $field) {
                        if ($field->name != 'id' && $field->name != 'NO') {
                            $headerColumns[] = $field->name;
                            echo "<th>" . $field->name . "</th>";
                        }
                    }
                    echo "</tr>";
                    echo "</div>";

                    $data = [];
                    $counter = 1;

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        $counter++;

                        $rowData = [];
                        foreach ($row as $key => $value) {
                            if ($key != 'id' && $key != 'NO') {
                                echo "<td>$value</td>";
                                $rowData[$key] = $value;
                            }
                        }
                        $data[] = $rowData;
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "Tidak ada data yang ditemukan.";
                }
                $koneksi1->close();
            } else {
                echo "Nama tabel tidak diberikan.";
            }
            ?>
            <div class="card-table">
                <div id="table-content-container">
                </div>
            </div>

        </div>
    </div>
</center>
<script>
    window.onload = function() {
        window.print();
    };
</script>