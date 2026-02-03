<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'admin'){ exit(); }

// Menghapus hanya data peminjaman yang statusnya sudah 'kembali'
// Data yang masih 'dipinjam' tidak akan dihapus agar tidak error
$query = "DELETE FROM peminjaman WHERE status = 'kembali'";

if(mysqli_query($conn, $query)) {
    header("location:laporan.php?pesan=reset_sukses");
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>