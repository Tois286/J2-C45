<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Training Process</title>
    <!-- Include Bootstrap CSS or your custom CSS here -->
</head>

<body>
    <h1>Proses data Training</h1>
    <div class="card-home">
        <div class="upload">
            <div class="dropdown">
                <button class="button button1" id="chooseTable" style="left: 50%;">Pilih Tabel</button>
                <div class="dropdown-content">
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "dbmining-base";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SHOW TABLES";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $table_name = $row["Tables_in_" . $dbname];
                            echo "<button name='table' class='button-mining' onclick='chooseTable(\"$table_name\")' value='$table_name'>$table_name</button><br>";
                        }
                    } else {
                        echo "<span>Tidak ada tabel</span>";
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
            <button class="button button1" style="left: 50%; background-color:red; border-radius:8px; color: white; width: 100%; ">Format wajib .xlsx atau excel</button>
        </div>
    </div>
    <div class="card-tree">
        <div class="table-container">
            <div class="card-table">
                <div id="table-content-container"></div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Your custom script -->
    <script>
        function chooseTable(tableName) {
            $.ajax({
                url: 'view/load_table.php', // Sesuaikan dengan file yang sesuai di proyek Anda
                type: 'GET',
                data: {
                    table: tableName
                },
                dataType: 'json',
                success: function(data) {
                    var tableHtml = '<table id="table-content">';
                    if (data.fields.length > 0) {
                        // Create table header
                        tableHtml += '<tr><th>Action</th>';
                        data.fields.forEach(function(field) {
                            tableHtml += '<th>' + field + '</th>';
                        });
                        tableHtml += '</tr>';

                        // Create table rows
                        data.rows.forEach(function(row) {
                            tableHtml += '<tr><td><a href="edit.php?id=' + row.id + '">Edit</a> | <a href="delete.php?id=' + row.id + '">Delete</a></td>';
                            for (var field in row) {
                                tableHtml += '<td>' + row[field] + '</td>';
                            }
                            tableHtml += '</tr>';
                        });
                    } else {
                        tableHtml += '<tr><td colspan="' + (data.fields.length + 1) + '">No data found</td></tr>';
                    }
                    tableHtml += '</table>';

                    $('#table-content-container').html(tableHtml);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading data:', textStatus, errorThrown); // Log the error details
                    $('#table-content-container').html('<p>Error loading data: ' + textStatus + ' - ' + errorThrown + '</p>');
                }
            });
        }
    </script>
</body>

</html>