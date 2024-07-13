<?php 

$id = $_GET["id"]; // Konsistensi penggunaan variabel $id

$conn = mysqli_connect("localhost", "root", "", "dbmining");

// Memastikan koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function hapus($id, $table) {
    global $conn;
    // Escape table name to prevent SQL injection
    $table = mysqli_real_escape_string($conn, $table);
    // Escape id to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    
    $query = "DELETE FROM `$table` WHERE id = $id";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

// Pastikan untuk menyebutkan nama tabel saat memanggil fungsi hapus
if ( hapus($id, 'book1') > 0 ) {
    echo "
        <script>
            alert('data berhasil di hapus');
            document.location.href = '../index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('data gagal di hapus');
            document.location.href = '../index.php';
        </script>
    ";
}

// untuk menghaous table book2
if ( hapus($id, 'book2') > 0 ) {
    echo "
        <script>
            alert('data berhasil di hapus');
            document.location.href = '../index.php';
        </script>
    ";
} else {    
    echo "
        <script>
            alert('data gagal di hapus');
            document.location.href = '../index.php';
        </script>
    ";
}

?>
<script src="../index.php"></script>
