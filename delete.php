<?php
include_once 'config.php';
include_once 'Tagihan.php';

$database = new Database();
$db = $database->getConnection();
$tagihan = new Tagihan($db);

$tagihan->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

if($tagihan->delete()){
    header("Location: index.php?message=Tagihan berhasil dihapus");
} else{
    header("Location: index.php?message=Gagal menghapus tagihan");
}
?>