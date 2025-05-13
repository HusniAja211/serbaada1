<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
require "../../../vendor/autoload.php";

// === STEP 1: VALIDASI EMAIL & KIRIM OTP ===
if (isset($_POST['valemail'])) {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Query pertama: Validasi email
    $stmt_check_email = $conn->prepare("SELECT id_karyawan FROM karyawan WHERE email_karyawan = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows === 1) {
        // Update kode OTP dan expiry_otp di database
        $stmt_update_otp = $conn->prepare("UPDATE karyawan SET kode_otp = ?, expiry_otp = ? WHERE email_karyawan = ?");
        $stmt_update_otp->bind_param("sss", $otp, $otp_expiry, $email);        
        $stmt_update_otp->execute();
        $stmt_update_otp->close();  // Pastikan untuk menutup statement setelah eksekusi

        // Kirim OTP via email menggunakan PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'husnimubarakportofolio@gmail.com';        
        $mail->Password = 'vimw qadp yrbx qyhm';          
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('husnimubarakportofolio@gmail.com', 'SerbaAda!');
        $mail->addAddress($email);
        $mail->Subject = 'Kode OTP Reset Password';
        $mail->Body = "Kode OTP Anda adalah: $otp. Berlaku 10 menit. Mohon jangan berikan kode ini kepada siapapun.";

        if ($mail->send()) {
            $_SESSION['step'] = 2;
            $_SESSION['email_reset'] = $email;
        } else {
            $_SESSION['error'] = "Gagal mengirim OTP: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan.";
    }

    // Pastikan statement pertama ditutup sebelum redirect
    $stmt_check_email->close();
    header("Location: ../views/pages/forgetPassword.php");
    exit();
}

// === STEP 2: VERIFIKASI OTP DAN RESET PASSWORD ===
elseif (isset($_POST['update_password'])) {
    if (!isset($_SESSION['email_reset'])) {
        $_SESSION['error'] = "Sesi email tidak ditemukan.";
        header("Location: ../views/pages/login.php");
        exit();
    }

    $email = $_SESSION['email_reset'];
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok.";
        header("Location: ../views/pages/login.php");
        exit();
    }

    // Query untuk mendapatkan OTP dan expiry_otp dari database
    $stmt_get_otp = $conn->prepare("SELECT kode_otp, expiry_otp FROM karyawan WHERE email_karyawan = ?");
    $stmt_get_otp->bind_param("s", $email);
    $stmt_get_otp->execute();
    $stmt_get_otp->bind_result($db_otp, $db_expiry);
    $stmt_get_otp->fetch();
    $stmt_get_otp->close();  // Pastikan statement ditutup setelah eksekusi

    if ($db_otp === $otp && strtotime($db_expiry) > time()) {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        // Update password dan reset OTP
        $stmt_update_password = $conn->prepare("UPDATE karyawan SET password_karyawan = ?, kode_otp = NULL, expiry_otp = NULL WHERE email_karyawan = ?");
        $stmt_update_password->bind_param("ss", $hashed, $email);
        $stmt_update_password->execute();
        $stmt_update_password->close();  // Pastikan untuk menutup statement setelah eksekusi

        unset($_SESSION['step']);
        unset($_SESSION['email_reset']);
        $_SESSION['success'] = "Password berhasil diubah.";
        header("Location: ../views/pages/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Kode OTP salah atau sudah kedaluwarsa.";
        header("Location: ../views/pages/login.php");
        exit();
    }
}

// === BATALKAN ===
elseif (isset($_POST['cancel'])) {
    unset($_SESSION['step']);
    unset($_SESSION['email_reset']);
    header("Location: ../views/pages/login.php");
    exit();
}
?>
