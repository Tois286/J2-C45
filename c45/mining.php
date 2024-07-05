<?php
include '../config/koneksi.php';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    // Mengambil Data dari Database
    try {
        $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, keterangan FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // include 'tree.php';
        // Hitung jumlah total data
        $total_count = count($data);

        // Hitung jumlah laki-laki dan perempuan
        $laki_laki_count = 0;
        $perempuan_count = 0;

        // Hitung jumlah nilai IPS1
        $ips1_kurang = 0;
        $ips1_cukup = 0;
        $ips1_baik = 0;
        $ips1_sangat_baik = 0;

        // Hitung jumlah nilai IPS2
        $ips2_kurang = 0;
        $ips2_cukup = 0;
        $ips2_baik = 0;
        $ips2_sangat_baik = 0;

        // Hitung jumlah nilai IPS3
        $ips3_kurang = 0;
        $ips3_cukup = 0;
        $ips3_baik = 0;
        $ips3_sangat_baik = 0;

        // Hitung jumlah nilai IPS4
        $ips4_kurang = 0;
        $ips4_cukup = 0;
        $ips4_baik = 0;
        $ips4_sangat_baik = 0;

        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                $laki_laki_count++;
            } elseif ($row['jenis_kelamin'] == 'PEREMPUAN') {
                $perempuan_count++;
            }

            switch ($row['ips1']) {
                case 'KURANG':
                    $ips1_kurang++;
                    break;
                case 'CUKUP':
                    $ips1_cukup++;
                    break;
                case 'BAIK':
                    $ips1_baik++;
                    break;
                case 'SANGAT BAIK':
                    $ips1_sangat_baik++;
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
            switch ($row['ips2']) {
                case 'KURANG':
                    $ips2_kurang++;
                    break;
                case 'CUKUP':
                    $ips2_cukup++;
                    break;
                case 'BAIK':
                    $ips2_baik++;
                    break;
                case 'SANGAT BAIK':
                    $ips2_sangat_baik++;
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
            switch ($row['ips3']) {
                case 'KURANG':
                    $ips3_kurang++;
                    break;
                case 'CUKUP':
                    $ips3_cukup++;
                    break;
                case 'BAIK':
                    $ips3_baik++;
                    break;
                case 'SANGAT BAIK':
                    $ips3_sangat_baik++;
                    break;
                default:
                    // Handle default case if needed
                    break;
            }

            switch ($row['ips4']) {
                case 'KURANG':
                    $ips4_kurang++;
                    break;
                case 'CUKUP':
                    $ips4_cukup++;
                    break;
                case 'BAIK':
                    $ips4_baik++;
                    break;
                case 'SANGAT BAIK':
                    $ips4_sangat_baik++;
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
        }

        // Output informasi yang diminta
        echo "Jumlah total data: " . $total_count . "<br>";
        echo "Jumlah LAKI-LAKI: " . $laki_laki_count . "<br>";
        echo "Jumlah PEREMPUAN: " . $perempuan_count . "<br>";
        echo "<br>";
        echo "Jumlah IPS1 KURANG: " . $ips1_kurang . "<br>";
        echo "Jumlah IPS1 CUKUP: " . $ips1_cukup . "<br>";
        echo "Jumlah IPS1 BAIK: " . $ips1_baik . "<br>";
        echo "Jumlah IPS1 SANGAT BAIK: " . $ips1_sangat_baik . "<br>";
        echo "<br>";
        echo "Jumlah IPS2 KURANG: " . $ips2_kurang . "<br>";
        echo "Jumlah IPS2 CUKUP: " . $ips2_cukup . "<br>";
        echo "Jumlah IPS2 BAIK: " . $ips2_baik . "<br>";
        echo "Jumlah IPS2 SANGAT BAIK: " . $ips2_sangat_baik . "<br>";
        echo "<br>";
        echo "Jumlah IPS3 KURANG: " . $ips3_kurang . "<br>";
        echo "Jumlah IPS3 CUKUP: " . $ips3_cukup . "<br>";
        echo "Jumlah IPS3 BAIK: " . $ips3_baik . "<br>";
        echo "Jumlah IPS3 SANGAT BAIK: " . $ips3_sangat_baik . "<br>";
        echo "<br>";
        echo "Jumlah IPS4 KURANG: " . $ips4_kurang . "<br>";
        echo "Jumlah IPS4 CUKUP: " . $ips4_cukup . "<br>";
        echo "Jumlah IPS4 BAIK: " . $ips4_baik . "<br>";
        echo "Jumlah IPS4 SANGAT BAIK: " . $ips4_sangat_baik . "<br>";
        echo "<br>";
        // Inisialisasi variabel jumlah kurang
        $laki_laki_tepat_waktu = 0;
        $laki_laki_terlambat = 0;
        $perempuan_tepat_waktu = 0;
        $perempuan_terlambat = 0;

        $ips1_kurang_tepat_waktu = 0;
        $ips1_kurang_terlambat = 0;

        $ips2_kurang_tepat_waktu = 0;
        $ips2_kurang_terlambat = 0;

        $ips3_kurang_tepat_waktu = 0;
        $ips3_kurang_terlambat = 0;

        $ips4_kurang_tepat_waktu = 0;
        $ips4_kurang_terlambat = 0;

        // Inisialisasi variabel jumlah cukup
        $ips1_cukup_tepat_waktu = 0;
        $ips1_cukup_terlambat = 0;

        $ips2_cukup_tepat_waktu = 0;
        $ips2_cukup_terlambat = 0;

        $ips3_cukup_tepat_waktu = 0;
        $ips3_cukup_terlambat = 0;

        $ips4_cukup_tepat_waktu = 0;
        $ips4_cukup_terlambat = 0;

        // Inisialisasi variabel jumlah cukup
        $ips1_baik_tepat_waktu = 0;
        $ips1_baik_terlambat = 0;

        $ips2_baik_tepat_waktu = 0;
        $ips2_baik_terlambat = 0;

        $ips3_baik_tepat_waktu = 0;
        $ips3_baik_terlambat = 0;

        $ips4_baik_tepat_waktu = 0;
        $ips4_baik_terlambat = 0;

        // Inisialisasi variabel jumlah cukup
        $ips1_s_baik_tepat_waktu = 0;
        $ips1_s_baik_terlambat = 0;

        $ips2_s_baik_tepat_waktu = 0;
        $ips2_s_baik_terlambat = 0;

        $ips3_s_baik_tepat_waktu = 0;
        $ips3_s_baik_terlambat = 0;

        $ips4_s_baik_tepat_waktu = 0;
        $ips4_s_baik_terlambat = 0;

        // Menghitung jumlah tepat waktu dan terlambat berdasarkan jenis kelamin
        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $laki_laki_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $laki_laki_terlambat++;
                        break;
                }
            }
            if ($row['jenis_kelamin'] == 'PEREMPUAN') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $perempuan_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $perempuan_terlambat++;
                        break;
                }
            }
            if ($row['ips1'] == 'KURANG') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips1_kurang_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips1_kurang_terlambat++;
                        break;
                }
            }
            if ($row['ips2'] == 'KURANG') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips2_kurang_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips2_kurang_terlambat++;
                        break;
                }
            }
            if ($row['ips3'] == 'KURANG') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips3_kurang_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips3_kurang_terlambat++;
                        break;
                }
            }
            if ($row['ips4'] == 'KURANG') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips4_kurang_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips4_kurang_terlambat++;
                        break;
                }
            }
            if ($row['ips1'] == 'CUKUP') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips1_cukup_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips1_cukup_terlambat++;
                        break;
                }
            }
            if ($row['ips2'] == 'CUKUP') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips2_cukup_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips2_cukup_terlambat++;
                        break;
                }
            }
            if ($row['ips3'] == 'CUKUP') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips3_cukup_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips3_cukup_terlambat++;
                        break;
                }
            }
            if ($row['ips4'] == 'CUKUP') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips4_cukup_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips4_cukup_terlambat++;
                        break;
                }
            }
            if ($row['ips1'] == 'BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips1_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips1_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips2'] == 'BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips2_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips2_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips3'] == 'BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips3_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips3_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips4'] == 'BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips4_s_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips4_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips1'] == 'SANGAT BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips1_s_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips1_s_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips2'] == 'SANGAT BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips2_s_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips2_s_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips3'] == 'SANGAT BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips3_s_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips3_s_baik_terlambat++;
                        break;
                }
            }
            if ($row['ips4'] == 'SANGAT BAIK') {
                switch ($row['keterangan']) {
                    case 'TEPAT WAKTU':
                        $ips4_s_baik_tepat_waktu++;
                        break;
                    case 'TERLAMBAT':
                        $ips4_s_baik_terlambat++;
                        break;
                }
            }
        }
        $prob_ips1_tepat = $ips1_s_baik_tepat_waktu / $ips1_baik;
        $prob_ips1_lambat = $ips1_s_baik_terlambat / $ips1_baik;
        $entropy_ips1 = ((-$prob_ips1_tepat * log($prob_ips1_tepat, 2)) + (-$prob_ips1_lambat * log($prob_ips1_lambat, 2)));
        echo "Jumlah IPS1 TEPAT WAKTU: " . $ips1_s_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS1 TERLAMBAT: " . $ips1_s_baik_terlambat . "<br>";
        echo "Entropy IPS1 BAIK : " . $entropy_ips1 . "<br>";
        echo "<br>";

        $prob_ips2_tepat = $ips2_s_baik_tepat_waktu / $ips2_baik;
        $prob_ips2_lambat = $ips2_s_baik_terlambat / $ips2_baik;
        $entropy_ips2 = ((-$prob_ips2_tepat * log($prob_ips2_tepat, 2)) + (-$prob_ips2_lambat * log($prob_ips2_lambat, 2)));
        echo "Jumlah IPS2 TEPAT WAKTU: " . $ips2_s_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS2 TERLAMBAT: " . $ips2_s_baik_terlambat . "<br>";
        echo "Entropy IPS2 BAIK : " . $entropy_ips2 . "<br>";
        echo "<br>";

        $prob_ips3_tepat = $ips3_s_baik_tepat_waktu / $ips3_baik;
        $prob_ips3_lambat = $ips3_s_baik_terlambat / $ips3_baik;
        $entropy_ips3 = ((-$prob_ips3_tepat * log($prob_ips3_tepat, 2)) + (-$prob_ips3_lambat * log($prob_ips3_lambat, 2)));
        echo "Jumlah IPS3 TEPAT WAKTU: " . $ips3_s_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS3 TERLAMBAT: " . $ips3_s_baik_terlambat . "<br>";
        echo "Entropy IPS3 BAIK : " . $entropy_ips3 . "<br>";
        echo "<br>";

        $prob_ips4_tepat = $ips4_s_baik_tepat_waktu / $ips4_baik;
        $prob_ips4_lambat = $ips4_s_baik_terlambat / $ips4_baik;
        $entropy_ips4 = ((-$prob_ips4_tepat * log($prob_ips4_tepat, 2)) + (-$prob_ips4_lambat * log($prob_ips4_lambat, 2)));
        echo "Jumlah IPS4 TEPAT WAKTU: " . $ips4_s_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS4 TERLAMBAT: " . $ips4_s_baik_terlambat . "<br>";
        echo "Entropy IPS4 BAIK : " . $entropy_ips4 . "<br>";
        echo "<br>";

        $prob_ips1_tepat = $ips1_baik_tepat_waktu / $ips1_baik;
        $prob_ips1_lambat = $ips1_baik_terlambat / $ips1_baik;
        $entropy_ips1 = ((-$prob_ips1_tepat * log($prob_ips1_tepat, 2)) + (-$prob_ips1_lambat * log($prob_ips1_lambat, 2)));
        echo "Jumlah IPS1 TEPAT WAKTU: " . $ips1_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS1 TERLAMBAT: " . $ips1_baik_terlambat . "<br>";
        echo "Entropy IPS1 BAIK : " . $entropy_ips1 . "<br>";
        echo "<br>";

        $prob_ips2_tepat = $ips2_baik_tepat_waktu / $ips2_baik;
        $prob_ips2_lambat = $ips2_baik_terlambat / $ips2_baik;
        $entropy_ips2 = ((-$prob_ips2_tepat * log($prob_ips2_tepat, 2)) + (-$prob_ips2_lambat * log($prob_ips2_lambat, 2)));
        echo "Jumlah IPS2 TEPAT WAKTU: " . $ips2_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS2 TERLAMBAT: " . $ips2_baik_terlambat . "<br>";
        echo "Entropy IPS2 BAIK : " . $entropy_ips2 . "<br>";
        echo "<br>";

        $prob_ips3_tepat = $ips3_baik_tepat_waktu / $ips3_baik;
        $prob_ips3_lambat = $ips3_baik_terlambat / $ips3_baik;
        $entropy_ips3 = ((-$prob_ips3_tepat * log($prob_ips3_tepat, 2)) + (-$prob_ips3_lambat * log($prob_ips3_lambat, 2)));
        echo "Jumlah IPS3 TEPAT WAKTU: " . $ips3_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS3 TERLAMBAT: " . $ips3_baik_terlambat . "<br>";
        echo "Entropy IPS3 BAIK : " . $entropy_ips3 . "<br>";
        echo "<br>";

        $prob_ips4_tepat = $ips4_baik_tepat_waktu / $ips4_baik;
        $prob_ips4_lambat = $ips4_baik_terlambat / $ips4_baik;
        $entropy_ips4 = ((-$prob_ips4_tepat * log($prob_ips4_tepat, 2)) + (-$prob_ips4_lambat * log($prob_ips4_lambat, 2)));
        echo "Jumlah IPS4 TEPAT WAKTU: " . $ips4_baik_tepat_waktu . "<br>";
        echo "Jumlah IPS4 TERLAMBAT: " . $ips4_baik_terlambat . "<br>";
        echo "Entropy IPS4 BAIK : " . $entropy_ips4 . "<br>";
        echo "<br>";

        $prob_ips1_tepat = $ips1_cukup_tepat_waktu / $ips1_cukup;
        $prob_ips1_lambat = $ips1_cukup_terlambat / $ips1_cukup;
        $entropy_ips1 = ((-$prob_ips1_tepat * log($prob_ips1_tepat, 2)) + (-$prob_ips1_lambat * log($prob_ips1_lambat, 2)));
        echo "Jumlah IPS1 TEPAT WAKTU: " . $ips1_cukup_tepat_waktu . "<br>";
        echo "Jumlah IPS1 TERLAMBAT: " . $ips1_cukup_terlambat . "<br>";
        echo "Entropy IPS1 CUKUP : " . $entropy_ips1 . "<br>";
        echo "<br>";

        $prob_ips2_tepat = $ips2_cukup_tepat_waktu / $ips2_cukup;
        $prob_ips2_lambat = $ips2_cukup_terlambat / $ips2_cukup;
        $entropy_ips2 = ((-$prob_ips2_tepat * log($prob_ips2_tepat, 2)) + (-$prob_ips2_lambat * log($prob_ips2_lambat, 2)));
        echo "Jumlah IPS2 TEPAT WAKTU: " . $ips2_cukup_tepat_waktu . "<br>";
        echo "Jumlah IPS2 TERLAMBAT: " . $ips2_cukup_terlambat . "<br>";
        echo "Entropy IPS2 CUKUP : " . $entropy_ips2 . "<br>";
        echo "<br>";

        $prob_ips3_tepat = $ips3_cukup_tepat_waktu / $ips3_cukup;
        $prob_ips3_lambat = $ips3_cukup_terlambat / $ips3_cukup;
        $entropy_ips3 = ((-$prob_ips3_tepat * log($prob_ips3_tepat, 2)) + (-$prob_ips3_lambat * log($prob_ips3_lambat, 2)));
        echo "Jumlah IPS3 TEPAT WAKTU: " . $ips3_cukup_tepat_waktu . "<br>";
        echo "Jumlah IPS3 TERLAMBAT: " . $ips3_cukup_terlambat . "<br>";
        echo "Entropy IPS3 CUKUP : " . $entropy_ips3 . "<br>";
        echo "<br>";

        $prob_ips4_tepat = $ips4_cukup_tepat_waktu / $ips4_cukup;
        $prob_ips4_lambat = $ips4_cukup_terlambat / $ips4_cukup;
        $entropy_ips4 = ((-$prob_ips4_tepat * log($prob_ips4_tepat, 2)) + (-$prob_ips4_lambat * log($prob_ips4_lambat, 2)));
        echo "Jumlah IPS4 TEPAT WAKTU: " . $ips4_cukup_tepat_waktu . "<br>";
        echo "Jumlah IPS4 TERLAMBAT: " . $ips4_cukup_terlambat . "<br>";
        echo "Entropy IPS4 CUKUP : " . $entropy_ips4 . "<br>";
        echo "<br>";

        $prob_ips1_tepat = $ips1_kurang_tepat_waktu / $ips1_kurang;
        $prob_ips1_lambat = $ips1_kurang_terlambat / $ips1_kurang;
        $entropy_ips1 = ((-$prob_ips1_tepat * log($prob_ips1_tepat, 2)) + (-$prob_ips1_lambat * log($prob_ips1_lambat, 2)));
        echo "Jumlah IPS1 TEPAT WAKTU: " . $ips1_kurang_tepat_waktu . "<br>";
        echo "Jumlah IPS1 TERLAMBAT: " . $ips1_kurang_terlambat . "<br>";
        echo "Entropy IPS1 KURANG : " . $entropy_ips1 . "<br>";
        echo "<br>";

        $prob_ips2_tepat = $ips2_kurang_tepat_waktu / $ips2_kurang;
        $prob_ips2_lambat = $ips2_kurang_terlambat / $ips2_kurang;
        $entropy_ips2 = ((-$prob_ips2_tepat * log($prob_ips2_tepat, 2)) + (-$prob_ips2_lambat * log($prob_ips2_lambat, 2)));
        echo "Jumlah IPS2 TEPAT WAKTU: " . $ips2_kurang_tepat_waktu . "<br>";
        echo "Jumlah IPS2 TERLAMBAT: " . $ips2_kurang_terlambat . "<br>";
        echo "Entropy IPS2 KURANG : " . $entropy_ips2 . "<br>";
        echo "<br>";

        $prob_ips3_tepat = $ips3_kurang_tepat_waktu / $ips3_kurang;
        $prob_ips3_lambat = $ips3_kurang_terlambat / $ips3_kurang;
        $entropy_ips3 = ((-$prob_ips3_tepat * log($prob_ips3_tepat, 2)) + (-$prob_ips3_lambat * log($prob_ips3_lambat, 2)));
        echo "Jumlah IPS3 TEPAT WAKTU: " . $ips3_kurang_tepat_waktu . "<br>";
        echo "Jumlah IPS3 TERLAMBAT: " . $ips3_kurang_terlambat . "<br>";
        echo "Entropy IPS3 KURANG : " . $entropy_ips3 . "<br>";
        echo "<br>";

        $prob_ips4_tepat = $ips4_kurang_tepat_waktu / $ips4_kurang;
        $prob_ips4_lambat = $ips4_kurang_terlambat / $ips4_kurang;
        $entropy_ips4 = ((-$prob_ips4_tepat * log($prob_ips4_tepat, 2)) + (-$prob_ips4_lambat * log($prob_ips4_lambat, 2)));
        echo "Jumlah IPS4 TEPAT WAKTU: " . $ips4_kurang_tepat_waktu . "<br>";
        echo "Jumlah IPS4 TERLAMBAT: " . $ips4_kurang_terlambat . "<br>";
        echo "Entropy IPS4 KURANG : " . $entropy_ips4 . "<br>";
        echo "<br>";

        // Output jumlah laki-laki dan perempuan tepat waktu dan terlambat
        echo "Jumlah LAKI-LAKI TEPAT WAKTU: " . $laki_laki_tepat_waktu . "<br>";
        echo "Jumlah LAKI-LAKI TERLAMBAT: " . $laki_laki_terlambat . "<br>";
        //mencari entropy laki laki
        $prob_lk_tepat = $laki_laki_tepat_waktu / $laki_laki_count;
        $prob_lk_lambat = $laki_laki_terlambat / $laki_laki_count;

        // Hitung entropy
        $entropy_lk = ((-$prob_lk_tepat * log($prob_lk_tepat, 2)) + (-$prob_lk_lambat * log($prob_lk_lambat, 2)));
        echo "Entropy LAKI-LAKI : " . $entropy_lk . "<br>";
        echo "<br>";
        //mencari entropy perempuan
        $prob_pr_tepat = $perempuan_tepat_waktu / $perempuan_count;
        $prob_pr_lambat = $perempuan_terlambat / $perempuan_count;

        echo "Jumlah PEREMPUAN TEPAT WAKTU: " . $perempuan_tepat_waktu . "<br>";
        echo "Jumlah PEREMPUAN TERLAMBAT: " . $perempuan_terlambat . "<br>";
        $entropy_pr = ((-$prob_pr_tepat * log($prob_pr_tepat, 2)) + (-$prob_pr_lambat * log($prob_pr_lambat, 2)));
        echo "Entropy PEREMPUAN : " . $entropy_pr . "<br>";

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

        // Hitung Gain untuk IPS1
        $ips1_values = ['KURANG', 'CUKUP', 'BAIK', 'SANGAT BAIK'];
        $entropy_ips1 = 0;
        foreach ($ips1_values as $value) {
            $count_tepat = 0;
            $count_terlambat = 0;
            foreach ($data as $row) {
                if ($row['ips1'] == $value) {
                    if ($row['keterangan'] == 'TEPAT WAKTU') {
                        $count_tepat++;
                    } else if ($row['keterangan'] == 'TERLAMBAT') {
                        $count_terlambat++;
                    }
                }
            }
            $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
            $total_value_count = $count_tepat + $count_terlambat;
            $entropy_ips1 += ($total_value_count / $total_count) * $entropy_value;
        }
        $gain_ips1 = $entropy_total - $entropy_ips1;
        echo "Gain IPS1: " . $gain_ips1 . "<br>";

        // Hitung Gain untuk IPS2
        $entropy_ips2 = 0;
        foreach ($ips1_values as $value) {
            $count_tepat = 0;
            $count_terlambat = 0;
            foreach ($data as $row) {
                if ($row['ips2'] == $value) {
                    if ($row['keterangan'] == 'TEPAT WAKTU') {
                        $count_tepat++;
                    } else if ($row['keterangan'] == 'TERLAMBAT') {
                        $count_terlambat++;
                    }
                }
            }
            $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
            $total_value_count = $count_tepat + $count_terlambat;
            $entropy_ips2 += ($total_value_count / $total_count) * $entropy_value;
        }
        $gain_ips2 = $entropy_total - $entropy_ips2;
        echo "Gain IPS2: " . $gain_ips2 . "<br>";

        // Hitung Gain untuk IPS3
        $entropy_ips3 = 0;
        foreach ($ips1_values as $value) {
            $count_tepat = 0;
            $count_terlambat = 0;
            foreach ($data as $row) {
                if ($row['ips3'] == $value) {
                    if ($row['keterangan'] == 'TEPAT WAKTU') {
                        $count_tepat++;
                    } else if ($row['keterangan'] == 'TERLAMBAT') {
                        $count_terlambat++;
                    }
                }
            }
            $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
            $total_value_count = $count_tepat + $count_terlambat;
            $entropy_ips3 += ($total_value_count / $total_count) * $entropy_value;
        }
        $gain_ips3 = $entropy_total - $entropy_ips3;
        echo "Gain IPS3: " . $gain_ips3 . "<br>";

        // Hitung Gain untuk IPS4
        $entropy_ips4 = 0;
        foreach ($ips1_values as $value) {
            $count_tepat = 0;
            $count_terlambat = 0;
            foreach ($data as $row) {
                if ($row['ips4'] == $value) {
                    if ($row['keterangan'] == 'TEPAT WAKTU') {
                        $count_tepat++;
                    } else if ($row['keterangan'] == 'TERLAMBAT') {
                        $count_terlambat++;
                    }
                }
            }
            $entropy_value = hitung_entropy($count_tepat, $count_terlambat);
            $total_value_count = $count_tepat + $count_terlambat;
            $entropy_ips4 += ($total_value_count / $total_count) * $entropy_value;
        }
        $gain_ips4 = $entropy_total - $entropy_ips4;
        echo "Gain IPS4: " . $gain_ips4 . "<br>";
        $total_count = count($data);

        // Hitung jumlah tepat waktu dan terlambat
        $tepat_waktu_count = 0;
        $terlambat_count = 0;
        foreach ($data as $row) {
            if ($row['keterangan'] == 'TEPAT WAKTU') {
                $tepat_waktu_count++;
            } elseif ($row['keterangan'] == 'TERLAMBAT') {
                $terlambat_count++;
            }
        }

        // Hitung entropi awal
        $prob_tepat_waktu = $tepat_waktu_count / $total_count;
        $prob_terlambat = $terlambat_count / $total_count;
        $entropy_awal = - ($prob_tepat_waktu * log($prob_tepat_waktu, 2)) - ($prob_terlambat * log($prob_terlambat, 2));

        // Hitung jumlah laki-laki dan perempuan
        $laki_laki_count = 0;
        $perempuan_count = 0;
        $laki_laki_tepat_waktu = 0;
        $laki_laki_terlambat = 0;
        $perempuan_tepat_waktu = 0;
        $perempuan_terlambat = 0;

        foreach ($data as $row) {
            if ($row['jenis_kelamin'] == 'LAKI-LAKI') {
                $laki_laki_count++;
                if ($row['keterangan'] == 'TEPAT WAKTU') {
                    $laki_laki_tepat_waktu++;
                } else {
                    $laki_laki_terlambat++;
                }
            } elseif ($row['jenis_kelamin'] == 'PEREMPUAN') {
                $perempuan_count++;
                if ($row['keterangan'] == 'TEPAT WAKTU') {
                    $perempuan_tepat_waktu++;
                } else {
                    $perempuan_terlambat++;
                }
            }
        }

        // Hitung entropi setelah membagi berdasarkan jenis kelamin
        $prob_laki_laki_tepat_waktu = $laki_laki_tepat_waktu / $laki_laki_count;
        $prob_laki_laki_terlambat = $laki_laki_terlambat / $laki_laki_count;
        $entropy_laki_laki = - ($prob_laki_laki_tepat_waktu * log($prob_laki_laki_tepat_waktu, 2)) - ($prob_laki_laki_terlambat * log($prob_laki_laki_terlambat, 2));

        $prob_perempuan_tepat_waktu = $perempuan_tepat_waktu / $perempuan_count;
        $prob_perempuan_terlambat = $perempuan_terlambat / $perempuan_count;
        $entropy_perempuan = - ($prob_perempuan_tepat_waktu * log($prob_perempuan_tepat_waktu, 2)) - ($prob_perempuan_terlambat * log($prob_perempuan_terlambat, 2));

        // Hitung rata-rata entropi setelah membagi
        $entropy_setelah_membagi = ($laki_laki_count / $total_count) * $entropy_laki_laki + ($perempuan_count / $total_count) * $entropy_perempuan;

        // Hitung informasi gain
        $information_gain = $entropy_awal - $entropy_setelah_membagi;

        // Output hasil
        echo "Entropy Awal: " . $entropy_awal . "<br>";
        echo "Entropy Laki-Laki: " . $entropy_laki_laki . "<br>";
        echo "Entropy Perempuan: " . $entropy_perempuan . "<br>";
        echo "Entropy Setelah Membagi: " . $entropy_setelah_membagi . "<br>";
        echo "Information Gain untuk Jenis Kelamin: " . $information_gain . "<br>";
        // Menampilkan pohon keputusan

    } catch (PDOException $e) {
        die("Error retrieving data: " . $e->getMessage());
    }
}
