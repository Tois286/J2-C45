<?php
session_start(); // Mulai sesi PHP
// include '../config/koneksi.php';
// Fungsi untuk membuat pohon keputusan
function buildDecisionTree($data, $attributes)
{
    $total_count = count($data);

    // Jika semua data dalam subset memiliki klasifikasi yang sama, kembalikan label klasifikasi tersebut
    $tepat_waktu_count = array_reduce($data, function ($count, $row) {
        return $count + ($row['KETERANGAN'] == 'TEPAT WAKTU' ? 1 : 0);
    }, 0);
    $terlambat_count = $total_count - $tepat_waktu_count;

    if ($tepat_waktu_count == 0) {
        return 'TERLAMBAT';
    }
    if ($terlambat_count == 0) {
        return 'TEPAT WAKTU';
    }

    // Jika tidak ada atribut yang tersisa, kembalikan label klasifikasi mayoritas
    if (empty($attributes)) {
        return $tepat_waktu_count > $terlambat_count ? 'TEPAT WAKTU' : 'TERLAMBAT';
    }

    // Hitung entropi total
    $entropy_total = calculateEntropyTotal($total_count, $tepat_waktu_count, $terlambat_count);

    // Hitung gain untuk setiap atribut
    $gains = [];
    foreach ($attributes as $attribute) {
        $counts = array_count_values(array_column($data, $attribute));
        $gains[$attribute] = calculateGain($entropy_total, $counts, $total_count);
    }

    // Pilih atribut dengan gain tertinggi
    $best_attribute = array_keys($gains, max($gains))[0];

    // Buat node pohon dengan atribut terbaik
    $tree = [
        'attribute' => $best_attribute,
        'branches' => []
    ];

    // Hapus atribut terbaik dari daftar atribut
    $new_attributes = array_diff($attributes, [$best_attribute]);

    // Buat cabang untuk setiap nilai dari atribut terbaik
    $attribute_values = array_unique(array_column($data, $best_attribute));
    foreach ($attribute_values as $value) {
        // Filter subset data untuk nilai atribut ini
        $subset = array_filter($data, function ($row) use ($best_attribute, $value) {
            return $row[$best_attribute] == $value;
        });

        // Rekursif membangun pohon untuk subset data
        $tree['branches'][$value] = buildDecisionTree($subset, $new_attributes);
    }

    return $tree;
}

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $attributes = ['jenis_kelamin', 'ips1', 'ips2', 'ips3', 'ips4'];

        // Bangun pohon keputusan
        $decision_tree = buildDecisionTree($data, $attributes);
        // Simpan decision tree ke dalam sesi
        $_SESSION['decision_tree'] = $decision_tree;

        echo "<pre>";
        print_r($decision_tree);
        echo "</pre>";
        // Redirect ke pk.php
        header("Location: ./view/pk.php");
        echo "<h3>Pohon Keputusan</h3>";
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
