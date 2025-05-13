<?php
session_start();
require '../../../../vendor/autoload.php';

use Dompdf\Dompdf;

// Ambil data dari session
$receipt = $_SESSION['receipt'] ?? null;
$pembayaran = $_SESSION['pembayaran'] ?? null;
$kasir = $_SESSION['logged']['nama_karyawan'] ?? 'Tidak diketahui';

if (!$receipt || !$pembayaran) die("Data tidak tersedia.");

// Buat HTML-nya
ob_start();
?>

<style>
  body { font-family: Arial, sans-serif; font-size: 12px; }
  h1 { text-align: center; font-size: 20px; }
  .center { text-align: center; }
  .bold { font-weight: bold; }
  .box { border: 1px solid black; padding: 10px; margin: 0 auto; width: 100%; max-width: 400px; }
  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  th, td { border-bottom: 1px solid #ccc; padding: 5px; text-align: left; }
  .total { font-weight: bold; font-size: 14px; }
</style>

<div class="box">
  <h1>SerbaAda!</h1>
  <p class="center">Jl. Gtw Jalan Apa Intinya Indonesia<br>
  Telp: Rahasia Wleee</p>
  <hr>

  <p>Tanggal: <?= date('d M Y') ?><br>
  Kasir: <?= $kasir ?><br>
  <?php if (!empty($receipt['no_hp_member'])): ?>
    Member: <?= $receipt['no_hp_member'] ?><br>
  <?php endif; ?>
  </p>

  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Jumlah</th>
        <th>Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($receipt['items'] as $item): ?>
      <tr>
        <td><?= $item['nama_produk'] ?></td>
        <td><?= $item['jumlah_dipesan'] ?></td>
        <td>Rp <?= number_format($item['harga_jual_produk'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p class="total">
    Subtotal: Rp <?= number_format($pembayaran['subtotal'], 0, ',', '.') ?><br>
    Diskon Member: -Rp <?= number_format($pembayaran['diskonPoint'], 0, ',', '.') ?><br>
    PPN (12%): Rp <?= number_format($pembayaran['pajak'], 0, ',', '.') ?><br>
    TOTAL: Rp <?= number_format($pembayaran['total'], 0, ',', '.') ?><br>
    Poin Didapat: <?= $pembayaran['poin_didapat'] ?> point
  </p>

  <p class="center" style="margin-top: 20px;">
    Terima kasih telah berbelanja di SerbaAda!<br>
    Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.<br>
    <span style="font-size: 10px;">*Simpan struk ini untuk keperluan garansi</span>
  </p>
</div>

<?php
$html = ob_get_clean();

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();

// Path penyimpanan
$saveDir = dirname(__DIR__, 3) . '../../../../public/resources/img/gambarStruk/';
$counterFile = $saveDir . 'last_id.txt';

// Pastikan folder ada
if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);

// Ambil ID terakhir
$lastId = file_exists($counterFile) ? (int)file_get_contents($counterFile) : 0;
$newId = $lastId + 1;

// Simpan ID baru
file_put_contents($counterFile, $newId);

// Nama file
$filename = $newId . '_struk.pdf';
$savePath = $saveDir . $filename;

// Simpan PDF ke server
file_put_contents($savePath, $dompdf->output());

// Atur header untuk download otomatis
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($savePath));

// Kirim file ke browser
readfile($savePath);
exit;
