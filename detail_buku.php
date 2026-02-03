<?php
session_start();
include '../config.php';

// PERBAIKAN: Tambahkan role dosen agar tidak ter-logout
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'mahasiswa' && $_SESSION['role'] != 'dosen')) {
    header("location:../login.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori FROM buku 
                               LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori 
                               WHERE buku.id = '$id'");
$b = mysqli_fetch_assoc($query);

// Jika buku tidak ditemukan
if (!$b) { header("location:index.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Buku - <?php echo $b['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #fff; position: fixed; left: 0; top: 0; border-right: 1px solid #eee; padding: 20px; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #555; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #667eea; color: white !important; }
        .book-detail-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; }
        .img-detail { width: 100%; height: 500px; object-fit: cover; }
        .badge-kat { background: #e0e7ff; color: #4338ca; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold text-primary mb-5"><i class="bi bi-book-half me-2"></i>E-PERPUS</h4>
    <a href="index.php" class="nav-link active"><i class="bi bi-grid"></i> Katalog Buku</a>
    <a href="pinjaman_saya.php" class="nav-link"><i class="bi bi-journal-bookmark"></i> Pinjaman Saya</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Keluar</a>
</div>

<div class="main-content">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Katalog</a></li>
            <li class="breadcrumb-item active">Detail Buku</li>
        </ol>
    </nav>

    <div class="card book-detail-card">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="../assets/sampul/<?php echo $b['gambar_sampul']; ?>" class="img-detail" alt="Sampul">
            </div>
            <div class="col-md-8 p-5">
                <span class="badge-kat text-uppercase mb-3 d-inline-block"><?php echo $b['nama_kategori']; ?></span>
                <h1 class="fw-bold mb-2"><?php echo $b['judul']; ?></h1>
                <p class="text-muted fs-5 mb-4">Ditulis oleh <span class="text-dark fw-bold"><?php echo $b['pengarang']; ?></span></p>
                
                <hr>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <small class="text-muted d-block">Nomor ISBN</small>
                        <span class="fw-bold"><?php echo $b['isbn'] ?: '-'; ?></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Lokasi Rak</small>
                        <span class="fw-bold"><?php echo $b['lokasi_rak'] ?: 'Area Digital'; ?></span>
                    </div>
                </div>

                <div class="alert <?php echo $b['stok'] > 0 ? 'alert-success' : 'alert-danger'; ?> rounded-4">
                    <i class="bi <?php echo $b['stok'] > 0 ? 'bi-check-circle' : 'bi-x-circle'; ?> me-2"></i>
                    Status: <b><?php echo $b['stok'] > 0 ? 'Tersedia untuk dipinjam' : 'Sedang tidak tersedia'; ?></b> 
                    (Sisa Stok: <?php echo $b['stok']; ?>)
                </div>

                <?php if($b['stok'] > 0): ?>
                    <form action="proses_pinjam_mandiri.php" method="POST">
                        <input type="hidden" name="buku_id" value="<?php echo $b['id']; ?>">
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm" onclick="return confirm('Ajukan peminjaman buku ini?')">
                            <i class="bi bi-send-fill me-2"></i> Pinjam Buku Sekarang
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100 rounded-pill" disabled>Maaf, Stok Habis</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>