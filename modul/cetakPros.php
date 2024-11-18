<?php
include '../config/koneksi.php';

if (isset($_GET['nik'])) {
    $nik_balita = $_GET['nik'];

    // Ambil data balita berdasarkan NIK
    $sql = "SELECT 
                balita.nik_balita, 
                balita.nama AS nama_balita, 
                balita.jenis_kelamin, 
                dataorangtua.nama_ayah, 
                dataorangtua.nama_ibu, 
                imunisasi.tanggal_imunisasi, 
                imunisasi.jenis_imunisasi 
            FROM 
                balita 
            JOIN 
                dataorangtua ON balita.id_orangtua = dataorangtua.id_orangtua 
            JOIN 
                imunisasi ON balita.nik_balita = imunisasi.nik_balita 
            WHERE 
                balita.nik_balita = '$nik_balita'";

    $result = mysqli_query($koneksi, $sql);

    if (!$result) {
        die("Error: " . htmlspecialchars(mysqli_error($koneksi)));
    }

    $data = mysqli_fetch_assoc($result);
} else {
    die("NIK Balita tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Balita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .card {
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 850px;
            margin: 0 auto;
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .header {
            flex: 1;
            text-align: left;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }

        .info {
            flex: 1;
            text-align: left;
            margin-left: 20px;
        }

        .info h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #444;
        }

        .print-button {
            margin-top: 20px;
            text-align: center;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .info table {
            border-collapse: collapse;
            width: 100%;
        }

        .info td {
            padding: 5px;
            vertical-align: top;
            font-size: 13px;
        }

        .info td:first-child {
            width: 30%;
        }

        .separator {
            margin: 0 10px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="header">
            <center>
                <img src="../asset/posyandu1.png" alt="Logo"> <!-- Ganti dengan path logo Anda -->
                <h2>Data Imunisasi Balita</h2>
                <p>Posyandu XYZ</p>
            </center>
        </div>

        <div class="info">
            <h3>Informasi Balita:</h3>
            <table>
                <tr>
                    <td><strong>NIK Balita</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['nik_balita']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Balita</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['nama_balita']); ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Ayah</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['nama_ayah']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Ibu</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['nama_ibu']); ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Imunisasi</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars(date('d-m-Y', strtotime($data['tanggal_imunisasi']))); ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Imunisasi</strong></td>
                    <td><span class="separator">:</span> <?php echo htmlspecialchars($data['jenis_imunisasi']); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        // Fungsi untuk mencetak halaman saat halaman terbuka
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>

<?php
// Tutup koneksi
mysqli_close($koneksi);
?>