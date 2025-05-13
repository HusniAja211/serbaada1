<?php
require "session.php";

require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// ---------------------------
// Fungsi Upload Gambar
function uploadGambar($file) {
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/public/resources/img/gambarAdmin/';
    
    // Ambil ekstensi dari file asli
    $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExt, $allowedTypes)) {
        $fileName = basename($_POST['email']) . '.' . $fileExt;
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $fileName;
        }
    }
    return null;
}


// ---------------------------
// Fungsi Create Admin
function store($conn, $data, $file) {
    $email = $data['email'];
    $username = $data['nama'];
    $password = $data['password'];
    $confirmPassword = $data['confirmPassword'];

    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE email_karyawan=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>
            alert('Email Sudah Terdaftar!');
            window.location.href = '/serbaada1/src/resources/views/pages/createAdmin.php';
        </script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>
            alert('Password dan konfirmasi tidak cocok!');
            window.location.href = '/serbaada1/src/resources/views/pages/createAdmin.php';
        </script>";
        exit;
    }

    $gambarName = uploadGambar($file);
    if (!$gambarName) {
        die("Gagal upload gambar.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO karyawan (email_karyawan, username_karyawan, level_karyawan, password_karyawan, gambar_karyawan) VALUES (?, ?, 'admin', ?, ?)");
    $stmt->bind_param("ssss", $email, $username, $hashedPassword, $gambarName);
    $stmt->execute();
    header("Location: /serbaada1/src/resources/views/pages/admin.php");
    exit;
        exit;
}

// ---------------------------
// Fungsi Update Admin
function put($conn, $data, $file) {
    $id = $data['id_karyawan'];
    $email = $data['email'];
    $username = $data['nama'];
    $password = $data['password'];
    $confirmPassword = $data['confirmPassword'];

    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE email_karyawan=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>
            alert('Email Sudah Terdaftar!');
            window.location.href = '/serbaada1/src/resources/views/pages/createAdmin.php';
        </script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE email_karyawan=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($password !== $confirmPassword) {
        die("Password dan konfirmasi tidak cocok!");
    }

    $gambarName = uploadGambar($file);
    if (!$gambarName) {
        die("Gagal upload gambar.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE karyawan SET email_karyawan=?, username_karyawan=?, password_karyawan=?, gambar_karyawan=?, updated_at=NOW() WHERE id_karyawan=?");
    $stmt->bind_param("ssssi", $email, $username, $hashedPassword, $gambarName, $id);
    $stmt->execute();
    header("Location: /serbaada1/src/resources/views/pages/admin.php");
    exit;
}

// ---------------------------
// Fungsi Delete Admin
function delete($conn, $id_karyawan) {
    $stmt = $conn->prepare("DELETE FROM karyawan WHERE id_karyawan=?");
    $stmt->bind_param("i", $id_karyawan);
    $stmt->execute();
    header("Location: /serbaada1/src/resources/views/pages/admin.php");
    exit;
}

// ---------------------------
// Fungsi Get Semua Admin
function index($conn) {
    $result = $conn->query("SELECT * FROM karyawan WHERE level_karyawan = 'admin' ORDER BY id_karyawan DESC");
    $admins = [];
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
    return $admins;
}

// ---------------------------
// Fungsi Get Admin by ID
function findAdminById($conn, $id_karyawan) {
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE id_karyawan=?");
    $stmt->bind_param("i", $id_karyawan);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// ---------------------------
// Eksekusi Aksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_karyawan']) && !empty($_POST['id_karyawan'])) {
        put($conn, $_POST, $_FILES['gambarAdmin']);
    } else {
        store($conn, $_POST, $_FILES['gambarAdmin']);
    }
}

if (isset($_GET['delete'])) {
    delete($conn, $_GET['delete']);
}

// Untuk dipakai di store.php
if (isset($_GET['id_karyawan'])) {
    $admin = findAdminById($conn, $_GET['id_karyawan']);
}
