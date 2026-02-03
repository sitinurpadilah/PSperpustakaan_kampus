<?php
// Memulai session
session_start();

// Menghapus semua data session yang ada
session_destroy();

// Mengarahkan kembali ke halaman login dengan pesan sukses
header("location:login.php?pesan=logout");
exit();
?>