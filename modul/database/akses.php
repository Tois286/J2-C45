<?php
$host = 'localhost';
$dbname = 'dbmining';
$username = 'root';
$password = '';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Buat koneksi ke database
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mendapatkan NPM dari tabel yang dipilih
    $query = "SELECT NAMA, NPM FROM $table_name";
    $result = $conn->query($query);

    if ($result) {
        // Query untuk mendapatkan ID terakhir dari tabel users
        $id_query = "SELECT MAX(id) AS max_id FROM users";
        $id_result = $conn->query($id_query);
        $last_id = 0;

        if ($id_result && $id_result->num_rows > 0) {
            $row = $id_result->fetch_assoc();
            $last_id = $row['max_id'];
        }

        // Menginisialisasi ID baru dengan menambahkan 1 ke ID terakhir
        $new_id = $last_id + 1;

        // Siapkan statement untuk memasukkan data ke tabel users
        $insert_stmt = $conn->prepare("INSERT INTO users (id, nama, username, password, role) VALUES (?, ?, ?, ?, ?)");

        // Periksa apakah ada baris yang dikembalikan
        if ($result->num_rows > 0) {
            // Bind parameter
            $insert_stmt->bind_param("issss", $new_id, $nama, $npm, $password, $role);

            // Fetch setiap baris dan masukkan ke tabel users
            while ($row = $result->fetch_assoc()) {
                $nama = $row['NAMA'];
                $npm = $row['NPM'];
                $role = 'user';
                // Ekstrak 4 digit terakhir dari NPM
                $last_four_digits = substr($npm, -4);
                // Buat password
                $password = "unipi#" . $last_four_digits;

                // Eksekusi statement insert
                if ($insert_stmt->execute()) {
                    // Tambahkan ID baru untuk entri berikutnya
                    $new_id++;
                } else {
                    echo "<p>Error updating data: " . $insert_stmt->error . "</p>";
                }
            }
        } else {
            echo "No records found in table $table_name";
        }
        header("Location: ../../index.php");
        exit();
        // Tutup statement
        $insert_stmt->close();
    } else {
        echo "Error executing query: " . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "No table name provided.";
}
