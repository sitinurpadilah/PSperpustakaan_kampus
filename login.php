<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-PERPUS MODERN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #eee; }
        .btn-login {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
        }
        .btn-login:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="mb-4">
        <i class="bi bi-book-half text-primary" style="font-size: 3rem;"></i>
        <h3 class="fw-bold mt-2">E-PERPUS</h3>
        <p class="text-muted small">Silakan masuk ke akun Anda</p>
    </div>

    <?php if(isset($_GET['pesan'])): ?>
        <div class="alert alert-warning py-2 small"><?php echo $_GET['pesan']; ?></div>
    <?php endif; ?>

    <form action="cek_login.php" method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-login w-100 mb-3">MASUK SEKARANG</button>
    </form>
    
    <p class="small text-muted">Belum punya akun? <a href="register.php" class="text-decoration-none fw-bold">Daftar</a></p>
</div>

</body>
</html>