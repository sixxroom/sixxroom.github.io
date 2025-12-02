<?php
require "../config/db.php";

$id = $_GET['id'];

// Ambil data antrian
$a = $conn->query("SELECT pasien_id FROM antrian WHERE id=$id")->fetch();
$pasien_id = $a['pasien_id'];

// Tandai selesai
$conn->query("UPDATE antrian SET status='selesai' WHERE id=$id");

// Redirect langsung ke buat transaksi
header("Location: ../transaksi/tambah.php?pasien_id=".$pasien_id);
exit();