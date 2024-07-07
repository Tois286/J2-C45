<?php
$host = 'localhost';
$dbname = 'dbmining-base';
$username = 'root';
$password = '';

$koneksi = mysqli_connect($host, $username, $password, $dbname);
$koneksi1 = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($koneksi1, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Sekarang variabel $koneksi menyimpan koneksi yang sukses
