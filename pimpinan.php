<?php
require 'config.php';
session_start();

// Periksa apakah pengguna sudah login dengan level pimpinan
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pimpinan') {
    header('Location: login.php');
    exit;
}

// Fungsi untuk mendownload laporan buku dalam format CSV
if (isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="laporan_buku.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Judul', 'Pengarang', 'Penerbit', 'Tahun Terbit', 'Kategori', 'Stok']);

    $stmt = $pdo->query("SELECT * FROM buku");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimpinan - Laporan Buku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('BACKGROUND-hd.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #000;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(246, 217, 197, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            width: 100%;
        }

        h2 {
            font-weight: bold;
            color: rgb(22, 15, 0);
            text-align: center;
        }

        .btn-success {
            background-color: rgb(22, 15, 0);
            border: none;
            font-weight: bold;
        }

        .btn-success:hover {
            background-color: rgba(38, 22, 10, 0.8);
        }

        .btn-danger {
            background-color: #c82333;
            border: none;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }

        table {
            margin-top: 1.5rem;
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: rgba(22, 15, 0, 0.8);
            color: #fff;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        tbody tr:nth-child(odd) {
            background-color: rgba(246, 217, 197, 0.5);
        }

        tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.8);
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            text-align: center;
            color: rgba(0, 0, 0, 0.6);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Laporan Buku</h2>
        <div class="d-flex justify-content-between mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <a href="?download=true" class="btn btn-success">Download Laporan Buku (CSV)</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mendapatkan data buku
                $stmt = $pdo->query("SELECT * FROM buku");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_buku']) ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['pengarang']) ?></td>
                        <td><?= htmlspecialchars($row['penerbit']) ?></td>
                        <td><?= htmlspecialchars($row['tahun_terbit']) ?></td>
                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                        <td><?= htmlspecialchars($row['stok']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="footer">&copy; 2024 Perpustakaan Kita. All rights reserved.</div>
    </div>
</body>

</html>
