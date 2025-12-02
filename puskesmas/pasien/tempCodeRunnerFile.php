<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require "../config/db.php";

// Pastikan PDO men-throw exception agar kita bisa tahu error
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Cek id ada dan numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // bisa redirect atau tampilkan pesan
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

try {
    // Mulai transaction supaya operasi terkait bisa di-handle aman
    $conn->beginTransaction();

    // Jika ada tabel transaksi yang mereferensi pasien, hapus transaksi dulu (opsional)
    // Sesuaikan nama tabel/kolom bila berbeda
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id_pasien = ?");
    $stmt->execute([$id]);
    // Jika kamu ingin tahu berapa baris terhapus:
    // $deletedTrans = $stmt->rowCount();

    // Hapus pasien
    $stmt2 = $conn->prepare("DELETE FROM pasien WHERE id = ?");
    $stmt2->execute([$id]);

    // cek apakah baris pasien benar-benar terhapus
    if ($stmt2->rowCount() === 0) {
        // tidak ada baris yang dihapus -> kemungkinan id tidak ada
        $conn->rollBack();
        // Kamu bisa set pesan error di session atau langsung echo
        $_SESSION['flash_error'] = "Gagal menghapus: data pasien tidak ditemukan.";
        header("Location: index.php");
        exit();
    }

    $conn->commit();

    $_SESSION['flash_success'] = "Data pasien berhasil dihapus.";
    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    // rollback jika terjadi error
    if ($conn->inTransaction()) $conn->rollBack();

    // Simpan pesan error sementara untuk debugging
    // Jangan tampilkan langsung ke user pada production, tapi berguna saat debug
    $_SESSION['flash_error'] = "Error saat menghapus: " . $e->getMessage();

    header("Location: index.php");
    exit();
}
?>