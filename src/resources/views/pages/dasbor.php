<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/dasborProses.php';
require "../components/header.php";
require "../components/sidebar.php";

// Mendapatkan data chart dari PHP
$keuntungan = $ringkasan['total_keuntungan'] ?? 0;
$penjualan = $ringkasan['total_penjualan'] ?? 0;
$modal = $ringkasan['total_modal'] ?? 0;

$bulanFilter = $_GET['bulan'] ?? date('m');
$mingguFilter = $_GET['minggu'] ?? null;

$dataChart = [
    'keuntungan' => $keuntungan,
    'penjualan' => $penjualan,
    'modal' => $modal
];
?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen">
   <div class="flex flex-row justify-center">
      <h1 class="font-bold text-2xl">Dasbor</h1>
   </div>
   <br>

   <!-- Statistik Utama -->
   <div class="grid grid-cols-3 gap-4 mb-4">
      <div class="p-4 bg-white rounded shadow flex flex-col items-center">
         <i class="fa-solid fa-store text-2xl mb-2"></i>
         <p class="text-lg font-semibold"><?= $jumlah_produk_terjual ?></p>
         <p class="text-sm text-gray-600">Produk Terjual</p>
      </div>
      <div class="p-4 bg-white rounded shadow flex flex-col items-center">
         <i class="fa-solid fa-dollar-sign text-2xl mb-2"></i>
         <p class="text-lg font-semibold">Rp <?= number_format($keuntunganBulanIni, 0, ',', '.') ?></p>
         <p class="text-sm text-gray-600">Keuntungan Bulan <?= date('F Y') ?></p>
      </div>
      <div class="p-4 bg-white rounded shadow flex flex-col items-center">
         <i class="fa-solid fa-dollar-sign text-2xl mb-2"></i>
         <p class="text-lg font-semibold">Rp <?= number_format($totalKeuntungan, 0, ',', '.') ?></p>
         <p class="text-sm text-gray-600">Total Keuntungan</p>
      </div>
   </div>

   <!-- Filter Bulan dan Minggu -->
   <form method="GET" class="mb-4 flex gap-2 items-center justify-start">
      <label for="bulan">Bulan:</label>
      <select name="bulan" id="bulan" class="border rounded p-1">
         <?php
         foreach (range(1, 12) as $i) {
            $val = str_pad($i, 2, '0', STR_PAD_LEFT);
            $selected = ($bulanFilter == $val) ? 'selected' : '';
            echo "<option value='$val' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . "</option>";
         }
         ?>
      </select>

      <label for="minggu">Minggu:</label>
      <select name="minggu" id="minggu" class="border rounded p-1">
         <option value="">Semua</option>
         <?php
         for ($i = 1; $i <= 5; $i++) {
            $selected = ($mingguFilter == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>Minggu ke-$i</option>";
         }
         ?>
      </select>

      <button type="submit" class="bg-highlight text-white px-4 py-1 rounded">Terapkan</button>
   </form>

   <div class="mb-4 flex gap-4 items-center justify-start">
      <a href="tableReportPdf.php" class="inline-block">
         <button class="bg-highlight text-white py-2 px-4 rounded-lg hover:bg-highlight-600 transition duration-300 ease-in-out">
            Download PDF Table
         </button>
      </a>
      <a href="chartReportPdf.php" class="inline-block">
         <button class="bg-highlight text-white py-2 px-4 rounded-lg hover:bg-highlight-600 transition duration-300 ease-in-out">
            Download Table Chart
         </button>
      </a>
   </div>


   <!-- Grafik Lingkaran -->
   <div class="bg-white rounded shadow p-4 mb-4">

      <!-- Grafik Keuntungan per Bulan -->
      <div class="bg-white rounded shadow p-4 mb-4">
         <h2 class="text-lg font-semibold mb-2 text-center">Grafik Keuntungan per Bulan</h2>
         <canvas id="keuntunganChart" width="250" height="250"></canvas>
      </div>


      <div class="space-y-2 text-center">
         <div class="flex items-center gap-2">
            <span class="w-4 h-4 bg-red-600 rounded-full"></span> <span>Total Keuntungan</span>
         </div>
         <div class="flex items-center gap-2">
            <span class="w-4 h-4 bg-lime-500 rounded-full"></span> <span>Total Penjualan</span>
         </div>
         <div class="flex items-center gap-2">
            <span class="w-4 h-4 bg-blue-600 rounded-full"></span> <span>Total Modal</span>
         </div>
      </div>
   </div>

   <!-- Tabel Ringkasan -->
   <div class="bg-white rounded shadow p-4">
      <table class="w-full table-auto border-collapse">
         <thead>
            <tr class="bg-blue-400 text-white">
               <th class="p-2 border">Total Modal</th>
               <th class="p-2 border">Total Penjualan</th>
               <th class="p-2 border">Total Keuntungan</th>
            </tr>
         </thead>
         <tbody>
         <tr>
            <td class="border p-2 text-center"><?= number_format($ringkasan['total_modal'] ?? 0, 2, ',', '.') ?></td>
            <td class="border p-2 text-center"><?= number_format($ringkasan['total_penjualan'] ?? 0, 2, ',', '.') ?></td>
            <td class="border p-2 text-center"><?= number_format($ringkasan['total_keuntungan'] ?? 0, 2, ',', '.') ?></td>
         </tr>
         </tbody>
      </table>
   </div>
</main>

<?php 
require "../components/footer.php";
?>
