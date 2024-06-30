<div>
    <h1>Kelola Data Siswa</h1>
    <div class="card-home">
        <div class="upload">
            <form action="modul/database/uploadPros.php" method="post" enctype="multipart/form-data">
                <input type="file" name="excelFile" accept=".xlsx, .xls">
                <button type="submit" class="button button1">Upload</button>
            </form>
        </div>
    </div>
    <div class="card-home">
        <div class="table-container">

        </div>
    </div>
</div>
<div>
    <?php include 'modul/footer.php'; ?>
</div>