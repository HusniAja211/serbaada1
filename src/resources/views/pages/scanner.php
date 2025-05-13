<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Scanner Produk</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <link rel="stylesheet" href="../../../../public/resources/css/output.css">
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center p-6">

  <div class="relative w-full max-w-screen-xl">

    <!-- Tombol Kembali di pojok kiri atas -->
    <div class="absolute top-4 left-4">
      <a href="jualan.php" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="font-medium">Kembali</span>
      </a>
    </div>

    <!-- Konten utama -->
    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md mx-auto text-center">
      <h1 class="text-3xl font-bold text-blue-600 mb-4">Scanner Produk</h1>

      <div id="reader" class="w-full aspect-square max-w-xs mx-auto bg-blue-100 border-2 border-dashed border-blue-400 rounded-lg flex items-center justify-center cursor-pointer hover:bg-blue-200 transition-all duration-300">
        Klik di sini untuk membuka kamera
      </div>

      <p class="text-sm text-gray-500 mt-4">Pastikan kamera menghadap ke barcode produk</p>
    </div>

  </div>

  <script>
  let scannerStarted = false;

  document.getElementById('reader').addEventListener('click', function () {
      if (scannerStarted) return;

      const readerDiv = document.getElementById('reader');
      readerDiv.innerHTML = ''; // Kosongkan isi agar kamera bisa tampil
      readerDiv.classList.remove('bg-blue-100', 'hover:bg-blue-200');
      readerDiv.classList.add('bg-transparent');

      const html5QrCode = new Html5Qrcode("reader");

      html5QrCode.start(
          { facingMode: "environment" },
          { fps: 10, qrbox: 250 },
          (decodedText, decodedResult) => {
              console.log("Scanned:", decodedText);

              fetch('../../php/scannerProses.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ id_produk: decodedText })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.status === 'success') {
                      alert('✅ Produk ditambahkan: ' + data.produk.nama_produk + ' (Qty: ' + data.produk.jumlah_dipesan + ')');
                  } else {
                      alert('❌ Gagal: ' + data.message);
                  }

                  html5QrCode.stop().then(() => {
                      scannerStarted = false;
                      readerDiv.innerHTML = 'Klik di sini untuk membuka kamera';
                      readerDiv.classList.remove('bg-transparent');
                      readerDiv.classList.add('bg-blue-100', 'hover:bg-blue-200');
                  });
              });
          },
          (errorMessage) => {
              // console.log("Scanning error", errorMessage);
          }
      ).then(() => {
          scannerStarted = true;
      }).catch(err => {
          alert("Tidak bisa mengakses kamera: " + err);
      });
  });
  </script>

</body>
</html>
