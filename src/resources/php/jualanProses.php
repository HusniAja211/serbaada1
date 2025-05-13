<?php
require 'session.php';
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

date_default_timezone_set('Asia/Jakarta');

// ==============================
// Inisialisasi Session
// ==============================
if (!isset($_SESSION['pesanan']) || !is_array($_SESSION['pesanan'])) {
    $_SESSION['pesanan'] = [];
}
if (!isset($_SESSION['pembayaran']) || !is_array($_SESSION['pembayaran'])) {
    $_SESSION['pembayaran'] = [];
}

// ==============================
// Fungsi
// ==============================
function hitungPembayaran() {
    $pesanan = $_SESSION['pesanan'];
    $subtotal = 0;
    foreach ($pesanan as $item) {
        $subtotal += $item['harga_jual_produk'] * $item['jumlah_dipesan'];
    }

    $pakaiPoint = $_SESSION['pembayaran']['pakaiPoint'] ?? 0;
    $_SESSION['pembayaran']['diskonPoint'] = $diskonPoint = min($pakaiPoint * 100, $subtotal * 0.3);
    $afterDiskon = $subtotal - $diskonPoint;
    $_SESSION['pembayaran']['pajak'] = $pajak = $afterDiskon * 0.1;
    $total = $afterDiskon + $pajak;

    $_SESSION['pembayaran']['subtotal'] = $subtotal;
    $_SESSION['pembayaran']['total'] = $total;
    $_SESSION['pembayaran']['poin_didapat'] = !empty($_SESSION['pembayaran']['no_telepon_member']) ? floor($total * 0.10) : 0;
}

function cekPoinMember($conn, $noTelepon) {
    $stmt = $conn->prepare("SELECT id_member, point_member FROM member WHERE no_telepon_member = ?");
    $stmt->bind_param("s", $noTelepon);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $_SESSION['pembayaran']['id_member'] = $row['id_member'];
        $_SESSION['pembayaran']['point_member'] = $row['point_member'];
        $_SESSION['pembayaran']['no_telepon_member'] = $noTelepon;
    } else {
        $_SESSION['pembayaran']['point_member'] = 0;
        unset($_SESSION['pembayaran']['no_telepon_member']);
    }
    $stmt->close();
}

function handleCartAction($id_produk, $action) {
    if (!isset($_SESSION['pesanan'][$id_produk])) return;

    switch ($action) {
        case 'tambah':
            $_SESSION['pesanan'][$id_produk]['jumlah_dipesan']++;
            break;
        case 'kurang':
            if (--$_SESSION['pesanan'][$id_produk]['jumlah_dipesan'] <= 0) {
                unset($_SESSION['pesanan'][$id_produk]);
            }
            break;
        case 'hapus':
            unset($_SESSION['pesanan'][$id_produk]);
            break;
    }
}

function addOrUpdateProduct($conn, $id_produk, $quantity) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();

    if ($produk = $stmt->get_result()->fetch_assoc()) {
        if (isset($_SESSION['pesanan'][$id_produk])) {
            $_SESSION['pesanan'][$id_produk]['jumlah_dipesan'] += $quantity;
        } else {
            $produk['jumlah_dipesan'] = $quantity;
            $_SESSION['pesanan'][$id_produk] = $produk;
        }
    }
    $stmt->close();
}

