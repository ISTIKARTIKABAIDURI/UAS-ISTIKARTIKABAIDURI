<?php
require 'config.php';
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: login.php');
    exit;
}

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM buku";
if ($search) {
    $query .= " WHERE judul LIKE :search OR pengarang LIKE :search OR kategori LIKE :search";
}

$stmt = $pdo->prepare($query);
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Buku</title>
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
            font-weight: bold;
        }

        .navbar .nav-link:hover {
            color: #BDC3C7 !important;
        }

        .container {
            margin-top: 50px;
            max-width: 1200px;
        }

        h2 {
            font-weight: bold;
            color: white;
            text-align: center;
        }

        .card-container {
            background: rgba(46, 61, 73, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .search-bar input {
            width: 80%;
            display: inline-block;
        }

        .search-bar button {
            display: inline-block;
        }

        .table {
            margin-top: 1.5rem;
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: rgba(22, 15, 0, 0.8);
            color: white;
        }

        th, td {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            color: white;
        }

        tbody tr:nth-child(odd) {
            background-color: rgba(46, 61, 73, 0.8);
        }

        tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Mengubah ukuran gambar */
        .table img {
            width: 40px; /* Mengurangi ukuran gambar */
            height: auto;
        }

        /* Menyesuaikan warna tombol dengan tema */
        .btn-success {
            background-color: #8B0000; /* Maroon tua */
            border-color: #8B0000;
        }

        .btn-success:hover {
            background-color: #660000;
            border-color: #660000;
        }

        .btn-warning {
            background-color: #E67E22; /* Oranye gelap */
            border-color: #E67E22;
        }

        .btn-warning:hover {
            background-color: #D35400;
            border-color: #D35400;
        }

        .btn-danger {
            background-color: #C0392B; /* Merah gelap */
            border-color: #C0392B;
        }

        .btn-danger:hover {
            background-color: #A93226;
            border-color: #A93226;
        }

        .action-buttons .btn {
            margin-right: 5px;
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">ADMIN PUSTAKA</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Halaman Kelola Buku -->
    <div class="container">
        <h2 class="my-4">KELOLA BUKU</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="tambah_buku.php" class="btn btn-success">TAMBAH BUKU</a>
            <form class="d-flex search-bar" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Cari buku..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary ms-2">Cari</button>
            </form>
        </div>

        <div class="card-container">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= $book['id_buku'] ?></td>
                            <td><?= $book['judul'] ?></td>
                            <td><?= $book['pengarang'] ?></td>
                            <td><?= $book['penerbit'] ?></td>
                            <td><?= $book['tahun_terbit'] ?></td>
                            <td><?= $book['kategori'] ?></td>
                            <td><?= $book['stok'] ?></td>
                            <td><img src="uploads/<?= $book['gambar'] ?>" alt="Gambar Buku"></td>
                            <td class="action-buttons">
                                <a href="edit_buku.php?id=<?= $book['id_buku'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_buku.php?id=<?= $book['id_buku'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Admin Pustaka. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
