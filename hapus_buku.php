<?php
require 'config.php';
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

try {
    // Mulai transaksi
    $pdo->beginTransaction();

    // Hapus data peminjaman terkait buku
    $stmt = $pdo->prepare("DELETE FROM peminjaman WHERE id_buku = ?");
    $stmt->execute([$id]);

    // Hapus data buku
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = ?");
    $stmt->execute([$id]);

    // Commit transaksi
    $pdo->commit();

    // Redirect ke halaman admin
    header('Location: admin.php');
    exit;
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    $pdo->rollBack();
    echo "Gagal menghapus buku: " . $e->getMessage();
}
