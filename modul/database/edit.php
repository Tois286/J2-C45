<?php
$host = 'localhost';
$dbname = 'dbmining';
$username = 'root';
$password = '';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table_name = $_GET['table'];
    $id = $_GET['id'];

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Ambil data yang akan diedit berdasarkan ID
    $sql = "SELECT * FROM $table_name WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "<p>Prepare statement failed: " . $conn->error . "</p>";
    } else {
        // Bind parameter id ke statement SQL
        $stmt->bind_param('i', $id);

        // Eksekusi statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Data ditemukan, tampilkan form untuk mengedit
                $row = $result->fetch_assoc();
?>
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Edit Data</title>
                    <style>
                        label {
                            display: inline-block;
                            width: 100px;
                            margin-bottom: 10px;
                        }

                        input[type="text"] {
                            width: 300px;
                            padding: 5px;
                        }

                        input[type="submit"] {
                            padding: 10px 20px;
                            background-color: #4CAF50;
                            color: white;
                            border: none;
                            cursor: pointer;
                        }

                        input[type="submit"]:hover {
                            background-color: #45a049;
                        }
                    </style>
                </head>

                <body>
                    <h3>Edit Data</h3>
                    <form method="POST" action="update.php">
                        <input type="hidden" name="table" value="<?= $table_name ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php
                        foreach ($row as $key => $value) {
                            // Tampilkan field yang bisa diubah, kecuali id
                            if ($key != 'id') {
                                echo "<label for='$key'>$key:</label>";
                                echo "<input type='text' id='$key' name='$key' value='$value'><br>";
                            }
                        }
                        ?>
                        <br>
                        <input type="submit" value="Update">
                    </form>
                </body>

                </html>
<?php
            } else {
                echo "<p>No data found for ID $id</p>";
            }
        } else {
            echo "<p>Error fetching data: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Invalid request. Please provide table name and ID.</p>";
}
?>