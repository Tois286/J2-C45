<?php
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


        // Output informasi yang diminta
        echo "Jumlah total data: " . $total_count . "<br>";
        echo "Jumlah LAKI-LAKI: " . $laki_laki_count . "<br>";
        echo "Jumlah PEREMPUAN: " . $perempuan_count . "<br>";
        // Output informasi yang diminta
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
        echo "<h3>JUMLAH TERLAMBAT</h3>";
        echo "Jumlah TERLAMBAT SANGAT BAIK: " . $terlambat_counts['SANGAT BAIK'] . "<br>";
        echo "Jumlah TERLAMBAT BAIK: " . $terlambat_counts['BAIK'] . "<br>";
        echo "Jumlah TERLAMBAT CUKUP: " . $terlambat_counts['CUKUP'] . "<br>";
        echo "Jumlah TERLAMBAT KURANG: " . $terlambat_counts['KURANG'] . "<br>";
        echo "<br>";
        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                $laki_laki_count++;
            } elseif ($row['jenis_kelamin'] == 'PEREMPUAN') {
                $perempuan_count++;
            }
        }

        // Menghitung entropi untuk masing-masing jenis kelamin
        $entropy_gender = calculateEntropyGender(
            array($keterangan_counts['LAKI-LAKI']['TEPAT WAKTU'], $keterangan_counts['LAKI-LAKI']['TERLAMBAT']),
            array($keterangan_counts['PEREMPUAN']['TEPAT WAKTU'], $keterangan_counts['PEREMPUAN']['TERLAMBAT'])
        );

        // Output entropi untuk masing-masing jenis kelamin
        echo "Entropy LAKI-LAKI: " . $entropy_gender['LAKI-LAKI'] . "<br>";
        echo "Entropy PEREMPUAN: " . $entropy_gender['PEREMPUAN'] . "<br>";
        echo "<br>";
        // Hitung entropi untuk setiap atribut IPS
        $entropy_ips1 = calculateEntropy($ips1_counts['SANGAT BAIK'], $ips1_counts['BAIK']);
        echo "Entropy IPS1 BAIK: " . $entropy_ips1 . "<br>";
        echo "<br>";

        $entropy_ips2 = calculateEntropy($ips2_counts['SANGAT BAIK'], $ips2_counts['BAIK']);
        echo "Entropy IPS2 BAIK: " . $entropy_ips2 . "<br>";
        echo "<br>";

        $entropy_ips3 = calculateEntropy($ips3_counts['SANGAT BAIK'], $ips3_counts['BAIK']);
        echo "Entropy IPS3 BAIK: " . $entropy_ips3 . "<br>";
        echo "<br>";

        $entropy_ips4 = calculateEntropy($ips4_counts['SANGAT BAIK'], $ips4_counts['BAIK']);
        echo "Entropy IPS4 BAIK: " . $entropy_ips4 . "<br>";
        echo "<br>";

        // Menghitung entropy untuk keseluruhan data
        $entropy_total = calculateEntropy(
            $keterangan_counts['LAKI-LAKI']['TEPAT WAKTU'] + $keterangan_counts['PEREMPUAN']['TEPAT WAKTU'],
            $total_count
        );

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
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
