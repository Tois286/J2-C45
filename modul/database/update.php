<?php
$host = 'localhost';
$dbname = 'dbmining';
$username = 'root';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['table']) && isset($_POST['id'])) {
    $table_name = $_POST['table'];
    $id = $_POST['id'];

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Persiapkan query UPDATE
    $sql = "UPDATE $table_name SET ";

    $params = [];
    foreach ($_POST as $key => $value) {
        // Skip table and id fields
        if ($key != 'table' && $key != 'id') {
            $sql .= "$key = ?, ";
            $params[] = $value;
        }
    }
    // Hapus koma terakhir dan tambahkan kondisi WHERE berdasarkan id
    $sql = rtrim($sql, ', ') . " WHERE id = ?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "<p>Prepare statement failed: " . $conn->error . "</p>";
    } else {
        // Bind parameters ke statement SQL
        $types = str_repeat('s', count($params)); // Semua parameter adalah string ('s')
        $stmt->bind_param($types, ...$params);

        // Eksekusi statement
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil di Update');</script>";
            echo "<script>window.location.href='../../index.php';</script>";
        } else {
            echo "<p>Error updating data: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Invalid request. Please provide table name and ID.</p>";
}
