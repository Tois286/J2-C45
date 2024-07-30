<?php
include '../config/koneksi.php';

if (isset($_GET['table'])) {
    // Sanitize the table name to prevent SQL injection
    $table_name = mysqli_real_escape_string($koneksi, $_GET['table']);

    // Validate the table name to ensure it matches expected patterns (e.g., alphanumeric characters and underscores)
    if (preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
        // Construct the SQL queries
        $sqlDrop = "DROP TABLE IF EXISTS `$table_name`"; // Drop the table if it exists
        $sql_entropy = "DELETE FROM entropy WHERE nama_tabel='$table_name'"; // Empty the 'entropy' table
        $sql_gain = "DELETE FROM gain WHERE nama_tabel='$table_name'"; // Empty the 'gain' table
        $sql_rule = "DELETE FROM steptree WHERE nama_tabel='$table_name'"; // Empty the 'steptree' table

        // Execute the query to drop the specified table
        if (mysqli_query($koneksi, $sqlDrop)) {
            // Execute the query to delete rows from the 'entropy' table
            if (mysqli_query($koneksi, $sql_entropy)) {
                // Execute the query to delete rows from the 'gain' table
                if (mysqli_query($koneksi, $sql_gain)) {
                    // Execute the query to delete rows from the 'steptree' table
                    if (mysqli_query($koneksi, $sql_rule)) {
                        header("Location: ../index.php");
                        exit;
                    } else {
                        echo "Error deleting rows from 'steptree' table: " . mysqli_error($koneksi);
                    }
                } else {
                    echo "Error deleting rows from 'gain' table: " . mysqli_error($koneksi);
                }
            } else {
                echo "Error deleting rows from 'entropy' table: " . mysqli_error($koneksi);
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
