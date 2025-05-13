<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/dasborProses.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Siapkan data
$keuntungan = $ringkasan['total_keuntungan'] ?? 0;
$penjualan = $ringkasan['total_penjualan'] ?? 0;
$modal = $ringkasan['total_modal'] ?? 0;

// Tanggal hari ini
$tanggal = date('d-m-Y');

// HTML untuk PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        th { background-color: #4a90e2; color: white; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan - ' . $tanggal . '</h1>
    <table>
        <thead>
            <tr>
                <th>Total Modal</th>
                <th>Total Penjualan</th>
                <th>Total Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>' . number_format($modal, 2, ',', '.') . '</td>
                <td>' . number_format($penjualan, 2, ',', '.') . '</td>
                <td>' . number_format($keuntungan, 2, ',', '.') . '</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
';

$html = ob_get_clean();

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();

// Path penyimpanan
$saveDir = dirname(__DIR__, 3) . '../../../../public/resources/img/gambarLaporan/table/';
$counterFile = $saveDir . 'last_id.txt';

// Pastikan folder ada
if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);

// Nama file
$filename = $tanggal . '_strukTable.pdf';
$savePath = $saveDir . $filename;

// Simpan PDF ke server
file_put_contents($savePath, $dompdf->output());

// Atur header untuk download otomatis
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($savePath));

// Kirim file ke browser

exit;
