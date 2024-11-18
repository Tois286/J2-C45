    <div>
        <h1>Cetak Hasil</h1>
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
                        <th>NIK Balita</th>
                        <th>Nama Balita</th>
                        <th>Jenis Kelamin</th>
                        <th>Nama Orang Tua</th>
                        <th>Tanggal Imunisasi</th>
                        <th>Jenis Imunisasi</th>
                        <?php if ($role == 'admin' || $role == 'user') : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config/koneksi.php';

                    // Menggunakan JOIN untuk mengambil data dari tiga tabel
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
                                imunisasi ON balita.nik_balita = imunisasi.nik_balita";

                    $result = mysqli_query($koneksi, $sql);

                    if (!$result) {
                        echo "<tr><td colspan='8'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                        exit;
                    }

                    $no = 0;
                    while ($data = mysqli_fetch_assoc($result)) {
                        $no++;
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo htmlspecialchars($data["nik_balita"]); ?></td>
                            <td><?php echo htmlspecialchars($data["nama_balita"]); ?></td>
                            <td><?php echo htmlspecialchars($data["jenis_kelamin"]); ?></td>
                            <td><?php echo htmlspecialchars($data["nama_ayah"] . ' & ' . $data["nama_ibu"]); ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($data["tanggal_imunisasi"]))); ?></td>
                            <td><?php echo htmlspecialchars($data["jenis_imunisasi"]); ?></td>
                            <?php if ($role == 'admin' || $role == 'user'): ?>
                                <td>
                                    <a href="modul/cetakPros.php?nik=<?php echo $data['nik_balita']; ?>" class="btn btn-warning custom-btn" style="text-decoration: none;color:white;"><i class="bi bi-printer"></i>Cetak</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php
                    }

                    // Tutup koneksi jika sudah tidak digunakan
                    mysqli_close($koneksi);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Your custom script -->
    <script src="src/js/script.js"></script>