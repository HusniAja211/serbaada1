<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/produkProses.php';

require "../components/header.php";
require "../components/sidebar.php";

$produks = index($conn);
?>
<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Produk</h1>
    </div>

    <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
      <thead class="bg-sky-500 text-white">
         <tr>
            <th class="border border-black px-2 py-1 text-center">ID Produk</th>
            <th class="border border-black px-2 py-1 text-center">Nama Produk</th>
            <th class="border border-black px-2 py-1 text-center">Tanggal Expired</th>
            <th class="border border-black px-2 py-1 text-center">Stok Produk</th>
            <th class="border border-black px-2 py-1 text-center">Uang Modal</th>
            <th class="border border-black px-2 py-1 text-center">Harga Jual</th>
            <th class="border border-black px-2 py-1 text-center">Keuntungan</th>
            <th class="border border-black px-2 py-1 text-center">Kategori</th>
            <th class="border border-black px-2 py-1 text-center">Gambar</th>
            <th class="border border-black px-2 py-1 text-center">Deskripsi</th>
            <th class="border border-black px-2 py-1 text-center">Opsi</th>
         </tr>
      </thead>
      <tbody class="bg-white">
         <?php if (empty($produks)): ?>
         <tr>
            <td colspan="11" class="border border-black px-2 py-1 text-center">Tidak ada data produk</td>
         </tr>
         <?php else: ?>
         <?php foreach ($produks as $produk): ?>
         <tr>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['id_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['nama_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['tanggal_expired'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['stok_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['uang_modal_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['harga_jual_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['keuntungan_produk'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['fid_kategori'] ?></td>
            <td class="border border-black px-2 py-1 text-center"><?=$produk['gambar_produk']?></td>
            <td class="border border-black px-2 py-1 text-center"><?= $produk['deskripsi_produk'] ?></td>
            <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
            <td class="border border-black px-2 py-1 text-center">
               <div class="flex justify-center space-x-2">
                  <a href="createProduk.php?id=<?= $produk['id_produk'] ?>" 
                     class="inline-block bg-green-400 hover:bg-green-500 text-white px-3 py-1 rounded-md mr-2 transition duration-200">
                     Edit
                  </a>
                  <a href="../../php/produkProses.php?delete=<?= $produk['id_produk'] ?>"  
                     onclick="return confirm('Yakin ingin menghapus produk ini?')"
                     class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition duration-200">
                     Hapus
                  </a>
               </div>
            </td>
            <?php else: ?>
               <td class="border border-black px-2 py-1 text-center">
                  Tidak ada akses!
               </td> 
            <?php endif; ?>
         </tr>
         <?php endforeach; ?>
         <?php endif; ?>
      </tbody>
    </table>

   <!-- Pagination
   <div class="absolute bottom-16 left-4 flex justify-center items-center space-x-4 mb-6 w-full">
      <?php require "../components/pagination.php"; ?>
   </div> -->

   <!-- Tombol tambah data -->
   <div class="absolute bottom-4 left-4">
      <a href="createProduk.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
   </div>
</main>

<?php 
require "../components/footer.php";
?>
