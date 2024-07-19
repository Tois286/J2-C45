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
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }

                        .card-home {
                            max-width: 600px;
                            margin: 20px auto;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                            background-color: #fff;
                        }

                        h3 {
                            margin-top: 0;
                        }

                        label {
                            display: block;
                            margin-bottom: 8px;
                            font-weight: bold;
                        }

                        input[type="text"] {
                            width: calc(100% - 22px);
                            padding: 10px;
                            margin-bottom: 15px;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;
                        }

                        input[type="submit"] {
                            padding: 10px 20px;
                            background-color: #4CAF50;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 16px;
                            transition: background-color 0.3s ease;
                        }

                        input[type="submit"]:hover {
                            background-color: #45a049;
                        }
                    </style>
                </head>

                <body>
                    <div class="card-home">
                        <h3>Edit Data</h3>
                        <form method="POST" action="update.php">
                            <input type="hidden" name="table" value="<?= htmlspecialchars($table_name, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
                            <?php
                            foreach ($row as $key => $value) {
                                // Tampilkan field yang bisa diubah, kecuali id
                                if ($key != 'id') {
                                    echo "<label for='$key'>" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . ":</label>";
                                    echo "<input type='text' id='$key' name='$key' value='" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "'><br>";
                                }
                            }
                            ?>
                            <input type="submit" value="Update">
                        </form>
                    </div>
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