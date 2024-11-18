<div>
    <h1>Pelaporan</h1>
    <div class="card-home">
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Cari..." required>
            <input type="hidden" name="nik_balita" value="<?php echo htmlspecialchars($nik_balita); ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
        <table class="table-container" id="table-content">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Orang Tua</th>
                    <th>NIK Balita</th>
                    <th>Nama Balita</th>
                    <th>Nama Orang Tua</th>
                    <th>Alamat</th>
                    <th>No.Hp</th>
                    <th>Tanggal Mendaftar</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Jenis Imunisasi</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <!-- <?php if ($role == 'admin') : ?>
                        <th>Action</th>
                    <?php endif; ?> -->
                </tr>
            </thead>
            <?php
            include 'config/koneksi.php';

            // Menyimpan data ke tabel laporan jika ada data baru
            if (isset($_POST['submit'])) {
                // Misalkan data dari form diambil
                $id_ortu = $_POST['id_orangtua'];
                $nik_balita = $_POST['nik_balita'];
                $nama_balita = $_POST['nama_balita'];
                $nama_ortu = $_POST['nama_ortu'];
                $alamat = $_POST['alamat'];
                $telepon = $_POST['telepon'];
                $tgl_daftar = $_POST['tgl_daftar'];
                $tgl_imunisasi = $_POST['tgl_imunisasi'];
                $jenis_imunisasi = $_POST['jenis_imunisasi'];
                $dokter = $_POST['dokter'];
                $status = $_POST['status'];
                $keterangan = $_POST['keterangan'];

                // Menyimpan data ke tabel laporan
                $insertLaporan = $koneksi->prepare("INSERT INTO laporan (id_orangtua, nik_balita, nama_balita, nama_ortu, alamat, telepon, tgl_daftar, tgl_imunisasi, jenis_imunisasi, dokter, status, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertLaporan->bind_param("ssssssssssss", $id_ortu, $nik_balita, $nama_balita, $nama_ortu, $alamat, $telepon, $tgl_daftar, $tgl_imunisasi, $jenis_imunisasi, $dokter, $status, $keterangan);
                $insertLaporan->execute();
                $insertLaporan->close();
            }

            // Mengambil data dari tabel laporan
            $sql = mysqli_query($koneksi, "SELECT * FROM laporan ORDER BY tgl_daftar DESC");

            $no = 0;
            while ($data = mysqli_fetch_array($sql)) {
                $no++;
            ?>
                <tbody>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["id_orangtua"]; ?></td>
                        <td><?php echo $data["nik_balita"]; ?></td>
                        <td><?php echo $data["nama_balita"]; ?></td>
                        <td><?php echo $data["nama_ortu"]; ?></td>
                        <td><?php echo $data["alamat"]; ?></td>
                        <td><?php echo $data["telepon"]; ?></td>
                        <td><?php echo $data["tgl_daftar"]; ?></td>
                        <td><?php echo $data["tgl_imunisasi"]; ?></td>
                        <td><?php echo $data["jenis_imunisasi"]; ?></td>
                        <td><?php echo $data["dokter"]; ?></td>
                        <td><?php echo $data["status"]; ?></td>
                        <td><?php echo $data["keterangan"]; ?></td>
                        <!-- <?php if ($role == 'admin'): ?>
                            <td>
                                <a href="modul/editTimbang.php?id=<?php echo $data['nik_balita']; ?>" type="button" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
                            </td>
                        <?php endif; ?> -->
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