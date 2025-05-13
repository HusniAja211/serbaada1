<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/vendor/autoload.php'; 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (!isset($_GET['id_produk'])) {
    http_response_code(400);
    echo 'ID Produk tidak ditemukan';
    exit;
}

$id_produk = $_GET['id_produk'];

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    http_response_code(404);
    echo 'Produk tidak ditemukan';
    exit;
}

$kodeBarcode = $produk['id_produk'];

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($kodeBarcode, $generator::TYPE_CODE_128);

header('Content-Type: image/png');
echo $barcode;
