<?php
session_start(); // Mulai sesi PHP
// include '../config/koneksi.php';
// Fungsi untuk membuat pohon keputusan
function buildDecisionTree($data, $best_attribute, $depth = 0, $max_depth = 10)
{
    // Batasi kedalaman pohon
    if ($depth >= $max_depth) {
        return "Leaf node"; // Atau gunakan prediksi mayoritas
    }

    // Pisahkan data berdasarkan nilai atribut terbaik
    $groups = array();
    foreach ($data as $row) {
        $attribute_value = $row[$best_attribute];
        if (!isset($groups[$attribute_value])) {
            $groups[$attribute_value] = array();
        }
        $groups[$attribute_value][] = $row;
    }

    // Tampilkan pohon keputusan dalam bentuk array
    $tree = array();
    foreach ($groups as $value => $group) {
        $next_best_attribute = getNextBestAttribute($group);
        if ($next_best_attribute !== null) {
            $tree[$best_attribute][$value] = buildDecisionTree($group, $next_best_attribute, $depth + 1, $max_depth);
        } else {
            $tree[$best_attribute][$value] = "Leaf node"; // Atau gunakan prediksi mayoritas
        }
    }

    return $tree;
}

// Fungsi untuk memilih atribut terbaik berikutnya (dummy untuk contoh)
function getNextBestAttribute($data)
{
    // Contoh: Secara acak memilih atribut lainnya sebagai contoh
    $attributes = array_keys($data[0]); // Ambil semua atribut
    if (count($attributes) > 1) {
        $next_best_attribute = $attributes[rand(0, count($attributes) - 1)]; // Pilih secara acak (ini hanya contoh)
        return $next_best_attribute;
    }
    return null; // Jika tidak ada atribut lain yang tersedia
}

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (isset($data) && isset($best_attribute)) {

            $decision_tree = buildDecisionTree($data, $best_attribute);
            $_SESSION['decision_tree'] = $decision_tree;

            // Outputkan pohon keputusan
            echo "<pre>";
            print_r($data);
            echo "</pre>";

            echo "<pre>";
            print_r($decision_tree);
            echo "</pre>";

            header("Location: view/pk.php");
            echo "<h3>Pohon Keputusan</h3>";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