// ==============================
// Handler: Bayar
// ==============================
if (isset($_POST['bayar'])) {
    $pesanans = $_SESSION['pesanan'] ?? [];
    $fid_member = $_SESSION['pembayaran']['id_member'] ?? null;
    $fid_karyawan = $_SESSION['user']['id_karyawan'] ?? 1;
    $total = $_SESSION['pembayaran']['total'] ?? 0;
    $uang_dibayar = (float)($_POST['uang_dibayar'] ?? 0);

    if (empty($pesanans)) {
        $_SESSION['error'] = 'Keranjang kosong!';
        header('Location: ../views/pages/jualan.php'); exit;
    }

    if ($uang_dibayar < $total) {
        $_SESSION['error'] = 'Uang dibayarkan kurang! Kekurangan: Rp' . number_format($total - $uang_dibayar, 0, ',', '.');
        header('Location: ../views/pages/jualan.php'); exit;
    }

    try {
        foreach ($pesanans as $item) {
            $stmt = $conn->prepare("INSERT INTO transaksi (tanggal_transaksi, total_harga_transaksi, fid_karyawan, fid_produk, fid_member, total_keuntungan)
                                    VALUES (NOW(), ?, ?, ?, ?, ?)");
            $total_harga = $item['harga_jual_produk'] * $item['jumlah_dipesan'];
            $keuntungan = ($item['harga_jual_produk'] - $item['uang_modal_produk']) * $item['jumlah_dipesan'];
            $stmt->bind_param("diiid", $total_harga, $fid_karyawan, $item['id_produk'], $fid_member, $keuntungan);
            if (!$stmt->execute()) throw new Exception($stmt->error);
            $stmt->close();
        }

        foreach ($pesanans as $item) {
            $stmt = $conn->prepare("UPDATE produk SET stok_produk = stok_produk - ? WHERE id_produk = ?");
            $stmt->bind_param("ii", $item['jumlah_dipesan'], $item['id_produk']);
            if (!$stmt->execute()) throw new Exception($stmt->error);
            $stmt->close();
        }

        if ($fid_member) {
            $poin_didapat = $_SESSION['pembayaran']['poin_didapat'];
            $poin_dipakai = $_SESSION['pembayaran']['pakaiPoint'] ?? 0;
            $poin_akhir = $poin_didapat - $poin_dipakai;

            $stmt = $conn->prepare("UPDATE member SET point_member = point_member + ? WHERE id_member = ?");
            $stmt->bind_param("ii", $poin_akhir, $fid_member);
            if (!$stmt->execute()) throw new Exception($stmt->error);
            $stmt->close();
        }

        $_SESSION['receipt'] = [
            'total' => $total,
            'uang_dibayar' => $uang_dibayar,
            'kembalian' => $uang_dibayar - $total,
            'items' => $pesanans
        ];

        // unset($_SESSION['pesanan'], $_SESSION['pembayaran']);
        header('Location: ../views/pages/setelahBayar.php'); exit;

    } catch (Exception $e) {
        $_SESSION['error'] = 'Transaksi gagal: ' . $e->getMessage();
        error_log('Transaction error: ' . $e->getMessage());
        header('Location: ../views/pages/jualan.php'); exit;
    }
}

// ==============================
// Handler: Cek Point Member
// ==============================
if (isset($_POST['btnCekPoint']) && isset($_POST['no_telepon_member'])) {
    cekPoinMember($conn, $_POST['no_telepon_member']);
    hitungPembayaran();
    header("Location: ../views/pages/jualan.php"); exit;
}

// ==============================
// Handler: Redeem Point
// ==============================
if (isset($_POST['terapkanPoint'])) {
    $_SESSION['pembayaran']['pakaiPoint'] = max(0, (int)$_POST['pakaiPoint']);
    hitungPembayaran();
    header("Location: ../views/pages/jualan.php"); exit;
}

// ==============================
// Handler: Cart Operation
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_produk = (int)$_POST['id_produk'];
    if (isset($_POST['action'])) {
        handleCartAction($id_produk, $_POST['action']);
    } else {
        $jumlah = isset($_POST['jumlah_produk']) ? max(1, (int)$_POST['jumlah_produk']) : 1;
        addOrUpdateProduct($conn, $id_produk, $jumlah);
    }
    hitungPembayaran();
    header("Location: ../views/pages/jualan.php"); exit;
}

// Hapus session jika tombol "Kembali" ditekan
if (isset($_GET['reset'])) {
    unset($_SESSION['receipt'], $_SESSION['pembayaran']); // ini sudah cukup
    header("Location: jualan.php");
    exit;
}
// ==============================
// Hitung ulang total setiap load
// ==============================
hitungPembayaran();


