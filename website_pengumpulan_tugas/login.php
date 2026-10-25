<?php
require 'config.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: dashboard.php'); exit;
    }
    $error = 'Email atau password salah.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login — Aroby Assignment</title><link rel="stylesheet" href="style.css"></head><body class="auth-bg"><main class="auth-card"><div class="brand">Aroby <span>Assignment</span></div><h1>Selamat datang</h1><p class="muted">Masuk untuk mengelola dan mengumpulkan tugas.</p>
<?php if(isset($_GET['daftar'])): ?><div class="success">Pendaftaran berhasil. Silakan masuk.</div><?php endif; ?>
<?php if($error): ?><div class="alert"><?=e($error)?></div><?php endif; ?>
<form method="post"><label>Email</label><input type="email" name="email" required autofocus><label>Password</label><input type="password" name="password" required><button class="btn full">Masuk</button></form><p class="muted center">Belum punya akun? <a href="register.php">Daftar mahasiswa</a></p><p class="hint">Admin demo dibuat lewat setup.php</p></main></body></html>
