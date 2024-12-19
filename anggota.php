<?php
require 'config.php';
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'anggota') {
    header('Location: login.php');
    exit;
}

// Mengambil data buku
$stmt = $pdo->prepare("SELECT * FROM buku WHERE stok > 0");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC); // Hanya key asosiatif
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Anggota - Lihat Buku</title>
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
        <h2>DAFTAR BUKU TERSEDIA</h2>
        <div class="d-flex justify-content-between mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id_buku']) ?></td>
                        <td><?= htmlspecialchars($book['judul']) ?></td>
                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                        <td><?= htmlspecialchars($book['penerbit']) ?></td>
                        <td><?= htmlspecialchars($book['kategori']) ?></td>
                        <td><?= htmlspecialchars($book['stok']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="footer">&copy; 2024 Perpustakaan Kita. All rights reserved.</div>
    </div>
</body>

</html>
