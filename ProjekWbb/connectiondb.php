<?php
$host     = "localhost";
$username = "root";
$password = ""; 
$dbname   = "proyek_timfdel"; // Sesuaikan dengan nama database Anda di Laragon

try {
    // KUNCI: Harus pakai $pdo agar sinkron dengan index.php Anda
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>