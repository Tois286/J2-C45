<div>
    <h1>Cetak Halaman</h1>
    <div class="card-home">
        <a href="modul/database/PrintPros.php?table=<?php echo $table_name; ?>" value="<?php echo $table_name; ?>" class="button-mining" onclick="printDocument('print')">Cetak Berkas Anda</a>
    </div>
    <div class=" card-tree">
        <div class="table-container">
            <?php

            include 'config/koneksi.php'; // Sesuaikan dengan path koneksi Anda

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
                    echo "<table id='table-content'>";
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
                    // Fungsi untuk membagi data menjadi training set dan testing set

                } else {
                    echo "Tidak ada data yang ditemukan.";
                }
                $koneksi1->close();
            } else {
                echo "Nama tabel tidak diberikan.";
            }
            ?>
            <!-- <a href="c45/Prediksi.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">Prediksi</a>
        <a href="c45/mining.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">mining</a>
        id="loading" onclick="startLoading(event)" -->
            <div class="card-table">
                <div id="table-content-container">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openPrintWindow() {
        var printWindow = window.open(this.href, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
        return false;
    }
</script>