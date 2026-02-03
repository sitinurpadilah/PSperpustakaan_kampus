<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'staff'){ header("location:../login.php"); exit(); }

// Ambil ID dari URL
$id = $_GET['id'];
$query_buku = mysqli_query($conn, "SELECT * FROM buku WHERE id = '$id'");
$b = mysqli_fetch_assoc($query_buku);

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Buku | Staff Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7fa; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; position: fixed; padding: 20px; color: white; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
        .preview-img { width: 100px; border-radius: 10px; margin-bottom: 10px; border: 2px solid #ddd; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-5 text-info"><i class="bi bi-shield-check me-2"></i>STAFF</h4>
    <a href="index.php" class="nav-link text-white text-decoration-none d-block mb-3"><i class="bi bi-grid me-2"></i> Dashboard</a>
    <a href="transaksi.php" class="nav-link text-white-50 text-decoration-none d-block"><i class="bi bi-arrow-left-right me-2"></i> Transaksi</a>
</div>

<div class="main-content">
    <div class="mb-4 text-between d-flex align-items-center justify-content-between">
        <h3 class="fw-bold m-0">Edit Data Buku</h3>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Batal</a>
    </div>

    <div class="card card-form p-4">
        <form action="proses_edit_buku.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $b['id']; ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" value="<?= $b['judul']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nomor ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="<?= $b['isbn']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pengarang</label>
                    <input type="text" name="pengarang" class="form-control" value="<?= $b['pengarang']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <?php while($k = mysqli_fetch_array($kategori)) { ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= ($k['id_kategori'] == $b['id_kategori']) ? 'selected' : ''; ?>>
                                <?= $k['nama_kategori']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Stok</label>
                    <input type="number" name="stok" class="form-control" value="<?= $b['stok']; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Rak</label>
                    <input type="text" name="lokasi_rak" class="form-control" value="<?= $b['lokasi_rak']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ganti Sampul (Kosongkan jika tidak diubah)</label>
                    <div class="d-flex align-items-center gap-3">
                        <img src="../assets/sampul/<?= $b['gambar_sampul']; ?>" class="preview-img">
                        <input type="file" name="sampul" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>