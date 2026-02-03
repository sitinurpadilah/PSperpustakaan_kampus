<?php
include '../config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$nama = $_POST['nama_lengkap'];
$nip = $_POST['nim_nip'];
$role = 'staff';

$query = "INSERT INTO users (username, password, nama_lengkap, nim_nip, role) VALUES ('$username', '$password', '$nama', '$nip', '$role')";

if(mysqli_query($conn, $query)){
    // --- START: TAMBAHAN KODE NOTIFIKASI ---
    // Mengambil ID user yang baru saja masuk ke database
    $new_user_id = mysqli_insert_id($conn); 
    
    // Pesan sambutan untuk staff baru
    $pesan = "Selamat datang $nama! Akun Staff Anda telah aktif. Silakan lengkapi profil Anda.";
    
    // Simpan ke tabel notifikasi
    mysqli_query($conn, "INSERT INTO notifikasi (user_id, pesan) VALUES ('$new_user_id', '$pesan')");
    // --- END: TAMBAHAN KODE NOTIFIKASI ---

    header("location:index.php?status=success");
} else {
    echo "Gagal menambah staff: " . mysqli_error($conn);
}
?>