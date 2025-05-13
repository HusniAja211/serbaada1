<?php
session_start();
header('Content-Type: application/json');

// Ambil JSON dari body request
$input = json_decode(file_get_contents('php://input'), true);
$id_produk = $input['id_produk'] ?? null;

// Validasi jika ID produk tidak ditemukan dalam request
if (!$id_produk) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID produk tidak ditemukan dalam request.'
    ]);
    exit;
}

// Koneksi ke database
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// Cek produk di database
$query = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$query->bind_param("s", $id_produk); // Bind parameter untuk keamanan
$query->execute();
$result = $query->get_result();
$produk = $result->fetch_assoc(); // Ambil hasil sebagai array asosiatif

// Jika produk tidak ditemukan, kirim pesan error
if (!$produk) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Produk tidak ditemukan di database.'
    ]);
    exit;
}

// Siapkan session pesanan jika belum ada
if (!isset($_SESSION['pesanan'])) {
    $_SESSION['pesanan'] = [];
}

// Tambahkan ke keranjang
if (isset($_SESSION['pesanan'][$id_produk])) {
    // Jika produk sudah ada, tambahkan jumlah
    $_SESSION['pesanan'][$id_produk]['jumlah_dipesan'] += 1;
} else {
    // Jika produk belum ada, tambahkan produk lengkap ke session
    $_SESSION['pesanan'][$id_produk] = [
        'id_produk' => $produk['id_produk'],
        'nama_produk' => $produk['nama_produk'],
        'tanggal_expired' => $produk['tanggal_expired'],
        'stok_produk' => $produk['stok_produk'],
        'uang_modal_produk' => $produk['uang_modal_produk'],
        'harga_jual_produk' => $produk['harga_jual_produk'],
        'keuntungan_produk' => $produk['keuntungan_produk'],
        'fid_kategori' => $produk['fid_kategori'],
        'gambar_produk' => $produk['gambar_produk'],
        'deskripsi_produk' => $produk['deskripsi_produk'],
        'created_at' => $produk['created_at'],
        'updated_at' => $produk['updated_at'],
        'jumlah_dipesan' => 1 // Set jumlah awal saat produk ditambahkan pertama kali
    ];
}

// Kirim respon sukses dengan produk yang ditambahkan
echo json_encode([
    'status' => 'success',
    'message' => 'Produk berhasil ditambahkan ke pesanan.',
    'produk' => $_SESSION['pesanan'][$id_produk]
]);
?>
