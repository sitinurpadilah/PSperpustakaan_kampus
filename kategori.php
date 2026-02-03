<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'staff'){ header("location:../login.php"); exit(); }

// Tambah Kategori
if(isset($_POST['tambah'])){
    $nama = $_POST['nama_kategori'];
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
}

$data_kat = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kategori | Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7fa; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: #1e293b; position: fixed; padding: 20px; color: white; }
        .main-content { margin-left: 260px; padding: 30px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px; border-radius: 10px; margin-bottom: 5px; display: block; text-decoration: none; }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="fw-bold mb-5">STAFF PANEL</h4>
    <a href="index.php" class="nav-link"><i class="bi bi-grid me-2"></i> Dashboard</a>
    <a href="kategori.php" class="nav-link active" style="background: #334155; color: white;"><i class="bi bi-tags me-2"></i> Kategori</a>
    <a href="transaksi.php" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i> Transaksi</a>
</div>
<div class="main-content">
    <h3 class="fw-bold mb-4">Manajemen Kategori</h3>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h6 class="fw-bold">Tambah Baru</h6>
                <form method="POST">
                    <input type="text" name="nama_kategori" class="form-control mb-3" placeholder="Contoh: Novel, Teknologi" required>
                    <button type="submit" name="tambah" class="btn btn-primary w-100 rounded-pill shadow-sm">Simpan</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table mb-0 align-middle">
                    <thead class="table-light"><tr><th class="ps-4">Nama Kategori</th><th class="text-center">Aksi</th></tr></thead>
                    <tbody>
                        <?php while($k = mysqli_fetch_array($data_kat)): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $k['nama_kategori']; ?></td>
                            <td class="text-center">
                                <a href="hapus_kat.php?id=<?php echo $k['id_kategori']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Hapus?')"><i class="bi bi-x"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>