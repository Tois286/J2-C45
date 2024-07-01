<!DOCTYPE html>
<html>

<head>
    <title>Kelola Data Siswa</title>

    <script>
        function loadTable(tableName) {
            document.getElementById("table-content").innerHTML = '';

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("table-content").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "?table=" + tableName, true);
            xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
            xhttp.send();
        }
    </script>
</head>

<body>
    <div>
        <h1>Kelola Data Siswa</h1>
        <div class="card-home">
            <div class="upload">
                <form action="modul/database/uploadPros.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="excelFile" accept=".xlsx, .xls">
                    <button type="submit" class="button button1">Upload</button>
                </form>
                <div class="dropdown dropdown-left">
                    <button class="button button1" style="left: 50%;">Pilih Tabel</button>
                    <div class="dropdown-content">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "dbmining";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SHOW TABLES";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<a href='javascript:void(0);' onclick='loadTable(\"" . $row["Tables_in_" . $dbname] . "\")'>" . $row["Tables_in_" . $dbname] . "</a><br>";
                            }
                        } else {
                            echo "<span>Tidak ada tabel</span>";
                        }

                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-tabel">
                <div class="table-container">
                    <table id="table-content">
                        <?php
                        if (isset($_GET['table'])) {
                            $table_name = $_GET['table'];

                            $koneksi = mysqli_connect($servername, $username, $password, $dbname);
                            if (!$koneksi) {
                                die("Connection failed: " . mysqli_connect_error());
                            }

                            $query = "SELECT * FROM $table_name";
                            $result = mysqli_query($koneksi, $query);

                            if (mysqli_num_rows($result) > 0) {
                                echo "<tr>";
                                echo "<th>Action</th>";
                                $fields = mysqli_fetch_fields($result);
                                foreach ($fields as $field) {
                                    echo "<th>" . $field->name . "</th>";
                                }
                                echo "</tr>";

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> | <a href='../modul/database/c45pros.php?id=" . $row['id'] . "&table=$table_name'>Kelola</a> | <a href='delete.php?id=" . $row['id'] . "'>Delete</a></td>";
                                    foreach ($row as $value) {
                                        echo "<td>$value</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='" . (count($fields) + 1) . "'>Tidak ada data dalam tabel '$table_name'</td></tr>";
                            }

                            mysqli_close($koneksi);
                        } else {
                            echo "<tr><td colspan='2'>Silakan pilih tabel dari dropdown di atas.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>