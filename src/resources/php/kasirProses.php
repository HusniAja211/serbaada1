<?php
require 'session.php';


require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// Fungsi: redirect ke halaman utama kasir
function redirectToKasir() {
    header("Location: ./serbaada1/src/resources/views/kasir.php");
    exit;
}

// Fungsi: handle upload file gambar dan kembalikan nama file
function uploadGambar($gambar) {
    $fileName = time() . '_' . basename($gambar['name']);
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/public/resources/img/gambarKasir/';
    move_uploaded_file($gambar['tmp_name'], $targetDir . $fileName);
    return $fileName;
}

// Fungsi: ambil semua data kasir
function index($conn) {
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE level_karyawan = 'kasir' ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fungsi: create kasir baru
function store($conn, $email, $nama, $password, $confirmPassword, $gambar) {
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE email_karyawan = ? AND level_karyawan = 'kasir'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Password dan konfirmasi tid_kasirak cocok!'); window.history.back();</script>";
        exit;
    }

    if ($gambar['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Gagal mengupload gambar!'); window.history.back();</script>";
        exit;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $ext = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));
    if (!in_array($gambar['type'], $allowedTypes) || !in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>alert('Format gambar tid_kasirak valid_kasir!'); window.history.back();</script>";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $fileName = uploadGambar($gambar);
    $token = bin2hex(random_bytes(32));

    $query = "INSERT INTO karyawan (email_karyawan, username_karyawan, level_karyawan, password_karyawan, gambar_karyawan) VALUES (?, ?, 'kasir', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $email, $nama, $hashedPassword, $fileName);

    if ($stmt->execute()) {
        echo "<script>alert('Kasir berhasil ditambahkan!'); window.location.href = '../views/pages/kasir.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data ke database!'); window.history.back();</script>";
    }
    exit;
}

// Fungsi: update data kasir
function put($conn, $id_kasir, $email, $nama, $password, $gambar) {
    $check = $conn->prepare("SELECT id_karyawan FROM karyawan WHERE email_karyawan = ? AND id_karyawan != ? AND level_karyawan = 'kasir'");
    $check->bind_param("si", $email, $id_kasir);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan oleh kasir lain!'); window.history.back();</script>";
        exit;
    }

    $setPassword = '';
    $params = [$nama, $email];
    $types = "ss";

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $setPassword .= ", password_karyawan = ?";
        $params[] = $hashedPassword;
        $types .= "s";
    }

    if ($gambar['size'] > 0) {
        $fileName = uploadGambar($gambar);
        $setPassword .= ", gambar_karyawan = ?";
        $params[] = $fileName;
        $types .= "s";
    }

    $params[] = $id_kasir;
    $types .= "i";

    $query = "UPDATE karyawan SET username_karyawan = ?, email_karyawan = ? $setPassword, updated_at = NOW() WHERE id_karyawan = ? AND level_karyawan = 'kasir'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<script>alert('Kasir berhasil diperbarui!'); window.location.href = '../views/pages/kasir.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate kasir!'); window.history.back();</script>";
    }
    exit;
}

// Fungsi: hapus kasir berdasarkan ID_kasir
function delete($conn, $id_kasir) {
    $stmt = $conn->prepare("DELETE FROM karyawan WHERE id_karyawan = ? AND level_karyawan = 'kasir'");
    $stmt->bind_param("i", $id_kasir);
    $stmt->execute();
    header("Location: ../views/pages/kasir.php");
    exit;
}

// Fungsi: ambil data kasir berdasarkan ID_kasir
function find($conn, $id_kasir) {
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan = ? AND level_karyawan = 'kasir'");
    $stmt->bind_param("i", $id_kasir);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// HANDLE POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kasir = $_POST['id_kasir'] ?? null;
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gambar = $_FILES['gambarKasir'];

    if (!empty($password) && $password !== $confirmPassword) {
        echo "<script>alert('Password dan konfirmasi tidak cocok'); window.history.back();</script>";
        exit;
    }

    if ($id_kasir) {
        // HANYA kirim yang dibutuhkan ke put()
        put($conn, $id_kasir, $email, $nama, $password, $gambar);
    } else {
        store($conn, $email, $nama, $password, $confirmPassword, $gambar);
    }
    redirectToKasir();
}

// HANDLE DELETE
if (isset($_GET['delete'])) {
    delete($conn, $_GET['delete']);
    redirectToKasir();
}
