<style>
    .custom-btn {
        display: flex;
        padding: 5px 10px;
        margin: 5px;
        /* Atur padding sesuai kebutuhan */
        font-size: 12px;
        /* Atur ukuran font */
        border-radius: 5px;
        /* Sudut membulat */
    }

    .btn-content {
        display: block;
        padding: 5px 10px;
        margin-bottom: 5px;
        /* Atur padding sesuai kebutuhan */
        font-size: 12px;
        /* Atur ukuran font */
        border-radius: 5px;
        background-color: #219ebc;
        /* Ganti dengan warna yang diinginkan */
        color: white;
        /* Warna teks */
        border: none;
        /* Menghilangkan border default */
    }

    .btn-content:hover {
        background-color: white;
        border: 1px solid #219ebc;
        /* Warna saat hover */
        color: #219ebc;
        /* Warna teks saat hover */
    }
</style>
<?php if ($role == 'admin') : ?>
    <div>
        <h1>Data User</h1>
        <div class="card-home">
            <a href="modul/tambah_Ortu.php" class="btn btn-content">Tambah</a>
            <table class="table-container" id="table-content">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Orang Tua</th>
                        <th>Nama Ayah</th>
                        <th>NIK Ayah</th>
                        <th>Nama Ibu</th>
                        <th>NIK Ibu</th>
                        <th>Alamat</th>
                        <th>Telphon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                include 'config/koneksi.php';
                $sql = mysqli_query($koneksi, "SELECT * FROM dataorangtua");
                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data["id_orangtua"];   ?></td>
                            <td><?php echo $data["nama_ayah"];   ?></td>
                            <td><?php echo $data["nik_ayah"];   ?></td>
                            <td><?php echo $data["nama_ibu"];   ?></td>
                            <td><?php echo $data["nik_ibu"];   ?></td>
                            <td><?php echo $data["alamat"];   ?></td>
                            <td><?php echo $data["telepon"];   ?></td>
                            <td>
                                <a href="modul/edit_Ortu.php?id=<?php echo $data['id_orangtua']; ?>" type="button" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
                                <a href="modul/database/delete.php?id=<?php echo $data['id_orangtua']; ?>" type="button" class="btn btn-danger custom-btn" style="text-decoration: none;color:white;" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="bi bi-trash"></i>Hapus</a>
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