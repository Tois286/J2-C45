// // scripts.js

document.addEventListener('DOMContentLoaded', function () {
    var dataTraining = document.getElementById('data-training');
    var submenuTraining = document.getElementById('submenu-training');

    // Tambahkan event listener untuk menampilkan submenu saat klik
    dataTraining.addEventListener('click', function (event) {
        event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
        if (submenuTraining.style.display === 'none' || submenuTraining.style.display === '') {
            submenuTraining.style.display = 'block';
        } else {
            submenuTraining.style.display = '';
        }
    });

    var dataTesting = document.getElementById('data-testing');
    var submenuTesting = document.getElementById('submenu-testing');

    // Tambahkan event listener untuk menampilkan submenu saat klik
    dataTesting.addEventListener('click', function (event) {
        event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
        if (submenuTesting.style.display === 'none' || submenuTesting.style.display === '') {
            submenuTesting.style.display = 'block';
        } else {
            submenuTesting.style.display = 'block';
        }
    });

    // Menutup submenu saat klik di luar elemen dropdown
    document.addEventListener('click', function () {
        submenuTraining.style.display = 'block';
        submenuTesting.style.display = 'block';
    });
});

function showContent(id) {
    // Hide all content
    const contents = document.querySelectorAll('.content');
    contents.forEach(content => {
        content.style.display = 'none';
    });

    // Show selected content
    const selectedContent = document.getElementById(id);
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
}

// // Show home content by default
document.addEventListener('DOMContentLoaded', () => {
    showContent('home');
});

function loadTable(tableName) {
    var tableContainer = document.getElementById("table-content");
    tableContainer.innerHTML = '';

    var xhttp = new XMLHttpRequest();

    xhttp.open("GET", "?table=" + tableName, true);
    xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            var tableHtml = xhttp.responseText;
            var tableElement = document.createElement("table");
            tableElement.innerHTML = tableHtml;
            tableContainer.appendChild(tableElement);
        }
    };

    xhttp.send();
}

function saveToLocalStorage(key, value) {
    if (localStorage.getItem(key) === null) {
        // Jika kunci belum ada, tambahkan nilai baru
        localStorage.setItem(key, value);
    } else {
        // Jika kunci sudah ada, update nilai
        localStorage.setItem(key, value);
    }
}

function chooseTable(tableName) {
    // Menyimpan tableName ke localStorage
    localStorage.setItem("chooseTableTrainingProcess", tableName);

    // Mengecek apakah sudah ada parameter di URL
    let currentUrl = new URL(window.location.href);
    let params = new URLSearchParams(currentUrl.search);

    // Menambahkan atau mengganti nilai parameter 'table'
    params.set('table', tableName);

    // Memperbarui URL tanpa reload halaman
    currentUrl.search = params.toString();
    window.history.replaceState({}, '', currentUrl);

    $.ajax({
        url: 'view/load_table.php', // Sesuaikan dengan file yang sesuai di proyek Anda
        type: 'GET',
        data: { table: tableName },
        dataType: 'json',
        success: function (data) {
            console.log('Server Response:', data); // Log server response

            if (data.error) {
                $('#table-content-container').html('<p>Error: ' + data.error + '</p>');
                return;
            }

            var tableHtml = '<table id="table-content">';
            // tableHtml += '<a href="c45/Prediksi.php?table=' + encodeURIComponent(tableName) + '" class="button-mining">Prediksi</a>';
            tableHtml += '<a href="c45/mining.php?table=' + encodeURIComponent(tableName) + '" class="button-mining">Mining</a>';
            // tableHtml += '<a href="modul/database/akses.php?table=' + encodeURIComponent(tableName) + '" class="button-mining">Beri Akses</a>';
            console.log(tableName);
            if (data.fields.length > 0) {
                // Create table header
                tableHtml += '<tr>';
                data.fields.forEach(function (field) {
                    if (field != 'PREDIKSI') {
                        tableHtml += '<th>' + field + '</th>';
                    }
                });

                // Create table rows
                data.rows.forEach(function (row) {
                    tableHtml += '<tr>';
                    data.fields.forEach(function (field) {
                        if (field != 'PREDIKSI') {
                            tableHtml += '<td>' + row[field] + '</td>';
                        }
                    });

                });
            } else {
                tableHtml += '<tr><td colspan="' + (data.fields.length + 1) + '">No data found</td></tr>';
            }
            tableHtml += '</table>';

            $('#table-content-container').html(tableHtml);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error loading data:', textStatus, errorThrown); // Log the error details
            $('#table-content-container').html('<p>Error loading data: ' + textStatus + ' - ' + errorThrown + '</p>');
        }
    });
}


function startLoading(event) {
    event.preventDefault(); // Mencegah pengalihan default
    // Tampilkan konfirmasi kepada pengguna
    var proceed = confirm("Apakah Anda ingin melanjutkan proses mining?");
    if (proceed) {
        var button = event.target;
        // Ubah teks tombol menjadi "Loading..."
        button.innerHTML = "Loading...";
        // Simulasikan proses mining
        setTimeout(function () {
            // Setelah selesai, arahkan halaman ke pk.php
            window.location.href = button.href;
        }, 2000); // Contoh waktu tunggu 2 detik (2000 milidetik)
        window.location.href = "home";
    } else {
        alert("Proses mining dibatalkan.");
    }
}
