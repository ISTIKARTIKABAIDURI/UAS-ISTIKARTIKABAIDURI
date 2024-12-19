<?php
require 'config.php';
session_start(); // Pastikan hanya dipanggil sekali

// Pastikan pengguna sudah login dengan level pustakawan
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pustakawan') {
    header('Location: login.php');
    exit;
}

// Periksa apakah ada ID buku yang diterima melalui URL
if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];

    // Ambil data buku berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = ?");
    $stmt->execute([$id_buku]);
    $book = $stmt->fetch();

    if (!$book) {
        die("Buku tidak ditemukan.");
    }

    // Pastikan ID Pengguna ada di session, jika tidak, arahkan ke halaman login
    if (!isset($_SESSION['user_id'])) {
        die("ID Pengguna tidak ditemukan. Anda harus login terlebih dahulu.");
    }

    // Ambil ID Pengguna dari sesi
    $id_pengguna = $_SESSION['user_id']; // Pastikan ID Pengguna ada dalam session

    // Logika peminjaman buku
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tanggal_pinjam = date('Y-m-d');
        $tanggal_kembali = date('Y-m-d', strtotime('+7 days')); // misalnya pinjam selama 7 hari
        $status = 'Pinjam'; // status peminjaman

        // Cek apakah stok buku cukup untuk dipinjam
        if ($book['stok'] > 0) {
            // Update stok buku
            $stmt = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?");
            $stmt->execute([$id_buku]);

            // Simpan peminjaman ke database
            $stmt2 = $pdo->prepare("INSERT INTO peminjaman (id_pengguna, id_buku, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?)");
            $stmt2->execute([$id_pengguna, $id_buku, $tanggal_pinjam, $tanggal_kembali, $status]);

            // Redirect ke halaman kelola peminjaman setelah berhasil
            header('Location: kelola_peminjaman.php');
            exit;
        } else {
            $error_message = "Stok buku tidak cukup untuk dipinjam.";
        }
    }
} else {
    die("ID buku tidak ada.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('BACKGROUND-hd.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            margin-top: 50px;
        }

        .card-container {
            background: rgba(46, 61, 73, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: #2C3E50;
            color: white;
            text-align: center;
            font-size: 1.5em;
        }

        .card-body {
            color: white;
        }

        .btn-primary {
            background-color: #1D2D44;
            border-color: #1A2632;
        }

        .btn-primary:hover {
            background-color: #12203C;
            border-color: #0F1C31;
        }

        .btn-secondary {
            background-color: #7F8C8D;
            border-color: #7F8C8D;
        }

        .btn-secondary:hover {
            background-color: #95A5A6;
            border-color: #95A5A6;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color:rgb(0, 0, 0);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-container">
            <div class="card-header">
                Pinjam Buku: <?= htmlspecialchars($book['judul']) ?>
            </div>
            <div class="card-body">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                <?php endif; ?>
                <p>Anda akan meminjam buku ini. Apakah Anda yakin?</p>
                <form method="POST">
                    <button type="submit" class="btn btn-primary">Konfirmasi Pinjam</button>
                    <a href="<?= $_SERVER['HTTP_REFERER'] ?? 'index.php' ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Pustakawan Pustaka. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
