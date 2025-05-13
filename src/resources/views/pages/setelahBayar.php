<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/jualanProses.php';
require "../components/header.php";



?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">

  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6">
        Konfirmasi
    </h2>

    <div>
      <label for="kembalian" class="block text-sm font-medium text-gray-700 mb-1">Kembalian</label>
      <input type="text" id="kembalian" name="kembalian"
        value="Rp<?= number_format($_SESSION['receipt']['kembalian'] ?? 0, 0, ',', '.') ?>"
        readonly
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
    </div>

    <br>

    <div class="flex justify-between">
      <a href="?reset=true" class="bg-red-500 text-white px-4 py-2 rounded-md hover:brightness-110 transition font-semibold">
        Kembali
      </a>

      <a href="strukPlaint.php" class="bg-highlight text-white px-4 py-2 rounded-md hover:brightness-110 transition font-semibold">
        Kirim Struk
      </a>

      <a href="strukPDF.php" class="bg-green-400 text-white px-4 py-2 rounded-md hover:brightness-110 transition font-semibold">
        Download Struk
      </a>
    </div>
  </div>
</main>
</body>
</html>
