<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/adminProses.php';
require "../components/header.php";
require "../components/sidebar.php";

$admins = index($conn);
?>

<main class="p-4 sm:ml-64 bg-gray-200 min-h-screen flex flex-col relative">
    <div class="flex flex-row justify-center">
        <h1 class="text-2xl font-semibold mb-4">Data Admin</h1>
    </div>

    <div class="overflow-x-auto rounded shadow mb-6 flex justify-center">
        <table class="min-w-full w-3/5 table-auto border-collapse border border-black text-sm">
            <thead class="bg-sky-500 text-white">
                <tr>
                    <th class="border border-black px-2 py-1 text-center">ID Karyawan</th>
                    <th class="border border-black px-2 py-1 text-center">Email Admin</th>
                    <th class="border border-black px-2 py-1 text-center">Username Admin</th>
                    <th class="border border-black px-2 py-1 text-center">Gambar Admin</th>
                    <th class="border border-black px-2 py-1 text-center">Selengkapnya</th>
                    <th class="border border-black px-2 py-1 text-center">Opsi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
            <?php if (empty($admins)): ?>
            <tr>
               <td colspan="6" class="border border-black px-2 py-1 text-center">Tidak ada data Admin</td>
            </tr>
            <?php endif; ?>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td class="border border-black px-2 py-1 text-center"><?= $admin['id_karyawan'] ?></td>
                    <td class="border border-black px-2 py-1"><?= $admin['email_karyawan'] ?></td>
                    <td class="border border-black px-2 py-1"><?= $admin['username_karyawan'] ?></td>
                    <td class="border border-black px-2 py-1"><?= $admin['gambar_karyawan'] ?></td>
                    <td class="border border-black px-2 py-1 text-center">
                        <a href="createAdmin.php?id_karyawan=<?= $admin['id_karyawan'] ?>" class="text-sky-600 hover:underline hover:text-sky-800">Selengkapnya</a>
                    </td>
                    <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
                    <td class="border border-black px-2 py-1 text-center">
                        <a href="createAdmin.php?id_karyawan=<?= $admin['id_karyawan'] ?>" class="bg-green-400 text-white px-2 py-1 rounded mr-2">Edit</a>
                        <a href="../../php/adminProses.php?delete=<?= $admin['id_karyawan'] ?>" onclick="return confirm('Yakin ingin menghapus admin ini?')" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</a>
                    </td>
                    <?php else: ?>
                        <td class="border border-black px-2 py-1 text-center">Tidak ada akses!</td>
                    <?php endif; ?>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<!-- 
    <div class="absolute bottom-16 left-4 flex justify-center items-center space-x-4 mb-6 w-full">
        <?php require "../components/pagination.php"; ?>
    </div> -->

    <div class="absolute bottom-4 left-4">
        <a href="createAdmin.php" class="inline-block bg-sky-500 text-white px-6 py-2 rounded hover:bg-sky-600">Tambah Data</a>
    </div>
</main>

<?php require "../components/footer.php"; ?>
