<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'admin'){ header("location:../login.php"); exit(); }

// --- LOGIKA FILTER ---
$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Ambil data rekap berdasarkan filter bulan dan tahun
$query = "SELECT peminjaman.*, users.nama_lengkap, users.nim_nip, buku.judul 
          FROM peminjaman 
          JOIN users ON peminjaman.user_id = users.id 
          JOIN buku ON peminjaman.buku_id = buku.id 
          WHERE MONTH(tgl_pinjam) = '$bulan_filter' AND YEAR(tgl_pinjam) = '$tahun_filter'
          ORDER BY tgl_pinjam DESC";
$rekap = mysqli_query($conn, $query);

// Hitung total denda berdasarkan filter
$total_denda_query = "SELECT SUM(denda) as total FROM peminjaman 
                      WHERE MONTH(tgl_pinjam) = '$bulan_filter' AND YEAR(tgl_pinjam) = '$tahun_filter'";
$total_denda = mysqli_fetch_assoc(mysqli_query($conn, $total_denda_query))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; position: fixed; padding: 20px; color: white; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: rgba(255,255,255,0.6); padding: 12px 15px; border-radius: 12px; margin-bottom: 5px; display: block; text-decoration: none; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: #38bdf8 !important; }
        .header-report { background: white; border-radius: 20px; padding: 30px; border: 1px solid #e2e8f0; margin-bottom: 30px; }
        .card-report { border: none; border-radius: 20px; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
        .table thead th { background: #f1f5f9; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 20px; border: none; }
        .table tbody td { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }
        .badge-pill { padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; }
        .bg-selesai { background: #dcfce7; color: #166534; }
        .bg-proses { background: #fef3c7; color: #92400e; }

        @media print {
            .sidebar, .btn-print, .no-print, .filter-box { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
            .card-report { box-shadow: none; border: 1px solid #eee; }
            .header-report { border: none; padding: 0; margin-bottom: 20px; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-5 text-info"><i class="bi bi-cpu me-2"></i>ADMIN</h4>
    <a href="index.php" class="nav-link mb-2"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="users.php" class="nav-link mb-2"><i class="bi bi-people me-2"></i> Manajemen User</a>
    <a href="laporan.php" class="nav-link active mb-2"><i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Rekap</a>
    <div style="position: absolute; bottom: 30px; width: calc(100% - 40px);">
        <a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
    </div>
</div>

<div class="main-content">
    <div class="header-report d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold m-0 text-dark">Laporan Rekapitulasi</h2>
            <p class="text-muted m-0">Periode: <?php echo date('F', mktime(0, 0, 0, $bulan_filter, 10)) . " " . $tahun_filter; ?></p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 shadow-sm btn-print">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
            <a href="proses_reset_laporan.php" onclick="return confirm('Hapus semua riwayat transaksi yang sudah SELESAI?')" class="btn btn-outline-danger rounded-pill px-4 btn-print">
                <i class="bi bi-trash me-2"></i> Reset Data Selesai
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4 no-print" style="border-radius: 20px;">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Pilih Bulan</label>
                <select name="bulan" class="form-select rounded-3">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 10));
                        $selected = ($m == $bulan_filter) ? 'selected' : '';
                        echo "<option value='$m' $selected>$monthName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Pilih Tahun</label>
                <select name="tahun" class="form-select rounded-3">
                    <?php
                    $yearNow = date('Y');
                    for ($y = $yearNow; $y >= $yearNow - 5; $y--) {
                        $selected = ($y == $tahun_filter) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                    <i class="bi bi-filter me-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <div class="row mb-4 no-print">
        <div class="col-md-4">
            <div class="p-3 bg-white border rounded-4 shadow-sm">
                <small class="text-muted d-block fw-bold">TRANSAKSI PERIODE INI</small>
                <h4 class="fw-bold m-0"><?php echo mysqli_num_rows($rekap); ?> Data</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-primary text-white border rounded-4 shadow-sm">
                <small class="opacity-75 d-block fw-bold">DENDA PERIODE INI</small>
                <h4 class="fw-bold m-0">Rp <?php echo number_format($total_denda); ?></h4>
            </div>
        </div>
    </div>

    <div class="card card-report">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($rekap) > 0): ?>
                        <?php while($r = mysqli_fetch_array($rekap)): ?>
                        <tr>
                            <td>
                                <span class="fw-bold d-block text-dark"><?php echo $r['nama_lengkap']; ?></span>
                                <small class="text-muted"><?php echo $r['nim_nip']; ?></small>
                            </td>
                            <td class="text-muted"><?php echo $r['judul']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($r['tgl_pinjam'])); ?></td>
                            <td>
                                <?php echo $r['tgl_kembali'] ? date('d/m/Y', strtotime($r['tgl_kembali'])) : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td class="fw-bold <?php echo $r['denda'] > 0 ? 'text-danger' : 'text-dark'; ?>">
                                Rp <?php echo number_format($r['denda']); ?>
                            </td>
                            <td class="text-center">
                                <?php if($r['status'] == 'kembali'): ?>
                                    <span class="badge-pill bg-selesai text-uppercase">Selesai</span>
                                <?php else: ?>
                                    <span class="badge-pill bg-proses text-uppercase">Dipinjam</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>