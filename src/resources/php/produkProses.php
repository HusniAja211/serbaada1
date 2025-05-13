<?php 
require 'session.php';

use Picqer\Barcode\BarcodeGeneratorPNG;
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/vendor/autoload.php'; 

// ==============================
// Koneksi Database
// ==============================
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

/**
 * Menampilkan semua produk dari tabel `produk`.
 *
 * @param mysqli $conn
 * @return array
 */
function index($conn) {
    $query = "SELECT * FROM produk";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Menyimpan produk baru ke database, unggah gambar, dan buat barcode.
 *
 * @param mysqli $conn
 * @param array $data
 */
function store($conn, $data) {
    $nama = ucwords(strtolower(trim($data['nama_produk'])));
    $expired    = $data['tanggal_expired'];
    $stok       = $data['stok_produk'];
    $modal      = $data['uang_modal_produk'];
    $harga_jual = $data['harga_jual_produk'];
    $keuntungan = $harga_jual - $modal;
    $kategori   = $data['fid_kategori'];
    $deskripsi  = $data['deskripsi_produk'];
    $tanggalMasuk = date('Ymd');

    // Cek duplikat nama produk
    $cekQuery = "SELECT nama_produk FROM produk WHERE nama_produk = ?";
    $cekStmt = $conn->prepare($cekQuery);
    $cekStmt->bind_param("s", $nama);
    $cekStmt->execute();
    $cekStmt->store_result();

    if ($cekStmt->num_rows > 0) {
        echo "<script>alert('Nama produk sudah ada. Silakan gunakan nama lain.'); window.history.back();</script>";
        exit;
    }

    // Simpan data produk tanpa gambar dulu
    $query = "INSERT INTO produk 
        (nama_produk, tanggal_expired, stok_produk, uang_modal_produk, harga_jual_produk, keuntungan_produk, fid_kategori, gambar_produk, deskripsi_produk) 
        VALUES (?, ?, ?, ?, ?, ?, ?, '', ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssidddis", $nama, $expired, $stok, $modal, $harga_jual, $keuntungan, $kategori, $deskripsi);
    $stmt->execute();

    $id_produk = $stmt->insert_id;

    // Membuat nama gambar: id_tanggal.ext
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar = $id_produk . "_" . $tanggalMasuk . "." . $ext;

    // Upload gambar ke direktori
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/public/resources/img/gambarProduk/';
    move_uploaded_file($tmp_name, $upload_dir . $gambar);

    // Update nama gambar di database
    $updateQuery = "UPDATE produk SET gambar_produk = ? WHERE id_produk = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $gambar, $id_produk);
    $updateStmt->execute();

    // Generate barcode dan simpan sebagai gambar
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($id_produk, $generator::TYPE_CODE_128);

    $barcodePath = $_SERVER['DOCUMENT_ROOT'] . "/serbaada1/public/resources/img/gambarBarcode/{$id_produk}.png";
    file_put_contents($barcodePath, $barcode);

    echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='/serbaada1/src/resources/views/pages/produk.php';</script>";
}

/**
 * Mengambil satu produk berdasarkan ID.
 *
 * @param mysqli $conn
 * @param int $id_produk
 * @return array|null
 */
function find($conn, $id_produk) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Mengupdate data produk. Jika gambar baru diunggah, maka gambar juga akan diubah.
 *
 * @param mysqli $conn
 * @param int $id_produk
 * @param array $data
 */
function put($conn, $id_produk, $data) {
    $nama         = $data['nama_produk'];
    $expired      = $data['tanggal_expired'];
    $stok         = $data['stok_produk'];
    $modal        = $data['uang_modal_produk'];
    $harga_jual   = $data['harga_jual_produk'];
    $keuntungan   = $harga_jual - $modal;
    $kategori     = $data['fid_kategori'];
    $deskripsi    = $data['deskripsi_produk'];

    // Cek duplikat nama produk
    $cekQuery = "SELECT id_produk FROM produk WHERE nama_produk = ? AND id_produk != ?";
    $cekStmt = $conn->prepare($cekQuery);
    $cekStmt->bind_param("si", $nama, $id_produk);
    $cekStmt->execute();
    $cekStmt->store_result();

    if ($cekStmt->num_rows > 0) {
        echo "<script>alert('Nama produk sudah ada. Silakan gunakan nama lain.'); window.history.back();</script>";
        exit;
    }



    if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar     = $_FILES['gambar']['name'];
        $tmp_name   = $_FILES['gambar']['tmp_name'];
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/public/resources/img/gambarProduk/';
        move_uploaded_file($tmp_name, $upload_dir . $gambar);

        $query = "UPDATE produk SET 
            nama_produk = ?, tanggal_expired = ?, stok_produk = ?, uang_modal_produk = ?, harga_jual_produk = ?, keuntungan_produk = ?, fid_kategori = ?, gambar_produk = ?, deskripsi_produk = ?
            WHERE id_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssidddissi", $nama, $expired, $stok, $modal, $harga_jual, $keuntungan, $kategori, $gambar, $deskripsi, $id_produk);
    } else {
        // Update tanpa gambar
        $query = "UPDATE produk SET 
            nama_produk = ?, tanggal_expired = ?, stok_produk = ?, uang_modal_produk = ?, harga_jual_produk = ?, keuntungan_produk = ?, fid_kategori = ?, deskripsi_produk = ?
            WHERE id_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssidddisi", $nama, $expired, $stok, $modal, $harga_jual, $keuntungan, $kategori, $deskripsi, $id_produk);
    }

    $stmt->execute();

        echo "<script>alert('Data Produk Diubah!'); window.location.href='/serbaada1/src/resources/views/pages/produk.php';</script>";

}

/**
 * Menghapus produk berdasarkan ID.
 *
 * @param mysqli $conn
 * @param int $id_produk
 */
function delete($conn, $id_produk) {
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk=?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    header("Location: /serbaada1/src/resources/views/pages/produk.php");
    exit;
}

// Handler hapus via query string
if (isset($_GET['delete'])) {
    delete($conn, $_GET['delete']);
}

/**
 * Mengambil semua data kategori dari tabel `kategori`.
 *
 * @param mysqli $conn
 * @return array
 */
function selectKategori($conn) {
    $stmt = $conn->prepare("SELECT id_kategori, nama_kategori FROM kategori");
    $stmt->execute();
    $result = $stmt->get_result();

    return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// ==============================
// Request Handler
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $data = [
        'nama_produk' => $_POST['nama_produk'],
        'tanggal_expired' => $_POST['tanggal_expired'],
        'stok_produk' => $_POST['stok_produk'],
        'uang_modal_produk' => $_POST['uang_modal_produk'],
        'harga_jual_produk' => $_POST['harga_jual_produk'],
        'fid_kategori' => $_POST['kategori'],
        'deskripsi_produk' => $_POST['deskripsi']
    ];

    if ($action === 'store') {
        store($conn, $data);
    } elseif ($action === 'update') {
        $id_produk = $_POST['id_produk'];
        put($conn, $id_produk, $data);
    }
}

/**
 * Mengembalikan URL path ke folder gambar produk.
 *
 * @return string
 */
function urlGambar(){
    return '/serbaada1/public/resources/img/gambarProduk/';
}
