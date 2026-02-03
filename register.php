<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | E-Perpus Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .reg-container {
            width: 100%;
            max-width: 450px;
        }

        .reg-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: #3b82f6;
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        h2 {
            color: #1e293b;
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        p.subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-group-text {
            background: transparent;
            border-right: none;
            color: #94a3b8;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
        }

        .btn-reg {
            background: #3b82f6;
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .btn-reg:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .link-login {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .link-login a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="reg-container">
        <div class="reg-card">
            <div class="text-center">
                <div class="brand-logo">
                    <i class="bi bi-book-half"></i>
                </div>
                <h2>Daftar Akun Baru</h2>
                <p class="subtitle">Silakan isi data diri untuk akses perpustakaan</p>
            </div>

            <form action="proses_register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nomor Induk (NIM / NIDN)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" name="nim_nip" class="form-control" placeholder="Contoh: 202100123" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama sesuai identitas" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Daftar Sebagai</label>
                    <select name="role" class="form-select shadow-sm">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                    </select>
                </div>

                <button type="submit" class="btn-reg">
                    <i class="bi bi-person-plus-fill me-2"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="link-login">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>