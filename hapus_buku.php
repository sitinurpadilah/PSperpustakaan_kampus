<?php
session_start();
include '../config.php';

// Proteksi agar hanya staff yang bisa menghapus
if($_SESSION['role'] != 'staff'){ header("location:../login.php"); exit(); }

$id = $_GET['id'];

// 1. Ambil nama file gambar agar bisa dihapus dari folder fisik
$data = mysqli_query($conn, "SELECT gambar_sampul FROM buku WHERE id='$id'");
$buku = mysqli_fetch_assoc($data);
$foto_lama = $buku['gambar_sampul'];

// Hapus file gambar jika ada di folder assets/sampul
if ($foto_lama != "" && file_exists("../assets/sampul/" . $foto_lama)) {
    unlink("../assets/sampul/" . $foto_lama);
}

// 2. HAPUS RIWAYAT PEMINJAMAN TERLEBIH DAHULU
// Ini dilakukan agar tidak terjadi error "Foreign Key Constraint"
mysqli_query($conn, "DELETE FROM peminjaman WHERE buku_id='$id'");

// 3. BARU HAPUS DATA BUKU DARI DATABASE
$delete = mysqli_query($conn, "DELETE FROM buku WHERE id='$id'");

if($delete){
    // Kembali ke halaman daftar buku dengan pesan sukses
    header("location:index.php?pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus: " . mysqli_error($conn);
}
?>