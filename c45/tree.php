<?php
include '../config/koneksi.php';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Mengambil Data dari Database
    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Menampilkan pohon keputusan
        function build_tree($data, $depth = 0)
        {
            // Indentasi untuk setiap level
            $indent = str_repeat("&nbsp;", $depth * 4);

            // Basis kasus: jika data kosong atau hanya satu kategori keterangan
            if (empty($data) || count(array_unique(array_column($data, 'keterangan'))) == 1) {
                $keterangan = empty($data) ? "UNKNOWN" : $data[0]['keterangan'];
                echo $indent . "Keputusan: " . $keterangan . "<br>";
                return;
            }

            // Hitung entropi dan informasi gain untuk setiap atribut
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

            if ($best_attr === null) {
                // Tidak ada atribut yang memberikan informasi gain positif
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
                    return $row[$best_attr] == $value;
                });
                build_tree($subset, $depth + 1);
            }
        }

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
            return $entropy;
        }

        function calculate_information_gain($data, $attribute)
        {
            $total_entropy = calculate_entropy($data);
            $values = array_unique(array_column($data, $attribute));
            $weighted_entropy = 0;
            foreach ($values as $value) {
                $subset = array_filter($data, function ($row) use ($attribute, $value) {
                    return $row[$attribute] == $value;
                });
                $subset_entropy = calculate_entropy($subset);
                $weighted_entropy += (count($subset) / count($data)) * $subset_entropy;
            }
            return $total_entropy - $weighted_entropy;
        }

        // Memulai pembuatan pohon
        build_tree($data);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
