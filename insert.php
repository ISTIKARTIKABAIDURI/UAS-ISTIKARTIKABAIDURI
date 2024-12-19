<?php
require 'config.php';
session_start();

// Periksa apakah user adalah admin sebelum akses halaman ini
// if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
//     header('Location: login.php');
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $level = $_POST['level'];

    // Password sama dengan username
    $password = password_hash($username, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO pengguna (username, password, nama, level) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $username, $level]);

    echo "Akun berhasil ditambahkan!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title>Tambah Akun</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Tambah Akun Baru</h2>
        <a href="admin.php" class="btn btn-secondary mb-3">Kembali</a>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="level" class="form-label">Level</label>
                <select name="level" id="level" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="pustakawan">Pustakawan</option>
                    <option value="pimpinan">Pimpinan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>

</html>