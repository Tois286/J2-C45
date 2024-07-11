<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/js/script.js"></script>
    <script>
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
    </script>
</head>