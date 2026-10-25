CREATE DATABASE IF NOT EXISTS pengumpulan_tugas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pengumpulan_tugas;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  nim VARCHAR(30) DEFAULT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tugas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  mata_kuliah VARCHAR(120) NOT NULL,
  deskripsi TEXT,
  deadline DATETIME NOT NULL,
  created_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tugas_admin FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS pengumpulan (
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
  CONSTRAINT fk_kumpul_tugas FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
  CONSTRAINT fk_kumpul_mahasiswa FOREIGN KEY (mahasiswa_id) REFERENCES users(id) ON DELETE CASCADE
);
