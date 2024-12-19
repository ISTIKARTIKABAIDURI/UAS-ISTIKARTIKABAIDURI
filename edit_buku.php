<?php
require 'config.php';
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = ?");
$stmt->execute([$id]);
$buku = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];

    // Jika ada gambar baru diunggah
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $target = "uploads/" . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    } else {
        $gambar = $buku['gambar'];
    }

    $stmt = $pdo->prepare("UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, kategori = ?, stok = ?, gambar = ? WHERE id_buku = ?");
    $stmt->execute([$judul, $pengarang, $penerbit, $tahun_terbit, $kategori, $stok, $gambar, $id]);

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
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

        .form-label {
            font-weight: 500;
        }

        .form-control {
            background-color: #34495E;
            border-color: #BDC3C7;
            color: white;
        }

        .form-control:focus {
            background-color: #2C3E50;
            border-color:rgb(41, 0, 0);
        }

        .btn-primary {
            background-color: rgb(32, 1, 1);
            border-color: rgb(38, 1, 1);
        }

        .btn-primary:hover {
            background-color: rgb(40, 0, 0);
            border-color: rgb(43, 0, 0);
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color:rgb(0, 0, 0);
        }

        .btn {
            font-weight: 500;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .img-thumbnail {
            max-width: 100px;
            margin-top: 10px;
        }

        .card-container form {
            width: 100%;
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
                        <a class="nav-link" href="admin.php">Kembali ke Daftar Buku</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Form Edit Buku -->
    <div class="container">
        <div class="card-container">
            <h2 class="text-center mb-4">EDIT BUKU</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="<?= htmlspecialchars($buku['judul']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" name="pengarang" id="pengarang" class="form-control" value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" class="form-control" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" value="<?= htmlspecialchars($buku['tahun_terbit']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?= htmlspecialchars($buku['kategori']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control" value="<?= htmlspecialchars($buku['stok']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Sampul</label>
                    <input type="file" name="gambar" id="gambar" class="form-control">
                    <img src="uploads/<?= htmlspecialchars($buku['gambar']) ?>" alt="Gambar Buku" class="img-thumbnail">
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Admin Pustaka. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
