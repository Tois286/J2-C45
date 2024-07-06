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

        // Langkah 4: Implementasi Algoritma C4.5 untuk membangun model
        // Anda perlu melengkapi algoritma C4.5 sesuai dengan spesifikasi Anda

        // Langkah 5: Prediksi
        function predict($model, $instance)
        {
            // Implementasikan fungsi prediksi menggunakan model C4.5
            // Misalnya, gunakan model yang telah dibangun sebelumnya
            $prediction = classifyInstance($model, $instance);
            return $prediction;
        }

        function classifyInstance($model, $instance)
        {
            // Traverse the decision tree model to classify the instance
            while (true) {
                // Jika model adalah sebuah leaf node, kembalikan label dari leaf node tersebut
                if ($model->isLeaf()) {
                    return $model->getLabel();
                }

                // Ambil atribut yang digunakan untuk split pada node saat ini
                $attribute = $model->getAttribute();

                // Ambil nilai atribut instance yang sedang diperiksa
                $instanceValue = $instance[$attribute];

                // Traverse sesuai dengan nilai atribut instance
                if (isset($model->getChildren()[$instanceValue])) {
                    $model = $model->getChildren()[$instanceValue];
                } else {
                    // Jika nilai atribut instance tidak ada dalam model, kembalikan nilai default atau nilai mayoritas
                    return $model->getDefaultValue(); // Atau sesuaikan dengan logika penanganan nilai yang tidak ada
                }
            }
        }


        // Langkah 6: Evaluasi Akurasi
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

        // Contoh: Evaluasi akurasi dari prediksi
        $predictions = []; // Inisialisasi variabel $predictions sebelum digunakan
        $accuracy = calculateAccuracy($testSet, $predictions);
        echo "<p>Accuracy: " . $accuracy . "%</p>";
    } else {
        echo "<p>No data found</p>";
    }

    $conn->close();
} else {
    echo "<p>Silakan pilih tabel dari dropdown di atas.</p>";
}
