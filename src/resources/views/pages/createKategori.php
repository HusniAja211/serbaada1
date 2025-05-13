<?php
require '../../php/kategoriProses.php';
require '../../../database/connection.php';

require $_SERVER['DOCUMENT_ROOT'] . '/serbaada/src/resources/views/components/header.php';


$id_kategori = $_GET['id_kategori'] ?? null;
$editData = $id_kategori ? find($conn, $id_kategori) : null;
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
    <!-- Tombol Kembali -->
    <div class="absolute top-6 left-6">
        <a href="kategori.php" class="flex items-center text-gray-700 hover:text-highlight transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-medium">Kembali</span>
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
        <h2 class="text-2xl font-semibold text-center text-highlight mb-6">
        <?= $id_kategori ? "Edit Kategori" : "Tambah Kategori" ?>
        </h2>

        <form action="../../php/kategoriProses.php" method="POST" class="space-y-5">
            <?php if($editData) :?>
                <input type="hidden" name="id_kategori" value="<?= $editData['id_kategori'] ?>">
            <?php endif; ?>

            <!-- Nama Kategori -->
            <div>
                <label for="namaKategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" id="namaKategori" name="namaKategori"
                       required pattern="[A-Za-z\s]{2,50}"
                       title="Nama hanya boleh mengandung huruf dan spasi, panjang 2-50 karakter"
                       value="<?= $id_kategori ? htmlspecialchars($editData['nama_kategori']) : '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" 
                        required minlength="5" maxlength="500"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-highlight focus:outline-none">
                        <?= $id_kategori ? htmlspecialchars($editData['deskripsi_kategori']) : '' ?>
                </textarea>

            </div>

            <!-- Button -->
            <button type="submit" name="action" value="create_or_edit"
                    class="w-full bg-highlight text-white py-2 rounded-md hover:brightness-110 transition font-semibold">
               Simpan Data
            </button>
        </form>
    </div>
</main>
</body>
</html>