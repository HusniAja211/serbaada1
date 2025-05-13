<?php 
require 'session.php';


require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';

function index($conn){
    $stmt = $conn->prepare("SELECT * FROM transaksi");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function delete($conn, $id_transaksi){
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");
    $stmt->bind_param("i", $id_transaksi);
    $stmt->execute();

    if($stmt->affected_rows > 0){
            echo 
            "<script>
                alert('Data berhasil dihapus');
                window.location.href = '/serbaada1/src/resources/views/pages/transaksi.php';
            </script>";
            exit;
    } else {
            "<script>
            alert('Data Gagal Dihapus');
            window.location.href = '/serbaada1/src/resources/views/pages/transaksi.php';
            </script>";
        exit;
    }
}