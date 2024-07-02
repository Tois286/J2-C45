// // scripts.js

// document.addEventListener('DOMContentLoaded', function() {
//     var dataTraining = document.getElementById('data-training');
//     var submenuTraining = document.getElementById('submenu-training');
    

//     // Tambahkan event listener untuk menampilkan submenu saat klik
//     dataTraining.addEventListener('click', function(event) {
        
//         event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
//         if (submenuTraining.style.display === 'none' || submenuTraining.style.display === 'block') {
//             submenuTraining.style.display = 'block';
//         } else {
//             submenuTraining.style.display = 'none';
//         }
//     });

//     var dataTesting = document.getElementById('data-testing');
//     var submenuTesting = document.getElementById('submenu-testing');

//     // Tambahkan event listener untuk menampilkan submenu saat klik
//     dataTesting.addEventListener('click', function(event) {
//         event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
//         if (submenuTesting.style.display === 'none' || submenuTesting.style.display === 'block') {
//             submenuTesting.style.display = 'block';
//         } else {
//             submenuTesting.style.display = 'block';
//         }
//     });

//     // Menutup submenu saat klik di luar elemen dropdown
//     document.addEventListener('click', function() {
//         submenuTraining.style.display = 'none';
//         submenuTesting.style.display = 'none';
//     });
// });

// function showContent(id) {
//     // Hide all content
//     const contents = document.querySelectorAll('.content');
//     contents.forEach(content => {
//         content.style.display = 'none';
//     });

//     // Show selected content
//     const selectedContent = document.getElementById(id);
//     if (selectedContent) {
//         selectedContent.style.display = 'block';
//     }
// }

// // Show home content by default
// document.addEventListener('DOMContentLoaded', () => {
//     showContent('home');
// });

// function loadTable(tableName) {
//         document.getElementById("table-content").innerHTML = '';

//         var xhttp = new XMLHttpRequest();
//         xhttp.onreadystatechange = function() {
//             if (this.readyState == 4 && this.status == 200) {
//                 document.getElementById("table-content").innerHTML = this.responseText;
//             }
//         };
//         xhttp.open("GET", "?table=" + tableName, true);
//         xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
//         xhttp.send();
//     }

document.addEventListener('DOMContentLoaded', function() {
    var dataTraining = document.getElementById('data-training');
    var submenuTraining = document.getElementById('submenu-training');

    // Tambahkan event listener untuk menampilkan submenu saat klik
    dataTraining.addEventListener('click', function(event) {
        event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
        if (submenuTraining.style.display === 'none' || submenuTraining.style.display === '') {
            submenuTraining.style.display = 'block';
        } else {
            submenuTraining.style.display = 'none';
        }
    });

    var dataTesting = document.getElementById('data-testing');
    var submenuTesting = document.getElementById('submenu-testing');

    // Tambahkan event listener untuk menampilkan submenu saat klik
    dataTesting.addEventListener('click', function(event) {
        event.stopPropagation(); // Mencegah event click menyebar ke elemen lain
        if (submenuTesting.style.display === 'none' || submenuTesting.style.display === '') {
            submenuTesting.style.display = 'block';
        } else {
            submenuTesting.style.display = 'none';
        }
    });

    // Menutup submenu saat klik di luar elemen dropdown
    document.addEventListener('click', function() {
        submenuTraining.style.display = 'none';
        submenuTesting.style.display = 'none';
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

// Show home content by default
document.addEventListener('DOMContentLoaded', () => {
    showContent('home');
});

function loadTable(tableName) {
    document.getElementById("table-content").innerHTML = '';

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "?table=" + tableName, true);
    xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, must-revalidate");
    xhttp.send();
}
