<?php
include '../config.php';

$id         = $_POST['id'];
$judul      = $_POST['judul'];
$isbn       = $_POST['isbn'];
$pengarang  = $_POST['pengarang'];
$id_kategori= $_POST['id_kategori'];
$stok       = $_POST['stok'];
$lokasi_rak = $_POST['lokasi_rak'];

$nama_file = $_FILES['sampul']['name'];
$tmp_file  = $_FILES['sampul']['tmp_name'];

// Cek apakah staff memilih file gambar baru
if ($nama_file != "") {
    // Jika ganti gambar, upload file baru
    $path = "../assets/sampul/" . $nama_file;
    move_uploaded_file($tmp_file, $path);
    
    $query = "UPDATE buku SET 
                judul='$judul', 
                isbn='$isbn', 
                pengarang='$pengarang', 
                id_kategori='$id_kategori', 
                stok='$stok', 
                lokasi_rak='$lokasi_rak', 
                gambar_sampul='$nama_file' 
              WHERE id='$id'";
} else {
    // Jika tidak ganti gambar, update data selain gambar
    $query = "UPDATE buku SET 
                judul='$judul', 
                isbn='$isbn', 
                pengarang='$pengarang', 
                id_kategori='$id_kategori', 
                stok='$stok', 
                lokasi_rak='$lokasi_rak' 
              WHERE id='$id'";
}

if (mysqli_query($conn, $query)) {
    header("location:index.php?pesan=update_berhasil");
} else {
    echo "Gagal Update: " . mysqli_error($conn);
}
?>