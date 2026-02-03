<?php
session_start();
include '../config.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'mahasiswa' && $_SESSION['role'] != 'dosen')) {
    header("location:../login.php");
    exit();
}

$id_user = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
$u = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $password = $_POST['password'];
    
    $foto_nama = $u['foto']; 
    if ($_FILES['foto']['name'] != "") {
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_baru = "USER_" . $id_user . "_" . time() . "." . $ekstensi;
        $target = "../assets/profil/" . $foto_baru;

        // PERBAIKAN DI SINI: menggunakan tmp_name
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            if (!empty($u['foto']) && $u['foto'] != "default.png" && file_exists("../assets/profil/" . $u['foto'])) {
                unlink("../assets/profil/" . $u['foto']);
            }
            $foto_nama = $foto_baru;
        }
    }

    if (!empty($password)) {
        $sql = "UPDATE users SET nama_lengkap='$nama', password='$password', foto='$foto_nama' WHERE id='$id_user'";
    } else {
        $sql = "UPDATE users SET nama_lengkap='$nama', foto='$foto_nama' WHERE id='$id_user'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['nama'] = $nama; 
        header("location:index.php?status=profil_updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .card-profile { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .img-preview { width: 120px; height: 120px; object-fit: cover; border-radius: 20px; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="mb-4">
                <a href="index.php" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog</a>
            </div>
            
            <div class="card card-profile p-4">
                <h4 class="fw-bold mb-4">Pengaturan Profil</h4>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <img src="../assets/profil/<?php echo !empty($u['foto']) ? $u['foto'] : 'default.png'; ?>" class="img-preview mb-3" id="load_foto">
                        <div class="mt-2">
                            <label for="foto" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ganti Foto</label>
                            <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage()">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $u['username']; ?>" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control rounded-3" value="<?php echo $u['nama_lengkap']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin ganti password">
                    </div>

                    <button type="submit" name="update" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('#load_foto');
        const oFReader = new FileReader();
        oFReader.readAsDataURL(foto.files[0]);
        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
</body>
</html>