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
      echo "<script>alert('username atau password salah!!');</script>";
      echo "<script>window.location.href='../../login.php';</script>";
    }
  } else {
    echo "<script>alert('username tidak ditemukan!!');</script>";
    echo "<script>window.location.href='../../login.php';</script>";
  }
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Daftar') {
  // Logika untuk pendaftaran (jika ada)
}
