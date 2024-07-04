<!DOCTYPE html>
<html lang="en">

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
                <div class="table-container">
                    <div class="card-table">
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
                                echo "<rev>$table_name</rev>";
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
                                echo "<br class='mining'>";
                                echo "<a href='#miningTree' onclick=\"showContent('miningTree')\" class='button-mining'>Proses Training</a>";
                                echo "<a href='#stepTree' onclick=\"showContent('stepTree')\" class='button-mining'>Step Tree</a>";
                                echo "<br>";
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
                <!-- Elemen untuk menampilkan pohon keputusan -->
                <div id="miningTree" class="hidden">
                    <div class="card-home">
                        <div class="table-container">
                            <div class="card-table" style="background-color:black; padding:40px; color:white; ">
                                <?php

                                if (isset($_GET['table'])) {
                                    $table_name = $_GET['table'];

                                    try {
                                        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
                                        $stmt->execute();
                                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Hitung jumlah data pada tabel
                                        $total_data = $stmt->rowCount();

                                        // Inisialisasi variabel untuk menghitung jumlah 'TERLAMBAT' dan 'TEPAT WAKTU'
                                        $count_terlambat = 0;
                                        $count_tepat_waktu = 0;

                                        // Iterasi data untuk menghitung jumlahnya
                                        foreach ($data as $row) {
                                            if ($row['keterangan'] == 'TERLAMBAT') {
                                                $count_terlambat++;
                                            } elseif ($row['keterangan'] == 'TEPAT WAKTU') {
                                                $count_tepat_waktu++;
                                            }
                                        }

                                        // Mapping nilai IPS
                                        function mapIPS($ips)
                                        {
                                            if ($ips < 2.5) {
                                                return 'Kurang';
                                            } elseif ($ips >= 2.5 && $ips < 3.0) {
                                                return 'Cukup';
                                            } elseif ($ips >= 3.0) {
                                                return 'Baik'; // Asumsi kategori "Baik" untuk IPS >= 3.0
                                            } else {
                                                return 'Undefined'; // Handle nilai IPS yang tidak terdefinisi
                                            }
                                        }

                                        // Hitung entropy dari sebuah set data
                                        function calculateEntropy($values)
                                        {
                                            $total = count($values);
                                            $entropy = 0;
                                            foreach (array_count_values($values) as $count) {
                                                $probability = $count / $total;
                                                $entropy -= $probability * log($probability, 2);
                                            }
                                            return $entropy;
                                        }

                                        // Hitung gain untuk setiap atribut
                                        function calculateInformationGain($data, $attribute)
                                        {
                                            $total_data = count($data);
                                            $attribute_values = array_unique(array_column($data, $attribute));
                                            $attribute_entropy = 0;

                                            foreach ($attribute_values as $value) {
                                                $subset = array_filter($data, function ($row) use ($attribute, $value) {
                                                    if ($attribute == 'jenis_kelamin') {
                                                        return $row[$attribute] == $value;
                                                    } elseif (strpos($attribute, 'ips') === 0) {
                                                        $ips_value = floatval($row[$attribute]);
                                                        $mapped_ips = mapIPS($ips_value);
                                                        return $mapped_ips == $value;
                                                    }
                                                    return false;
                                                });

                                                $subset_count = count($subset);
                                                if ($subset_count > 0) {
                                                    $subset_entropy = calculateEntropy(array_column($subset, 'keterangan'));
                                                    $attribute_entropy += ($subset_count / $total_data) * $subset_entropy;
                                                }
                                            }

                                            $entropy_all = calculateEntropy(array_column($data, 'keterangan'));
                                            $information_gain = $entropy_all - $attribute_entropy;

                                            return $information_gain;
                                        }

                                        // Array of attributes to build decision tree
                                        $attributes = ['jenis_kelamin', 'ips1', 'ips2', 'ips3', 'ips4'];

                                        // Calculate and display information gain for each attribute
                                        foreach ($attributes as $attribute) {
                                            $gain = calculateInformationGain($data, $attribute);
                                            echo "Information Gain for $attribute: " . $gain . "<br>";
                                        }

                                        // Tampilkan hasil perhitungan
                                        echo "Total data in table $table_name: $total_data<br>";
                                        echo "Jumlah data TERLAMBAT: $count_terlambat<br>";
                                        echo "Jumlah data TEPAT WAKTU: $count_tepat_waktu<br>";

                                        // Hitung entropy keseluruhan
                                        $entropy_total = calculateEntropy(array_column($data, 'keterangan'));
                                        echo "Entropy keseluruhan: " . $entropy_total . "<br>";

                                        // Fungsi untuk membangun pohon keputusan
                                        function buildDecisionTree($data, $attributes)
                                        {
                                            $lulus_values = array_column($data, 'keterangan');
                                            $unique_lulus_values = array_unique($lulus_values);

                                            // Jika semua baris memiliki nilai yang sama untuk 'keterangan', maka return label
                                            if (count($unique_lulus_values) == 1) {
                                                return ['label' => $unique_lulus_values[0]];
                                            }

                                            $bestAttribute = null;
                                            $bestGain = -1; // Inisialisasi gain terbaik dengan nilai negatif

                                            foreach ($attributes as $attribute) {
                                                $gain = calculateInformationGain($data, $attribute);
                                                if ($gain > $bestGain) {
                                                    $bestGain = $gain;
                                                    $bestAttribute = $attribute;
                                                }
                                            }

                                            $tree = ['attribute' => $bestAttribute];
                                            $attribute_values = array_unique(array_column($data, $bestAttribute));

                                            foreach ($attribute_values as $value) {
                                                $subset = array_filter($data, function ($row) use ($bestAttribute, $value) {
                                                    if ($bestAttribute == 'jenis_kelamin') {
                                                        return $row[$bestAttribute] == $value;
                                                    } elseif (strpos($bestAttribute, 'ips') === 0) {
                                                        $ips_value = floatval($row[$bestAttribute]);
                                                        $mapped_ips = mapIPS($ips_value);
                                                        return $mapped_ips == $value;
                                                    }
                                                    return false;
                                                });

                                                $tree[$value] = buildDecisionTree($subset, array_diff($attributes, [$bestAttribute]));
                                            }

                                            return $tree;
                                        }

                                        // Membangun pohon keputusan
                                        $decision_tree = buildDecisionTree($data, $attributes);

                                        // Fungsi untuk mencetak pohon keputusan dengan indentasi
                                        function printTree($tree, $indent = '')
                                        {
                                            if (isset($tree['label'])) {
                                                echo $indent . $tree['label'] . "<br>";
                                            } else {
                                                echo $indent . $tree['attribute'] . "<br>";
                                                foreach ($tree as $value => $child) {
                                                    if ($value != 'attribute') {
                                                        echo $indent . "  " . $value . "<br>";
                                                        printTree($child, $indent . "  ");
                                                    }
                                                }
                                            }
                                        }

                                        // Cetak pohon keputusan dengan indentasi
                                        echo "<br><b>Decision Tree:</b><br>";
                                        printTree($decision_tree);
                                    } catch (PDOException $e) {
                                        die("Error retrieving data: " . $e->getMessage());
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="stepTree" class="hidden">
                    <div class="card-home" style="color:black;">
                        <p>Ini adalah konten untuk Step Tree.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>

</html>