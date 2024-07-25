<style>
    .hidden {
        display: none;
    }
</style>

<div>
    <h1>Pohon Keputusan</h1>
    <div class="card-home">
        <div>
            <a href='#miningTree' onclick="showContent('miningTree')" class='button-mining'>Proses Training</a>
            <a href='#stepTree' onclick="showContent('stepTree')" class='button-mining'>Step Tree</a>
            Dari <span style="display: inline; font-size: 2em; font-weight: bold; margin: 0;">70%</span> data
            <div class="table-container">
                <?php
                $host = 'localhost';
                $dbname = 'dbmining';
                $username = 'root';
                $password = '';

                if (isset($_GET['table'])) {
                    $table_name = $_GET['table'];

                    $conn = new mysqli($host, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    // Hitung jumlah total baris pada tabel
                    $result = $conn->query("SELECT COUNT(*) AS total_rows FROM $table_name");
                    $row = $result->fetch_assoc();
                    $total_rows = $row['total_rows'];

                    // Hitung jumlah baris yang ingin ditampilkan (70% dari total baris)
                    $limit = ceil(0.7 * $total_rows);

                    // Query untuk mengambil 70% data terbaru
                    $query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT $limit";
                    $result = $conn->query($query);


                    $conn->close();
                } else {
                    echo "<p>Silakan pilih tabel dari dropdown di atas.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <div id="miningTree" class="hidden">
        <div class="card-home">
            <div class="table-container" style="width:100%;height:200px;">
                <div class="card-table" style="background-color:black;color:white; display: inline-table; padding:20px;">
                    <div id="table-content-container"></div>
                    <?php
                    session_start();
                    if (isset($_SESSION['decision_tree'])) {
                        $decision_tree = $_SESSION['decision_tree'];
                        echo "<h3>Pohon Keputusan</h3>";
                        echo "<pre>";
                        print_r($decision_tree);
                        echo "</pre>";
                    } else {
                        echo "Tidak ada pohon keputusan yang ditemukan di sesi.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="stepTree" class="hidden">
        <div class="card-home" style="color: black;">
            <p>Ini adalah konten untuk Step Tree <?php echo $table_name ?></p>
            <?php

            if (isset($_SESSION['decision_tree'])) {
                $decision_tree = $_SESSION['decision_tree'];

                function getRules($tree, $currentRule = [])
                {
                    $rules = [];
                    if (is_array($tree)) {
                        foreach ($tree as $attribute => $branches) {
                            foreach ($branches as $value => $subtree) {
                                $newRule = $currentRule;
                                $newRule[$attribute] = $value;
                                if (is_array($subtree)) {
                                    $rules = array_merge($rules, getRules($subtree, $newRule));
                                } else {
                                    $newRule['Status Lulus'] = $subtree;
                                    $rules[] = $newRule;
                                }
                            }
                        }
                    }
                    return $rules;
                }

                $rules = getRules($decision_tree);
            }
            ?>
            <div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rules)) : ?>
                            <?php foreach ($rules as $index => $rule) : ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <?php
                                        $ruleStr = [];
                                        foreach ($rule as $key => $value) {
                                            if ($key !== 'Status Lulus') {
                                                $ruleStr[] = "$key = $value";
                                            }
                                        }
                                        echo implode(', ', $ruleStr);
                                        echo " -> " . $rule['Status Lulus'];
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2">Tidak ada aturan yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>