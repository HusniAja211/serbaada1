<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/memberProses.php';
require '../../../database/connection.php'; 

// Memanggil fungsi index untuk mengambil data kategori
$response = index($conn);

if ($response['success']) {
    // Jika sukses, tampilkan data kategori
    $members = $response['data'];
} else {
    // Jika terjadi error, tampilkan pesan error dan set member ke array kosong
    echo "<p class='text-red-500'>{$response['error']}</p>";
    $members = [];
}

require "../components/header.php";
require "../components/sidebar.php";
?>
<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
   <!-- Judul / Header halaman -->
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Member</h1>
    </div>
   <!-- Tabel Member (dengan center alignment) -->
   <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
      <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
         <thead class="bg-sky-500 text-white">
            <tr>
               <th class="border border-black px-2 py-1 text-center">ID Member</th>
               <th class="border border-black px-2 py-1 text-center">Nama Member</th>
               <th class="border border-black px-2 py-1 text-center">Nomor Telepon</th>
               <th class="border border-black px-2 py-1 text-center">Point</th>
               <th class="border border-black px-2 py-1 text-center">Status</th>
               <th class="border border-black px-2 py-1 text-center">Opsi</th>
            </tr>
         </thead>
         <tbody class="bg-white">
            <?php foreach ($members as $member): ?>
            <tr>
               <td class="border border-black px-2 py-1 text-center"><?= $member['id_member'] ?></td>
               <td class="border border-black px-2 py-1"><?= $member['nama_member'] ?></td>
               <td class="border border-black px-2 py-1"><?= $member['no_telepon_member'] ?></td>
               <td class="border border-black px-2 py-1"><?= $member['point_member'] ?></td>
               <td class="border border-black px-2 py-1"><?= $member['status_member'] ?></td>   
               <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>        
               <td class="border border-black px-2 py-1 text-center">
                  <a href="createMember.php?id_member=<?= $member['id_member'] ?>" class="bg-green-400 text-white px-2 py-1 rounded mr-2">Edit</a>
                  <a href="../../php/memberProses.php?delete=<?= $member['id_member']; ?>" 
                  onclick="return confirm('Yakin ingin menghapus data ini?')" 
                  class="bg-red-500 text-white px-2 py-1 rounded">Hapus</a>
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

   <!-- Tombol tambah data -->
   <div class="absolute bottom-4 left-4">
      <a href="createMember.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
   </div>
</main>

<?php 
require "../components/footer.php";
?>
