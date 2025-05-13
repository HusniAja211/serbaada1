
<footer class="flex items-center justify-start bg-black w-full h-15 p-4 sm:ml-64">
    <p class="text-white">&copy; 2025. Husni Mubarak. Semua Hak Dilindungi </p>
</footer>
<script src="/serbaada1/node_modules/flowbite/dist/flowbite.min.js"></script>
<script src="/serbaada1/src/resources/js/script.js" defer></script>
<script src="../../../../node_modules/chart.js/dist/chart.umd.js"></script>
<script>
   const keuntunganData = <?= json_encode([
      [
         'bulan' => "$bulanFilter" . ($mingguFilter ? " - Minggu ke-$mingguFilter" : ""),
         'total' => $dataChart['keuntungan'] ?? 0
      ]
   ]) ?>;
</script>
<script src="../../js/dasbor.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="../../js/scanner.js"></script>
</body>
</html>