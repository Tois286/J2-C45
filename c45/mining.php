<?php
include '../config/koneksi.php';
session_start();

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];
    // Mengambil Data dari Database
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
        echo '<a href="../index.php?table=' . $table_name . '" class="button-mining" id="back">Back</a>';
        echo '<a href="save.php?table=' . htmlspecialchars($table_name) . '" class="button-mining">Save</a>';
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

        echo "<br>";
        echo "<pre>";
        echo "<h1>C45 Training</h1>";
        echo "</pre>";
        echo "<br>";
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
        echo "<br>";
        echo "<pre>";
        echo "<h1>Split Node</h1>";
        echo "<div class='card-home'>";
        printDecisionTree($decisionTree);
        echo "</div>";
        echo "</pre>";
        echo "<br>";

        // Print decision rules
        echo "<pre>";
        echo "<h1>Rule Node</h1>";
        echo "<div class='card-home'>";
        printDecisionRules($decisionTree);
        echo "</div>";
        echo "</pre>";
        // Build and store decision tree in session
        $_SESSION['decision_tree'] = $decisionTree;

        // Redirect to another page to display the decision tree and rules
        // header("Location: /view/pk.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    echo '<div>';
}
