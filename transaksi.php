<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'staff'){ header("location:../login.php"); exit(); }

// Query ambil data transaksi
$query = "SELECT peminjaman.*, users.nama_lengkap, users.nim_nip, buku.judul, buku.gambar_sampul 
          FROM peminjaman 
          JOIN users ON peminjaman.user_id = users.id 
          JOIN buku ON peminjaman.buku_id = buku.id 
          ORDER BY peminjaman.status ASC, peminjaman.tgl_deadline ASC";
$data_transaksi = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Transaksi | Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; position: fixed; padding: 20px; color: white; }
        .main-content { margin-left: 260px; padding: 40px; }
        
        /* Table Styling agar rapi */
        .card-table { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .table { margin-bottom: 0; vertical-align: middle; }
        .table thead th { 
            background: #f8fafc; 
            padding: 18px; 
            font-size: 12px; 
            text-transform: uppercase; 
            color: #64748b; 
            letter-spacing: 1px;
            border: none;
        }
        .table tbody td { padding: 18px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        
        /* Status Badges */
        .badge-status { padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 600; display: inline-block; }
        .bg-warning-light { background: #fef3c7; color: #92400e; } /* Dipinjam */
        .bg-success-light { background: #dcfce7; color: #166534; } /* Kembali */
        .bg-danger-light { background: #fee2e2; color: #991b1b; }  /* Terlambat */

        .img-book { width: 40px; height: 55px; object-fit: cover; border-radius: 6px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px; border-radius: 10px; text-decoration: none; display: block; }
        .nav-link.active { background: #334155; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-5"><i class="bi bi-shield-check me-2 text-info"></i>STAFF</h4>
    <a href="index.php" class="nav-link mb-2"><i class="bi bi-grid me-2"></i> Dashboard</a>
    <a href="kategori.php" class="nav-link mb-2"><i class="bi bi-tags me-2"></i> Kategori</a>
    <a href="transaksi.php" class="nav-link active mb-2"><i class="bi bi-arrow-left-right me-2"></i> Transaksi</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0">Data Transaksi</h3>
            <p class="text-muted small">Daftar peminjaman dan pengembalian buku</p>
        </div>
    </div>

    <div class="card card-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Informasi Buku</th>
                        <th style="width: 20%;">Peminjam</th>
                        <th style="width: 15%;">Tgl Pinjam</th>
                        <th style="width: 15%;">Deadline</th>
                        <th style="width: 10%;">Denda</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($t = mysqli_fetch_array($data_transaksi)): 
                        // Cek apakah terlambat
                        $deadline = strtotime($t['tgl_deadline']);
                        $hari_ini = strtotime(date('Y-m-d'));
                        $telat = ($hari_ini > $deadline && $t['status'] == 'dipinjam');
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="../assets/sampul/<?php echo $t['gambar_sampul']; ?>" class="img-book me-3 border">
                                <div>
                                    <span class="fw-bold d-block text-truncate" style="max-width: 180px;"><?php echo $t['judul']; ?></span>
                                    <small class="text-muted">ID: #B-<?php echo $t['buku_id']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold"><?php echo $t['nama_lengkap']; ?></div>
                            <small class="text-muted">NIM: <?php echo $t['nim_nip']; ?></small>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($t['tgl_pinjam'])); ?></td>
                        <td>
                            <span class="<?php echo $telat ? 'text-danger fw-bold' : ''; ?>">
                                <?php echo date('d/m/Y', strtotime($t['tgl_deadline'])); ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold <?php echo $t['denda'] > 0 ? 'text-danger' : 'text-muted'; ?>">
                                <?php echo $t['denda'] > 0 ? "Rp ".number_format($t['denda']) : "-"; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($t['status'] == 'dipinjam'): ?>
                                <?php if($telat): ?>
                                    <div class="badge-status bg-danger-light mb-1">TERLAMBAT</div>
                                <?php else: ?>
                                    <div class="badge-status bg-warning-light mb-1">DIPINJAM</div>
                                <?php endif; ?>
                                <br>
                                <a href="proses_kembali.php?id=<?php echo $t['id_pinjam']; ?>&buku=<?php echo $t['buku_id']; ?>" 
                                   class="btn btn-sm btn-primary rounded-pill px-3 py-1 shadow-sm" style="font-size: 11px;"
                                   onclick="return confirm('Konfirmasi pengembalian buku?')">
                                   Kembalikan
                                </a>
                            <?php else: ?>
                                <div class="badge-status bg-success-light">KEMBALI</div>
                                <div class="small text-muted mt-1" style="font-size: 10px;">
                                    <?php echo date('d/m/y', strtotime($t['tgl_kembali'])); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>