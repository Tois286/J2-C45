<?php
require '../../config/koneksi.php';

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table_name = $_GET['table'];

    // Mengambil Data dari Database
    try {
        $stmt = $pdo->prepare("SELECT ips1, ips2, ips3, ips4 FROM $table_name WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tentukan fungsi untuk menetapkan bobot berdasarkan nilainya
        function getWeight($value)
        {
            if ($value < 2.5) {
                return 'KURANG';
            } elseif ($value < 3.0) {
                return 'CUKUP';
            } elseif ($value < 3.5) {
                return 'BAIK';
            } elseif ($value > 3.5) {
                return 'SANGAT BAIK';
            } else {
                echo "tidak memiliki nilai IPS";
            }
        }

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
            if (count(array_unique($values)) == 1) {
                return array('label' => $values[0]['lulus']);
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

        // Siapkan data untuk pohon keputusan
        $values = [];
        foreach ($data as $row) {
            $values[] = [
                'ips1' => getWeight($row['ips1']),
                'ips2' => getWeight($row['ips2']),
                'ips3' => getWeight($row['ips3']),
                'ips4' => getWeight($row['ips4']),
                'lulus' => ($row['ips1'] + $row['ips2'] + $row['ips3'] + $row['ips4']) / 4 >= 3.0 ? 'LULUS' : 'TIDAK LULUS',
            ];
        }

        // Membangun pohon keputusan
        $tree = buildDecisionTree($values, ['ips1', 'ips2', 'ips3', 'ips4']);

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

        // Tentukan hasilnya
        $output = predict($tree, [
            'ips1' => getWeight($data[0]['ips1']),
            'ips2' => getWeight($data[0]['ips2']),
            'ips3' => getWeight($data[0]['ips3']),
            'ips4' => getWeight($data[0]['ips4']),
        ]);

        echo "Output: " . $output;
    } catch (PDOException $e) {
        die("Error retrieving data: " . $e->getMessage());
    }
}
