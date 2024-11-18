<div>
    <h1>Data Balita</h1>

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
                    <th>NIK Balita</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Catatan Kesehatan</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Nama Ayah/Ibu</th>
                    <?php if ($role == 'admin') : ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config/koneksi.php';

                // Menangani Pencarian
                $search_query = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
                $sql = "SELECT balita.*, dataorangtua.* FROM balita JOIN dataorangtua ON balita.id_orangtua = dataorangtua.id_orangtua";

                if (!empty($search_query)) {
                    $sql .= " WHERE balita.nama LIKE '%$search_query%' OR balita.nik_balita LIKE '%$search_query%'"; // Sesuaikan dengan kolom yang ingin dicari
                }

                $result = mysqli_query($koneksi, $sql);
                $no = 0;

                while ($data = mysqli_fetch_array($result)) {
                    $no++;
                ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data["nik_balita"]; ?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["tanggal_lahir"]; ?></td>
                        <td><?php echo $data["jenis_kelamin"]; ?></td>
                        <td><?php echo $data["catatan_kesehatan"]; ?></td>
                        <td><?php echo $data["tanggal_imunisasi"]; ?></td>
                        <td><?php echo $data["nama_ayah"] . ' / ' . $data['nama_ibu']; ?></td>
                        <?php if ($role == 'admin'): ?>
                            <td>
                                <a href="modul/timbang.php?id=<?php echo $data['nik_balita']; ?>" class="btn btn-success custom-btn" style="text-decoration: none;color:white;"><i class="fas fa-weight"></i>Timbang</a>
                                <a href="modul/editBalita.php?id=<?php echo $data['id_orangtua']; ?>" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-pencil-square"></i>Edit</a>
                            </td>
                        <?php endif; ?>
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