<?php
// include '../config/koneksi.php';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_count = count($data);

        // Hitung jumlah tepat waktu dan terlambat
        $tepat_waktu_count = 0;
        $terlambat_count = 0;
        foreach ($data as $row) {
            if ($row['keterangan'] == 'TEPAT WAKTU') {
                $tepat_waktu_count++;
            } else if ($row['keterangan'] == 'TERLAMBAT') {
                $terlambat_count++;
            }
        }

        // Hitung entropy total
        $prob_tepat_waktu = $tepat_waktu_count / $total_count;
        $prob_terlambat = $terlambat_count / $total_count;
        $entropy_total = - ($prob_tepat_waktu * log($prob_tepat_waktu, 2)) - ($prob_terlambat * log($prob_terlambat, 2));

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
            return $entropy;
        }

        // Hitung Gain untuk setiap atribut IPS
        $ips_attributes = ['ips1', 'ips2', 'ips3', 'ips4'];
        foreach ($ips_attributes as $ips_attr) {
            $ips_values = array_unique(array_column($data, $ips_attr));
            $entropy_ips = 0;
            foreach ($ips_values as $value) {
                $count_tepat = 0;
                $count_terlambat = 0;
                foreach ($data as $row) {
                    if ($row[$ips_attr] == $value) {
                        if ($row['keterangan'] == 'TEPAT WAKTU') {
                            $count_tepat++;
                        } else if ($row['keterangan'] == 'TERLAMBAT') {
                            $count_terlambat++;
                        }
                    }
                }
                $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
                $total_value_count = $count_tepat + $count_terlambat;
                $entropy_ips += ($total_value_count / $total_count) * $entropy_value;
            }
            $gain_ips = $entropy_total - $entropy_ips;
            echo "Gain $ips_attr: " . $gain_ips . "<br>";
        }

        // Hitung informasi gain untuk jenis kelamin
        $jenis_kelamin_values = ['LAKI-LAKI', 'PEREMPUAN'];
        $entropy_jenis_kelamin = 0;
        foreach ($jenis_kelamin_values as $value) {
            $count_tepat = 0;
            $count_terlambat = 0;
            foreach ($data as $row) {
                if ($row['jenis_kelamin'] == $value) {
                    if ($row['keterangan'] == 'TEPAT WAKTU') {
                        $count_tepat++;
                    } else if ($row['keterangan'] == 'TERLAMBAT') {
                        $count_terlambat++;
                    }
                }
            }
            $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
            $total_value_count = $count_tepat + $count_terlambat;
            $entropy_jenis_kelamin += ($total_value_count / $total_count) * $entropy_value;
        }
        $gain_jenis_kelamin = $entropy_total - $entropy_jenis_kelamin;
        echo "Gain Jenis Kelamin: " . $gain_jenis_kelamin . "<br>";

        // Memulai pembuatan pohon keputusan
        function build_tree($data, $depth = 0)
        {
            $indent = str_repeat("&nbsp;", $depth * 4);

            if (empty($data) || count(array_unique(array_column($data, 'keterangan'))) == 1) {
                if (empty($data)) {
                    echo $indent . "Tidak memiliki gain tertinggi";
                } else {
                    // Pastikan $data[0] ada sebelum mencoba mengakses 'keterangan'
                    if (isset($data[0]['keterangan'])) {
                        echo "<br>";

                        echo $indent . "Keputusan: " . $data[0]['keterangan'];
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
                    build_tree($subset, $depth + 1);
                }
            }
        }

        // Fungsi untuk menghitung entropy
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

        // Fungsi untuk menghitung information gain
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

        // Memulai pembuatan pohon
        build_tree($data);
        // Periksa apakah query mengembalikan data yang diharapkan
        if (empty($data)) {
            echo "Data kosong atau tidak ditemukan.";
            return;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
