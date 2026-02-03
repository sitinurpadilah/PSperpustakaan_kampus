<?php
session_start();
include '../config.php';

// Izinkan mahasiswa dan dosen
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'mahasiswa' && $_SESSION['role'] != 'dosen')) {
    header("location:../login.php");
    exit();
}

$user_id = $_SESSION['id']; // Pastikan ini sesuai dengan di cek_login.php

$user_id = $_SESSION['id'];

// Ambil data buku yang sedang dipinjam oleh user ini
$query = "SELECT peminjaman.*, buku.judul, buku.gambar_sampul, kategori.nama_kategori 
          FROM peminjaman 
          JOIN buku ON peminjaman.buku_id = buku.id 
          JOIN kategori ON buku.id_kategori = kategori.id_kategori
          WHERE peminjaman.user_id = '$user_id' AND peminjaman.status = 'dipinjam'
          ORDER BY id_pinjam DESC";
$data_pinjam = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pinjaman Saya | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #fff; position: fixed; left: 0; top: 0; border-right: 1px solid #eee; padding: 20px; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #555; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #667eea; color: white !important; }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }
        
        .card-pinjam { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; overflow: hidden; background: #fff; }
        .img-pinjam { width: 100px; height: 140px; object-fit: cover; border-radius: 10px; background: #eee; }
        .status-badge { font-size: 11px; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
        .denda-box { background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 10px; border-radius: 10px; font-size: 13px; }
        .btn-logout-container { position: absolute; bottom: 20px; width: calc(100% - 40px); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="mb-5 px-2">
        <h4 class="fw-bold text-primary"><i class="bi bi-book-half me-2"></i>E-PERPUS</h4>
    </div>

    <p class="text-muted small fw-bold px-2 mb-2">MENU UTAMA</p>
    <a href="index.php" class="nav-link"><i class="bi bi-grid"></i> Katalog Buku</a>
    <a href="pinjaman_saya.php" class="nav-link active"><i class="bi bi-journal-bookmark"></i> Pinjaman Saya</a>
    <a href="riwayat.php" class="nav-link"><i class="bi bi-clock-history"></i> Riwayat</a>

    <div class="btn-logout-container">
        <div class="p-3 bg-light rounded-3 mb-3">
            <small class="text-muted d-block">Login sebagai:</small>
            <span class="fw-bold small"><?php echo $_SESSION['nama']; ?></span>
        </div>
        <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-3"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Buku yang Sedang Dipinjam</h3>

    <?php if(mysqli_num_rows($data_pinjam) == 0): ?>
        <div class="text-center py-5 shadow-sm bg-white rounded-4">
            <i class="bi bi-emoji-smile fs-1 text-muted"></i>
            <p class="mt-3 text-muted">Kamu tidak sedang meminjam buku apapun.</p>
            <a href="index.php" class="btn btn-primary rounded-pill px-4">Cari Buku</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php while($d = mysqli_fetch_array($data_pinjam)): 
                // Hitung Denda Otomatis
                $deadline = new DateTime($d['tgl_deadline']);
                $sekarang = new DateTime(date('Y-m-d'));
                $denda = 0;
                $telat = 0;
                
                if($sekarang > $deadline){
                    $telat = $sekarang->diff($deadline)->days;
                    $denda = $telat * 1000; // Rp 1.000 per hari
                }
            ?>
            <div class="col-md-12 mb-3">
                <div class="card card-pinjam p-3 border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <img src="../assets/sampul/<?php echo $d['gambar_sampul']; ?>" class="img-pinjam me-4" onerror="this.src='https://via.placeholder.com/100x140?text=No+Cover'">
                        <div class="flex-grow-1">
                            <span class="badge bg-primary-subtle text-primary text-uppercase mb-1" style="font-size: 10px;"><?php echo $d['nama_kategori']; ?></span>
                            <h5 class="fw-bold mb-1"><?php echo $d['judul']; ?></h5>
                            <p class="text-muted small mb-2">Pinjam: <b><?php echo $d['tgl_pinjam']; ?></b> | Deadline: <b class="text-danger"><?php echo $d['tgl_deadline']; ?></b></p>
                            
                            <?php if($denda > 0): ?>
                                <div class="denda-box d-inline-block shadow-sm">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Terlambat <?php echo $telat; ?> hari. Denda: <b>Rp <?php echo number_format($denda, 0, ',', '.'); ?></b>
                                </div>
                            <?php else: ?>
                                <span class="status-badge bg-success-subtle text-success">
                                    <i class="bi bi-clock me-1"></i> Masih dalam masa pinjam
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="text-end border-start ps-4 ms-2">
                            <p class="small text-muted mb-0 text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Status</p>
                            <span class="fw-bold text-warning text-uppercase" style="font-size: 14px;">Dipinjam</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>