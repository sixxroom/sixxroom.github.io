<?php
require "../config/db.php";

$username = "admin";
$password = "password123";
$fullname = "Administrator";
$role = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, role, fullname)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute([$username, $hash, $role, $fullname]);
    echo "Admin berhasil dibuat!<br>";
    echo "Username: $username<br>Password: $password";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
