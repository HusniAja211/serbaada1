<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/dasborProses.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$keuntungan = $ringkasan['total_keuntungan'] ?? 0;
$penjualan = $ringkasan['total_penjualan'] ?? 0;
$modal = $ringkasan['total_modal'] ?? 0;

$bulanFilter = $_GET['bulan'] ?? date('m');
$mingguFilter = $_GET['minggu'] ?? null;
$periode = "$bulanFilter" . ($mingguFilter ? " - Minggu ke-$mingguFilter" : "");
$tanggal = date('d-m-Y');

// HTML string langsung tanpa ob_start
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Grafik Keuntungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        th { background-color: #4a90e2; color: white; }
        .legend {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 25px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .red { background-color: #dc2626; }
        .green { background-color: #84cc16; }
        .blue { background-color: #2563eb; }
    </style>
</head>
<body>
    <h1>Laporan Grafik Keuangan</h1>
    <h2>Periode: ' . $periode . ' | Dicetak: ' . $tanggal . '</h2>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Total Keuntungan</td><td>' . number_format($keuntungan, 2, ',', '.') . '</td></tr>
            <tr><td>Total Penjualan</td><td>' . number_format($penjualan, 2, ',', '.') . '</td></tr>
            <tr><td>Total Modal</td><td>' . number_format($modal, 2, ',', '.') . '</td></tr>
        </tbody>
    </table>
    <div class="legend">
        <div class="legend-item"><span class="circle red"></span> Keuntungan</div>
        <div class="legend-item"><span class="circle green"></span> Penjualan</div>
        <div class="legend-item"><span class="circle blue"></span> Modal</div>
    </div>
</body>
</html>';

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();

// Simpan file
$saveDir = $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/public/resources/img/gambarLaporan/chart/';
if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);

$filename = $tanggal . '_ReportChart.pdf';
$savePath = $saveDir . $filename;
file_put_contents($savePath, $dompdf->output());

// Kirim ke browser
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($savePath));
readfile($savePath);
exit;
// 