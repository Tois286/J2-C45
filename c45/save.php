<?php
// save.php

// Sertakan file yang berisi fungsi
include 'mining.php';

// Simpan data entropi
$stmt = $koneksi->prepare("INSERT INTO entropy (nama_tabel, attribute, value, count, tepat_waktu, terlambat, entropy) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiiid", $table_name, $attribute, $value, $subsetCount, $countTEPAT_WAKTU, $countTERLAMBAT, $subsetEntropy);

$totalEntropy = calculateEntropy($data, $targetAttribute);
$totalCount = count($data);

foreach ($attributes as $attribute) {
    $attributeValues = array_unique(array_column($data, $attribute));

    foreach ($attributeValues as $value) {
        $subset = array_filter($data, function ($row) use ($attribute, $value) {
            return $row[$attribute] == $value;
        });

        $subsetCount = count($subset);
        if ($subsetCount == 0) {
            continue;
        }

        $subsetEntropy = calculateEntropy($subset, $targetAttribute);
        $gain = calculateGain($data, $attribute, $targetAttribute, $totalEntropy);

        // Count instances of each class
        $classCounts = array_count_values(array_column($subset, $targetAttribute));
        $countTEPAT_WAKTU = isset($classCounts['TEPAT WAKTU']) ? $classCounts['TEPAT WAKTU'] : 0;
        $countTERLAMBAT = isset($classCounts['TERLAMBAT']) ? $classCounts['TERLAMBAT'] : 0;

        // Simpan data entropi
        $stmt->execute();

        // Simpan data gain
        $stmt_gain = $koneksi->prepare("INSERT INTO gain (nama_tabel,attribute, value, gain) VALUES (?, ?, ?, ?)");
        $stmt_gain->bind_param("sssd", $table_name, $attribute, $value, $gain);
        $stmt_gain->execute();
    }
}

// Tutup statement dan koneksi
$stmt->close();
$stmt_gain->close();
?>
<script>
    alert('Proses selesai dan data berhasil disimpan.');
    window.location.href = '../index.php';
</script>