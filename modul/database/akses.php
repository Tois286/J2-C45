<?php
$host = 'localhost';
$dbname = 'dbmining';
$username = 'root';
$password = '';

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch NPM from the table
    $query = "SELECT NPM FROM $table_name";
    $result = $conn->query($query);

    if ($result) {
        // Prepare a statement for inserting into users table
        $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

        // Check if there are rows returned
        if ($result->num_rows > 0) {
            // Bind parameters
            $insert_stmt->bind_param("sss", $npm, $password, $role);

            // Fetch each row and output NPM values with generated passwords
            while ($row = $result->fetch_assoc()) {
                $role = 'user';
                $npm = $row['NPM'];
                // Extract last 4 digits of NPM
                $last_four_digits = substr($npm, -4);
                // Generate password
                $password = "unipi#" . $last_four_digits;

                // Execute the insert statement
                if ($insert_stmt->execute()) {
                    echo "<script>alert('Data berhasil Membuat Akses');</script>";
                    echo "<script>window.location.href='../../index.php';</script>";
                } else {
                    echo "<p>Error updating data: " . $insert_stmt->error . "</p>";
                }
            }
        } else {
            echo "No records found in table $table_name";
        }

        // Close statement
        $insert_stmt->close();
    } else {
        echo "Error executing query: " . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    echo "No table name provided.";
}
