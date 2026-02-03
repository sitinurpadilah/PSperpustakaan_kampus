<?php
include '../config.php';

// Pastikan semua data ditangkap dari form
$judul      = $_POST['judul'];
$isbn       = $_POST['isbn'];
$pengarang  = $_POST['pengarang'];
$id_kategori= $_POST['id_kategori'];
$stok       = $_POST['stok'];
$lokasi_rak = $_POST['lokasi_rak'];

// Urusan Gambar
$nama_file = $_FILES['sampul']['name'];
$tmp_file  = $_FILES['sampul']['tmp_name'];
$path      = "../assets/sampul/" . $nama_file;

if (move_uploaded_file($tmp_file, $path)) {
    // Jalankan Query Simpan
    $query = "INSERT INTO buku (judul, isbn, pengarang, id_kategori, stok, lokasi_rak, gambar_sampul) 
              VALUES ('$judul', '$isbn', '$pengarang', '$id_kategori', '$stok', '$lokasi_rak', '$nama_file')";
    
    if (mysqli_query($conn, $query)) {
        header("location:index.php?pesan=berhasil");
    } else {
        echo "Gagal ke database: " . mysqli_error($conn);
    }
} else {
    echo "Gagal Upload. Pastikan folder 'assets/sampul' ada.";
}
?>