<?php
session_start();
include '../config.php';
if($_SESSION['role'] != 'admin'){ header("location:../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Staff | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-light" style="font-family: 'Poppins', sans-serif;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-4">Tambah Staff Baru</h4>
                    <form action="tambah_staff.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama asli staff" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NIP / ID Staff</label>
                            <input type="text" name="nim_nip" class="form-control" placeholder="Contoh: STF001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Untuk login" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="******" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Simpan Staff</button>
                            <a href="users.php" class="btn btn-light w-100 rounded-pill py-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>