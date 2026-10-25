<?php
session_start();

$host = 'localhost';
$db   = 'pengumpulan_tugas';
$user = 'root';
$pass = ''; // sesuaikan jika MySQL kamu memakai password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    exit('Koneksi database gagal. Pastikan MySQL aktif dan database sudah diimpor.');
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function wajib_login() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}
function wajib_admin() {
    wajib_login();
    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini khusus admin.');
    }
}
