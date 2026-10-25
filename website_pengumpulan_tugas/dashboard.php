<?php
require 'config.php'; wajib_login();
$user = $_SESSION['user'];
if ($user['role'] === 'admin') {
    $totalTugas = (int)$pdo->query('SELECT COUNT(*) FROM tugas')->fetchColumn();
    $totalKumpul = (int)$pdo->query('SELECT COUNT(*) FROM pengumpulan')->fetchColumn();
    $totalMhs = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='mahasiswa'")->fetchColumn();
    $stmt = $pdo->query("SELECT p.*, t.judul, t.mata_kuliah, u.nama, u.nim FROM pengumpulan p JOIN tugas t ON t.id=p.tugas_id JOIN users u ON u.id=p.mahasiswa_id ORDER BY p.dikumpulkan_at DESC LIMIT 8");
    $recent = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT t.*, p.id AS pengumpulan_id, p.status AS status_kumpul, p.nilai FROM tugas t LEFT JOIN pengumpulan p ON p.tugas_id=t.id AND p.mahasiswa_id=? ORDER BY t.deadline ASC");
    $stmt->execute([$user['id']]); $tugas = $stmt->fetchAll();
    $totalTugas = count($tugas);
    $totalKumpul = count(array_filter($tugas, fn($t) => !empty($t['pengumpulan_id'])));
    $belum = $totalTugas - $totalKumpul;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Aroby Assignment — Dashboard</title><link rel="stylesheet" href="style.css"></head><body>
<aside class="sidebar"><a class="brand" href="dashboard.php">Aroby <span>Assignment</span></a><div class="side-label">MENU UTAMA</div><a class="nav active" href="dashboard.php">▦ &nbsp; Dashboard</a><?php if($user['role']==='admin'): ?><a class="nav" href="tambah_tugas.php">＋ &nbsp; Tambah Tugas</a><a class="nav" href="pengumpulan_admin.php">▤ &nbsp; Pengumpulan</a><?php endif; ?><div class="side-bottom"><div class="avatar"><?=e(strtoupper(substr($user['nama'],0,1)))?></div><div class="user-mini"><b><?=e($user['nama'])?></b><small><?=e(ucfirst($user['role']))?></small></div><a class="logout" href="logout.php" title="Keluar">↪</a></div></aside>
<main class="main"><header class="topbar"><div><span class="eyebrow">RUANG AKADEMIK</span><h1>Dashboard</h1></div><div class="top-user"><span class="status-dot"></span><?=e($user['nama'])?></div></header>
<section class="welcome"><div><span class="pill">PORTAL AKADEMIK</span><h2>Selamat Datang di Aroby Assignment</h2><p><?= $user['role']==='admin' ? 'Kelola tugas, pantau pengumpulan, dan nikmati pengalaman belajar yang lebih terorganisir.' : 'Temukan tugas kuliah, kumpulkan tepat waktu, dan pantau progres belajarmu.' ?></p></div><div class="welcome-art"></div></section>
<section class="stats">
<div class="stat-card"><div class="stat-icon purple">▦</div><div><p><?= $user['role']==='admin'?'Total Tugas':'Tugas Tersedia' ?></p><strong><?=$totalTugas?></strong></div></div>
<div class="stat-card"><div class="stat-icon blue">⇧</div><div><p><?= $user['role']==='admin'?'Pengumpulan Masuk':'Sudah Dikumpulkan' ?></p><strong><?=$totalKumpul?></strong></div></div>
<div class="stat-card"><div class="stat-icon orange">◷</div><div><p><?= $user['role']==='admin'?'Mahasiswa Terdaftar':'Belum Dikumpulkan' ?></p><strong><?=$user['role']==='admin'?$totalMhs:$belum?></strong></div></div>
</section>
<?php if($user['role']==='admin'): ?>
<section class="panel"><div class="panel-head"><div><h3>Pengumpulan terbaru</h3><p>Aktivitas mahasiswa yang terakhir masuk</p></div><a class="text-link" href="pengumpulan_admin.php">Lihat semua →</a></div><div class="table-wrap"><table><thead><tr><th>MAHASISWA</th><th>TUGAS</th><th>MATA KULIAH</th><th>STATUS</th><th>WAKTU</th></tr></thead><tbody>
<?php foreach($recent as $r): ?><tr><td><b><?=e($r['nama'])?></b><small class="subline"><?=e($r['nim'])?></small></td><td><?=e($r['judul'])?></td><td><?=e($r['mata_kuliah'])?></td><td><span class="badge <?=e($r['status'])?>"><?=e(ucfirst($r['status']))?></span></td><td><?=e(date('d/m/Y H:i',strtotime($r['dikumpulkan_at'])))?></td></tr><?php endforeach; ?>
<?php if(!$recent): ?><tr><td colspan="5" class="empty">Belum ada pengumpulan tugas.</td></tr><?php endif; ?></tbody></table></div></section>
<?php else: ?>
<section class="panel"><div class="panel-head"><div><h3>Daftar tugas</h3><p>Periksa deadline dan status pengumpulanmu</p></div></div><div class="table-wrap"><table><thead><tr><th>JUDUL TUGAS</th><th>MATA KULIAH</th><th>DEADLINE</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody>
<?php foreach($tugas as $t): $lewat=strtotime($t['deadline'])<time() && empty($t['pengumpulan_id']); ?><tr><td><b><?=e($t['judul'])?></b><small class="subline"><?=e($t['deskripsi'])?></small></td><td><?=e($t['mata_kuliah'])?></td><td><?=e(date('d M Y, H:i',strtotime($t['deadline'])))?></td><td><?php if($t['pengumpulan_id']): ?><span class="badge <?=e($t['status_kumpul'])?>"><?=e(ucfirst($t['status_kumpul']))?></span><?php elseif($lewat): ?><span class="badge revisi">Deadline lewat</span><?php else: ?><span class="badge pending">Belum dikumpulkan</span><?php endif; ?></td><td><?php if(!$t['pengumpulan_id']): ?><a class="small-btn" href="kumpulkan.php?id=<?=$t['id']?>">Kumpulkan</a><?php else: ?><span class="muted">Terkirim<?php if($t['nilai']!==null): ?> · Nilai <?=e($t['nilai'])?><?php endif; ?></span><?php endif; ?></td></tr><?php endforeach; ?>
<?php if(!$tugas): ?><tr><td colspan="5" class="empty">Belum ada tugas. Cek lagi nanti ya.</td></tr><?php endif; ?></tbody></table></div></section>
<?php endif; ?>
<footer>© <?=date('Y')?> Aroby Assignment. All rights reserved.</footer></main></body></html>
