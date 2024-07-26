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
    event.preventDefault(); // Mencegah refresh halaman
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

function view(id) {
    // Sembunyikan semua elemen dengan class 'content'
    var contents = document.querySelectorAll('.view');
    contents.forEach(function(content) {
        content.style.display = 'none';
    });

    // Tampilkan elemen dengan ID yang sesuai
    var element = document.getElementById(id);
    if (element) {
        element.style.display = 'block';
    }
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

function chooseTable(tableName) {
    // Store tableName in localStorage
    localStorage.setItem("chooseTableTrainingProcess", tableName);

    // Update URL parameter without reloading the page
    let currentUrl = new URL(window.location.href);
    let params = new URLSearchParams(currentUrl.search);
    params.set('table', tableName);
    currentUrl.search = params.toString();
    window.history.replaceState({}, '', currentUrl);

    // Check if redirect is needed
    if (tableName !== 'index.php') {
        alert('Lanjut tampilkan ' + tableName + ' untuk di kelola.');
        setTimeout(function() {
            window.location.href = "index.php?table=" + encodeURIComponent(tableName);
        }, 1); // Adjust timeout if needed
    }

    // Load table content via AJAX
    loadTableDataView(tableName);
}

function loadTableDataView(tableName) {
    $.ajax({
        url: 'view/load_table.php', // Adjust URL as necessary
        type: 'GET',
        data: { table: tableName },
        dataType: 'json',
        success: function(data) {
            console.log('Server Response:', data);

            if (data.error) {
                $('#table-content-container').html('<p>Error: ' + data.error + '</p>');
                return;
            }

            let tableHtml = '<table id="table-content">';
            tableHtml += '<a href="c45/mining.php?table=' + encodeURIComponent(tableName) + '" class="button-mining">Mining</a>';
            tableHtml += '<a href="c45/deleteUpload.php?table=' + encodeURIComponent(tableName) + '" class="button-mining">Delete</a>';
            
            if (data.fields && data.fields.length > 0) {
                // Create table header
                tableHtml += '<tr>';
                data.fields.forEach(function(field) {
                    if (field !== 'PREDIKSI') {
                        tableHtml += '<th>' + field + '</th>';
                    }
                });
                tableHtml += '</tr>';

                // Display 70% of rows
                const totalRows = data.rows.length;
                const rowsToShow = Math.ceil(totalRows * 0.7);

                for (let i = 0; i < rowsToShow; i++) {
                    tableHtml += '<tr>';
                    data.fields.forEach(function(field) {
                        if (field !== 'PREDIKSI') {
                            tableHtml += '<td>' + data.rows[i][field] + '</td>';
                        }
                    });
                    tableHtml += '</tr>';
                }
            } else {
                tableHtml += '<tr><td colspan="' + (data.fields ? data.fields.length : 2) + '">No data found</td></tr>';
            }
            tableHtml += '</table>';

            $('#table-content-container').html(tableHtml);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error loading data:', textStatus, errorThrown);
            $('#table-content-container').html('<p>Error loading data: ' + textStatus + ' - ' + errorThrown + '</p>');
        }
    });
}
$(document).ready(function() {
    // Check URL for 'table' parameter
    let urlParams = new URLSearchParams(window.location.search);
    let tableName = urlParams.get('table');

    // If tableName is not found in URL, check localStorage
    if (!tableName) {
        tableName = localStorage.getItem("chooseTableTrainingProcess");
    }

    // If tableName is found, load the table data
    if (tableName) {
        loadTableDataView(tableName);
    } else {
        $('#table-content-container').html('<p>Please select a table to display.</p>');
    }
});


 document.addEventListener("DOMContentLoaded", function() {
    
            // Dapatkan URL saat ini
            let currentUrl = window.location.href;

            // Cari posisi dari tanda '?' (jika ada)
            let queryPosition = currentUrl.indexOf('?');

            // Ambil bagian URL sebelum '?' untuk mendapatkan base URL
            let baseUrl = (queryPosition !== -1) ? currentUrl.substring(0, queryPosition) : currentUrl;

            // Ubah URL tanpa query parameters menggunakan history.replaceState
            history.replaceState(null, '', baseUrl);
        });

 $(document).ready(function() {
       
        $('#search-form').on('submit', function(event) {
            event.preventDefault(); // Mencegah refresh halaman

            $.ajax({
                url: 'modul/database/search.php', // Ganti dengan URL untuk memproses pencarian
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#result-container').html(response);
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan jika terjadi
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        });
    });