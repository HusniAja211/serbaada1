<?php
require 'session.php';
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

$bulanFilter = $_GET['bulan'] ?? date('m');
$mingguFilter = $_GET['minggu'] ?? null;


// Fungsi jumlah produk terjual
function getJumlahProdukTerjual($conn) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_produk_terjual FROM transaksi");
    $data = mysqli_fetch_assoc($query);
    return $data['jumlah_produk_terjual'] ?? 0;
}

// Fungsi jumlah admin
function getJumlahAdmin($conn) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_admin FROM karyawan WHERE level_karyawan = 'admin'");
    $data = mysqli_fetch_assoc($query);
    return $data['jumlah_admin'] ?? 0;
}

// Fungsi jumlah kasir
function getJumlahKasir($conn) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_kasir FROM karyawan WHERE level_karyawan = 'kasir'");
    $data = mysqli_fetch_assoc($query);
    return $data['jumlah_kasir'] ?? 0;
}

// Fungsi jumlah member
function getJumlahMember($conn) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah_member FROM member");
    $data = mysqli_fetch_assoc($query);
    return $data['jumlah_member'] ?? 0;
}

// Fungsi keuntungan bulan ini
function getKeuntunganBulanIni($conn) {
    $bulanIni = date('Y-m');
    $query = mysqli_query($conn, "SELECT SUM(total_keuntungan) AS total_keuntungan_bulan FROM transaksi WHERE DATE_FORMAT(tanggal_transaksi, '%Y-%m') = '$bulanIni'");
    $data = mysqli_fetch_assoc($query);
    return $data['total_keuntungan_bulan'] ?? 0;
}

// Fungsi total keuntungan semua waktu
function getTotalKeuntungan($conn) {
    $query = mysqli_query($conn, "SELECT SUM(total_keuntungan) AS total_keuntungan FROM transaksi");
    $data = mysqli_fetch_assoc($query);
    return $data['total_keuntungan'] ?? 0;
}

// Fungsi ringkasan transaksi per minggu dan bulan
function getRingkasanTransaksi($conn, $bulan = null, $minggu = null) {
    $where = "1=1";

    if ($bulan) {
        $where .= " AND DATE_FORMAT(tanggal_transaksi, '%Y-%m') = '" . mysqli_real_escape_string($conn, $bulan) . "'";
    }

    if ($minggu) {
        $where .= " AND WEEK(tanggal_transaksi, 1) = " . intval($minggu);
    }

    $query = mysqli_query($conn, "
        SELECT 
            SUM(p.uang_modal_produk) AS total_modal,
            SUM(t.total_harga_transaksi) AS total_penjualan,
            SUM(t.total_keuntungan) AS total_keuntungan
        FROM transaksi t
        JOIN produk p ON p.id_produk = t.fid_produk
        WHERE $where
    ");

    return mysqli_fetch_assoc($query);
}

function getKeuntunganByRange($conn, $bulan = null, $minggu = null) {
    $where = "1";

    if ($bulan) {
        $where .= " AND MONTH(tanggal_transaksi) = '$bulan'";
    }

    if ($minggu) {
        $where .= " AND WEEK(tanggal_transaksi, 1) = '$minggu'";
    }

    $query = mysqli_query($conn, "
        SELECT 
            SUM(total_keuntungan) AS total_keuntungan,
            SUM(total_harga_transaksi) AS total_penjualan,
            SUM(p.uang_modal_produk) AS total_modal
        FROM transaksi t
        JOIN produk p ON t.fid_produk = p.id_produk
        WHERE $where
    ");

    $data = mysqli_fetch_assoc($query);

    return [
        'keuntungan' => $data['total_keuntungan'] ?? 0,
        'penjualan' => $data['total_penjualan'] ?? 0,
        'modal' => $data['total_modal'] ?? 0
    ];
}


// Panggilan utama
$jumlah_produk_terjual = getJumlahProdukTerjual($conn);
$jumlahAdmin = getJumlahAdmin($conn);
$jumlahKasir = getJumlahKasir($conn);
$jumlahMember = getJumlahMember($conn);
$keuntunganBulanIni = getKeuntunganBulanIni($conn);
$totalKeuntungan = getTotalKeuntungan($conn);

// Jika kamu ingin dapatkan ringkasan berdasarkan input bulan dan minggu dari AJAX/GET:
$bulanFilter = $_GET['bulan'] ?? null;
$mingguFilter = $_GET['minggu'] ?? null;
$ringkasan = getRingkasanTransaksi($conn, $bulanFilter, $mingguFilter);
$dataChart = getKeuntunganByRange($conn, $bulanFilter, $mingguFilter);
