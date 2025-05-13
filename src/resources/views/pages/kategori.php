<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/kategoriProses.php';
require '../../../database/connection.php'; 

// Memanggil fungsi index untuk mengambil data kategori
$kategoris = index($conn);

require "../components/header.php";
require "../components/sidebar.php";
?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
   <div class="flex flex-row justify-center">
       <h1 class="text-2xl font-semibold mb-4">Data Kategori</h1>
   </div>

   <!-- Tabel kategori (dengan center alignment) -->
   <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
      <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
         <thead class="bg-sky-500 text-white">
            <tr>
               <th class="border border-black px-2 py-1 text-center">ID Kategori</th>
               <th class="border border-black px-2 py-1 text-center">Nama Kategori</th>
               <th class="border border-black px-2 py-1 text-center">Deskripsi</th>
               <th class="border border-black px-2 py-1 text-center">Opsi</th>
            </tr>
         </thead>
         <tbody class="bg-white">
            <?php foreach ($kategoris as $kategori): ?>
               <tr>
                  <td class="border border-black px-2 py-1 text-center"><?php echo $kategori['id_kategori']; ?></td>
                  <td class="border border-black px-2 py-1"><?php echo $kategori['nama_kategori']; ?></td>
                  <td class="border border-black px-2 py-1"><?php echo $kategori['deskripsi_kategori']; ?></td>
                  <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
                  <td class="border border-black px-2 py-1 text-center">
                     <a href="createKategori.php?id_kategori=<?php echo $kategori['id_kategori']; ?>" class="bg-green-400 text-white px-2 py-1 rounded mr-2">Edit</a>
                     <a 
                        href="../../php/kategoriProses.php?delete=<?php echo $kategori['id_kategori']; ?>" 
                        onclick="return confirm('Yakin ingin menghapus data ini?')" 
                        class="bg-red-500 text-white px-2 py-1 rounded">
                        Hapus
                     </a>
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



     <!-- Tombol tambah data -->
   <div class="absolute bottom-4 left-4">
      <a href="createKategori.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
   </div>

</main>

<?php 
require "../components/footer.php";
?>
