<?php
require "../config/db.php";

// Ambil nomor antrian terakhir
$last = $conn->query("SELECT MAX(nomor_antrian) AS n FROM antrian")->fetch();
$next = ($last['n'] ?? 0) + 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO antrian (pasien_id, nomor_antrian) VALUES (?, ?)");
    $stmt->execute([$_POST['pasien_id'], $next]);

    header("Location: index.php");
    exit();
}

// Ambil daftar pasien
$pasien = $conn->query("SELECT id, nama FROM pasien ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Daftar Antrian</h2>
<form method="POST">
    <label>Pilih Pasien</label>
    <select name="pasien_id" required>
        <?php foreach ($pasien as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nama'] ?></option>
        <?php endforeach; ?>
    </select>

    <p>Nomor Antrian berikutnya: <strong><?= $next ?></strong></p>

    <button type="submit">Daftar</button>
</form>
