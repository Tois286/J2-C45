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

    // Lakukan proses delete berdasarkan id
    $delete_query = "DELETE FROM $table_name WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location.href='../../index.php';</script>"; // Ganti index.php dengan halaman yang sesuai
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Parameter table dan id tidak ditemukan.</p>";
}
