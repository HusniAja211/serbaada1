<?php
require 'session.php';

// ==============================
// Koneksi Database
// ==============================
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// ==============================
// Handler: Delete (GET method)
// ==============================
if (isset($_GET['delete'])) {
    $id_member = $_GET['delete'];  // Ambil ID member yang ingin dihapus
    delete($conn, $id_member);     // Panggil fungsi delete
}

// ==============================
// Handler: Create/Edit (POST method)
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $data = [
        'nama_member' => $_POST['nama'],
        'no_telepon_member' => "+62" .  $_POST['nomorTelepon'],
        'point_member' => 0,
        'status_member' => 'aktif'
    ];

    if ($action === 'edit' && isset($_POST['id_member'])) {
        $result = update($conn, $_POST['id_member'], $data);
        $status = $result['success'] ? 'berhasil+diupdate' : 'gagal+update';
    } elseif ($action === 'create') {
        $result = create($conn, $data);
        $status = $result['success'] ? 'berhasil+ditambahkan' : 'gagal+create';
    } else {
        $status = 'aksi+takterdefinisi';
    }

    header("Location: member.php?status=$status");
    exit;
}

// ==============================
// Function: Ambil semua data member
// ==============================
function index($conn) {
    $data = [];
    try {
        $stmt = $conn->prepare("SELECT * FROM member");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return ['success' => true, 'data' => $data];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
    }
}

// ==============================
// Function: Tambah data member
// ==============================
function create($conn, $data) {
    // Cek duplikat nama produk
    $cekQuery = "SELECT * FROM member WHERE no_telepon_member = ?";
    $cekStmt = $conn->prepare($cekQuery);
    $cekStmt->bind_param("s", $data['no_telepon_member']);
    $cekStmt->execute();
    $cekStmt->store_result();

    if ($cekStmt->num_rows > 0) {
        echo "<script>alert('Nomor Telepon Sudah Terdaftar!.'); window.history.back();</script>";
        exit;
    }

    // Insert data baru
    try {
        $stmt = $conn->prepare("
            INSERT INTO member (nama_member, no_telepon_member, point_member, status_member)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssis", $data['nama_member'], $data['no_telepon_member'], $data['point_member'], $data['status_member']);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan!'); window.location.href = '../views/pages/member.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data!'); window.location.href = '../views/pages/member.php';</script>";
        }

    } catch (Exception $e) {
        echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location.href = '../views/pages/member.php';</script>";
    }
}


// ==============================
// Function: Ambil data berdasarkan ID
// ==============================
function find($conn, $id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM member WHERE id_member = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['success' => true, 'data' => $result->fetch_assoc()];
        } else {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
    }
}

// ==============================
// Function: Update data member
// ==============================
function update($conn, $id, $data) {

    // Cek duplikat nama produk
    $cekQuery = "SELECT * FROM member WHERE no_telepon_member = ?";
    $cekStmt = $conn->prepare($cekQuery);
    $cekStmt->bind_param("s", $data['no_telepon_member']);
    $cekStmt->execute();
    $cekStmt->store_result();

    if ($cekStmt->num_rows > 0) {
        echo "<script>alert('Nomor Telepon Sudah Terdaftar!.'); window.history.back();</script>";
        exit;
    }

    try {
        $stmt = $conn->prepare("
            UPDATE member
            SET nama_member = ?, no_telepon_member = ?, point_member = ?, status_member = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id_member = ?
        ");
        $stmt->bind_param("ssisi", $data['nama_member'], $data['no_telepon_member'], $data['point_member'], $data['status_member'], $id);
        $stmt->execute();

        return ['success' => true, 'message' => 'Data berhasil diperbarui'];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
    }
}

// ==============================
// Function: Hapus data member
// ==============================
function delete($conn, $id_member) {
    try {
        $stmt = $conn->prepare("DELETE FROM member WHERE id_member = ?");
        $stmt->bind_param("i", $id_member);
        $stmt->execute();

         if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href = '../views/pages/member.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location.href = '../views/pages/member.php';</script>";
        }

    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
    }
}
