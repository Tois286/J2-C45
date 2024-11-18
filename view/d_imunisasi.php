<div>
    <h1>Data Imunisasi Balita</h1>
    <div class="card-home">
        <!-- Form Pencarian -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Cari..." required>
            <input type="hidden" name="nik_balita" value="<?php echo htmlspecialchars($nik_balita); ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
        <table class="table-container" id="table-content">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Imunisasi</th>
                    <th>NIK Balita</th>
                    <th>Nama Balita</th>
                    <th>Jenis Imunisasi</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <?php if ($role == 'admin') : ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <?php
            include 'config/koneksi.php';
            $sql = mysqli_query($koneksi, "SELECT * FROM imunisasi");
            $no = 0;
            while ($data = mysqli_fetch_array($sql)) {
                $no++;
            ?>
                <tbody>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["id_imunisasi"];   ?></td>
                        <td><?php echo $data["nik_balita"];   ?></td>
                        <td><?php echo $data["nama"];   ?></td>
                        <td><?php echo $data["jenis_imunisasi"];   ?></td>
                        <td><?php echo $data["tanggal_imunisasi"];   ?></td>
                        <td><?php echo $data["status"];   ?></td>
                        <td><?php echo $data["keterangan"];   ?></td>
                        <?php if ($role == 'admin'): ?>
                            <td>
                                <a href="modul/database/editTimbang.php?id=<?php echo $data['nik_balita']; ?>" type="button" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            <?php
            }
            ?>
        </table>
    </div>
</div>


<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Your custom script -->
<script src="src/js/script.js"></script>