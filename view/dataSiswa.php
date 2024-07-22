<?php if ($role == 'admin') : ?>
    <div>
        <h1>Data Pengguna</h1>
        <div class="card-home">
            <a href="#" class="button-mining">
                Tambah
            </a>
            <table class="table-container" id="table-content">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>username</th>
                        <th>password</th>
                        <th>role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                include 'config/koneksi.php';
                $sql = mysqli_query($koneksi, "SELECT * FROM users");
                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data["nama"];   ?></td>
                            <td><?php echo $data["username"];   ?></td>
                            <td><?php echo $data["password"];   ?></td>
                            <td><?php echo $data["role"];   ?></td>
                            <td>
                                <a href="modul/database/hapusAkses.php?id=<?php echo $data['username']; ?>" type="button" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a> |
                                <a href="modul/database/editAkses.php?id=<?php echo $data['username']; ?>" type="button">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php if ($role == 'user') : ?>
    <div class="container">
        <div>
            <h1>Data Siswa</h1>
            <div class="card-home">
                <div class="upload">
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="excelFile" accept=".xlsx, .xls">
                        <button type="submit" class="button button1">Upload</button>
                    </form>
                    <button class="button button1" style="background-color:#009879; color:white; width:70%; font-size: 12px;">Menggunakan Huruf kapital dan Tanpa spasi!! (WAJIB memiliki kolom IPS dan KETERANGAN, dengan Format xlsx)</button>
                    <div class="dropdown">
                        <button class="button button1" id="chooseTable" style="left: 50%;">Pilih Tabel</button>
                        <div class="dropdown-content">
                            <?php
                            $host = 'localhost';
                            $dbname = 'dbmining';
                            $username = 'root';
                            $password = '';

                            try {
                                // Create a new PDO instance
                                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                                // Set the PDO error mode to exception
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            } catch (PDOException $e) {
                                die("Connection failed: " . $e->getMessage());
                            }

                            $conn = new mysqli($host, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SHOW TABLES";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $table_name = $row["Tables_in_" . $dbname];

                                    // Check if table_name starts with "m_"
                                    if (strpos($table_name, "m_") === 0) {
                                        // Display the table button
                                        echo "<button name='table' class='button-mining' onclick='chooseTable(\"$table_name\")' value='$table_name'>$table_name</button><br>";
                                    }
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
        </div>
    </div>
<?php endif; ?>

<div class="result-section">
    <?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excelFile"])) {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        $fileName = $_FILES['excelFile']['name'];
        $fileSize = $_FILES['excelFile']['size'];
        $fileType = $_FILES['excelFile']['type'];

        // Pastikan file yang diunggah adalah file Excel
        $allowedExtensions = array("xlsx", "xls");
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die("Error: Hanya file Excel (.xlsx, .xls) yang diizinkan.");
        }

        // Pindahkan file yang diunggah ke lokasi yang diinginkan
        $uploadDir = './modul/database/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Membuat direktori uploads jika belum ada
        }
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Load file Excel yang diunggah menggunakan PhpSpreadsheet
            $spreadsheet = IOFactory::load($destPath);
            $sheet = $spreadsheet->getActiveSheet();

            // Dapatkan kolom dari baris pertama (header)
            $columns = [];
            $firstRow = $sheet->getRowIterator()->current();
            $cellIterator = $firstRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);

            foreach ($cellIterator as $cell) {
                // Sanitasi nama kolom
                $columnName = $cell->getValue();
                $sanitizedColumnName = preg_replace('/[^a-zA-Z0-9_]/', '_', $columnName); // Ganti karakter yang tidak valid dengan _

                // Pastikan nama kolom yang divalidasi tidak kosong
                if (!empty($sanitizedColumnName)) {
                    $columns[] = $sanitizedColumnName;
                } else {
                    die("Error: Nama kolom tidak valid.");
                }
            }

            // Sanitize file name to use as table name
            $tableName = "M_" . preg_replace('/[^a-zA-Z0-9_]/', '_', pathinfo($fileName, PATHINFO_FILENAME));

            // Construct CREATE TABLE SQL statement
            $createTableSql = "CREATE TABLE IF NOT EXISTS `$tableName` (id INT AUTO_INCREMENT PRIMARY KEY, ";
            foreach ($columns as $column) {
                $createTableSql .= "`$column` TEXT, "; // Gunakan nama kolom yang sudah divalidasi
            }
            $createTableSql = rtrim($createTableSql, ", ") . ");";

            try {
                $pdo->exec($createTableSql);
            } catch (PDOException $e) {
                die("Error creating table: " . $e->getMessage());
            }

            // Kemas data dari file Excel dalam bentuk form
            echo "<form class='excel-form' method='post' action='modul/database/uploadPush.php'>";
            echo "<input type='hidden' name='tableName' value='$tableName'>";

            foreach ($columns as $column) {
                echo "<input type='hidden' name='columns[]' value='$column'>";
            }

            $rowCounter = 0;
            foreach ($sheet->getRowIterator() as $row) {
                // Skip the first row (header)
                if ($rowCounter === 0) {
                    $rowCounter++;
                    continue;
                }

                echo "<div class='form-row'>";
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);
                $columnCounter = 0;
                $columnProcessed = 0; // Counter for processed columns

                // Limit the number of columns to iterate through (assuming $columns is set accordingly)
                $maxColumns = min(9, count($columns)); // Limit to a maximum of 9 columns or the number of columns available

                foreach ($cellIterator as $cell) {
                    if ($columnProcessed >= $maxColumns) {
                        break; // Stop iterating if we've processed the maximum allowed columns
                    }

                    $value = $cell->getValue();

                    // Check if the cell has a value
                    if (!empty($value)) {
                        $label = $columns[$columnCounter];
                        $id = "value" . $rowCounter; // Generate ID based on row number
                        echo "<div class='form-group'>";
                        echo "<label class='form-label'>{$label}</label>";
                        echo "<input type='text' class='form-input' name='{$label}[]' id='{$id}' value='{$value}'>";
                        echo "</div>";
                        $columnProcessed++;
                    }

                    $columnCounter++;
                }

                echo "</div>"; // Close form-row
                $rowCounter++;
            }

            echo "<button type='submit' class='submit-button' onclick='return confirmSubmit()'>Lanjut</button>";
            echo "<button type='button' class='cancel-button' onclick='return confirmCancel()'>Cancel</button>";
            echo "</form>";
        } else {
            die("Error: Gagal mengunggah file.");
        }
    }
    ?>
</div>

<?php if ($role == 'user') : ?>
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
<?php endif; ?>


<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Your custom script -->
<script src="src/js/script.js"></script>