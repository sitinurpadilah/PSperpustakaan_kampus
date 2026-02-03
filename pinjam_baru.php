<?php
session_start();
include '../config.php';

$users = mysqli_query($conn, "SELECT * FROM users WHERE role IN ('mahasiswa', 'dosen')");
$buku  = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Catat Pinjam</title>
    <style>
        .box { width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; background: #fff; }
        select, input { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #27ae60; color: white; width: 100%; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Form Peminjaman</h3>
        <form action="proses_pinjam.php" method="POST">
            <label>Pilih Peminjam (Mhs/Dosen):</label>
            <select name="user_id" required>
                <?php while($u = mysqli_fetch_array($users)){ ?>
                    <option value="<?php echo $u['id']; ?>"><?php echo $u['nama_lengkap']; ?> (<?php echo $u['role']; ?>)</option>
                <?php } ?>
            </select>

            <label>Pilih Buku:</label>
            <select name="buku_id" required>
                <?php while($b = mysqli_fetch_array($buku)){ ?>
                    <option value="<?php echo $b['id']; ?>"><?php echo $b['judul']; ?> (Stok: <?php echo $b['stok']; ?>)</option>
                <?php } ?>
            </select>

            <label>Lama Pinjam (Hari):</label>
            <input type="number" name="durasi" value="7" min="1">

            <button type="submit">Konfirmasi Pinjam</button>
        </form>
    </div>
</body>
</html>