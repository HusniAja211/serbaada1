<?php
require "session.php";

require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $data = [
        'id_karyawan' => $_POST['id_karyawan'] ?? null,
        'nama_karyawan' => $_POST['nama_karyawan'] ?? null,
        'hari_presensi' => $_POST['hari_presensi'] ?? null,
        'status_presensi' => $_POST['status_presensi'] ?? null,
    ];
        store($conn, $data);

    // Redirect ke halaman produk setelah berhasil simpan/update
    header("Location: /serbaada1/src/resources/views/pages/riwayatPresensi.php");
    exit;
}

function getKaryawan($conn) {
    $query = "
        SELECT pk.id_presensi_karyawan, pk.fid_karyawan, pk.status, pk.tanggal_presensi,
               k.username_karyawan, k.level_karyawan
        FROM presensi_karyawan pk
        INNER JOIN karyawan k ON pk.fid_karyawan = k.id_karyawan
        ORDER BY pk.tanggal_presensi DESC
    ";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



function store($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO presensi_karyawan (fid_karyawan, status, tanggal_presensi) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $data['id_karyawan'], $data['status_presensi'], $data['hari_presensi']);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}