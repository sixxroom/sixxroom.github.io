<?php
require "../config/db.php";

$id = $_GET['id'];
$conn->query("UPDATE antrian SET status='dipanggil' WHERE id=$id");

header("Location: index.php");