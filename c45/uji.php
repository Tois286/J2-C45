<?php
include 'config/koneksi.php';

// Fungsi untuk mengklasifikasikan data menggunakan pohon keputusan
function classify($tree, $data)
{
    foreach ($tree as $attribute => $branches) {
        if (isset($branches[$data[$attribute]])) {
            $subtree = $branches[$data[$attribute]];
            if (is_array($subtree)) {
                return classify($subtree, $data);
            } else {
                return $subtree;
            }
        }
    }
    return null; // Jika tidak ada keputusan yang cocok
}

// Fungsi untuk mengambil data dari database
function getAllData($pdo, $table_name)
{
    $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengambil 30% data sebagai data uji dari awal
function getTestData($data)
{
    $total = count($data);
    $testSize = (int)($total * 0.3); // 30% dari data

    // Ambil 30% dari data awal
    $testData = array_slice($data, 0, $testSize);

    return $testData;
}

// Fungsi untuk menghitung akurasi, sensitivitas, spesifisitas, dan metrik lainnya
function evaluateModel($tree, $testData)
{
    $TP = $TN = $FP = $FN = 0;
    $total = count($testData);
    $results = [];

    foreach ($testData as $instance) {
        $predicted = classify($tree, $instance);
        $actual = $instance['KETERANGAN'];
        $results[] = [
            'id' => $instance['id'],
            'jenis_kelamin' => $instance['jenis_kelamin'],
            'ips1' => $instance['ips1'],
            'ips2' => $instance['ips2'],
            'ips3' => $instance['ips3'],
            'ips4' => $instance['ips4'],
            'actual' => $actual,
            'predicted' => $predicted,
            'correct' => ($predicted == $actual) ? 'Yes' : 'No'
        ];
        if ($predicted == $actual) {
            if ($actual == 'TEPAT WAKTU') { // Misalkan 'TEPAT WAKTU' adalah kelas positif
                $TP++;
            } else {
                $TN++;
            }
        } else {
            if ($predicted == 'TEPAT WAKTU') { // Kelas positif prediksi tetapi salah
                $FP++;
            } else {
                $FN++;
            }
        }
    }

    $accuracy = ($TP + $TN) / $total * 100; // Akurasi
    $sensitivity = ($TP / ($TP + $FN)) * 100; // Sensitivitas (True Positive Rate)
    $specificity = ($TN / ($TN + $FP)) * 100; // Spesifisitas (True Negative Rate)

    return [
        'accuracy' => $accuracy,
        'sensitivity' => $sensitivity,
        'specificity' => $specificity,
        'TP' => $TP,
        'FN' => $FN,
        'FP' => $FP,
        'TN' => $TN,
        'results' => $results
    ];
}

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    try {
        // Ambil semua data
        $allData = getAllData($pdo, $table_name);

        // Ambil subset data testing (30% awal)
        $testData = getTestData($allData);

        // Ambil pohon keputusan dari session
        if (isset($_SESSION['decision_tree'])) {
            $decisionTree = $_SESSION['decision_tree'];

            // Evaluasi model
            $metrics = evaluateModel($decisionTree, $testData);

            // Tampilkan metrik evaluasi dalam tabel
            echo "<table border='1' cellspacing='0' cellpadding='5' class='styled-table'>";
            echo "<center>";
            echo "<tr>";
            echo "<th rowspan='2'>Actual</th>";
            echo "<th colspan='2'>Prediksi</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Tepat Waktu</th>";
            echo "<th>Terlambat</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Tepat Waktu</td>";
            echo "<td>{$metrics['TP']}</td>";
            echo "<td>{$metrics['FN']}</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Terlambat</td>";
            echo "<td>{$metrics['FP']}</td>";
            echo "<td>{$metrics['TN']}</td>";
            echo "</tr>";
            echo "</center>";
            echo "</table>";

            // Tampilkan metrik evaluasi tambahan
            echo "<p>Accuracy: " . number_format($metrics['accuracy'], 2) . "%</p>";
            echo "<p>Specificity: " . number_format($metrics['specificity'], 2) . "%</p>";
            echo "<p>Sensitivity: " . number_format($metrics['sensitivity'], 2) . "%</p>";
        } else {
            echo "<h2>Pohon keputusan tidak ditemukan di session.</h2>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<h2>Parameter table_name tidak ditemukan.</h2>";
}
