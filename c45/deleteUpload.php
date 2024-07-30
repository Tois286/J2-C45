<?php
include '../config/koneksi.php';

if (isset($_GET['table'])) {
    // Sanitize the table name to prevent SQL injection
    $table_name = mysqli_real_escape_string($koneksi, $_GET['table']);

    // Validate the table name to ensure it matches expected patterns (e.g., alphanumeric characters and underscores)
    if (preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
        // Construct the SQL queries
        $sqlDrop = "DROP TABLE IF EXISTS $table_name"; // Drop the table if it exists
        $sql_entropy = "TRUNCATE TABLE entropy"; // Empty the 'entropy' table
        $sql_gain = "TRUNCATE TABLE gain"; // Empty the 'gain' table
        $sql_rule = "TRUNCATE TABLE steptree"; // Empty the 'stepTree' table

        // Execute the query to drop the specified table
        if (mysqli_query($koneksi, $sqlDrop)) {
            // Execute the query to truncate the 'entropy' table
            if (mysqli_query($koneksi, $sql_entropy)) {
                // Execute the query to truncate the 'gain' table
                if (mysqli_query($koneksi, $sql_gain)) {
                    // Execute the query to truncate the 'stepTree' table
                    if (mysqli_query($koneksi, $sql_rule)) {
                        header("Location: ../index.php");
                        exit;
                    } else {
                        echo "Error truncating 'stepTree' table: " . mysqli_error($koneksi);
                    }
                } else {
                    echo "Error truncating 'gain' table: " . mysqli_error($koneksi);
                }
            } else {
                echo "Error truncating 'entropy' table: " . mysqli_error($koneksi);
            }
        } else {
            echo "Error dropping table: " . mysqli_error($koneksi);
        }
    } else {
        echo "Invalid table name.";
    }
} else {
    echo "No table name provided.";
}
