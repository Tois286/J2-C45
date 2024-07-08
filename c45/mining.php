<?php
include '../config/koneksi.php';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Mengambil Data dari Database
    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

            $keterangan_counts[$row['jenis_kelamin']][$row['keterangan']]++;
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

        // Output informasi yang diminta
        echo "Jumlah total data: " . $total_count . "<br>";
        echo "Jumlah LAKI-LAKI: " . $laki_laki_count . "<br>";
        echo "Jumlah PEREMPUAN: " . $perempuan_count . "<br>";
        echo "<br>";

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
        // Menghitung entropi untuk setiap jenis kelamin
        function calculateEntropyGender($count_laki, $count_perempuan, $total_count)
        {
            if ($total_count == 0) {
                return 0; // Menghindari pembagian dengan nol
            }

            // Hitung probabilitas
            $prob_laki = $count_laki / $total_count;
            $prob_perempuan = $count_perempuan / $total_count;

            // Hitung entropi
            $entropy = 0;
            if ($prob_laki > 0 && $prob_perempuan > 0) {
                $entropy = (-$prob_laki * log($prob_laki, 2)) + (-$prob_perempuan * log($prob_perempuan, 2));
            }

            return $entropy;
        }

        // ...
        // Menghitung jumlah data berdasarkan jenis kelamin
        $laki_laki_count = 0;
        $perempuan_count = 0;

        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                $laki_laki_count++;
            } elseif ($row['jenis_kelamin'] == 'PEREMPUAN') {
                $perempuan_count++;
            }
        }

        // Hitung entropi untuk jenis kelamin
        $entropy_gender = calculateEntropyGender($laki_laki_count, $perempuan_count, $total_count);
        echo "Entropy Jenis Kelamin: " . $entropy_gender . "<br>";
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
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
