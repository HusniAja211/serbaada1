<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/transaksiProses.php';
require "../components/header.php";
require "../components/sidebar.php";


$transaksis = index($conn);


?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Transaksi</h1>
    </div>
   <!-- Tabel kategori (dengan center alignment) -->
   <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
      <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
         <thead class="bg-sky-500 text-white">
            <tr>
               <th class="border border-black px-2 py-1 text-center">ID Transaksi</th>
               <th class="border border-black px-2 py-1 text-center">Tanggal Pembelian</th>
               <th class="border border-black px-2 py-1 text-center">Total Harga</th>
               <th class="border border-black px-2 py-1 text-center">Id Kasir</th>
               <th class="border border-black px-2 py-1 text-center">ID Produk</th>
               <th class="border border-black px-2 py-1 text-center">ID Member</th>
               <th class="border border-black px-2 py-1 text-center">Opsi</th>
            </tr>
         </thead>
         <tbody class="bg-white">
         <?php if (empty($transaksis)) : ?>
            <tr>
               <td colspan="8" class="border border-black px-2 py-1 text-center">Tidak ada data transaksi</td>
            </tr>
         <?php endif; ?>
         <?php foreach($transaksis as $transaksi) :?>
            <tr>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['id_transaksi'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['tanggal_transaksi'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['total_harga_transaksi'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['fid_karyawan'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['fid_produk'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $transaksi['fid_member'] ?></td>
               <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
               <td class="border border-black px-2 py-1 text-center">
                  <a href="../../php/produkProses.php?php echo $produk['id_produk']; ?>"
                  onclick="return confirm('Yakin ingin menghapus data ini?')" 
                  class=" text-red-500 px-2 py-1 text-center rounded"
                  >Hapus</a>
               </td>
               <?php else: ?>
                  <td class="border border-black px-2 py-1 text-center">
                     Tidak ada akses!
                  </td>
               <?php endif; ?>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>

   <!-- Pagination -->
   <!-- <div class="absolute bottom-16 left-4 flex justify-center items-center space-x-4 mb-6 w-full">
      <?php require "../components/pagination.php"; ?>
   </div> -->

</main>

<?php 
require "../components/footer.php";
?>
