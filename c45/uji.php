<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmining-base";

// Langkah 1: Ambil Nama Tabel dari Parameter GET
if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    $lulus = "TEPAT WAKTU"; // Ganti dengan nilai yang sesuai untuk Keterangan 'LULUS'

    // Langkah 2: Koneksi ke Database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hitung jumlah total baris pada tabel dengan Keterangan 'LULUS'
    $count_query = "SELECT COUNT(*) AS total_rows FROM $table_name WHERE Keterangan='$lulus'";
    $result_count = $conn->query($count_query);
    if (!$result_count) {
        die("Query failed: " . $conn->error);
    }
    $row_count = $result_count->fetch_assoc();
    $total_rows = $row_count['total_rows'];

    // Hitung jumlah baris yang ingin ditampilkan (70% dari total baris)
    $limit = ceil(0.7 * $total_rows);

    // Query untuk mengambil data terbaru sejumlah $limit dengan Keterangan 'LULUS'
    $query = "SELECT * FROM $table_name WHERE Keterangan='$lulus' ORDER BY id DESC LIMIT $limit";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table id='table-content'>";
        echo "<tr>";

        // Tambahkan kolom NO sebagai header pertama
        echo "<th>NO</th>";

        $fields = $result->fetch_fields();
        $headerColumns = [];

        foreach ($fields as $field) {
            // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
            if ($field->name != 'id' && $field->name != 'NO') {
                $headerColumns[] = $field->name;
                echo "<th>" . $field->name . "</th>";
            }
        }
        echo "</tr>";

        $data = []; // Array untuk menyimpan data dari tabel yang dipilih
        $counter = 1; // Counter untuk nomor urut

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";

            // Tampilkan nomor urut (NO) di bagian pertama
            echo "<td>" . $counter . "</td>";
            $counter++; // Increment counter untuk nomor urut

            // Tambahkan data ke dalam array $data
            $rowData = [];
            foreach ($row as $key => $value) {
                // Tambahkan kondisi untuk mengecualikan kolom 'id' dan 'NO'
                if ($key != 'id' && $key != 'NO') {
                    echo "<td>$value</td>";
                    $rowData[] = $value; // Simpan nilai ke dalam $rowData
                }
            }
            $data[] = $rowData; // Tambahkan baris data ke dalam array $data

            echo "</tr>";
        }
        echo "</table>";

        // Setelah menampilkan data, Anda dapat melanjutkan dengan proses pengujian algoritma C4.5 di sini
        // Misalnya, lanjutkan dengan pembagian data, pelatihan model, prediksi, dan evaluasi akurasi.
        // Implementasikan langkah-langkah ini berdasarkan langkah-langkah sebelumnya yang telah dijelaskan.

        // Contoh:
        // Langkah 3: Bagi Data menjadi Training dan Testing
        function splitData($data, $splitRatio)
        {
            $trainSize = round(count($data) * $splitRatio);
            $trainSet = array_slice($data, 0, $trainSize);
            $testSet = array_slice($data, $trainSize);
            return [$trainSet, $testSet];
        }

        list($trainSet, $testSet) = splitData($data, 0.7);

        // Debug: Tampilkan isi dari training dan testing set
        echo "<pre>Training Set:\n";
        print_r($trainSet);
        echo "\nTesting Set:\n";
        print_r($testSet);
        echo "</pre>";

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
        $dummyModel = new Node(true, 'LULUS'); // Ganti ini dengan model C4.5 yang sebenarnya

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

        // Membuat prediksi untuk setiap instance dalam $testSet
        $predictions = [];
        foreach ($testSet as $instance) {
            $predictions[] = predict($dummyModel, $instance);
        }

        // Debug: Tampilkan prediksi yang dihasilkan
        echo "<pre>Predictions:\n";
        print_r($predictions);
        echo "</pre>";

        // Fungsi untuk menghitung akurasi
        function calculateAccuracy($testSet, $predictions)
        {
            $correct = 0;
            for ($i = 0; $i < count($testSet); $i++) {
                // Kolom terakhir pada setiap baris data adalah label (misalnya 'LULUS')
                $actualLabel = end($testSet[$i]);
                if ($actualLabel == $predictions[$i]) {
                    $correct++;
                }
            }
            return $correct / count($testSet) * 100.0;
        }

        // Fungsi untuk menghitung sensitivitas, spesifisitas, dan akurasi
        function calculateMetrics($testSet, $predictions)
        {
            $TP = $TN = $FP = $FN = 0;

            for ($i = 0; $i < count($testSet); $i++) {
                $actualLabel = end($testSet[$i]);
                $predictedLabel = $predictions[$i];

                if ($actualLabel == 'LULUS' && $predictedLabel == 'LULUS') {
                    $TP++;
                } elseif ($actualLabel == 'LULUS' && $predictedLabel != 'LULUS') {
                    $FN++;
                } elseif ($actualLabel != 'LULUS' && $predictedLabel != 'LULUS') {
                    $TN++;
                } elseif ($actualLabel != 'LULUS' && $predictedLabel == 'LULUS') {
                    $FP++;
                }
            }

            $accuracy = ($TP + $TN) / ($TP + $TN + $FP + $FN) * 100.0;
            $sensitivity = ($TP + $FN) > 0 ? ($TP / ($TP + $FN) * 100.0) : 0;
            $specificity = ($TN + $FP) > 0 ? ($TN / ($TN + $FP) * 100.0) : 0;

            return [
                'accuracy' => $accuracy,
                'sensitivity' => $sensitivity,
                'specificity' => $specificity
            ];
        }

        // Hitung metrik dari prediksi
        $metrics = calculateMetrics($testSet, $predictions);
        echo "<p>Accuracy: " . $metrics['accuracy'] . "%</p>";
        echo "<p>Sensitivity: " . $metrics['sensitivity'] . "%</p>";
        echo "<p>Specificity: " . $metrics['specificity'] . "%</p>";
    } else {
        echo "<p>No data found</p>";
    }

    $conn->close();
} else {
    echo "<p>Silakan pilih tabel dari dropdown di atas.</p>";
}
