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
</head>

<body>
    <h2>Edit User</h2>
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
        <button type="submit">Update</button>
    </form>
</body>

</html>