<?php
session_start();
include '../../config/koneksi.php'; // Sesuaikan path dengan lokasi file config.php

if (isset($_POST['submit']) && $_POST['submit'] == 'Masuk') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Cek username dan password dari database
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->execute(['username' => $username]);
  $user = $stmt->fetch();

  if ($user) {
    // Verifikasi password
    if ($password === $user['password']) { // Perhatikan, ini hanya contoh sederhana
      // Simpan data user di session
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['user_role'] = $user['role'];

      // Redirect ke halaman index
      header("Location: ../../index.php");
      exit();
    } else {
      // Password salah
      echo "Password salah!";
    }
  } else {
    // Username tidak ditemukan
    echo "Username tidak ditemukan!";
  }
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Daftar') {
  // Logika untuk pendaftaran (jika ada)
}
