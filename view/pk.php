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
                                    // Mengambil Data dari Database
                                    try {
                                        $stmt = $pdo->prepare("SELECT id, ips1, ips2, ips3, ips4 FROM $table_name");
                                        $stmt->execute();
                                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Debug: Periksa data yang diterima
                                        echo "<pre>";
                                        print_r($data);
                                        echo "</pre>";

                                        // Siapkan data untuk pohon keputusan
                                        $values = [];
                                        foreach ($data as $row) {
                                            // Debug: Periksa setiap baris data yang diterima
                                            echo "Processing row: ";
                                            print_r($row);

                                            // Pastikan semua kunci ada dalam array $row dan konversi nilai menjadi numerik
                                            if (isset($row['ips1']) && isset($row['ips2']) && isset($row['ips3']) && isset($row['ips4'])) {
                                                $ips1 = $row['ips1'];
                                                $ips2 = $row['ips2'];
                                                $ips3 = $row['ips3'];
                                                $ips4 = $row['ips4'];

                                                // Tentukan rata-rata berdasarkan kategori
                                                $categories = ['KURANG' => 1, 'CUKUP' => 2, 'BAIK' => 3, 'SANGAT BAIK' => 4];
                                                $average = ($categories[$ips1] + $categories[$ips2] + $categories[$ips3] + $categories[$ips4]) / 4;

                                                // Debug: Periksa rata-rata
                                                echo "Average for row ID {$row['id']}: $average\n";

                                                $values[] = [
                                                    'ips1' => $ips1,
                                                    'ips2' => $ips2,
                                                    'ips3' => $ips3,
                                                    'ips4' => $ips4,
                                                    'lulus' => $average >= 2.5 ? 'TEPAT WAKTU' : 'TERLAMBAT',  // Menggunakan 2.5 sebagai ambang batas rata-rata kategori
                                                ];
                                            } else {
                                                echo "Missing IPS values in row: ";
                                                print_r($row);
                                            }
                                        }

                                        // Debug: Periksa nilai yang sudah diubah
                                        echo "<pre>";
                                        print_r($values);
                                        echo "</pre>";

                                        // Tentukan fungsi untuk menghitung entropi
                                        function calculateEntropy($values)
                                        {
                                            $total = count($values);
                                            $entropy = 0;
                                            foreach ($values as $value) {
                                                $probability = count(array_filter($values, function ($v) use ($value) {
                                                    return $v == $value;
                                                })) / $total;
                                                $entropy -= $probability * log($probability, 2);
                                            }
                                            return $entropy;
                                        }

                                        // Tentukan fungsi untuk menghitung perolehan informasi
                                        function calculateInformationGain($values, $attribute)
                                        {
                                            $entropyTotal = calculateEntropy($values);
                                            $gain = $entropyTotal;
                                            $attributeValues = array_column($values, $attribute);
                                            foreach (array_unique($attributeValues) as $value) {
                                                $subset = array_filter($values, function ($v) use ($value, $attribute) {
                                                    return $v[$attribute] == $value;
                                                });
                                                $gain -= (count($subset) / count($values)) * calculateEntropy($subset);
                                            }
                                            return $gain;
                                        }

                                        // Tentukan fungsi untuk membangun pohon keputusan
                                        function buildDecisionTree($values, $attributes)
                                        {
                                            $lulus_values = array_column($values, 'lulus');
                                            $unique_lulus_values = array_unique($lulus_values);

                                            // Jika semua baris memiliki nilai yang sama untuk 'lulus', maka return label
                                            if (count($unique_lulus_values) == 1) {
                                                return array('label' => $unique_lulus_values[0]);
                                            }

                                            $bestAttribute = null;
                                            $bestGain = 0;
                                            foreach ($attributes as $attribute) {
                                                $gain = calculateInformationGain($values, $attribute);
                                                if ($gain > $bestGain) {
                                                    $bestGain = $gain;
                                                    $bestAttribute = $attribute;
                                                }
                                            }

                                            $tree = array('attribute' => $bestAttribute);
                                            foreach (array_unique(array_column($values, $bestAttribute)) as $value) {
                                                $subset = array_filter($values, function ($v) use ($value, $bestAttribute) {
                                                    return $v[$bestAttribute] == $value;
                                                });
                                                $tree[$value] = buildDecisionTree($subset, array_diff($attributes, [$bestAttribute]));
                                            }
                                            return $tree;
                                        }

                                        // Membangun pohon keputusan
                                        $tree = buildDecisionTree($values, ['ips1', 'ips2', 'ips3', 'ips4']);
                                        var_dump($tree);

                                        // Cetak pohon keputusan
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
                                        printTree($tree);

                                        // Gunakan pohon keputusan untuk menentukan output
                                        function predict($tree, $values)
                                        {
                                            if (isset($tree['label'])) {
                                                return $tree['label'];
                                            }

                                            $attribute = $tree['attribute'];
                                            $value = $values[$attribute];
                                            unset($values[$attribute]);

                                            return predict($tree[$value], $values);
                                        }
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
<script>
    function showContent(sectionId) {
        var sections = document.querySelectorAll('.card-tree > div');
        sections.forEach(function(section) {
            if (section.id === sectionId) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        });
    }
</script>

</html>