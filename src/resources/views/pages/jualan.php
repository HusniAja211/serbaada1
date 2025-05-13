jualan.php
<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/produkProses.php';
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/jualanProses.php';
require "../components/header.php";
require "../components/sidebar.php";

$produks = index($conn); 
$path = '../../../../public/resources/img/gambarProduk/';
$pesanans = $_SESSION['pesanan'] ?? [];

?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col">
   <div class="flex-1 flex gap-4 overflow-hidden h-full">
      <!-- Produk -->
      <div class="flex-1 overflow-y-auto bg-gray-200 p-2">
         <h2 class="text-xl font-semibold mb-2">Pilih Produk Anda</h2>
         
         <div class="grid grid-cols-3 gap-4 justify-center">
             <?php foreach ($produks as $produk): 
                  $stok_ada = $produk['stok_produk'] > 0;
                  $hari_ini = new DateTime();
                  $tanggal_expiry = new DateTime($produk['tanggal_expired']);
                  
                  // Produk belum expired (termasuk jika tanggal expiry adalah hari ini)
                  $belum_expired = empty($produk['tanggal_expired']) || 
                                    $tanggal_expiry > $hari_ini;
                  
                  // Produk expired hari ini (opsional, jika perlu penanganan khusus)
                  $expired_hari_ini = !empty($produk['tanggal_expired']) && 
                                       $tanggal_expiry->format('Y-m-d') === $hari_ini->format('Y-m-d');

                  $sudah_kadaluarsa = !empty($produk['tanggal_expired']) && $tanggal_expiry < $hari_ini;
                  
                  $tampilkan_button = $stok_ada && $belum_expired;?>
               <div class="bg-white rounded shadow p-2 flex flex-col">
                  <img src="<?= $path . $produk['gambar_produk'] ?>" alt=""
                     class="w-24 h-24 object-cover mb-2 rounded">
                  <p class="font-semibold text-left"><?= $produk['nama_produk'] ?></p>  
                  <span class="text-sm text-gray-600">Rp<?= number_format($produk['harga_jual_produk'], 0, ',', '.') ?></span>
                  <?php if ($tampilkan_button): ?>
                     <form action="../../php/jualanProses.php" method="post">
                        <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
                        <button type="submit" class="bg-highlight text-white border rounded-sm w-full m-1 hover:bg-sky-600">
                           Pesan
                        </button>
                     </form>
                  <?php else: ?>
                      <div class="bg-red-500 text-white text-center text-sm py-2 rounded-sm w-full m-1 hover:bg-red-600">
                        <?php 
                           if (!$stok_ada && $expired_hari_ini) {
                                 echo 'Stok Habis dan Kadaluarsa Hari Ini';
                              } elseif (!$stok_ada && $sudah_kadaluarsa) {
                                 echo 'Stok Habis dan Sudah Kadaluarsa';
                              } elseif (!$stok_ada) {
                                 echo 'Stok Habis';
                              } elseif ($expired_hari_ini) {
                                 echo 'Kadaluarsa Hari Ini';
                              } elseif ($sudah_kadaluarsa) {
                                 echo 'Sudah Kadaluarsa';
                              } else {
                                 echo 'Tidak Tersedia';
                              }
                           ?>
                     </div>
                  <?php endif; ?>
               </div>
            <?php endforeach; ?>
         </div>
      </div>

      <!-- Menu Pesanan -->
      <aside class="w-1/3 bg-white rounded shadow p-4 flex flex-col justify-between min-h-full max-h-[calc(100vh-4rem)]">
         <div class="space-y-4 overflow-y-auto flex-grow">
            <h2 class="text-xl font-semibold">Menu Pesanan</h2>

            <!-- Member Check Form -->
            <form method="post" action="../../php/jualanProses.php" class="mb-4">
               <div>
                  <label for="kode" class="block font-medium">Kode Member</label>
                  <div class="flex gap-2">
                     <input type="text" name="no_telepon_member" 
                            class="flex-1 border border-gray-300 rounded px-2 py-1 mt-1"
                            placeholder="Masukkan nomor telepon member">
                     <button type="submit" name="btnCekPoint" 
                             class="mt-1 px-4 py-1 bg-highlight text-white rounded">
                         Cek
                     </button>
                  </div>
               </div>
            </form>

            <!-- Member Point Info -->
            <?php if(isset($_SESSION['pembayaran']['point_member'])): ?>
               <div class="flex flex-col gap-2 border border-gray-300 rounded p-3 bg-gray-50">
                  <div class="bg-blue-50 p-3 rounded">
                     <p class="font-medium">Poin Member</p>
                     <p class="text-2xl font-bold"><?= number_format($_SESSION['pembayaran']['point_member'], 0, ',', '.') ?> Poin</p>
                     <p class="text-sm text-gray-600">1 Poin = Rp100</p>

                     <div class="flex gap-2 w-2/3">
                        <form method="post" action="../../php/jualanProses.php">
                           <input type="number" name="pakaiPoint"
                                 class="flex-1 border border-gray-300 rounded px-3 py-1 text-sm"
                                 placeholder="Jumlah poin"
                                 value="<?php echo $_SESSION['pembayaran']['point_member'] ?? 0; ?>"
                                 min="0" 
                                 max="375">
                           <button class="bg-highlight text-white px-3 py-1 rounded text-sm" name="terapkanPoint" type="submit">
                              Terapkan
                           </button>
                        </form>
                     </div>
                  </div>
               </div> 
            <?php endif; ?>


            <!-- Cart Items -->
            <div class="space-y-2 max-h-52 overflow-y-auto  border border-red-700 rounded p-3 bg-gray-50">
               <?php if (empty($pesanans)): ?>
                  <p class="text-center text-gray-500">Keranjang Kosong</p>
               <?php else: ?>
               <?php foreach ($pesanans as $pesanan): ?>
                  <div class="flex gap-2 items-start border-b pb-2">
                     <img src="<?= $path . $pesanan['gambar_produk'] ?>" alt=""
                        class="w-12 h-12 rounded">
                     <div class="flex-1">
                        <p class="font-semibold text-sm"><?= $pesanan['nama_produk'] ?></p>
                        <p class="text-xs text-gray-600">Rp<?= number_format($pesanan['harga_jual_produk'], 0, ',', '.') ?></p>
                        <div class="flex items-center gap-2 mt-1">
                           <form action="../../php/jualanProses.php" method="post" class="m-0">
                              <input type="hidden" name="id_produk" value="<?= $pesanan['id_produk'] ?>">
                              <input type="hidden" name="action" value="kurang">
                              <button type="submit" class="bg-gray-200 px-2 rounded hover:bg-gray-300">-</button>
                           </form>
                           <span class="text-sm"><?= $pesanan['jumlah_dipesan'] ?></span>
                           <form action="../../php/jualanProses.php" method="post" class="m-0">
                              <input type="hidden" name="id_produk" value="<?= $pesanan['id_produk'] ?>">
                              <input type="hidden" name="action" value="tambah">
                              <button type="submit" class="bg-gray-200 px-2 rounded hover:bg-gray-300">+</button>
                           </form>
                        </div>
                     </div>
                     <form action="../../php/jualanProses.php" method="post" class="m-0">
                        <input type="hidden" name="id_produk" value="<?= $pesanan['id_produk'] ?>">
                        <input type="hidden" name="action" value="hapus">
                        <button type="submit" class="text-red-500 hover:text-red-700">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                           </svg>
                        </button>
                     </form>
                  </div>
               <?php endforeach; ?>
            </div>
            <?php endif; ?>
         </div>

   <div class="space-y-4 pt-4">
       <!-- Order Summary -->
      <div class="border border-gray-300 rounded p-3 bg-gray-50">
         <h3 class="font-semibold text-lg mb-3">Ringkasan Pembayaran</h3>
         <br>
         <div class="space-y-2 text-sm">
               <div class="flex justify-between">
                  <span class="text-gray-600">Subtotal</span>
                  <span class="font-medium">Rp<?= $_SESSION['pembayaran']['subtotal'] ?></span>
               </div>
               
               <div class="flex justify-between">
                  <span class="text-gray-600">Diskon Member</span>
                  <span class="text-green-600">-Rp<?= $_SESSION['pembayaran']['diskonPoint'] ?></span>
               </div>
               
               <div class="flex justify-between">
                  <span class="text-gray-600">Pajak (12%)</span>
                  <span>Rp<?= $_SESSION['pembayaran']['pajak'] ?></span>
               </div>
               
               <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between font-bold text-base">
                  <span>Total Pembayaran</span>
                  <span>Rp<?= $_SESSION['pembayaran']['total'] ?></span>
               </div>
         </div>
    </div>

    <form action="../../php/jualanProses.php" method="post" id="formBayar">
         <div class="bg-white p-4 rounded-lg border border-gray-200">
         <div class="mb-3">
               <label class="block font-medium text-gray-700 mb-1">Uang Dibayarkan</label>
               <input type="number" name="uang_dibayar" required
                     class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                     placeholder="Masukkan jumlah uang">
         </div>

         <br>

         <button type="submit" name="bayar" 
                  class="w-full h-12 bg-highlight text-white py-3 rounded-lg hover:bg-blue-700 font-semibold transition-colors duration-200 shadow-md">
               Bayar Sekarang
         </button>
         </div>
      </form>
</div>

</main>

<?php 
require "../components/footer.php";
?>