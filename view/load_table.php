<?php
// load_table.php

if (isset($_GET['table'])) {
    $table_name = $_GET['table'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbmining-base";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch table data
    $query = "SELECT * FROM $table_name";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $response = array();
        $response['fields'] = array();
        $response['rows'] = array();

        // Fetch table fields
        while ($field = $result->fetch_field()) {
            array_push($response['fields'], $field->name);
        }

        // Fetch table rows
        while ($row = $result->fetch_assoc()) {
            array_push($response['rows'], $row);
        }

        // Close connection
        $conn->close();

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(array('error' => 'No data found'));
    }
} else {
    echo json_encode(array('error' => 'Table parameter is missing'));
}
