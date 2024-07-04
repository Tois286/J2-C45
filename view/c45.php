<body>
    <h1>Proses data Training</h1>
    <div class="card-home">
        <div class="upload">
            <div class="dropdown">
                <button class="button button1" style="left: 50%;">Pilih Tabel</button>
                <div class=" dropdown-content">
                    <form method="GET" action="">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "dbmining-base";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SHOW TABLES";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $table_name = $row["Tables_in_" . $dbname];
                                echo "<button type='submit' name='table' class='button-mining' value='$table_name'>$table_name</button><br>";
                            }
                        } else {
                            echo "<span>Tidak ada tabel</span>";
                        }

                        $conn->close();
                        ?>
                    </form>
                </div>
            </div>
            <button class="button button1" style="left: 50%; background-color:red; border-radius:8px; color: white; width: 100%; ">Format wajib .xlsx atau excel</button>
        </div>
    </div>
    <div class="card-tree">
        <div class="table-container">
            <div class="card-table">
                <?php
                if (isset($_GET['table'])) {
                    $table_name = $_GET['table'];

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $query = "SELECT * FROM $table_name";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {

                        echo "<table id='table-content'>";
                        echo "<tr>";
                        echo "<th>Action</th>";
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            echo "<th>" . $field->name . "</th>";
                        }
                        echo "</tr>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> | <a href='../c45/c45pros.php?id=" . $row['id'] . "&table=$table_name'>Kelola</a> | <a href='delete.php?id=" . $row['id'] . "'>Delete</a></td>";
                            foreach ($row as $value) {
                                echo "<td>$value</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";

                        // Tambahkan tombol Mining di luar loop while
                        echo "<br class='mining'>";
                        echo "<a href='./c45/prediksi.php?table=" . $table_name . "' class='button-mining'>Prediksi</a>";
                        echo "<a href='./c45/mining.php?table=" . $table_name . "' class='button-mining'>Mining</a>";
                        echo "<a href='' class='button-mining' style='background-color:red; border:none;color:white;'>Prediksi Hanya dapat Dilakukan satu kali saja</a>";
                        echo "<br>";
                    } else {
                        echo "<p>No data found</p>";
                    }
                    $conn->close();
                } else {
                    echo "<p>Silakan pilih tabel dari dropdown di atas.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
<?php include 'modul/footer.php' ?>

</html>