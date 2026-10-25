<?php
require 'config.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  nim VARCHAR(30) DEFAULT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS tugas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  mata_kuliah VARCHAR(120) NOT NULL,
  deskripsi TEXT,
  deadline DATETIME NOT NULL,
  created_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS pengumpulan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tugas_id INT NOT NULL,
  mahasiswa_id INT NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  file_asli VARCHAR(255) NOT NULL,
  catatan TEXT,
  status ENUM('terkirim','dinilai','revisi') NOT NULL DEFAULT 'terkirim',
  nilai DECIMAL(5,2) DEFAULT NULL,
  feedback TEXT,
  dikumpulkan_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unik_pengumpulan (tugas_id, mahasiswa_id),
  FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
  FOREIGN KEY (mahasiswa_id) REFERENCES users(id) ON DELETE CASCADE
)");

$email = 'admin@kampus.local';
$cek = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$cek->execute([$email]);
if (!$cek->fetch()) {
    $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute(['Administrator', $email, password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
}
echo '<h2>Setup berhasil!</h2><p>Akun admin: <b>admin@kampus.local</b></p><p>Password: <b>Admin123!</b></p><p>Silakan login lalu hapus file setup.php setelah selesai demi keamanan.</p><p><a href="login.php">Ke halaman login</a></p>';
