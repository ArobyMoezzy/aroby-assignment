<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($nama === '' || $nim === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Lengkapi data. Password minimal 8 karakter.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (nama,nim,email,password,role) VALUES (?,?,?,?,?)');
            $stmt->execute([$nama,$nim,$email,password_hash($password, PASSWORD_DEFAULT),'mahasiswa']);
            header('Location: login.php?daftar=berhasil'); exit;
        } catch (PDOException $ex) {
            $error = 'Email sudah terdaftar atau data tidak valid.';
        }
    }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Daftar — Aroby Assignment</title><link rel="stylesheet" href="style.css"></head><body class="auth-bg"><main class="auth-card"><div class="brand">Aroby <span>Assignment</span></div><h1>Buat akun mahasiswa</h1><p class="muted">Daftar untuk mulai mengumpulkan tugas.</p>
<?php if($error): ?><div class="alert"><?=e($error)?></div><?php endif; ?>
<form method="post"><label>Nama lengkap</label><input name="nama" required maxlength="100"><label>NIM</label><input name="nim" required maxlength="30"><label>Email</label><input type="email" name="email" required><label>Password (minimal 8 karakter)</label><input type="password" name="password" minlength="8" required><button class="btn full">Daftar</button></form><p class="muted center">Sudah punya akun? <a href="login.php">Masuk</a></p></main></body></html>
