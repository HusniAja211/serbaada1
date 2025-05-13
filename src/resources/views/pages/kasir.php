<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/kasirProses.php';
require "../components/header.php";
require "../components/sidebar.php";

$kasirs = index($conn);
?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Kasir</h1>
    </div>
   <!-- Tabel kategori (dengan center alignment) -->
   <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
      <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
         <thead class="bg-sky-500 text-white">
            <tr>
               <th class="border border-black px-2 py-1 text-center">ID Karyawan</th>
               <th class="border border-black px-2 py-1 text-center">Email Kasir</th>
               <th class="border border-black px-2 py-1 text-center">Username Kasir</th>
               <th class="border border-black px-2 py-1 text-center">Gambar Kasir</th>
                <th class="border border-black px-2 py-1 text-center">Selengkapnya</th>
               <th class="border border-black px-2 py-1 text-center">Opsi</th>
            </tr>
         </thead>
         <tbody class="bg-white">

         <?php if (empty($kasirs)): ?>
            <tr>
               <td colspan="6" class="border border-black px-2 py-1 text-center">Tidak ada data kasir</td>
            </tr>
         <?php endif; ?>
            <?php foreach ($kasirs as $kasir): ?>
            <tr>
               <td class="border border-black px-2 py-1 text-center"><?= $kasir['id_karyawan'] ?></td>
               <td class="border border-black px-2 py-1"><?= $kasir['email_karyawan'] ?></td>
               <td class="border border-black px-2 py-1"><?= $kasir['username_karyawan'] ?></td>
               <td class="border border-black px-2 py-1"><?= $kasir['gambar_karyawan'] ?></td>
               <td class="border border-black px-2 py-1">
                    <a href="createKasir.php?id_karyawan=<?= $kasir['id_karyawan'] ?>" class="text-sky-600 hover:underline hover:text-sky-800">Selengkapnya</a>
               </td>
               <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
               <td class="border border-black px-2 py-1 text-center">
                  <a href="createKasir.php?id_karyawan=<?= $kasir['id_karyawan']?>" 
                  class="bg-green-400 text-white px-2 py-1 rounded mr-2"
                  >Edit</a>

                  <a href="../../php/kasirProses.php?delete=<?= $kasir['id_karyawan'] ?>" 
                  class="bg-red-500 text-white px-2 py-1 rounded"
                  onclick="return confirm('Yakin ingin menghapus data kasir ini?')"
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

   <!-- Pagination
   <div class="absolute bottom-16 left-4 flex justify-center items-center space-x-4 mb-6 w-full">
      <?php require "../components/pagination.php"; ?>
   </div> -->

   <!-- Tombol tambah data -->
   <div class="absolute bottom-4 left-4">
      <a href="createKasir.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
   </div>
</main>

<?php 
require "../components/footer.php";
?>
