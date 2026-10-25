<?php
require 'config.php'; wajib_admin();
$id=(int)($_GET['id']??0);
$s=$pdo->prepare('SELECT nama_file,file_asli FROM pengumpulan WHERE id=?');$s->execute([$id]);$f=$s->fetch();
if(!$f){http_response_code(404);exit('Berkas tidak ditemukan.');}
$path=__DIR__.'/uploads/'.$f['nama_file'];
if(!is_file($path)){http_response_code(404);exit('File tidak tersedia.');}
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.str_replace('"','',basename($f['file_asli'])).'"');
header('Content-Length: '.filesize($path));
readfile($path); exit;
