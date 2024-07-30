<div class="card-home">
    <?php
    include '../config/koneksi.php';
    // include 'mining.php';

    // Pastikan ID user diberikan sebagai parameter di URL
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $table_name = $_GET['table'];

        // Mengambil Data dari Database untuk satu user
        try {
            $stmt = $pdo->prepare("SELECT id, ips1, ips2, ips3, ips4 FROM $table_name WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                // Debug: Periksa data yang diterima
                echo "Processing row: ";
                print_r($data);

                // Siapkan data untuk pohon keputusan
                $ips1 = $data['ips1'];
                $ips2 = $data['ips2'];
                $ips3 = $data['ips3'];
                $ips4 = $data['ips4'];

                $categories = ['KURANG' => 1, 'CUKUP' => 2, 'BAIK' => 3, 'SANGAT BAIK' => 4];
                $average = ($categories[$ips1] + $categories[$ips2] + $categories[$ips3] + $categories[$ips4]) / 4;

                // Debug: Periksa rata-rata
                echo "Average for row ID {$data['id']}: $average\n";

                $values = [
                    'ips1' => $ips1,
                    'ips2' => $ips2,
                    'ips3' => $ips3,
                    'ips4' => $ips4,
                    'lulus' => $average >= 2.5 ? 'TEPAT WAKTU' : 'TERLAMBAT',  // Menggunakan 2.5 sebagai ambang batas rata-rata kategori
                ];

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
                    $entropyTotal = calculateEntropy(array_column($values, 'lulus'));
                    $gain = $entropyTotal;
                    $attributeValues = array_column($values, $attribute);
                    foreach (array_unique($attributeValues) as $value) {
                        $subset = array_filter($values, function ($v) use ($value, $attribute) {
                            return $v[$attribute] == $value;
                        });
                        $gain -= (count($subset) / count($values)) * calculateEntropy(array_column($subset, 'lulus'));
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
                $tree = buildDecisionTree([$values], ['ips1', 'ips2', 'ips3', 'ips4']);
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

                // Periksa apakah kolom 'PREDIKSI' sudah ada di tabel
                $stmt_check_column = $pdo->query("SHOW COLUMNS FROM $table_name LIKE 'PREDIKSI'");
                $column_exists = $stmt_check_column->rowCount() > 0;

                if (!$column_exists) {
                    // Jika kolom 'PREDIKSI' belum ada, tambahkan kolom
                    $stmt_add_column = $pdo->query("ALTER TABLE $table_name ADD COLUMN PREDIKSI VARCHAR(255)");

                    if ($stmt_add_column) {
                        echo "Added column PREDIKSI to table $table_name\n";
                    } else {
                        echo "Failed to add column PREDIKSI to table $table_name\n";
                    }
                }

                // Periksa apakah kolom 'tgl_prediksi' sudah ada di tabel
                $stmt_check_date_column = $pdo->query("SHOW COLUMNS FROM $table_name LIKE 'tgl_prediksi'");
                $date_column_exists = $stmt_check_date_column->rowCount() > 0;

                if (!$date_column_exists) {
                    // Jika kolom 'tgl_prediksi' belum ada, tambahkan kolom
                    $stmt_add_date_column = $pdo->query("ALTER TABLE $table_name ADD COLUMN tgl_prediksi DATE");

                    if ($stmt_add_date_column) {
                        echo "Added column tgl_prediksi to table $table_name\n";
                    } else {
                        echo "Failed to add column tgl_prediksi to table $table_name\n";
                    }
                }

                $tgl = date('Y-m-d');

                // Lakukan update kolom tgl_prediksi dan PREDIKSI untuk user tertentu
                $output = predict($tree, [
                    'ips1' => $data['ips1'],
                    'ips2' => $data['ips2'],
                    'ips3' => $data['ips3'],
                    'ips4' => $data['ips4'],
                ]);

                $stmt_update = $pdo->prepare("UPDATE $table_name SET tgl_prediksi = :tgl_prediksi, PREDIKSI = :PREDIKSI WHERE id = :id");
                $stmt_update->execute(['tgl_prediksi' => $tgl, 'PREDIKSI' => $output, 'id' => $user_id]);

                // Debug: Periksa hasil eksekusi
                if ($stmt_update->rowCount() > 0) {
                    echo "Update successful for ID $user_id\n";
                } else {
                    echo "Update failed for ID $user_id\n";
                }
            } else {
                echo "No data found for ID $user_id\n";
            }

            // Alihkan ke halaman index setelah proses selesai
            header('Location: ../index.php');
            exit();
        } catch (PDOException $e) {
            die("Error retrieving data: " . $e->getMessage());
        }
    } else {
        echo "User ID not specified.";
    }
    ?>
</div>