<?php
session_start();
include '../config.php';

// Proteksi: Hanya Admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:../login.php");
    exit();
}

// Ambil semua data user kecuali Admin yang sedang login agar tidak terhapus sendiri
$admin_id = $_SESSION['id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id != '$admin_id' ORDER BY role ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User | Admin E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; margin: 0; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; position: fixed; left: 0; top: 0; padding: 20px; color: white; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        
        .nav-link { color: rgba(255,255,255,0.6); padding: 12px 15px; border-radius: 12px; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: #38bdf8 !important; }
        .nav-link i { margin-right: 12px; }

        .card-table { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .table thead { background-color: #f8fafc; }
        .table th { font-weight: 600; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 20px; }
        .table td { padding: 18px 20px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

        .badge-role { padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .bg-staff { background: #fef3c7; color: #92400e; }
        .bg-mhs { background: #e0f2fe; color: #075985; }
        .bg-dosen { background: #dcfce7; color: #166534; }
        
        .avatar-circle { width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="mb-5 px-2">
        <h4 class="fw-bold text-info"><i class="bi bi-cpu-fill me-2"></i>ADMIN PANEL</h4>
    </div>
    <p class="text-muted small fw-bold px-2 mb-2">UTAMA</p>
    <a href="index.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="users.php" class="nav-link active"><i class="bi bi-people"></i> Manajemen User</a>
    <a href="laporan.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Rekap</a>

    <div style="position: absolute; bottom: 30px; width: calc(100% - 40px);">
        <a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Keluar</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0">Manajemen Pengguna</h3>
            <p class="text-muted small m-0">Kelola data mahasiswa, dosen, dan staff perpustakaan.</p>
        </div>
        <a href="form_tambah_staff.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Staff
        </a>
    </div>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Akun Staff berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card card-table">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="ps-4">Pengguna</th>
                        <th>Username</th>
                        <th>NIM / NIP</th>
                        <th>Role / Peran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = mysqli_fetch_array($query_user)): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <?php echo strtoupper(substr($u['nama_lengkap'], 0, 1)); ?>
                                </div>
                                <div>
                                    <span class="fw-bold d-block"><?php echo $u['nama_lengkap']; ?></span>
                                    <small class="text-muted">ID: #USR-<?php echo $u['id']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted">@<?php echo $u['username']; ?></span></td>
                        <td><?php echo $u['nim_nip'] ?: '<span class="text-muted small italic">- Belum diisi -</span>'; ?></td>
                        <td>
                            <?php if($u['role'] == 'staff'): ?>
                                <span class="badge-role bg-staff">Staff</span>
                            <?php elseif($u['role'] == 'dosen'): ?>
                                <span class="badge-role bg-dosen">Dosen</span>
                            <?php else: ?>
                                <span class="badge-role bg-mhs">Mahasiswa</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-light border shadow-sm rounded-3 me-1" title="Edit User">
                                <i class="bi bi-gear-fill text-primary"></i>
                            </a>
                            <a href="hapus_user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-light border shadow-sm rounded-3" onclick="return confirm('Hapus user ini? Akun tidak dapat dikembalikan.')" title="Hapus User">
                                <i class="bi bi-trash-fill text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>