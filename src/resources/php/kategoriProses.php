<?php
require 'session.php';

// Memasukkan koneksi database
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// Fungsi untuk mendapatkan semua kategori
function index($conn) {
    $stmt = $conn->prepare("SELECT * FROM kategori ORDER BY created_at ASC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk menyimpan kategori baru
function store($conn, $nama_kategori, $deskripsi) {
    // Cek apakah kategori sudah ada
    $stmt = $conn->prepare("SELECT nama_kategori FROM kategori WHERE nama_kategori = ?");
    $stmt->bind_param("s", $nama_kategori);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Kategori sudah ada!'); window.history.back();</script>";
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, deskripsi_kategori) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_kategori, $deskripsi);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href = '../views/pages/kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!'); window.location.href = '../views/pages/kategori.php';</script>";
    }
}

// Fungsi untuk menemukan kategori berdasarkan ID
function find($conn, $id_kategori) {
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Fungsi untuk memperbarui kategori
function put($conn, $id_kategori, $nama_kategori, $deskripsi) {
    
    // Cek duplikat nama produk
    $cekQuery = "SELECT id_kategori FROM kategori WHERE nama_kategori = ? AND id_kategori != ?";
    $cekStmt = $conn->prepare($cekQuery);
    $cekStmt->bind_param("si", $nama_kategori, $id_kategori);
    $cekStmt->execute();
    $cekStmt->store_result();

    if ($cekStmt->num_rows > 0) {
        echo "<script>alert('Nama Kategori sudah ada. Silakan gunakan nama lain.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ?, deskripsi_kategori = ? WHERE id_kategori = ?");
    $stmt->bind_param("ssi", $nama_kategori, $deskripsi, $id_kategori);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = '../views/pages/kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); window.location.href = '../views/pages/kategori.php';</script>";
    }
}

function delete($conn, $id_kategori) {
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param("i", $id_kategori);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = '../views/pages/kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href = '../views/pages/kategori.php';</script>";
    }
}

// Memproses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kategori = $_POST['id_kategori'] ?? null;
    $nama_kategori = $_POST['namaKategori'];
    $deskripsi = $_POST['deskripsi'];

    // Validasi input (contoh sederhana)
    if (empty($nama_kategori) || empty($deskripsi)) {
        echo "<script>alert('Nama kategori dan deskripsi harus diisi!'); window.history.back();</script>";
        exit;
    }

    // Jika ID kategori ada, maka lakukan update, jika tidak maka simpan data baru
    if ($id_kategori) {
        put($conn, $id_kategori, $nama_kategori, $deskripsi);
    } else {
        store($conn, $nama_kategori, $deskripsi);
    }
}

if (isset($_GET['delete'])) {
    $id_kategori = $_GET['delete'];  // Ambil ID kategori yang ingin dihapus
    delete($conn, $id_kategori);     // Panggil fungsi delete
}

