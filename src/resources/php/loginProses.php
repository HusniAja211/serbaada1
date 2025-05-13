<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

// Fungsi login
function login($conn, $email, $password) {
    // Cek apakah email ada di database
    $stmt = $conn->prepare("SELECT * FROM karyawan WHERE email_karyawan = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password yang dimasukkan dengan hash password yang ada di database
        if (password_verify($password, $user['password_karyawan'])) {
            // Set session variables
            session_start();
            $_SESSION['logged'] = [
                'id_karyawan' => $user['id_karyawan'],
                'email_karyawan' => $user['email_karyawan'],
                'nama_karyawan' => $user['username_karyawan'],
                'level_karyawan' => $user['level_karyawan'],
                'gambar_karyawan' => $user['gambar_karyawan'],
            ];
            
            // Redirect ke dashboard atau halaman beranda
            header("Location: ../views/pages/dasbor.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!'); window.history.back();</script>";
    }
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Panggil fungsi login
    login($conn, $email, $password);
}
?>
