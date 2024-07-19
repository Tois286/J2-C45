<?php
$host = 'localhost';
$dbname = 'dbmining';
$username = 'root';
$password = '';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table_name = $_GET['table'];
    $id = $_GET['id'];

    // Validasi nama tabel dan ID
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name) || !preg_match('/^[0-9]+$/', $id)) {
        die("Invalid input.");
    }

    // Buat koneksi ke database
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query delete dengan JOIN
    $delete_query = "DELETE $table_name, users
                     FROM $table_name
                     INNER JOIN users ON users.id = $table_name.id
                     WHERE users.id = ?";
    $stmt = $conn->prepare($delete_query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location.href='../../index.php';</script>"; // Ganti index.php dengan halaman yang sesuai
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Parameter table dan id tidak ditemukan.</p>";
}
