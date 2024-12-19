<?php
require 'config.php';
session_start();

// Pastikan pengguna sudah login dengan level pustakawan
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'pustakawan') {
    header('Location: login.php');
    exit;
}

// Ambil data peminjaman dari database
$stmt = $pdo->prepare("SELECT p.*, b.judul, u.nama FROM peminjaman p JOIN buku b ON p.id_buku = b.id_buku JOIN pengguna u ON p.id_pengguna = u.id_pengguna");
$stmt->execute();
$loans = $stmt->fetchAll();

// Logika untuk memperbarui status peminjaman
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE peminjaman SET status = ? WHERE id_peminjaman = ?");
    $stmt->execute([$status, $id_peminjaman]);

    // Redirect untuk mencegah refresh form yang tidak diinginkan
    header('Location: kelola_peminjaman.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman</title>
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

        .table {
            background-color: #34495E;
        }

        .table th,
        .table td {
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary,
        .btn-danger {
            background-color: rgb(38, 1, 1);
            border-color: rgb(36, 1, 1);
        }

        .btn-primary:hover,
        .btn-danger:hover {
            background-color: rgb(25, 0, 0);
            border-color: rgb(45, 1, 1);
        }

        .btn-back {
            background-color: #7F8C8D;
            border-color: #7F8C8D;
        }

        .btn-back:hover {
            background-color: #95A5A6;
            border-color: #95A5A6;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color:rgb(0, 0, 0);
        }

        h2 {
            text-align: center;
            color: #FFF;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Tombol Kembali -->
        <button class="btn btn-back mb-3" onclick="history.back()">← Kembali</button>

        <!-- Judul Kelola Peminjaman -->
        <div class="card-container">
            <h2>DAFTAR PEMINJAMAN</h2>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="card-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>
                                <!-- Form untuk mengubah status -->
                                <form method="POST" action="kelola_peminjaman.php">
                                    <input type="hidden" name="id_peminjaman" value="<?= $loan['id_peminjaman'] ?>">
                                    <select name="status" class="form-select">
                                        <option value="Pinjam" <?= $loan['status'] == 'Pinjam' ? 'selected' : '' ?>>Pinjam</option>
                                        <option value="Selesai" <?= $loan['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="Terlambat" <?= $loan['status'] == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary mt-2">Update Status</button>
                                </form>
                            </td>
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
