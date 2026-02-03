<?php 
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($login);

if($cek > 0){
    $data = mysqli_fetch_assoc($login);

    // Buat session
   // Simpan ke session (Gunakan nama yang seragam)
    $_SESSION['id']       = $data['id']; // Pakai 'id' saja agar simpel
    $_SESSION['username'] = $username;
    $_SESSION['nama']     = $data['nama_lengkap'];
    $_SESSION['role']     = $data['role'];
    $_SESSION['status']   = "login"; // Tambahkan penanda status

    // Redirect sesuai role
    if($data['role'] == "admin"){
        header("location:admin/index.php");
    } else if($data['role'] == "staff"){
        header("location:staff/index.php");
    } else if($data['role'] == "mahasiswa" || $data['role'] == "dosen"){
        header("location:user/index.php");
    }
} else {
    header("location:login.php?pesan=gagal");
}
?>