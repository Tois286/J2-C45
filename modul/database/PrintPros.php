<style>
    .styled-table {
        width: 80%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 1em;
        font-family: sans-serif;
        min-width: 300px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr:hover {
        background-color: #dddddd;
    }
</style>
<center>
    <div class="img">
        <center>
            <img src="../../asset/unipi.png" alt="image" style="width: 5%; height: auto; margin:2%;">
            <h1 style="margin:0;">Data Prediksi Kelulusan Mahasiswa</h1>
            <p style="margin:0;">Universitas Insan Pembangunan Indonesia</p>
        </center>
    </div>
    <div class="card-tree">
        <div class="table-container">
            <?php

            include '../../config/koneksi.php'; // Sesuaikan dengan path koneksi Anda

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
                    echo "<div class='table-container'>";
                    echo "<table class='styled-table'>";
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
                    echo "</div>";

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
                    echo "</div>";
                    // Fungsi untuk membagi data menjadi training set dan testing set

                } else {
                    echo "Tidak ada data yang ditemukan.";
                }
                $koneksi1->close();
            } else {
                echo "Nama tabel tidak diberikan.";
            }
            try {
                $stmt = $pdo->prepare("SELECT id, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
                $stmt->execute();
                $subset_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Hitung jumlah data
                $total_data = count($subset_data);

                // Hitung jumlah data yang ingin diambil (70%)
                $subset_size = ceil(0.7 * $total_data);

                // Ambil subset data
                $data = array_slice($subset_data, 0, $subset_size);
                echo  '<link rel="stylesheet" href="../src/css/style.css">';
                echo '<div class="card-home">';
                // Function to calculate entropy
                function calculateEntropy($data, $targetAttribute)
                {
                    $total = count($data);
                    $classCounts = array_count_values(array_column($data, $targetAttribute));
                    $entropy = 0.0;

                    foreach ($classCounts as $count) {
                        $probability = $count / $total;
                        if ($probability > 0) {
                            $entropy -= $probability * log($probability) / log(2);
                        }
                    }

                    return $entropy;
                }

                // Function to calculate attribute entropy
                function calculateAttributeEntropy($data, $attribute, $targetAttribute)
                {
                    $total = count($data);
                    $attributeValues = array_unique(array_column($data, $attribute));
                    $entropy = 0.0;

                    foreach ($attributeValues as $value) {
                        $subset = array_filter($data, function ($row) use ($attribute, $value) {
                            return $row[$attribute] == $value;
                        });

                        if (empty($subset)) {
                            continue;
                        }

                        $subsetEntropy = calculateEntropy($subset, $targetAttribute);
                        $subsetProbability = count($subset) / $total;
                        $entropy += $subsetProbability * $subsetEntropy;
                    }

                    return $entropy;
                }

                // Function to calculate gain
                function calculateGain($data, $attribute, $targetAttribute, $totalEntropy)
                {
                    $attributeEntropy = calculateAttributeEntropy($data, $attribute, $targetAttribute);
                    $gain = $totalEntropy - $attributeEntropy;

                    return $gain;
                }

                // Function to get the best attribute
                function getBestAttribute($data, $attributes, $targetAttribute)
                {
                    $totalEntropy = calculateEntropy($data, $targetAttribute);
                    $bestAttribute = null;
                    $bestGain = -1;

                    foreach ($attributes as $attribute) {
                        $gain = calculateGain($data, $attribute, $targetAttribute, $totalEntropy);
                        if ($gain > $bestGain) {
                            $bestGain = $gain;
                            $bestAttribute = $attribute;
                        }
                    }

                    return $bestAttribute;
                }

                // Function to build the decision tree
                function buildDecisionTree($data, $attributes, $targetAttribute)
                {
                    $classLabels = array_column($data, $targetAttribute);
                    if (count(array_unique($classLabels)) === 1) {
                        return $classLabels[0];
                    }

                    if (empty($attributes)) {
                        return array_search(max(array_count_values($classLabels)), array_count_values($classLabels));
                    }

                    $bestAttribute = getBestAttribute($data, $attributes, $targetAttribute);
                    if ($bestAttribute === null) {
                        return array_search(max(array_count_values($classLabels)), array_count_values($classLabels));
                    }

                    $tree = [$bestAttribute => []];
                    $attributeValues = array_unique(array_column($data, $bestAttribute));

                    foreach ($attributeValues as $value) {
                        $subset = array_filter($data, function ($row) use ($bestAttribute, $value) {
                            return $row[$bestAttribute] == $value;
                        });

                        $remainingAttributes = array_diff($attributes, [$bestAttribute]);
                        $tree[$bestAttribute][$value] = buildDecisionTree($subset, $remainingAttributes, $targetAttribute);
                    }

                    return $tree;
                }

                // Function to print the decision tree
                function printDecisionTree($tree, $level = 0)
                {
                    foreach ($tree as $attribute => $branches) {
                        echo str_repeat("--", $level) . "$attribute\n";
                        foreach ($branches as $value => $subtree) {
                            if (is_array($subtree)) {
                                printDecisionTree($subtree, $level + 1);
                            } else {
                                echo str_repeat("--", $level + 1) . "$value: $subtree\n";
                            }
                        }
                    }
                }

                // Function to print decision rules
                function printDecisionRules($tree, $rules = [], $level = 0)
                {
                    foreach ($tree as $attribute => $branches) {
                        foreach ($branches as $value => $subtree) {
                            $newRules = $rules;
                            $newRules[] = "$attribute = $value";

                            if (is_array($subtree)) {
                                printDecisionRules($subtree, $newRules, $level + 1);
                            } else {
                                echo str_repeat("  ", $level) . "IF " . implode(' AND ', $newRules) . " THEN $subtree\n";
                            }
                        }
                    }
                }
                function generateDecisionRules($tree, $rules = [], $level = 0)
                {
                    $output = "";
                    foreach ($tree as $attribute => $branches) {
                        foreach ($branches as $value => $subtree) {
                            $newRules = $rules;
                            $newRules[] = "$attribute = $value";

                            if (is_array($subtree)) {
                                $output .= generateDecisionRules($subtree, $newRules, $level + 1);
                            } else {
                                $output .= str_repeat("  ", $level) . "IF " . implode(' AND ', $newRules) . " THEN $subtree\n";
                            }
                        }
                    }
                    return $output;
                }

                echo "<br>";
                echo "<h1>70% Data diguankan sebagai Data Training</h1>";

                // Function to print attribute information
                function printAttributeInformation($data, $attributes, $targetAttribute)
                {
                    $totalEntropy = calculateEntropy($data, $targetAttribute);
                    $totalCount = count($data);

                    echo "<table class='styled-table'>";
                    echo "<thead>";
                    echo "<tr>
            <th>Attribute</th>
            <th>Value</th>
            <th>Count</th>
            <th>TEPAT WAKTU</th>
            <th>TERLAMBAT</th>
            <th>Entropy</th>
            <th>Gain</th>
            </tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    // Display total entropy
                    echo "<tr>";
                    echo "<td>Total</td>";
                    echo "<td>-</td>";
                    echo "<td>" . $totalCount . "</td>";
                    echo "<td>-</td>";
                    echo "<td>-</td>";
                    echo "<td>" . number_format($totalEntropy, 3) . "</td>";
                    echo "<td>-</td>";
                    echo "</tr>";

                    foreach ($attributes as $attribute) {
                        $attributeValues = array_unique(array_column($data, $attribute));

                        foreach ($attributeValues as $value) {
                            $subset = array_filter($data, function ($row) use ($attribute, $value) {
                                return $row[$attribute] == $value;
                            });

                            $subsetCount = count($subset);
                            if ($subsetCount == 0) {
                                continue;
                            }

                            $subsetEntropy = calculateEntropy($subset, $targetAttribute);
                            $gain = calculateGain($data, $attribute, $targetAttribute, $totalEntropy);

                            // Count instances of each class
                            $classCounts = array_count_values(array_column($subset, $targetAttribute));
                            $countTEPAT_WAKTU = isset($classCounts['TEPAT WAKTU']) ? $classCounts['TEPAT WAKTU'] : 0;
                            $countTERLAMBAT = isset($classCounts['TERLAMBAT']) ? $classCounts['TERLAMBAT'] : 0;

                            echo "<tr>";
                            echo "<td>$attribute</td>";
                            echo "<td>$value</td>";
                            echo "<td>$subsetCount</td>";
                            echo "<td>$countTEPAT_WAKTU</td>";
                            echo "<td>$countTERLAMBAT</td>";
                            echo "<td>" . number_format($subsetEntropy, 3) . "</td>";
                            echo "<td>" . number_format($gain, 3) . "</td>";
                            echo "</tr>";
                        }
                    }

                    echo "</tbody>";
                    echo "</table>";
                }

                // Define attributes and target attribute
                $attributes = ['jenis_kelamin', 'ips1', 'ips2', 'ips3', 'ips4'];
                $targetAttribute = 'KETERANGAN';

                // Print attribute information table
                printAttributeInformation($data, $attributes, $targetAttribute);

                // Build and print decision tree
                $decisionTree = buildDecisionTree($data, $attributes, $targetAttribute);

                // Build and store decision tree in session
                $_SESSION['decision_tree'] = $decisionTree;
                function classify($tree, $data)
                {
                    foreach ($tree as $attribute => $branches) {
                        if (isset($branches[$data[$attribute]])) {
                            $subtree = $branches[$data[$attribute]];
                            if (is_array($subtree)) {
                                return classify($subtree, $data);
                            } else {
                                return $subtree;
                            }
                        }
                    }
                    return null; // If no decision matches
                }

                // Function to fetch data from the database
                function getAllData($pdo, $table_name)
                {
                    $stmt = $pdo->prepare("SELECT id,nama, jenis_kelamin, ips1, ips2, ips3, ips4, KETERANGAN FROM $table_name");
                    $stmt->execute();
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                // Function to get 30% of data as test data from the beginning
                function getTestData($data)
                {
                    $total = count($data);
                    $testSize = (int)($total * 0.3); // 30% of data
                    return array_slice($data, 0, $testSize);
                }

                // Function to evaluate the model
                function evaluateModel($tree, $testData)
                {
                    $TP = $TN = $FP = $FN = 0;
                    $total = count($testData);
                    $results = [];

                    foreach ($testData as $instance) {
                        $predicted = classify($tree, $instance);
                        $actual = $instance['KETERANGAN'];
                        $results[] = [
                            'id' => $instance['id'],
                            'nama' => $instance['nama'],
                            'jenis_kelamin' => $instance['jenis_kelamin'],
                            'ips1' => $instance['ips1'],
                            'ips2' => $instance['ips2'],
                            'ips3' => $instance['ips3'],
                            'ips4' => $instance['ips4'],
                            'actual' => $actual,
                            'predicted' => $predicted,
                            'correct' => ($predicted == $actual) ? 'Yes' : 'No'
                        ];
                        if ($predicted == $actual) {
                            if ($actual == 'TEPAT WAKTU') {
                                $TP++;
                            } else {
                                $TN++;
                            }
                        } else {
                            if ($predicted == 'TEPAT WAKTU') {
                                $FP++;
                            } else {
                                $FN++;
                            }
                        }
                    }

                    $accuracy = ($TP + $TN) / $total * 100;
                    $sensitivity = ($TP / ($TP + $FN)) * 100;
                    $specificity = ($TN / ($TN + $FP)) * 100;

                    return [
                        'accuracy' => $accuracy,
                        'sensitivity' => $sensitivity,
                        'specificity' => $specificity,
                        'TP' => $TP,
                        'FN' => $FN,
                        'FP' => $FP,
                        'TN' => $TN,
                        'results' => $results
                    ];
                }

                if (isset($_GET['table'])) {
                    $table_name = htmlspecialchars($_GET['table']); // Sanitize input

                    try {
                        // Fetch all data
                        $allData = getAllData($pdo, $table_name);

                        // Check if data is fetched
                        if (empty($allData)) {
                            echo "<h2>Data tidak ditemukan di tabel $table_name.</h2>";
                        } else {
                            // Fetch test data (30% from start)
                            $testData = getTestData($allData);

                            // Check if test data is available
                            if (empty($testData)) {

                                echo "<h2>Data uji tidak tersedia.</h2>";
                            } else {
                                // Fetch decision tree from session
                                if (isset($_SESSION['decision_tree'])) {
                                    $decisionTree = $_SESSION['decision_tree'];

                                    // Evaluate the model
                                    $metrics = evaluateModel($decisionTree, $testData);
                                    echo "<br>";
                                    echo "<h1>30% Data digunakan sebagai Data Testing</h1>";
                                    // Display detailed evaluation results
                                    echo "<table border='1' cellspacing='0' cellpadding='5' class='styled-table'>";
                                    echo "<tr>";
                                    echo "<th>ID</th>";
                                    echo "<th>Nama</th>";
                                    echo "<th>Jenis Kelamin</th>";
                                    echo "<th>IPS 1</th>";
                                    echo "<th>IPS 2</th>";
                                    echo "<th>IPS 3</th>";
                                    echo "<th>IPS 4</th>";
                                    echo "<th>Actual</th>";
                                    // echo "<th>Predicted</th>";
                                    // echo "<th>Correct</th>";
                                    echo "</tr>";
                                    foreach ($metrics['results'] as $result) {
                                        echo "<tr>";
                                        echo "<td>{$result['id']}</td>";
                                        echo "<td>{$result['nama']}</td>";
                                        echo "<td>{$result['jenis_kelamin']}</td>";
                                        echo "<td>{$result['ips1']}</td>";
                                        echo "<td>{$result['ips2']}</td>";
                                        echo "<td>{$result['ips3']}</td>";
                                        echo "<td>{$result['ips4']}</td>";
                                        echo "<td>{$result['actual']}</td>";
                                        // echo "<td>{$result['predicted']}</td>";
                                        // echo "<td>{$result['correct']}</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
                                }
                            }
                        }
                    } catch (PDOException $e) {
                        echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
                    }
                } else {
                    echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
                }
                // Redirect to another page to display the decision tree and rules
                // header("Location: /view/pk.php");
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

            if (isset($_GET['table'])) {
                $table_name = htmlspecialchars($_GET['table']); // Sanitize input

                try {
                    // Fetch all data
                    $allData = getAllData($pdo, $table_name);

                    // Check if data is fetched
                    if (empty($allData)) {
                        echo "<h2>Data tidak ditemukan di tabel $table_name.</h2>";
                    } else {
                        // Fetch test data (30% from start)
                        $testData = getTestData($allData);

                        // Check if test data is available
                        if (empty($testData)) {
                            echo "<h2>Data uji tidak tersedia.</h2>";
                        } else {
                            // Fetch decision tree from session
                            if (isset($_SESSION['decision_tree'])) {
                                $decisionTree = $_SESSION['decision_tree'];

                                $metrics = evaluateModel($decisionTree, $testData);
                                // Display evaluation metrics in table
                                echo "<h2>Menghasilkan Evaluasi Model dari 30% Data Testing</h2>";
                                echo "<table border='1' cellspacing='0' cellpadding='5' class='styled-table'>";
                                echo "<tr>";
                                echo "<th rowspan='2'>Actual</th>";
                                echo "<th colspan='2'>Prediksi</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<th>Tepat Waktu</th>";
                                echo "<th>Terlambat</th>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>Tepat Waktu</td>";
                                echo "<td>{$metrics['TP']}</td>";
                                echo "<td>{$metrics['FN']}</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td>Terlambat</td>";
                                echo "<td>{$metrics['FP']}</td>";
                                echo "<td>{$metrics['TN']}</td>";
                                echo "</tr>";
                                echo "</table>";

                                // Display additional metrics
                                echo "<p>Accuracy: " . number_format($metrics['accuracy'], 2) . "%</p>";
                                echo "<p>Specificity: " . number_format($metrics['specificity'], 2) . "%</p>";
                                echo "<p>Sensitivity: " . number_format($metrics['sensitivity'], 2) . "%</p>";
                            } else {
                                echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
                            }
                        }
                    }
                } catch (PDOException $e) {
                    echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
                }
            } else {
                echo "<p>Silakan lakukan mining terlebih dahulu agar proses berjalan dengan baik.</p>";
            }
            ?>
            <div class="card-table">
                <div id="table-content-container">
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php' ?>
</center>
<script>
    window.onload = function() {
        window.print();
    };
</script>