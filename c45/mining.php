<head>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<a href="../index.php" value="' . $table_name . '" class="button-mining">Back</a>
<?php
// echo ini_get('memory_limit');
memory_get_usage();
ini_set('memory_limit', '512M');

session_start();
include '../config/koneksi.php';
// include 'tree.php';

if (!function_exists('calculateEntropyGender')) {
    function calculateEntropyGender($laki_counts, $perempuan_counts)
    {
        // Menghitung entropi untuk LAKI-LAKI
        $total_laki = array_sum($laki_counts);
        $entropy_laki = 0;
        if ($total_laki > 0) {
            foreach ($laki_counts as $count) {
                if ($count > 0) {
                    $prob = $count / $total_laki;
                    $entropy_laki -= $prob * log($prob, 2);
                }
            }
        }

        // Menghitung entropi untuk PEREMPUAN
        $total_perempuan = array_sum($perempuan_counts);
        $entropy_perempuan = 0;
        if ($total_perempuan > 0) {
            foreach ($perempuan_counts as $count) {
                if ($count > 0) {
                    $prob = $count / $total_perempuan;
                    $entropy_perempuan -= $prob * log($prob, 2);
                }
            }
        }

        return array('LAKI-LAKI' => $entropy_laki, 'PEREMPUAN' => $entropy_perempuan);
    }
}
if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    // Mengambil Data dari Database

    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inisialisasi variabel jumlah untuk nilai IPS dan KETERANGAN
        $tepat_waktu_counts = array(
            'SANGAT BAIK' => 0,
            'BAIK' => 0,
            'CUKUP' => 0,
            'KURANG' => 0
        );

        $terlambat_counts = array(
            'SANGAT BAIK' => 0,
            'BAIK' => 0,
            'CUKUP' => 0,
            'KURANG' => 0
        );

        // Hitung jumlah berdasarkan KETERANGAN dan nilai IPS
        foreach ($data as $row) {
            if ($row['KETERANGAN'] == 'TEPAT WAKTU') {
                $tepat_waktu_counts[$row['ips1']]++;
                $tepat_waktu_counts[$row['ips2']]++;
                $tepat_waktu_counts[$row['ips3']]++;
                $tepat_waktu_counts[$row['ips4']]++;
            } elseif ($row['KETERANGAN'] == 'TERLAMBAT') {
                $terlambat_counts[$row['ips1']]++;
                $terlambat_counts[$row['ips2']]++;
                $terlambat_counts[$row['ips3']]++;
                $terlambat_counts[$row['ips4']]++;
            }
        }
        // Hitung jumlah total data
        $total_count = count($data);

        // Inisialisasi variabel jumlah untuk jenis kelamin dan nilai IPS
        $laki_laki_count = 0;
        $perempuan_count = 0;
        // Inisialisasi jumlah data TEPAT WAKTU dan TERLAMBAT
        $tepat_waktu_count = 0;
        $terlambat_count = 0;

        foreach ($data as $row) {
            if ($row['KETERANGAN'] == 'TEPAT WAKTU') {
                $tepat_waktu_count++;
            } elseif ($row['KETERANGAN'] == 'TERLAMBAT') {
                $terlambat_count++;
            }
        }

        $ips1_counts = array('KURANG' => 0, 'CUKUP' => 0, 'BAIK' => 0, 'SANGAT BAIK' => 0);
        $ips2_counts = array('KURANG' => 0, 'CUKUP' => 0, 'BAIK' => 0, 'SANGAT BAIK' => 0);
        $ips3_counts = array('KURANG' => 0, 'CUKUP' => 0, 'BAIK' => 0, 'SANGAT BAIK' => 0);
        $ips4_counts = array('KURANG' => 0, 'CUKUP' => 0, 'BAIK' => 0, 'SANGAT BAIK' => 0);

        $keterangan_counts = array(
            'LAKI-LAKI' => array('TEPAT WAKTU' => 0, 'TERLAMBAT' => 0),
            'PEREMPUAN' => array('TEPAT WAKTU' => 0, 'TERLAMBAT' => 0)
        );


        // Hitung jumlah berdasarkan jenis kelamin dan nilai IPS
        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                $laki_laki_count++;
            } elseif ($row['jenis_kelamin'] == 'PEREMPUAN') {
                $perempuan_count++;
            }

            $ips1_counts[$row['ips1']]++;
            $ips2_counts[$row['ips2']]++;
            $ips3_counts[$row['ips3']]++;
            $ips4_counts[$row['ips4']]++;

            $keterangan_counts[$row['jenis_kelamin']][$row['KETERANGAN']]++;
        }

        function calculateEntropyTotal($total_count, $tepat_waktu_count, $terlambat_count)
        {
            // Menghitung probabilitas untuk setiap kategori
            $prob_tepat_waktu = $tepat_waktu_count / $total_count;
            $prob_terlambat = $terlambat_count / $total_count;

            // Menghitung entropy total
            $entropy_total = (-$prob_tepat_waktu * log($prob_tepat_waktu, 2)) - ($prob_terlambat * log($prob_terlambat, 2));

            return $entropy_total;
        }
        // Menghitung entropi untuk setiap atribut IPS
        function calculateEntropy($count_good, $count_total)
        {
            if ($count_total == 0) {
                return 0; // Menghindari pembagian dengan nol
            }
            $prob_good = $count_good / $count_total;
            $prob_bad = 1 - $prob_good;

            // Handle log(0) case
            $entropy = 0;
            if ($prob_good != 0 && $prob_bad != 0) {
                $entropy = (-$prob_good * log($prob_good, 2)) + (-$prob_bad * log($prob_bad, 2));
            }
            return $entropy;
        }
        // Inisialisasi variabel jumlah untuk nilai IPS dan KETERANGAN
        $tepat_waktu_counts_ips1 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $tepat_waktu_counts_ips2 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $tepat_waktu_counts_ips3 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $tepat_waktu_counts_ips4 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);

        $terlambat_counts_ips1 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $terlambat_counts_ips2 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $terlambat_counts_ips3 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);
        $terlambat_counts_ips4 = array('SANGAT BAIK' => 0, 'BAIK' => 0, 'CUKUP' => 0, 'KURANG' => 0);

        // Hitung jumlah berdasarkan KETERANGAN dan nilai IPS
        foreach ($data as $row) {
            if ($row['KETERANGAN'] == 'TEPAT WAKTU') {
                $tepat_waktu_counts_ips1[$row['ips1']]++;
                $tepat_waktu_counts_ips2[$row['ips2']]++;
                $tepat_waktu_counts_ips3[$row['ips3']]++;
                $tepat_waktu_counts_ips4[$row['ips4']]++;
            } elseif ($row['KETERANGAN'] == 'TERLAMBAT') {
                $terlambat_counts_ips1[$row['ips1']]++;
                $terlambat_counts_ips2[$row['ips2']]++;
                $terlambat_counts_ips3[$row['ips3']]++;
                $terlambat_counts_ips4[$row['ips4']]++;
            }
        }
        echo "<h3>DATA MINING</h3>";

        echo "<h3>----------------------------------------</h3>";

        // Output informasi yang diminta
        echo "Jumlah total data: " . $total_count . "<br>";
        echo "Jumlah LAKI-LAKI: " . $laki_laki_count . "<br>";
        echo "Jumlah PEREMPUAN: " . $perempuan_count . "<br>";
        echo "Jumlah data TEPAT WAKTU: " . $tepat_waktu_count . "<br>";
        echo "Jumlah data TERLAMBAT: " . $terlambat_count . "<br>";
        // Output informasi yang diminta
        echo "<h3>----------------------------------------</h3>";

        echo "<br>";
        echo "<h3>TEPAT WAKTU</h3>";
        foreach ($tepat_waktu_counts_ips1 as $grade => $count) {
            echo "Jumlah IPS1 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($tepat_waktu_counts_ips2 as $grade => $count) {
            echo "Jumlah IPS2 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($tepat_waktu_counts_ips3 as $grade => $count) {
            echo "Jumlah IPS3 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($tepat_waktu_counts_ips4 as $grade => $count) {
            echo "Jumlah IPS4 $grade: " . $count . "<br>";
        }
        echo "<br>";
        echo "<h3>----------------------------------------</h3>";

        echo "<h3>TERLAMBAT</h3>";
        foreach ($terlambat_counts_ips1 as $grade => $count) {
            echo "Jumlah IPS1 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($terlambat_counts_ips2 as $grade => $count) {
            echo "Jumlah IPS2 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($terlambat_counts_ips3 as $grade => $count) {
            echo "Jumlah IPS3 $grade: " . $count . "<br>";
        }
        echo "<br>";
        foreach ($terlambat_counts_ips4 as $grade => $count) {
            echo "Jumlah IPS4 $grade: " . $count . "<br>";
        }
        echo "<h3>----------------------------------------</h3>";
        echo "<h3>JUMLAH IPS TOTAL</h3>";
        // Inisialisasi array untuk menyimpan nilai

        foreach ($ips1_counts as $key => $value) {
            echo "Jumlah IPS1 $key: " . $value . "<br>";
        }
        echo "<br>";

        foreach ($ips2_counts as $key => $value) {
            echo "Jumlah IPS2 $key: " . $value . "<br>";
        }
        echo "<br>";

        foreach ($ips3_counts as $key => $value) {
            echo "Jumlah IPS3 $key: " . $value . "<br>";
        }
        echo "<br>";

        foreach ($ips4_counts as $key => $value) {
            echo "Jumlah IPS4 $key: " . $value . "<br>";
        }
        echo "<br>";

        foreach ($keterangan_counts as $jenis_kelamin => $keterangan) {
            foreach ($keterangan as $status => $count) {
                echo "Jumlah $jenis_kelamin $status: " . $count . "<br>";
            }
        }

        echo "<br>";
        echo "<h3>----------------------------------------</h3>";
        echo "<h3>JUMLAH TEPAT WAKTU</h3>";

        // Output informasi yang diminta
        echo "Jumlah TEPAT WAKTU SANGAT BAIK: " . $tepat_waktu_counts['SANGAT BAIK'] . "<br>";
        echo "Jumlah TEPAT WAKTU BAIK: " . $tepat_waktu_counts['BAIK'] . "<br>";
        echo "Jumlah TEPAT WAKTU CUKUP: " . $tepat_waktu_counts['CUKUP'] . "<br>";
        echo "Jumlah TEPAT WAKTU KURANG: " . $tepat_waktu_counts['KURANG'] . "<br>";
        echo "<br>";
        echo "<h3>----------------------------------------</h3>";

        echo "<h3>JUMLAH TERLAMBAT</h3>";
        echo "Jumlah TERLAMBAT SANGAT BAIK: " . $terlambat_counts['SANGAT BAIK'] . "<br>";
        echo "Jumlah TERLAMBAT BAIK: " . $terlambat_counts['BAIK'] . "<br>";
        echo "Jumlah TERLAMBAT CUKUP: " . $terlambat_counts['CUKUP'] . "<br>";
        echo "Jumlah TERLAMBAT KURANG: " . $terlambat_counts['KURANG'] . "<br>";
        echo "<br>";


        // Menghitung entropi untuk masing-masing jenis kelamin
        $entropy_gender = calculateEntropyGender(
            array($keterangan_counts['LAKI-LAKI']['TEPAT WAKTU'], $keterangan_counts['LAKI-LAKI']['TERLAMBAT']),
            array($keterangan_counts['PEREMPUAN']['TEPAT WAKTU'], $keterangan_counts['PEREMPUAN']['TERLAMBAT'])
        );

        echo "<h3>Entropy Total Data</h3>";
        $entropy_total = calculateEntropyTotal($total_count, $tepat_waktu_count, $terlambat_count);

        echo "Entropy Total: " . $entropy_total;
        echo "<h3>----------------------------------------</h3>";

        echo "<h3>Entropy Jenis Kelamin</h3>";
        // Output entropi untuk masing-masing jenis kelamin
        echo "Entropy LAKI-LAKI: " . $entropy_gender['LAKI-LAKI'] . "<br>";
        echo "Entropy PEREMPUAN: " . $entropy_gender['PEREMPUAN'] . "<br>";
        echo "<br>";

        // Menghitung entropy untuk keseluruhan data
        function calculateGainGender($entropy_total, $entropy_gender, $total_count, $laki_laki_count, $perempuan_count)
        {
            $entropy_laki = $entropy_gender['LAKI-LAKI']; // Entropi untuk LAKI-LAKI
            $entropy_perempuan = $entropy_gender['PEREMPUAN']; // Entropi untuk PEREMPUAN
            // Inisialisasi variabel jumlah untuk jenis kelamin dan nilai IPS

            // Hitung gain untuk jenis kelamin
            $gender_gain = $entropy_total - (($laki_laki_count / $total_count) * $entropy_laki + ($perempuan_count / $total_count) * $entropy_perempuan);

            return $gender_gain;
        }
        // Hitung gain untuk jenis kelamin
        $gender_gain = calculateGainGender($entropy_total, $entropy_gender, $total_count, $laki_laki_count, $perempuan_count);
        echo "Gain untuk Jenis Kelamin: " . $gender_gain;

        // Menghitung entropi untuk setiap atribut IPS
        function calculateEntropyIPS($counts)
        {
            // mulai hitung gain ips
            $total = array_sum($counts);
            $entropy = 0;

            if ($total > 0) {
                foreach ($counts as $count) {
                    if ($count > 0) {
                        $prob = $count / $total;
                        $entropy -= $prob * log($prob, 2);
                    }
                }
            }

            return $entropy;
        }

        // Menghitung entropy untuk setiap kategori pada IPS1
        $entropy_ips1 = array();
        foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
            $counts_ips1 = array(
                $tepat_waktu_counts_ips1[$kategori],
                $terlambat_counts_ips1[$kategori]
            );
            $entropy_ips1[$kategori] = calculateEntropyIPS($counts_ips1);
        }

        $entropy_ips2 = array();
        foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
            $counts_ips2 = array(
                $tepat_waktu_counts_ips2[$kategori],
                $terlambat_counts_ips2[$kategori]
            );
            $entropy_ips2[$kategori] = calculateEntropyIPS($counts_ips2);
        }

        $entropy_ips3 = array();
        foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
            $counts_ips3 = array(
                $tepat_waktu_counts_ips3[$kategori],
                $terlambat_counts_ips3[$kategori]
            );
            $entropy_ips3[$kategori] = calculateEntropyIPS($counts_ips3);
        }

        $entropy_ips4 = array();
        foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
            $counts_ips4 = array(
                $tepat_waktu_counts_ips4[$kategori],
                $terlambat_counts_ips4[$kategori]
            );
            $entropy_ips4[$kategori] = calculateEntropyIPS($counts_ips4);
        }

        echo "<h3>----------------------------------------</h3>";
        echo "<br>";
        // Melanjutkan perhitungan entropi untuk setiap kategori pada IPS2, IPS3, dan IPS4
        // Disesuaikan dengan pola yang sama seperti yang dilakukan untuk IPS1
        // Output entropi untuk setiap kategori pada setiap IPS

        echo "<h3>Entropy untuk kategori SANGAT BAIK, BAIK, CUKUP, dan KURANG pada setiap IPS</h3>";

        foreach (array('IPS1', 'IPS2', 'IPS3', 'IPS4') as $ips) {
            foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
                echo "Entropy $ips $kategori: " . ${"entropy_" . strtolower($ips)}[$kategori] . "<br>";
            }
        }
        function calculateCategoryEntropy($counts_tepat, $counts_terlambat)
        {
            $entropy = array();
            foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
                $counts = array(
                    $counts_tepat[$kategori],
                    $counts_terlambat[$kategori]
                );
                $entropy[$kategori] = calculateEntropyIPS($counts);
            }
            return $entropy;
        }

        function calculateGain($entropy_total, $counts_tepat, $counts_terlambat, $entropy_attribute)
        {
            $total_counts = array_sum($counts_tepat) + array_sum($counts_terlambat);
            $weighted_entropy = 0;

            foreach (array('SANGAT BAIK', 'BAIK', 'CUKUP', 'KURANG') as $kategori) {
                $subset_counts = $counts_tepat[$kategori] + $counts_terlambat[$kategori];
                if ($subset_counts > 0) {
                    $weighted_entropy += ($subset_counts / $total_counts) * $entropy_attribute[$kategori];
                }
            }

            return $entropy_total - $weighted_entropy;
        }

        $gain_ips1 = calculateGain($entropy_total, $tepat_waktu_counts_ips1, $terlambat_counts_ips1, $entropy_ips1);
        $gain_ips2 = calculateGain($entropy_total, $tepat_waktu_counts_ips2, $terlambat_counts_ips2, $entropy_ips2);
        $gain_ips3 = calculateGain($entropy_total, $tepat_waktu_counts_ips3, $terlambat_counts_ips3, $entropy_ips3);
        $gain_ips4 = calculateGain($entropy_total, $tepat_waktu_counts_ips4, $terlambat_counts_ips4, $entropy_ips4);

        // Menampilkan hasil
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
        echo "<h3>Data Rule</h3>";
        echo "<h3>----------------------------------------</h3>";

        echo "<br>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
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
        // Memanggil fungsi untuk membangun pohon keputusan
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
