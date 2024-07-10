<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmining-base";

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    $lulus = "TEPAT WAKTU"; // Kategori positif
    $tidak_lulus = "TERLAMBAT"; // Kategori negatif

    // Koneksi ke Database
    $koneksi1 = new mysqli($servername, $username, $password, $dbname);
    if ($koneksi1->connect_error) {
        die("Connection failed: " . $koneksi1->connect_error);
    }

    // Query untuk menghitung jumlah total baris pada tabel dengan Keterangan 'LULUS' dan 'TIDAK LULUS'
    $count_query = "SELECT COUNT(*) AS total_rows FROM $table_name WHERE KETERANGAN='$lulus' OR KETERANGAN='$tidak_lulus'";
    $result_count = $koneksi1->query($count_query);
    if (!$result_count) {
        die("Query failed: " . $koneksi1->error);
    }
    $row_count = $result_count->fetch_assoc();
    $total_rows = $row_count['total_rows'];

    // Hitung jumlah baris yang ingin ditampilkan (70% dari total baris)
    $limit = ceil(0.3 * $total_rows);

    // Query untuk mengambil data terbaru sejumlah $limit dengan KETERANGAN 'LULUS' atau 'TIDAK LULUS'
    $query = "SELECT * FROM $table_name WHERE KETERANGAN='$lulus' OR KETERANGAN='$tidak_lulus' ORDER BY id DESC LIMIT $limit";
    $result = $koneksi1->query($query);

    if ($result->num_rows > 0) {
        echo "<table id='table-content'>";
        echo "<tr>";
        echo "<th>NO</th>";

        $fields = $result->fetch_fields();
        $headerColumns = [];

        foreach ($fields as $field) {
            if ($field->name != 'id' && $field->name != 'NO') {
                $headerColumns[] = $field->name;
                echo "<th>" . $field->name . "</th>";
            }
        }
        echo "</tr>";

        $data = [];
        $counter = 1;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            $counter++;

            $rowData = [];
            foreach ($row as $key => $value) {
                if ($key != 'id' && $key != 'NO') {
                    echo "<td>$value</td>";
                    $rowData[$key] = $value;
                }
            }
            $data[] = $rowData;
            echo "</tr>";
        }
        echo "</table>";

        // Fungsi untuk membagi data menjadi training set dan testing set
        function splitData($data, $splitRatio)
        {
            $trainSize = round(count($data) * $splitRatio);
            $trainSet = array_slice($data, 0, $trainSize);
            $testSet = array_slice($data, $trainSize);
            return [$trainSet, $testSet];
        }

        list($trainSet, $testSet) = splitData($data, 0.7);

        echo "<pre>Training Set:\n";
        print_r($trainSet);
        echo "\nTesting Set:\n";
        print_r($testSet);
        echo "</pre>";

        // Implementasi Decision Tree C4.5
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
        $tree = new Node(true, 'TEPAT WAKTU'); // Ganti ini dengan model C4.5 yang sebenarnya

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

        $predictions = [];
        foreach ($testSet as $instance) {
            $predictions[] = predict($tree, $instance);
        }

        echo "<pre>Predictions:\n";
        print_r($predictions);
        echo "</pre>";

        // Fungsi untuk menghitung metrik akurasi, sensitivitas, dan spesifisitas
        function calculateMetrics($testSet, $predictions)
        {
            $TP = $TN = $FP = $FN = 0;

            for ($i = 0; $i < count($testSet); $i++) {
                $actualLabel = $testSet[$i]['KETERANGAN'];
                $predictedLabel = $predictions[$i];

                if ($actualLabel == 'TEPAT WAKTU' && $predictedLabel == 'TEPAT WAKTU') {
                    $TP++;
                } elseif ($actualLabel == 'TEPAT WAKTU' && $predictedLabel != 'TEPAT WAKTU') {
                    $FN++;
                } elseif ($actualLabel != 'TEPAT WAKTU' && $predictedLabel != 'TEPAT WAKTU') {
                    $TN++;
                } elseif ($actualLabel != 'TEPAT WAKTU' && $predictedLabel == 'TEPAT WAKTU') {
                    $FP++;
                }
            }

            $accuracy = ($TP + $TN) / ($TP + $TN + $FP + $FN) * 100.0;
            $sensitivity = ($TP + $FN) > 0 ? ($TP / ($TP + $FN) * 100.0) : 0;
            $specificity = ($TN + $FP) > 0 ? ($TN / ($TN + $FP) * 100.0) : 0;

            return [
                'accuracy' => $accuracy,
                'sensitivity' => $sensitivity,
                'specificity' => $specificity,
                'TP' => $TP,
                'FN' => $FN,
                'FP' => $FP
            ];
        }

        // Hitung dan tampilkan metrik
        $metrics = calculateMetrics($testSet, $predictions);
        echo "<p>Accuracy: " . $metrics['accuracy'] . "%</p>";
        echo "<p>Sensitivity: " . $metrics['sensitivity'] . "%</p>";
        echo "<p>Specificity: " . $metrics['specificity'] . "%</p>";
        echo "<p>TP: " . $metrics['TP'] . "</p>";
        echo "<p>FN: " . $metrics['FN'] . "</p>";
        echo "<p>FP: " . $metrics['FP'] . "</p>";
    } else {
        echo "Tidak ada data yang ditemukan.";
    }

    $koneksi1->close();
} else {
    echo "Nama tabel tidak diberikan.";
}
