<?php
session_start();
include '../config/koneksi.php';

function calculateEntropy($counts)
{
    $total = array_sum($counts);
    $entropy = 0;

    foreach ($counts as $count) {
        if ($count > 0) {
            $prob = $count / $total;
            $entropy -= $prob * log($prob, 2);
        }
    }

    return $entropy;
}

function calculateGain($entropy_total, $counts_tepat, $counts_terlambat)
{
    $total_counts = array_sum($counts_tepat) + array_sum($counts_terlambat);
    $weighted_entropy = 0;

    $categories = array_unique(array_merge(array_keys($counts_tepat), array_keys($counts_terlambat)));
    foreach ($categories as $category) {
        $count_tepat = $counts_tepat[$category] ?? 0;
        $count_terlambat = $counts_terlambat[$category] ?? 0;
        $subset_counts = $count_tepat + $count_terlambat;
        if ($subset_counts > 0) {
            $entropy_attribute = calculateEntropy([$count_tepat, $count_terlambat]);
            $weighted_entropy += ($subset_counts / $total_counts) * $entropy_attribute;
        }
    }

    return $entropy_total - $weighted_entropy;
}

function buildDecisionTree($data, $attributes)
{
    if (empty($data)) {
        return null;
    }

    // Hitung entropi total
    $total_count = count($data);
    $tepat_waktu_count = count(array_filter($data, fn ($row) => $row['KETERANGAN'] == 'TEPAT WAKTU'));
    $terlambat_count = count(array_filter($data, fn ($row) => $row['KETERANGAN'] == 'TERLAMBAT'));
    $entropy_total = calculateEntropy([$tepat_waktu_count, $terlambat_count]);

    $best_attribute = null;
    $best_gain = -1;

    foreach ($attributes as $attribute) {
        $counts_tepat = array_fill_keys(['SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG'], 0);
        $counts_terlambat = array_fill_keys(['SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG'], 0);

        foreach ($data as $row) {
            $attribute_value = $row[$attribute];
            if ($row['KETERANGAN'] == 'TEPAT WAKTU') {
                $counts_tepat[$attribute_value]++;
            } elseif ($row['KETERANGAN'] == 'TERLAMBAT') {
                $counts_terlambat[$attribute_value]++;
            }
        }

        $gain = calculateGain($entropy_total, $counts_tepat, $counts_terlambat);

        if ($gain > $best_gain) {
            $best_gain = $gain;
            $best_attribute = $attribute;
        }
    }

    return $best_attribute;
}

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $attributes = ['ips1', 'ips2', 'ips3', 'ips4'];
        $best_attribute = buildDecisionTree($data, $attributes);

        echo "<h3>Pohon Keputusan</h3>";
        echo "Atribut terbaik untuk split pertama: " . $best_attribute . "<br>";

        echo "<h3>Step Tree</h3>";
        echo "Implementasi pohon keputusan dengan atribut terbaik: " . $best_attribute . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
