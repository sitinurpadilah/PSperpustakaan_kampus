<?php
session_start();
include '../config.php';

// Izinkan Mahasiswa DAN Dosen masuk
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'mahasiswa' && $_SESSION['role'] != 'dosen')) {
    header("location:../login.php");
    exit();
}

$id_user = $_SESSION['id'];

// --- UPDATE: AMBIL DATA PROFIL UNTUK FOTO ---
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'"));
$file_foto = !empty($user_data['foto']) ? $user_data['foto'] : "default.png";

// --- UPDATE: QUERY NOTIFIKASI ---
$query_notif = mysqli_query($conn, "SELECT * FROM notifikasi WHERE user_id = '$id_user' AND is_read = 0 ORDER BY created_at DESC");
$total_notif = mysqli_num_rows($query_notif);

// --- LOGIKA PENCARIAN (TETAP SAMA) ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT buku.*, kategori.nama_kategori FROM buku 
        LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori";

if ($search != '') {
    $sql .= " WHERE buku.judul LIKE '%$search%' 
              OR buku.pengarang LIKE '%$search%' 
              OR buku.isbn LIKE '%$search%'";
}

$sql .= " ORDER BY buku.id DESC";
$query_buku = mysqli_query($conn, $sql);
// ----------------------------------------
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; margin: 0; }
        .sidebar { width: 260px; height: 100vh; background: #ffffff; position: fixed; left: 0; top: 0; border-right: 1px solid #eee; padding: 20px; z-index: 1000; display: flex; flex-direction: column; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .nav-link { color: #555; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; display: flex; align-items: center; transition: 0.3s; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #667eea; color: white !important; }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }
        .hero-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 50px 30px; border-radius: 20px; margin-bottom: 40px; position: relative; }
        .book-card { border: none; border-radius: 15px; transition: 0.3s; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); position: relative; }
        .book-card:hover { transform: translateY(-10px); box-shadow: 0 12px 20px rgba(0,0,0,0.1); }
        .card-img-top { height: 280px; object-fit: cover; border-radius: 15px 15px 0 0; background: #eee; }
        .badge-stok { position: absolute; top: 15px; right: 15px; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 10; }
        .tersedia { background-color: #27ae60; color: white; }
        .habis { background-color: #e74c3c; color: white; }
        .badge-kategori { background: rgba(102, 126, 234, 0.1); color: #667eea; font-size: 10px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
        
        /* UPDATE STYLE UNTUK PROFIL & NOTIFIKASI */
        .btn-logout-container { margin-top: auto; padding-bottom: 20px; }
        .profile-img-nav { width: 35px; height: 35px; border-radius: 8px; object-fit: cover; }
        .notif-badge { font-size: 9px; padding: 3px 6px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="mb-5 px-2">
        <h4 class="fw-bold text-primary"><i class="bi bi-book-half me-2"></i>E-PERPUS</h4>
    </div>
    <p class="text-muted small fw-bold px-2 mb-2">MENU UTAMA</p>
    <a href="index.php" class="nav-link active"><i class="bi bi-grid"></i> Katalog Buku</a>
    <a href="pinjaman_saya.php" class="nav-link"><i class="bi bi-journal-bookmark"></i> Pinjaman Saya</a>
    <a href="riwayat.php" class="nav-link"><i class="bi bi-clock-history"></i> Riwayat</a>

    <div class="btn-logout-container">
        <a href="edit_profil.php" class="text-decoration-none text-dark">
            <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center border shadow-sm" style="cursor: pointer;">
                <img src="../assets/profil/<?php echo $file_foto; ?>" class="profile-img-nav me-2 border">
                <div class="overflow-hidden">
                    <span class="fw-bold small d-block text-truncate"><?php echo $_SESSION['nama']; ?></span>
                    <small class="text-primary" style="font-size: 10px;"><i class="bi bi-pencil-square"></i> Edit Profil</small>
                </div>
            </div>
        </a>
        <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-3"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid p-4">
        
        <header class="hero-section shadow-sm">
            <div class="position-absolute" style="top: 20px; right: 30px;">
                <div class="dropdown">
                    <button class="btn btn-white bg-white rounded-circle shadow-sm p-2 position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell text-primary fs-5"></i>
                        <?php if($total_notif > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-badge">
                                <?php echo $total_notif; ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-3 mt-2" style="width: 280px;">
                        <li class="fw-bold mb-2 border-bottom pb-2">Notifikasi</li>
                        <?php if($total_notif > 0): ?>
                            <?php while($n = mysqli_fetch_assoc($query_notif)): ?>
                                <li class="mb-2">
                                    <div class="dropdown-item p-2 rounded-3 bg-light" style="white-space: normal;">
                                        <small class="d-block" style="font-size: 11px;"><?php echo $n['pesan']; ?></small>
                                        <small class="text-muted" style="font-size: 9px;"><?php echo date('d M, H:i', strtotime($n['created_at'])); ?></small>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center small text-primary fw-bold" href="tandai_baca.php">Tandai Sudah Dibaca</a></li>
                        <?php else: ?>
                            <li class="text-center py-3 text-muted small">Tidak ada notifikasi baru</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <h1 class="fw-bold">Koleksi Perpustakaan</h1>
            <p class="opacity-75">Cari buku favoritmu di sini.</p>
            
            <div class="mt-4 col-md-6">
                <form action="" method="GET">
                    <div class="input-group bg-white rounded-pill p-1">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search ms-2"></i></span>
                        <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="Cari judul, pengarang, atau ISBN..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Cari</button>
                    </div>
                </form>
            </div>
        </header>

        <h5 class="fw-bold mb-4">
            <?php echo ($search != '') ? 'Hasil Pencarian: "'.$search.'"' : 'Katalog Buku'; ?>
        </h5>

        <div class="row">
            <?php 
            if(mysqli_num_rows($query_buku) > 0) {
                while($b = mysqli_fetch_array($query_buku)) { 
                    $status_label = ($b['stok'] > 0) ? 'Tersedia' : 'Habis';
                    $status_class = ($b['stok'] > 0) ? 'tersedia' : 'habis';
            ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card book-card h-100">
                    <span class="badge-stok <?php echo $status_class; ?>">
                        <?php echo $status_label; ?>
                    </span>
                    
                    <img src="../assets/sampul/<?php echo $b['gambar_sampul']; ?>" 
                         class="card-img-top" 
                         onerror="this.src='https://via.placeholder.com/280x400?text=No+Cover'">
                    
                    <div class="card-body p-3">
                        <span class="badge-kategori mb-2 d-inline-block text-uppercase"><?php echo $b['nama_kategori']; ?></span>
                        <h6 class="card-title fw-bold text-truncate mb-1"><?php echo $b['judul']; ?></h6>
                        <p class="text-muted small mb-1"><?php echo $b['pengarang']; ?></p>
                        <p class="small fw-bold mb-3">Stok: <?php echo $b['stok']; ?></p>
                        
                        <?php if($b['stok'] > 0): ?>
                            <a href="detail_buku.php?id=<?php echo $b['id']; ?>" class="btn btn-primary w-100 rounded-pill btn-sm">Lihat Detail</a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 rounded-pill btn-sm" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php 
                } 
            } else {
                echo "<div class='col-12 text-center py-5'><i class='bi bi-emoji-frown fs-1'></i><p class='mt-3'>Buku tidak ditemukan.</p></div>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>