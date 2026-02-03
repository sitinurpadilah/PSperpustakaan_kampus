<?php
session_start();
include '../config.php';

// PERBAIKAN: Tambahkan role dosen agar tidak ter-logout
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'mahasiswa' && $_SESSION['role'] != 'dosen')) {
    header("location:../login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Ambil data buku yang statusnya 'kembali'
$query = "SELECT peminjaman.*, buku.judul, buku.gambar_sampul 
          FROM peminjaman 
          JOIN buku ON peminjaman.buku_id = buku.id 
          WHERE peminjaman.user_id = '$user_id' AND peminjaman.status = 'kembali'
          ORDER BY tgl_kembali DESC";
$data_riwayat = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Pinjam | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #fff; position: fixed; left: 0; top: 0; border-right: 1px solid #eee; padding: 20px; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #555; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #667eea; color: white !important; }
        .table-riwayat { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold text-primary mb-5"><i class="bi bi-book-half me-2"></i>E-PERPUS</h4>
    <a href="index.php" class="nav-link"><i class="bi bi-grid"></i> Katalog Buku</a>
    <a href="pinjaman_saya.php" class="nav-link"><i class="bi bi-journal-bookmark"></i> Pinjaman Saya</a>
    <a href="riwayat.php" class="nav-link active"><i class="bi bi-clock-history"></i> Riwayat</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left"></i> Keluar</a>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Riwayat Peminjaman</h3>

    <div class="table-riwayat">
        <table class="table table-hover m-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Denda Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($data_riwayat) == 0): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pengembalian buku.</td></tr>
                <?php endif; ?>
                
                <?php while($r = mysqli_fetch_array($data_riwayat)): ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="../assets/sampul/<?php echo $r['gambar_sampul']; ?>" style="width: 40px; height: 55px; object-fit: cover;" class="rounded me-3">
                            <span class="fw-bold"><?php echo $r['judul']; ?></span>
                        </div>
                    </td>
                    <td class="align-middle"><?php echo $r['tgl_pinjam']; ?></td>
                    <td class="align-middle"><?php echo $r['tgl_kembali']; ?></td>
                    <td class="align-middle text-danger fw-bold">
                        <?php echo $r['denda'] > 0 ? "Rp ".number_format($r['denda']) : "-"; ?>
                    </td>
                    <td class="align-middle">
                        <span class="badge bg-success-subtle text-success rounded-pill px-3">Sudah Kembali</span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>