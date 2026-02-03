<?php
session_start();
include '../config.php';

$id_user = $_SESSION['id'];
// Mengubah status semua notifikasi user ini menjadi sudah dibaca
mysqli_query($conn, "UPDATE notifikasi SET is_read = 1 WHERE user_id = '$id_user'");

// Kembali ke halaman index
header("location:index.php");
?>