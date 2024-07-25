<?php

include 'config/koneksi.php'; // Sesuaikan dengan path koneksi Anda

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    $lulus = "TEPAT WAKTU"; // Kategori positif
    $tidak_lulus = "TERLAMBAT"; // Kategori negatif

    // Koneksi ke Database
    $koneksi1 = new mysqli($host, $username, $password, $dbname);
    if ($koneksi1->connect_error) {
        die("Connection failed: " . $koneksi1->connect_error);
    }

    // Query untuk mengambil data dari tabel
    $query = "SELECT * FROM $table_name";
    $result = $koneksi1->query($query);

    if ($result->num_rows > 0) {
        $data = [];
        $counter = 1;

        while ($row = $result->fetch_assoc()) {
            $rowData = [];
            foreach ($row as $key => $value) {
                if ($key != 'id' && $key != 'NO') {
                    $rowData[$key] = $value;
                }
            }
            $data[] = $rowData;
        }

        // Fungsi untuk membagi data menjadi training set dan testing set
        function splitData($data, $splitRatio)
        {
            $trainSize = round(count($data) * $splitRatio);
            $trainSet = array_slice($data, 0, $trainSize);
            $testSet = array_slice($data, $trainSize);
            return [$trainSet, $testSet];
        }

        list($trainSet, $testSet) = splitData($data, 0.7);

        class Node
        {
            public $isLeaf = false;
            public $label;
            public $attribute;
            public $children = [];
            public $defaultValue;

            public function __construct($isLeaf = false, $label = null)
            {
                $this->isLeaf = $isLeaf;
                $this->label = $label;
            }

            public function isLeaf()
            {
                return $this->isLeaf;
            }

            public function getLabel()
            {
                return $this->label;
            }

            public function getAttribute()
            {
                return $this->attribute;
            }

            public function getChildren()
            {
                return $this->children;
            }

            public function getDefaultValue()
            {
                return $this->defaultValue;
            }
        }

        // Dummy model untuk demonstrasi
        $tree = new Node(true, 'TEPAT WAKTU');

        function predict($model, $instance)
        {
            return classifyInstance($model, $instance);
        }

        function classifyInstance($model, $instance)
        {
            if ($model == null) {
                throw new Exception("Model is null");
            }

            while (true) {
                if ($model->isLeaf()) {
                    return $model->getLabel();
                }

                $attribute = $model->getAttribute();
                $instanceValue = $instance[$attribute];

                if (isset($model->getChildren()[$instanceValue])) {
                    $model = $model->getChildren()[$instanceValue];
                } else {
                    return $model->getDefaultValue();
                }
            }
        }

        // Prediksi untuk setiap instance di test set
        $predictions = [];
        foreach ($testSet as $instance) {
            $predictions[] = predict($tree, $instance);
        }

        // Fungsi untuk menghitung metrik akurasi, sensitivitas, dan spesifisitas
        function calculateMetrics($data)
        {
            $TP = $TN = $FP = $FN = 0;

            foreach ($data as $row) {
                $actualLabel = $row['KETERANGAN'];
                $predictedLabel = $row['PREDIKSI'];

                if ($actualLabel == 'TEPAT WAKTU' && $predictedLabel == 'TEPAT WAKTU') {
                    $TP++;
                } elseif ($actualLabel == 'TERLAMBAT' && $predictedLabel == 'TEPAT WAKTU') {
                    $FP++;
                } elseif ($actualLabel == 'TERLAMBAT' && $predictedLabel == 'TERLAMBAT') {
                    $TN++;
                } elseif ($actualLabel == 'TEPAT WAKTU' && $predictedLabel == 'TERLAMBAT') {
                    $FN++;
                }
            }

            // Hitung spesifisitas, akurasi, dan sensitivitas
            $accuracy = ($TP + $TN) / ($TP + $TN + $FP + $FN) * 100;
            $sensitivity = ($TP + $FN) > 0 ? ($TP / ($TP + $FN) * 100) : 0;
            $specificity = ($TN + $FP) > 0 ? ($TN / ($TN + $FP) * 100) : 0;

            return [
                'accuracy' => $accuracy,
                'sensitivity' => $sensitivity,
                'specificity' => $specificity,
                'TP' => $TP,
                'FN' => $FN,
                'FP' => $FP,
                'TN' => $TN
            ];
        }

        // Hitung dan tampilkan metrik
        $metrics = calculateMetrics($testSet);

        echo "
        <table border='1' cellspacing='0' cellpadding='5' class='styled-table'>
        <center>
            <tr>
                <th rowspan='2'>Actual</th>
                <th colspan='2'>Prediksi</th>
            </tr>
            <tr>
                <th>Tepat Waktu</th>
                <th>Terlambat</th>
            </tr>
            <tr>
                <td>Tepat Waktu</td>
                <td>{$metrics['TP']}</td>
                <td>{$metrics['FN']}</td>
            </tr>
            <tr>
                <td>Terlambat</td>
                <td>{$metrics['FP']}</td>
                <td>{$metrics['TN']}</td>
            </tr>
            </center>
        </table>";

        echo "<p>Accuracy: " . $metrics['accuracy'] . "%</p>";
        echo "<p>Specificity: " . $metrics['specificity'] . "%</p>";
        echo "<p>Sensitivity: " . $metrics['sensitivity'] . "%</p>";
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
    $koneksi1->close();
} else {
    echo "Nama tabel tidak diberikan.";
}
