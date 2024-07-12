<div>
    <h1>Proses Prediksi</h1>
    <div class="card-home">
        <div class="upload">
            <form action="modul/database/uploadPros.php" method="post" enctype="multipart/form-data">
                <input type="file" name="excelFile" accept=".xlsx, .xls">
                <button type="submit" class="button button1">Upload</button>
            </form>
            <button class="button button1" style="background-color:red; color:white; width:55%; font-size: 12px;">file Wjib Excel, Menggunakan Huruf kapital dan Tandapa spasi!! (IPS1,KETERANGAN,DLL)</button>
        </div>
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

                        // Debugging var_dump
                        // var_dump($table_name);

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
        <div class="card-tree">
            <div class="table-container">
                <!-- <a href="c45/Prediksi.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">Prediksi</a>
        <a href="c45/mining.php?table=<?php echo $table_name; ?>" class="button-mining" value="<?php echo $table_name; ?>">mining</a>
        id="loading" onclick="startLoading(event)" -->
                <div class="card-table">
                    <div id="table-content-container">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div