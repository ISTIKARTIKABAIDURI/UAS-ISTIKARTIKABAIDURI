<?php
require 'config.php';
session_start();

// Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']); // Hapus spasi
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Validasi hasil query dan cek password
    if ($user && password_verify($password, $user['password'])) {
        // Set session jika login berhasil
        $_SESSION['user_id'] = $user['id_pengguna'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Redirect berdasarkan level pengguna
        switch ($user['level']) {
            case 'admin':
                header('Location: admin.php');
                break;
            case 'pustakawan':
                header('Location: pustakawan.php');
                break;
            case 'pimpinan':
                header('Location: pimpinan.php');
                break;
            default:
                header('Location: anggota.php');
                break;
        }
        exit;
    } else {
        // Pesan error jika login gagal
        $error = "Username atau password salah.";
    }
}

// Register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['reg_username']); // Hapus spasi
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
    $level = 'anggota'; // Default level untuk pengguna baru

    // Cek apakah username sudah digunakan
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE username = ?");
    $checkStmt->execute([$username]);
    if ($checkStmt->fetchColumn() > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        // Masukkan pengguna baru ke database
        $stmt = $pdo->prepare("INSERT INTO pengguna (username, password, level) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $level])) {
            $success = "Pendaftaran berhasil. Silakan login.";
        } else {
            $error = "Terjadi kesalahan. Coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Login & Daftar Perpustakaan</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('BACKGROUND-hd.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(246, 217, 197, 0.8);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .login-container h2 {
            font-weight: 700;
            color: #000;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #000;
        }

        .btn-primary, .btn-secondary {
            border: none;
            font-weight: 700;
            transition: background 0.3s;
        }

        .btn-primary {
            background: rgb(22, 15, 0);
            color: #fff;
        }

        .btn-primary:hover {
            background: rgb(38, 22, 10);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #000;
        }

        .btn-secondary:hover {
            background: rgb(246, 217, 197);
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #000;
        }

        .logo {
            width: 70px;
            margin-bottom: 1rem;
        }

        .d-flex button {
            margin-right: 1rem;
        }

        .alert {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="logo.png" alt="Logo Perpustakaan" class="logo">
        <h2>PERPUSTAKAAN KITA</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar</button>
            </div>
        </form>

        <div class="footer">
            &copy; 2024 Perpustakaan Kita. All rights reserved.
        </div>
    </div>

    <!-- Modal Daftar -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Form Daftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="reg_username" class="form-label">Username</label>
                            <input type="text" name="reg_username" id="reg_username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label for="reg_password" class="form-label">Password</label>
                            <input type="password" name="reg_password" id="reg_password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
