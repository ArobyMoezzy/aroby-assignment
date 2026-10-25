Aroby Assignment - WEBSITE PENGUMPULAN TUGAS MAHASISWA
Teknologi: PHP 8+, MySQL/MariaDB, HTML, CSS.

FITUR
- Registrasi dan login mahasiswa.
- Login admin.
- Admin menambah tugas dan deadline.
- Mahasiswa melihat daftar tugas dan mengunggah file.
- Admin melihat dan mengunduh file pengumpulan.
- Admin mengubah status, memberi nilai, dan feedback.
- Database relasional MySQL.

CARA MENJALANKAN DI WINDOWS DENGAN XAMPP
1. Install dan buka XAMPP.
2. Jalankan Apache dan MySQL.
3. Ekstrak folder website_pengumpulan_tugas ke C:\\xampp\\htdocs\\
   Pastikan menjadi C:\\xampp\\htdocs\\website_pengumpulan_tugas\\
4. Buka http://localhost/phpmyadmin
5. Pilih Import, pilih file database.sql, lalu klik Import/Go.
   Alternatif: kunjungi http://localhost/website_pengumpulan_tugas/setup.php
   untuk membuat tabel dan akun admin secara otomatis.
6. Pastikan config.php sesuai dengan pengaturan MySQL kamu. Default XAMPP biasanya
   username root dan password kosong.
7. Buka http://localhost/website_pengumpulan_tugas/setup.php satu kali untuk
   membuat akun admin (kalau belum dibuat).
8. Login admin:
   Email: 
   Password: 
9. Setelah setup berhasil, hapus setup.php.
10. Daftarkan akun mahasiswa melalui halaman Daftar.

CATATAN
- File unggahan disimpan di folder uploads dan dibuat otomatis saat upload pertama.
- Ukuran file maksimal 10 MB. Batas ini juga dipengaruhi pengaturan upload_max_filesize
  dan post_max_size di php.ini.
- Untuk produksi/hosting, aktifkan HTTPS, atur kredensial database yang kuat,
  lindungi folder uploads dari eksekusi skrip, tambahkan CSRF protection dan backup.
- Ini adalah starter project untuk lokal/tugas kuliah, belum diaudit untuk produksi.
