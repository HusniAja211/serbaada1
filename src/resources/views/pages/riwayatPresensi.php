<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/presensiProses.php';

require "../components/header.php";
require "../components/sidebar.php";

$karyawans = getKaryawan($conn);
?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Riwayat Presensi</h1>
    </div>
   <!-- Tabel kategori (dengan center alignment) -->
   <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
      <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
         <thead class="bg-sky-500 text-white">
            <tr>
               <th class="border border-black px-2 py-1 text-center">ID Presensi</th>
               <th class="border border-black px-2 py-1 text-center">ID Karyawan</th>
               <th class="border border-black px-2 py-1 text-center">Nama Karyawan</th>
               <th class="border border-black px-2 py-1 text-center">Level Karyawan</th>
               <th class="border border-black px-2 py-1 text-center">Status</th>
               <th class="border border-black px-2 py-1 text-center">Waktu Presensi</th>
            </tr>
         </thead>
         <tbody class="bg-white">
            <?php if(empty($karyawans)): ?>
               <tr>
                  <td class="border border-black px-2 py-1 text-center" colspan="5">Tidak Ada Data</td>
               </tr>
            <?php endif; ?>
            <?php foreach ($karyawans as $karyawan) : ?>
            <tr>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['id_presensi_karyawan'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['fid_karyawan'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['username_karyawan'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['level_karyawan'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['status'] ?></td>
               <td class="border border-black px-2 py-1 text-center"><?= $karyawan['tanggal_presensi'] ?></td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>

   <!-- Pagination -->
   <!-- <div class="absolute bottom-16 left-4 flex justify-center items-center space-x-4 mb-6 w-full">
      <?php require "../components/pagination.php"; ?>
   </div> -->

   <!-- Tombol tambah data -->
   <div class="absolute bottom-4 left-4">
      <a href="presensi.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
   </div>
</main>

<?php 
require "../components/footer.php";
?>
