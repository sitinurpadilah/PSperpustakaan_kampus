<?php
include '../config.php';

$user_id = $_POST['user_id'];
$buku_id = $_POST['buku_id'];
$durasi  = $_POST['durasi'];

$tgl_pinjam   = date('Y-m-d');
$tgl_deadline = date('Y-m-d', strtotime("+$durasi days"));

// 1. Masukkan data ke tabel peminjaman
$query_pinjam = "INSERT INTO peminjaman (user_id, buku_id, tgl_pinjam, tgl_deadline, status) 
                 VALUES ('$user_id', '$buku_id', '$tgl_pinjam', '$tgl_deadline', 'dipinjam')";

if(mysqli_query($conn, $query_pinjam)){
    // 2. KURANGI STOK BUKU
    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id = '$buku_id'");
    header("location:transaksi.php?pesan=berhasil");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>