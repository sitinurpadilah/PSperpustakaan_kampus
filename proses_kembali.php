<?php
include '../config.php';
$id_pinjam = $_GET['id'];
$buku_id = $_GET['buku'];
$tgl_kembali = date('Y-m-d');

// Ambil data pinjam untuk hitung denda
$query = mysqli_query($conn, "SELECT tgl_deadline FROM peminjaman WHERE id_pinjam = '$id_pinjam'");
$data = mysqli_fetch_assoc($query);

$deadline = new DateTime($data['tgl_deadline']);
$kembali = new DateTime($tgl_kembali);
$denda = 0;

if($kembali > $deadline) {
    $selisih = $kembali->diff($deadline)->days;
    $denda = $selisih * 1000; // Misal 1.000 per hari
}

// 1. Update status pinjam, tgl kembali, dan denda
mysqli_query($conn, "UPDATE peminjaman SET status='kembali', tgl_kembali='$tgl_kembali', denda='$denda' WHERE id_pinjam='$id_pinjam'");

// 2. Kembalikan stok buku
mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = '$buku_id'");

header("location:transaksi.php?pesan=kembali_sukses");
?>