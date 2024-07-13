<?php
session_start();

// Jika sudah login, redirect ke halaman index
if (isset($_SESSION['user_id'])) {
  header("Location: ../../index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Koneksi ke database
  $conn = new mysqli('localhost', 'root', '', 'dbmining');

  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  // Escape string untuk keamanan
  $username = $conn->real_escape_string($username);
  $password = $conn->real_escape_string($password);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['user_id'];
    header("Location: ../../index.php");
    exit();
  } else {
    $error = "Username atau password salah.";
  }

  $conn->close();
}
