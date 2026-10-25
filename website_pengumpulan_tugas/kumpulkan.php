<?php
require 'config.php'; wajib_login();
if($_SESSION['user']['role']!=='mahasiswa'){header('Location: dashboard.php');exit;}
$id=(int)($_GET['id']??0);
$s=$pdo->prepare('SELECT * FROM tugas WHERE id=?');$s->execute([$id]);$tugas=$s->fetch();
if(!$tugas){http_response_code(404);exit('Tugas tidak ditemukan.');}
$cek=$pdo->prepare('SELECT id FROM pengumpulan WHERE tugas_id=? AND mahasiswa_id=?');$cek->execute([$id,$_SESSION['user']['id']]);
if($cek->fetch()){header('Location: dashboard.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $catatan=trim($_POST['catatan']??'');
  if(strtotime($tugas['deadline'])<time()) $error='Deadline tugas sudah lewat.';
  elseif(empty($_FILES['berkas'])||$_FILES['berkas']['error']!==UPLOAD_ERR_OK) $error='Pilih file tugas yang akan diunggah.';
  else {
    $f=$_FILES['berkas']; $ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION)); $allowed=['pdf','doc','docx','ppt','pptx','xls','xlsx','zip','rar','py','java','txt','jpg','png'];
    if($f['size']>10*1024*1024) $error='Ukuran file maksimal 10 MB.';
    elseif(!in_array($ext,$allowed,true)) $error='Format file tidak diizinkan.';
    else {
      $dir=__DIR__.'/uploads'; if(!is_dir($dir)) mkdir($dir,0750,true);
      $nama=bin2hex(random_bytes(16)).'.'.$ext; $tujuan=$dir.'/'.$nama;
      if(move_uploaded_file($f['tmp_name'],$tujuan)){
        try{$ins=$pdo->prepare('INSERT INTO pengumpulan (tugas_id,mahasiswa_id,nama_file,file_asli,catatan) VALUES (?,?,?,?,?)');$ins->execute([$id,$_SESSION['user']['id'],$nama,basename($f['name']),$catatan]);header('Location: dashboard.php');exit;}
        catch(PDOException $ex){@unlink($tujuan);$error='Pengumpulan gagal. Mungkin tugas ini sudah pernah dikumpulkan.';}
      }else $error='File gagal disimpan. Periksa izin folder uploads.';
    }
  }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Kumpulkan Tugas — Aroby Assignment</title><link rel="stylesheet" href="style.css"></head><body><main class="main standalone"><header class="topbar"><div><span class="eyebrow">PENGUMPULAN TUGAS</span><h1>Unggah tugas</h1></div><a class="text-link" href="dashboard.php">← Dashboard</a></header><section class="panel form-panel"><div class="task-detail"><span class="pill"><?=e($tugas['mata_kuliah'])?></span><h2><?=e($tugas['judul'])?></h2><p><?=nl2br(e($tugas['deskripsi']))?></p><p class="muted">Deadline: <b><?=e(date('d M Y, H:i',strtotime($tugas['deadline'])))?></b></p></div><?php if($error): ?><div class="alert"><?=e($error)?></div><?php endif; ?><form method="post" enctype="multipart/form-data"><div class="field"><label>File tugas (maks. 10 MB)</label><input type="file" name="berkas" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.py,.java,.txt,.jpg,.png"><small class="muted">Format: PDF, Word, PowerPoint, Excel, ZIP/RAR, Python, Java, TXT, JPG, PNG.</small></div><div class="field"><label>Catatan (opsional)</label><textarea name="catatan" rows="4" placeholder="Catatan untuk dosen..."></textarea></div><button class="btn">Kirim Pengumpulan</button></form></section></main></body></html>
