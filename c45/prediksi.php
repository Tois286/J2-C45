<head>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<div class="card-home">
    <?php
    include '../config/koneksi.php';
    // include 'mining.php';
    if (isset($_GET['table'])) {
        $table_name = $_GET['table'];

        // Mengambil Data dari Database
        try {
            $stmt = $pdo->prepare("SELECT id, ips1, ips2, ips3, ips4 FROM $table_name");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<a class='button button-mining' type='button' href='../index.php'>Back</a>";

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

            // Simpan hasil ke dalam database
            foreach ($data as $row) {
                // Pastikan semua kunci ada dalam array $row
                if (isset($row['ips1']) && isset($row['ips2']) && isset($row['ips3']) && isset($row['ips4'])) {
                    $output = predict($tree, [
                        'ips1' => $row['ips1'],
                        'ips2' => $row['ips2'],
                        'ips3' => $row['ips3'],
                        'ips4' => $row['ips4'],
                    ]);

                    // Pastikan kunci 'id' ada dalam array $row
                    if (isset($row['id'])) {
                        $user_id = $row['id'];

                        // Debug: Periksa nilai yang akan diupdate
                        echo "Updating ID $user_id with PREDIKSI: $output\n";

                        // Lakukan update kolom Keterangan untuk setiap user id
                        $stmt_update = $pdo->prepare("UPDATE $table_name SET PREDIKSI = :PREDIKSI WHERE id = :id");
                        $stmt_update->execute(['PREDIKSI' => $output, 'id' => $user_id]);

                        // Debug: Periksa hasil eksekusi
                        if ($stmt_update->rowCount() > 0) {
                            echo "Update successful for ID $user_id\n";
                        } else {
                            echo "Update failed for ID $user_id\n";
                        }
                    } else {
                        echo "Missing ID in row: ";
                        print_r($row);
                    }
                } else {
                    echo "Missing IPS values in row: ";
                    print_r($row);
                }
            }
        } catch (PDOException $e) {
            die("Error retrieving data: " . $e->getMessage());
        }
    }
    ?>
</div>