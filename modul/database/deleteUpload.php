<?php
include '../../config/koneksi.php';

if (isset($_GET['table'])) {
    // Sanitize the table name to prevent SQL injection
    $table_name = mysqli_real_escape_string($koneksi, $_GET['table']);

    // Validate the table name to ensure it matches expected patterns (e.g., alphanumeric characters and underscores)
    if (preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
        // Construct the SQL query
        $sqlDrop = "DROP TABLE $table_name";

        // Execute the query
        if (mysqli_query($koneksi, $sqlDrop)) {
            echo "Table '$table_name' dropped successfully.";
        } else {
            echo "Error dropping table: " . mysqli_error($koneksi);
        }
    } else {
        echo "Invalid table name.";
    }
} else {
    echo "No table name provided.";
}
