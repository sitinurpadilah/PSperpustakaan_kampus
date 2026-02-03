<?php
include 'config.php';

$nim      = $_POST['nim_nip'];
$nama     = $_POST['nama_lengkap'];
$username = $_POST['username'];
$password = $_POST['password'];
$role     = $_POST['role'];

// Cek apakah username sudah ada
$cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if(mysqli_num_rows($cek_user) > 0){
    echo "<script>alert('Username sudah digunakan! Gunakan yang lain.'); window.location='register.php';</script>";
} else {
    // Simpan ke database
    $query = "INSERT INTO users (username, password, nama_lengkap, nim_nip, role) 
              VALUES ('$username', '$password', '$nama', '$nim', '$role')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>