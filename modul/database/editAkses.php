<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses form jika di-submit
    if (isset($_POST['username'])) {
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);
        $role = mysqli_real_escape_string($koneksi, $_POST['role']);

        // Update query
        $sql = "UPDATE users SET nama='$nama', password='$password', role='$role' WHERE username='$username'";

        if (mysqli_query($koneksi, $sql)) {
            echo "Data berhasil diperbarui.";
            header('Location: ../../index.php'); // Ganti dengan path halaman yang sesuai
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
} else {
    // Menampilkan form jika tidak ada POST
    if (isset($_GET['id'])) {
        $username = mysqli_real_escape_string($koneksi, $_GET['id']);
        $sql = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        $data = mysqli_fetch_array($sql);
        if (!$data) {
            die("Data tidak ditemukan.");
        }
    } else {
        die("ID tidak ditemukan.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        .card-home {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card-home form {
            display: flex;
            flex-direction: column;
        }

        .card-home label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-home input[type="text"],
        .card-home input[type="password"],
        .card-home select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button {
            display: inline-block;
            /* Menyusun tombol secara inline-block untuk mendukung text-align center */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #DB0404;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
            /* Mengatur teks agar berada di tengah */
            line-height: 1.5;
            /* Mengatur tinggi garis untuk memastikan tampilan vertikal yang baik */
        }

        .button:hover {
            background-color: #FF3030;
        }

        .card-home button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card-home button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Edit User</h2>
    <div class="card-home">
        <form action="editAkses.php?id=<?php echo urlencode($data['username']); ?>" method="post">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($data['password'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php if ($data['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="user" <?php if ($data['role'] == 'user') echo 'selected'; ?>>User</option>
            </select><br><br>
            <button type="submit">Update</button><br>
            <a href="../../index.php" type="button" class="button">Cancel</a>
        </form>
    </div>
</body>

</html>