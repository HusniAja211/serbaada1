<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/jualanProses.php';
require "../components/header.php";
require '../../../../vendor/autoload.php';

$receipt = $_SESSION['receipt'] ?? null;
$pembayaran = $_SESSION['pembayaran'] ?? null;
if (!$receipt || !$pembayaran) die("Data tidak tersedia.");


// Ultramsg.com token
$token = "us2uxdgp8l2cc6fc"; 
// Ultramsg.com instance id
$instance_id = "instance119614"; 

$client = new UltraMsg\WhatsAppApi($token, $instance_id);

// Nomor tujuan
$to = $_SESSION['pembayaran']['no_telepon_member']; 

// Prepare data for message
$date = date('d M Y');
$kasir = $_SESSION['logged']['nama_karyawan'] ?? 'Tidak diketahui';
$member = !empty($receipt['no_hp_member']) ? $receipt['no_hp_member'] : 'Tidak ada';
$items = '';
foreach ($receipt['items'] as $item) {
    $items .= "- {$item['nama_produk']} x{$item['jumlah_dipesan']} = Rp " . number_format((float)$item['harga_jual_produk'], 0, ',', '.') . " \n";
}

$subtotal = number_format($pembayaran['subtotal'], 0, ',', '.');
$diskon = number_format($pembayaran['diskonPoint'], 0, ',', '.');
$pajak = number_format($pembayaran['pajak'], 0, ',', '.');
$total = number_format($pembayaran['total'], 0, ',', '.');
$poin = $pembayaran['poin_didapat'];

// Construct message body
$body = "
🧾 *Struk Pembelian - SerbaAda!* \n
Jl. Gtw Jalan Apa Intinya Indonesia \n
Telp: Rahasia Wleee \n\n
📅 Tanggal: $date \n
👤 Kasir: $kasir \n
📱 Member: $member \n\n
📦 *Daftar Belanja:* \n
$items \n
💵 *Rincian Pembayaran:* \n
Subtotal: Rp $subtotal \n 
Diskon Member: -Rp $diskon \n
PPN (12%): Rp $pajak \n
TOTAL: *Rp $total* \n
🎁 Point Didapat: $poin point \n\n
🙏 Terima kasih telah berbelanja di *SerbaAda!* \n
⚠️ Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan \n
📌 Simpan struk ini untuk keperluan garansi \n
";

// Send message via UltraMsg
$api = $client->sendChatMessage($to, $body);

// Print API response
print_r($api);

// Redirect to another page (e.g., thank you page or transaction history)
header("Location: setelahBayar.php");
exit;
?>
