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
                                function calculate_entropy($data, $attribute)
                                {
                                    // Hitung frekuensi tiap kategori
                                    $frequency = array_count_values(array_column($data, $attribute));

                                    // Hitung total jumlah data
                                    $total = count($data);

                                    // Hitung entropi
                                    $entropy = 0;
                                    foreach ($frequency as $count) {
                                        $probability = $count / $total;
                                        if ($probability > 0) { // Menghindari log(0)
                                            $entropy -= $probability * log($probability, 2);
                                        }
                                    }

                                    return $entropy;
                                }

                                function calculate_gain($data, $target_attribute, $split_attribute)
                                {
                                    $total_entropy = calculate_entropy($data, $target_attribute);

                                    // Hitung frekuensi nilai pada atribut pembagi
                                    $values_frequency = array_count_values(array_column($data, $split_attribute));
                                    $total_instances = count($data);

                                    // Hitung entropi tertimbang untuk setiap subset data
                                    $weighted_entropy = 0;
                                    foreach ($values_frequency as $value => $count) {
                                        // Ambil subset data untuk nilai tertentu
                                        $subset = array_filter($data, function ($row) use ($split_attribute, $value) {
                                            return $row[$split_attribute] == $value;
                                        });
                                        $subset_entropy = calculate_entropy($subset, $target_attribute);
                                        $weighted_entropy += ($count / $total_instances) * $subset_entropy;
                                    }

                                    // Hitung gain
                                    $gain = $total_entropy - $weighted_entropy;
                                    return $gain;
                                }

                                function build_decision_tree($data, $target_attribute, $attributes)
                                {
                                    // Jika semua data dalam subset adalah sama untuk target attribute, return node leaf dengan nilai tersebut
                                    $unique_values = array_unique(array_column($data, $target_attribute));
                                    if (count($unique_values) == 1) {
                                        return $unique_values[0];
                                    }

                                    // Jika tidak ada atribut lagi untuk dipilih, return nilai mayoritas dari target attribute
                                    if (empty($attributes)) {
                                        $counts = array_count_values(array_column($data, $target_attribute));
                                        arsort($counts);
                                        return key($counts);
                                    }

                                    // Pilih atribut dengan gain tertinggi
                                    $best_attribute = null;
                                    $max_gain = -1;
                                    foreach ($attributes as $attribute) {
                                        $gain = calculate_gain($data, $target_attribute, $attribute);
                                        if ($gain > $max_gain) {
                                            $max_gain = $gain;
                                            $best_attribute = $attribute;
                                        }
                                    }

                                    // Buat node baru untuk atribut terbaik
                                    $tree = array();
                                    $tree[$best_attribute] = array();

                                    // Ambil nilai unik untuk atribut terbaik
                                    $attribute_values = array_unique(array_column($data, $best_attribute));

                                    // Hapus atribut terbaik dari daftar atribut
                                    $attributes = array_diff($attributes, array($best_attribute));

                                    // Rekursif membangun pohon untuk setiap nilai atribut terbaik
                                    foreach ($attribute_values as $value) {
                                        // Ambil subset data untuk nilai tertentu pada atribut terbaik
                                        $subset = array_filter($data, function ($row) use ($best_attribute, $value) {
                                            return $row[$best_attribute] == $value;
                                        });

                                        // Rekursif membangun pohon untuk subset
                                        $subtree = build_decision_tree($subset, $target_attribute, $attributes);

                                        // Tambahkan subtree ke node saat ini
                                        $tree[$best_attribute][$value] = $subtree;
                                    }

                                    return $tree;
                                }


                                try {
                                    $table_name = $_GET['table'];
                                    $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
                                    $stmt->execute();
                                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Tentukan atribut target dan atribut lainnya
                                    $target_attribute = 'keterangan';
                                    $attributes = array('jenis_kelamin', 'ips1', 'ips2', 'ips3', 'ips4');

                                    // Hitung entropi untuk setiap atribut
                                    $entropy_gender = calculate_entropy($data, 'jenis_kelamin');
                                    $entropy_keterangan = calculate_entropy($data, 'keterangan');
                                    $entropy_ips1 = calculate_entropy($data, 'ips1');
                                    $entropy_ips2 = calculate_entropy($data, 'ips2');
                                    $entropy_ips3 = calculate_entropy($data, 'ips3');
                                    $entropy_ips4 = calculate_entropy($data, 'ips4');

                                    // Hitung total entropi
                                    $total_entropy = $entropy_gender + $entropy_keterangan + $entropy_ips1 + $entropy_ips2 + $entropy_ips3 + $entropy_ips4;

                                    // Hitung gain untuk setiap atribut
                                    $gain_gender = calculate_gain($data, 'keterangan', 'jenis_kelamin');
                                    $gain_ips1 = calculate_gain($data, 'keterangan', 'ips1');
                                    $gain_ips2 = calculate_gain($data, 'keterangan', 'ips2');
                                    $gain_ips3 = calculate_gain($data, 'keterangan', 'ips3');
                                    $gain_ips4 = calculate_gain($data, 'keterangan', 'ips4');

                                    // Hitung total gain
                                    $total_gain = $gain_gender + $gain_ips1 + $gain_ips2 + $gain_ips3 + $gain_ips4;

                                    // Bangun pohon keputusan
                                    $decision_tree = build_decision_tree($data, $target_attribute, $attributes);

                                    // Tampilkan hasil entropi dan gain
                                    echo "Entropi untuk jenis kelamin: " . $entropy_gender . "<br>";
                                    echo "Entropi untuk keterangan: " . $entropy_keterangan . "<br>";
                                    echo "Entropi untuk ips1: " . $entropy_ips1 . "<br>";
                                    echo "Entropi untuk ips2: " . $entropy_ips2 . "<br>";
                                    echo "Entropi untuk ips3: " . $entropy_ips3 . "<br>";
                                    echo "Entropi untuk ips4: " . $entropy_ips4 . "<br>";
                                    echo "Total Entropi: " . $total_entropy . "<br><br>";

                                    echo "Gain untuk jenis kelamin: " . $gain_gender . "<br>";
                                    echo "Gain untuk ips1: " . $gain_ips1 . "<br>";
                                    echo "Gain untuk ips2: " . $gain_ips2 . "<br>";
                                    echo "Gain untuk ips3: " . $gain_ips3 . "<br>";
                                    echo "Gain untuk ips4: " . $gain_ips4 . "<br>";
                                    echo "Total Gain: " . $total_gain . "<br><br>";

                                    // Tampilkan pohon keputusan
                                    echo "<pre>";
                                    print_r($decision_tree);
                                    echo "</pre>";
                                } catch (PDOException $e) {
                                    echo 'Connection failed: ' . $e->getMessage();
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="stepTree" class="hidden">
                    <div class="card-home" style="color:black;">
                        <p>Ini adalah konten untuk Step Tree.</p>
                        <?php
                        echo "<pre>";
                        print_r($decision_tree);
                        echo "</pre>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>

</html>