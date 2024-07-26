<h1>Proses data Training</h1>
<div class="card-home">
    <div class="upload">
        <form action="modul/database/uploadPros.php" method="post" enctype="multipart/form-data">
            <input type="file" name="excelFile" accept=".xlsx, .xls">
            <button type="submit" class="button button1">Upload</button>
        </form>
        <button class="button button1" style="background-color:#009879; color:white; width:70%; font-size: 12px;">Menggunakan Huruf kapital dan
            Tanpa spasi!! (WAJIB memiliki kolom IPS dan KETERANGAN, dengan Format xlsx)</button>
        <div class="dropdown">
            <button class="button button1" id="chooseTable" style="left: 50%;">Pilih Tabel</button>
            <div class="dropdown-content">
                <?php
                $host = 'localhost';
                $dbname = 'dbmining';
                $username = 'root';
                $password = '';

                $conn = new mysqli($host, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SHOW TABLES";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $table_name = $row["Tables_in_" . $dbname];

                        // Check if table_name is "users"
                        if (strpos($table_name, 'r_') === 0) {
                            continue; // Lewati tabel yang nama-namanya diawali dengan 'R_'
                        }
                        if (strpos($table_name, 'm_') === 0) {
                            continue; // Lewati tabel yang nama-namanya diawali dengan 'R_'
                        }

                        // Check if table_name is "users"
                        if ($table_name == "users") {
                            // Check if user is logged in (you need to implement this check)
                            $isLoggedIn = false; // Example: Replace with your actual login check

                            if (!$isLoggedIn) {
                                continue; // Skip displaying this table if user is not logged in
                            }
                        }

                        // Display the table button
                        echo "<button name='table' class='button-mining' onclick='chooseTable(\"$table_name\")' value='$table_name'>$table_name</button><br>";
                    }
                } else {
                    echo "<span>Tidak ada tabel</span>";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>
<div class="card-tree">
    <div class="table-container">
        <!-- <a href="c45/Prediksi.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">Prediksi</a>
        <a href="c45/mining.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">mining</a>
        id="loading" onclick="startLoading(event)" -->
        <div class="card-table">
            <div id="table-content-container"></div>
        </div>
    </div>
</div>
<!-- Include jQuery -->
<!-- Your custom script -->
<!-- <script src="src/js/script.js"></script> -->