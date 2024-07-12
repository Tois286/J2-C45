<?php

// Menghitung gain untuk setiap atribut
function calculateGain($total_entropy, $counts, $total_count)
{
    $weighted_entropy_sum = 0;

    foreach ($counts as $count) {
        $weighted_entropy_sum += ($count / $total_count) * calculateEntropy($count, $total_count);
    }

    return $total_entropy - $weighted_entropy_sum;
}

$gain_ips1 = calculateGain($entropy_total, $ips1_counts, $total_count);
$gain_ips2 = calculateGain($entropy_total, $ips2_counts, $total_count);
$gain_ips3 = calculateGain($entropy_total, $ips3_counts, $total_count);
$gain_ips4 = calculateGain($entropy_total, $ips4_counts, $total_count);

echo "<br>";
echo "<h3>----------------------------------------</h3>";

echo "<h3>Gain untuk setiap IPS</h3>";
echo "Gain IPS1: " . $gain_ips1 . "<br>";
echo "Gain IPS2: " . $gain_ips2 . "<br>";
echo "Gain IPS3: " . $gain_ips3 . "<br>";
echo "Gain IPS4: " . $gain_ips4 . "<br>";

// Memilih atribut dengan gain tertinggi untuk split pertama
$attributes_gain = array(
    'ips1' => $gain_ips1,
    'ips2' => $gain_ips2,
    'ips3' => $gain_ips3,
    'ips4' => $gain_ips4
);

$best_attribute = array_keys($attributes_gain, max($attributes_gain))[0];
echo "Atribut terbaik untuk split pertama: " . $best_attribute . "<br>";
echo "<br>";
echo "<h3>----------------------------------------</h3>";
echo "<br>";
