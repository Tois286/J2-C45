<?php
// include '../config/koneksi.php';
// Fungsi untuk menghitung entropy
function hitung_entropy($count_tepat, $count_terlambat)
{
    $total = $count_tepat + $count_terlambat;
    if ($total == 0) return 0;
    $prob_tepat = $count_tepat / $total;
    $prob_terlambat = $count_terlambat / $total;
    $entropy = 0;
    if ($prob_tepat > 0) {
        $entropy -= $prob_tepat * log($prob_tepat, 2);
    }
    if ($prob_terlambat > 0) {
        $entropy -= $prob_terlambat * log($prob_terlambat, 2);
    }

    // Handle NaN case
    if (is_nan($entropy)) {
        return 0;
    }

    return $entropy;
}

// Fungsi untuk menghitung entropy dari seluruh dataset
function calculate_entropy($data)
{
    $total_count = count($data);
    if ($total_count == 0) {
        return 0;
    }

    $counts = array_count_values(array_column($data, 'keterangan'));
    $entropy = 0;
    foreach ($counts as $count) {
        $probability = $count / $total_count;
        $entropy -= $probability * log($probability, 2);
    }

    // Handle NaN case
    if (is_nan($entropy)) {
        return 0;
    }

    return $entropy;
}

// Fungsi untuk menghitung information gain dari suatu atribut
function calculate_information_gain($data, $attribute)
{
    $total_entropy = calculate_entropy($data);
    $values = array_unique(array_column($data, $attribute));
    $weighted_entropy = 0;
    foreach ($values as $value) {
        $subset = array_filter($data, function ($row) use ($attribute, $value) {
            return isset($row[$attribute]) && $row[$attribute] == $value;
        });
        $subset_entropy = calculate_entropy($subset);
        $weighted_entropy += (count($subset) / count($data)) * $subset_entropy;
    }
    return $total_entropy - $weighted_entropy;
}

// Fungsi untuk membangun pohon keputusan
function build_tree($data, $depth = 0, $max_depth = PHP_INT_MAX)
{
    $indent = str_repeat("&nbsp;", $depth * 4);

    if (empty($data) || count(array_unique(array_column($data, 'keterangan'))) == 1 || $depth >= $max_depth) {
        if (empty($data)) {
            echo $indent . "Tidak memiliki gain tertinggi";
        } else {
            if (isset($data[0]['keterangan'])) {
                echo "<br>";
                echo $indent . "Keputusan: " . $data[0]['keterangan'];
                echo "<br>";
                echo $indent . "Entropy: " . calculate_entropy($data);
                echo "<br>";
            } else {
                echo "<br>";
                echo $indent . "Tidak memiliki gain tertinggi";
                echo "<br>";
            }
        }
        return;
    }

    // Hitung informasi gain untuk setiap atribut
    $attributes = ['jenis_kelamin', 'ips1', 'ips2', 'ips3', 'ips4'];
    $best_gain = 0;
    $best_attr = null;
    foreach ($attributes as $attr) {
        $gain = calculate_information_gain($data, $attr);
        if ($gain > $best_gain) {
            $best_gain = $gain;
            $best_attr = $attr;
        }
    }

    // Jika entropi bernilai 0, kembalikan keputusan berdasarkan mayoritas
    if ($best_gain == 0) {
        $most_common_keterangan = array_count_values(array_column($data, 'keterangan'));
        arsort($most_common_keterangan);
        $keterangan = array_key_first($most_common_keterangan);
        echo $indent . "Keputusan: " . $keterangan . "<br>";
        return;
    }

    // Pisahkan data berdasarkan atribut terbaik
    $values = array_unique(array_column($data, $best_attr));
    foreach ($values as $value) {
        echo $indent . $best_attr . " = " . $value . "<br>";
        $subset = array_filter($data, function ($row) use ($best_attr, $value) {
            return isset($row[$best_attr]) && $row[$best_attr] == $value;
        });

        if (empty($subset)) {
            $most_common_keterangan = array_count_values(array_column($data, 'keterangan'));
            arsort($most_common_keterangan);
            $keterangan = array_key_first($most_common_keterangan);
            echo $indent . "Keputusan: " . $keterangan . "<br>";
        } else {
            build_tree($subset, $depth + 1, $max_depth);
        }
    }
}
