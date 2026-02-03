<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'admin'){ header("location:../login.php"); exit(); }

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$u = mysqli_fetch_assoc($query);

// Proses Update
if(isset($_POST['update'])){
    $nama = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    $nim  = $_POST['nim_nip'];
    
    $update = mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', role='$role', nim_nip='$nim' WHERE id='$id'");
    if($update){
        echo "<script>alert('Data user berhasil diperbarui!'); window.location='users.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit User | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; position: fixed; padding: 20px; color: white; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-edit { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .form-label { font-size: 13px; font-weight: 600; color: #64748b; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold mb-5 text-info">ADMIN</h4>
    <a href="index.php" class="text-white-50 text-decoration-none d-block mb-3 small"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="users.php" class="text-white text-decoration-none d-block mb-3 fw-bold"><i class="bi bi-people me-2 text-info"></i> Manajemen User</a>
</div>

<div class="main-content">
    <div class="mb-4">
        <a href="users.php" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Daftar User</a>
        <h3 class="fw-bold mt-2">Edit Pengguna</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-edit p-4 bg-white">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $u['nama_lengkap']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username (Tidak dapat diubah)</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $u['username']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM / NIP</label>
                        <input type="text" name="nim_nip" class="form-control" value="<?php echo $u['nim_nip']; ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Peran Sistem (Role)</label>
                        <select name="role" class="form-select">
                            <option value="mahasiswa" <?php if($u['role'] == 'mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                            <option value="staff" <?php if($u['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                            <option value="admin" <?php if($u['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">
                        <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-edit p-4 bg-info text-white">
                <h5 class="fw-bold"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h5>
                <p class="small opacity-75 mt-3">Sebagai Admin, Anda dapat meningkatkan role mahasiswa menjadi Staff untuk membantu pengelolaan buku.</p>
                <hr>
                <p class="small">Password tidak ditampilkan demi privasi. Jika user lupa password, buatkan fitur <b>Reset Password</b> di tahap selanjutnya.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>