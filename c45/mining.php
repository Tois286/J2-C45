<?php
include '../config/koneksi.php';

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
                return 'KURANG';
            } elseif ($ips >= 2.5 && $ips < 3.0) {
                return 'CUKUP';
            } elseif ($ips >= 3.0 && $ips < 3.5) {
                return 'BAIK';
            } elseif ($ips >= 3.5) {
                return 'SANGAT BAIK';
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
            $keterangan_values = array_column($data, 'keterangan');
            $unique_keterangan_values = array_unique($keterangan_values);

            // Jika semua baris memiliki nilai yang sama untuk 'keterangan', maka return label
            if (count($unique_keterangan_values) == 1) {
                return ['label' => $unique_keterangan_values[0]];
            }

            // Jika tidak ada atribut yang tersisa, return label mayoritas
            if (empty($attributes)) {
                $counts = array_count_values($keterangan_values);
                arsort($counts);
                return ['label' => key($counts)];
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
                        printTree($child, $indent . "    ");
                    }
                }
            }
        }

        // Cetak pohon keputusan dengan indentasi
        echo "<br><b>Decision Tree:</b><br>";
        printTree($decision_tree);

        // Debug: Periksa setiap baris data yang diterima
        $values = [];
        foreach ($data as $row) {
            // Debug: Periksa setiap baris data yang diterima
            echo "Processing row: ";
            print_r($row);

            // Pastikan semua kunci ada dalam array $row dan konversi nilai menjadi numerik
            if (isset($row['ips1']) && isset($row['ips2']) && isset($row['ips3']) && isset($row['ips4'])) {
                $ips1 = mapIPS(floatval($row['ips1']));
                $ips2 = mapIPS(floatval($row['ips2']));
                $ips3 = mapIPS(floatval($row['ips3']));
                $ips4 = mapIPS(floatval($row['ips4']));

                // Tentukan rata-rata berdasarkan kategori
                $categories = ['KURANG' => 1, 'CUKUP' => 2, 'BAIK' => 3, 'SANGAT BAIK' => 4];
                $average = ($categories[$ips1] + $categories[$ips2] + $categories[$ips3] + $categories[$ips4]) / 4;

                // Debug: Periksa rata-rata
                echo "Average for row ID {$row['id']}: $average\n";

                $values[] = [
                    'id' => $row['id'],
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
    } catch (PDOException $e) {
        die("Error retrieving data: " . $e->getMessage());
    }
}
