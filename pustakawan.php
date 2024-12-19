<?php
require 'config.php';
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pustakawan') {
    header('Location: login.php');
    exit;
}

// Mengambil data buku
$stmt = $pdo->prepare("SELECT * FROM buku");
$stmt->execute();
$books = $stmt->fetchAll();

// Mengambil data peminjaman
$stmt2 = $pdo->prepare("SELECT p.*, b.judul, u.nama FROM peminjaman p JOIN buku b ON p.id_buku = b.id_buku JOIN pengguna u ON p.id_pengguna = u.id_pengguna");
$stmt2->execute();
$loans = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustakawan - Kelola Peminjaman</title>
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
            padding: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            background: transparent; /* Tidak ada background card */
            border-radius: 0; /* Menghapus border-radius */
            box-shadow: none; /* Menghapus bayangan */
        }

        h2, h4 {
            font-weight: bold;
            color: white; /* Ganti warna menjadi putih agar lebih kontras */
            text-align: center;
        }

        .navbar {
            background-color: rgba(22, 15, 0, 0.8);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }

        .navbar .nav-link {
            color: #fff !important;
            font-weight: bold; /* Menebalkan teks Logout */
        }

        .navbar .nav-link:hover {
            color: #BDC3C7 !important;
        }

        /* Card efek untuk bagian kelola peminjaman, judul dan tabel */
        .card-container {
            background: rgba(46, 61, 73, 0.9); /* Background gelap transparan */
            padding: 2rem;
            border-radius: 10px; /* Sudut melengkung */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan */
            margin-bottom: 2rem;
        }

        table {
            margin-top: 1.5rem;
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: rgba(22, 15, 0, 0.8);
            color: white; /* Mengubah warna teks menjadi putih */
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2); /* Warna border lebih terang */
            text-align: center;
            color: white; /* Warna teks tabel putih */
        }

        tbody tr:nth-child(odd) {
            background-color: rgba(46, 61, 73, 0.8); /* Warna latar lebih gelap */
        }

        tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1); /* Warna latar lebih terang agar kontras */
        }

        footer {
            text-align: center;
            margin: 2rem 0;
            font-size: 0.9rem;
            color: rgba(0, 0, 0, 0.6);
        }

        .btn-primary {
            background-color: rgba(41, 0, 0, 0.7); 
            border-color: rgba(41, 0, 0, 0.7); 
        }

        .btn-primary:hover {
            background-color: rgba(41, 0, 0, 0.7); 
            border-color: rgba(41, 0, 0, 0.7);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Pustakawan Pustaka</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Halaman Kelola Peminjaman -->
    <div class="container">
        <!-- Kelola Peminjaman di dalam Card -->
        <div class="card-container">
            <h2>KELOLA PEMINJAMAN</h2>
        </div>

        <!-- Sekat pemisah -->
        <div class="section-divider"></div>

        <!-- Tabel Buku -->
        <div class="card-container">
            <h4>DAFTAR BUKU</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= $book['id_buku'] ?></td>
                            <td><?= $book['judul'] ?></td>
                            <td><?= $book['pengarang'] ?></td>
                            <td><?= $book['stok'] ?></td>
                            <td>
                                <a href="pinjam_buku.php?id=<?= $book['id_buku'] ?>" class="btn btn-primary btn-sm">Pinjam Buku</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="card-container">
            <h4>DAFTAR PEMINJAMAN</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?= $loan['id_peminjaman'] ?></td>
                            <td><?= htmlspecialchars($loan['judul']) ?></td>
                            <td><?= htmlspecialchars($loan['nama']) ?></td>
                            <td><?= htmlspecialchars($loan['tanggal_pinjam']) ?></td>
                            <td><?= $loan['tanggal_kembali'] ?? '-' ?></td>
                            <td><?= htmlspecialchars($loan['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Pustakawan Pustaka. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
