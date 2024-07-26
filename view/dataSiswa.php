<?php if ($role == 'admin') : ?>
    <div>
        <h1>Data Pengguna</h1>
        <div class="card-home">
            <a href="modul/tambahUser.php" class="button-mining">Tambah</a>
            <table class="table-container" id="table-content">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>username</th>
                        <th>password</th>
                        <th>role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                include 'config/koneksi.php';
                $sql = mysqli_query($koneksi, "SELECT * FROM users");
                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data["nama"];   ?></td>
                            <td><?php echo $data["username"];   ?></td>
                            <td><?php echo $data["password"];   ?></td>
                            <td><?php echo $data["role"];   ?></td>
                            <td>
                                <a href="modul/database/hapusAkses.php?id=<?php echo $data['username']; ?>" type="button" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a> |
                                <a href="modul/database/editAkses.php?id=<?php echo $data['username']; ?>" type="button">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php endif; ?>


<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Your custom script -->
<script src="src/js/script.js"></script>