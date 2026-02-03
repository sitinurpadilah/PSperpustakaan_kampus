<?php
session_start();
include '../config.php';

// Cek apakah session ID ada, jika tidak ada suruh login ulang
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Sesi habis, silakan login kembali'); window.location='../login.php';</script>";
    exit();
}

$user_id = $_SESSION['id'];
$buku_id = $_POST['buku_id'];
$tgl_pinjam = date('Y-m-d');
$tgl_deadline = date('Y-m-d', strtotime('+7 days'));

// 1. Cek stok terakhir
$cek_stok = mysqli_query($conn, "SELECT judul, stok FROM buku WHERE id = '$buku_id'");
$s = mysqli_fetch_assoc($cek_stok);

if($s && $s['stok'] > 0) {
    // 2. Insert ke tabel peminjaman
    $query = "INSERT INTO peminjaman (user_id, buku_id, tgl_pinjam, tgl_deadline, status) 
              VALUES ('$user_id', '$buku_id', '$tgl_pinjam', '$tgl_deadline', 'dipinjam')";
    
    if(mysqli_query($conn, $query)) {
        // 3. Kurangi stok buku
        mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id = '$buku_id'");

        // --- SISIPAN KODE NOTIFIKASI (VERSI PALING AMAN) ---
        $judul_asli = $s['judul'];
        $format_tgl = date('d-m-Y', strtotime($tgl_deadline));
        
        // Gabungkan pesan dulu, baru di-escape total agar karakter simbol tidak error
        $isi_pesan = "Berhasil meminjam buku '$judul_asli'. Batas kembali: $format_tgl";
        $pesan_final = mysqli_real_escape_string($conn, $isi_pesan);
        
        mysqli_query($conn, "INSERT INTO notifikasi (user_id, pesan) VALUES ('$user_id', '$pesan_final')");
        // --- SELESAI ---

        echo "<script>alert('Berhasil meminjam! Cek menu Pinjaman Saya.'); window.location='pinjaman_saya.php';</script>";
    } else {
        echo "Error Database Peminjaman: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Maaf, stok sudah habis!'); window.location='index.php';</script>";
}
?>