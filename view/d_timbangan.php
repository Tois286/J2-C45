    <div>
        <h1>Data Penimbangan</h1>
        <div class="card-home">
            <!-- <a href="modul/timbang.php" class="button button1">Timbang</a> -->
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
                        <th>NIK Balita</th>
                        <th>Nama Balita</th>
                        <th>Berat</th>
                        <th>Tinggi Badan</th>
                        <th>Tanggal Penimbangan</th>
                        <?php if ($role == 'admin') : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <?php
                include 'config/koneksi.php';
                $sql = mysqli_query($koneksi, "SELECT * FROM timbangan");
                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                ?>
                    <tbody>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data["nik_balita"];   ?></td>
                            <td><?php echo $data["nama_balita"];   ?></td>
                            <td><?php echo $data["berat"];   ?></td>
                            <td><?php echo $data["tinggi_badan"];   ?></td>
                            <td><?php echo $data["tanggal"];   ?></td>
                            <?php if ($role == 'admin'): ?>
                                <td>
                                    <a href="modul/imunisasi.php?id=<?php echo $data['nik_balita']; ?>" type="button" class="btn btn-success custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-thermometer-half"></i>Imunisasi</a>
                                    <a href="modul/editTimbang.php?id=<?php echo $data['nik_balita']; ?>" type="button" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
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