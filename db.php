<?php
$host = "localhost";
$db   = "velvet_vogue";
$user = "root";
$pass = "";

$conn = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
