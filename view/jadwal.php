<style>
    .custom-btn {
        display: flex;
        padding: 5px 10px;
        margin: 5px;
        font-size: 12px;
        border-radius: 5px;
    }

    .btn-content {
        display: block;
        padding: 5px 10px;
        margin-bottom: 5px;
        font-size: 12px;
        border-radius: 5px;
        background-color: #219ebc;
        color: white;
        border: none;
    }

    .btn-content:hover {
        background-color: white;
        border: 1px solid #219ebc;
        color: #219ebc;
    }
</style>
<div>
    <h1>Jadwal</h1>
    <div class="card-home">
        <?php if ($role == 'superUser') : ?>
            <a href="modul/jadwal.php" class="btn btn-content">Atur Jadwal</a>
        <?php endif; ?>

        <table class="table-container" id="table-content">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Tempat</th>
                    <th>Tanggal</th>
                    <th>Vaksin</th>
                    <th>Penyelengara</th>
                    <th>Dokter</th>
                    <th>Sumber Daya</th>
                    <th>Tujuan</th>
                    <?php if ($role == 'superUser' || $role == 'admin'): ?>
                        <th>Noted</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config/koneksi.php';
                $sql = mysqli_query($koneksi, "SELECT * FROM jadwal ORDER BY tanggal DESC");
                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["nama_kegiatan"]; ?></td>
                        <td><?php echo $data["tempat"]; ?></td>
                        <td><?php echo $data["tanggal"]; ?></td>
                        <td><?php echo $data["peserta"]; ?></td>
                        <td><?php echo $data["penyelengara"]; ?></td>
                        <td><?php echo $data["dokter"]; ?></td>
                        <td><?php echo $data["sumber_daya"]; ?></td>
                        <td><?php echo $data["keterangan"]; ?></td>

                        <?php if ($role == 'superUser' || $role == 'admin'): ?>
                            <td><?php echo $data["noted"]; ?></td>
                        <?php endif; ?>
                        <td>
                            <a href="modul/tambah_Ortu.php" class="btn btn-primary custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-clipboard-plus"></i>Daftar</a>
                            <?php if ($role == 'superUser'): ?>
                                <a href="modul/editJadwal.php?id=<?php echo $data['id']; ?>" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
                                <a href="modul/database/hapusJadwal.php?id=<?php echo $data['id']; ?>" class="btn btn-danger custom-btn" style="text-decoration: none;color:white;" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="bi bi-trash"></i>Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Your custom script -->
<script src="src/js/script.js"></script>