<style>
    .view {
        display: none;
    }
</style>

<div>
    <h1>Proses Testing</h1>
    <div class="card-home">
        <div id="table-content">

            <a href='#proji' onclick="view('proji')" class='button-mining'>Proses Uji </a>
            <a href='#hasil' onclick="view('')" class='button-mining'>Hasil Uji </a>
            Dari <span style="display: inline; font-size: 2em; font-weight: bold; margin: 0;">30%</span> data
            <div class="table-container">
                <div class="card-home" id="content">
                    <div id="table-content-container">
                        <?php
                        include 'config/koneksi.php';
                        // Function to classify data using the decision tree
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
                                            echo "<h2>Pohon keputusan tidak ditemukan di session.</h2>";
                                        }
                                    }
                                }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                        } else {
                            echo "<h2>Parameter table_name tidak ditemukan.</h2>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="proji" class="view">
        <div class="card-home">
            <?php
            $metrics = evaluateModel($decisionTree, $testData);
            // // Display detailed evaluation results
            echo "<h2>Hasil Evaluasi</h2>";
            echo "<div class='table-container'>";
            echo '<div class="styled-table" style="overflow-x: auto;">';
            echo '<table style="width: 100%; border-collapse: collapse; font-size: 14px;">';
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nama</th>";
            echo "<th>Jenis Kelamin</th>";
            echo "<th>IPS 1</th>";
            echo "<th>IPS 2</th>";
            echo "<th>IPS 3</th>";
            echo "<th>IPS 4</th>";
            echo "<th>Actual</th>";
            echo "<th>Predicted</th>";
            echo "<th>Correct</th>";
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
                echo "<td>{$result['predicted']}</td>";
                echo "<td>{$result['correct']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "</div>";

            ?>
        </div>
    </div>
    <div id="hasil" class="view">
        <div class="card-home">
            <?php
            $metrics = evaluateModel($decisionTree, $testData);
            // Display evaluation metrics in table
            echo "<h2>Evaluasi Model</h2>";
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
            ?>
        </div>
    </div>
</div>