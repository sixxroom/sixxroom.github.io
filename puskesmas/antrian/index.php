<?php
require "../config/db.php";

$data = $conn->query("
    SELECT a.*, p.nama 
    FROM antrian a
    JOIN pasien p ON a.pasien_id = p.id
    WHERE a.status = 'menunggu'
    ORDER BY a.nomor_antrian ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Daftar Antrian</h2>
<table border="1">
    <tr>
        <th>No.</th>
        <th>Nama Pasien</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($data as $d): ?>
        <tr>
            <td><?= $d['nomor_antrian'] ?></td>
            <td><?= $d['nama'] ?></td>
            <td><?= $d['status'] ?></td>
            <td>
                <a href="panggil.php?id=<?= $d['id'] ?>">Panggil</a>
                <a href="selesai.php?id=<?= $d['id'] ?>">Selesai Berobat</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>