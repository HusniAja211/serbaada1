<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/produkProses.php';
require "../components/header.php";

// Cek apakah dalam mode edit
$editMode = false;
$produk = [
  'id_produk' => '',
  'nama_produk' => '',
  'tanggal_expired' => '',
  'stok_produk' => '',
  'uang_modal_produk' => '',
  'harga_jual_produk' => '',
  'fid_kategori' => '',
  'gambar_produk' => '',
  'deskripsi_produk' => ''
];

$selectKategori = selectKategori($conn); // Ambil data kategori dari database

// Jika ada parameter id di URL, masuk ke mode edit
if (isset($_GET['id'])) {
  $editMode = true;
  $produk = find($conn, $_GET['id']);
}
?>

<main class="bg-gray-50 min-h-screen flex flex-col items-center justify-center px-4 py-10">
  <!-- Tombol Kembali -->
  <div class="self-start mb-4 ml-4">
    <a href="produk.php" class="inline-flex items-center text-gray-600 hover:text-highlight transition">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="font-medium">Kembali</span>
    </a>
  </div>

  <!-- Card Form -->
  <div class="bg-white shadow-lg rounded-xl w-full max-w-3xl p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6"><?= $editMode ? 'Update Produk' : 'Tambah Produk' ?></h2>

    <form action="../../php/produkProses.php" method="POST" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="action" value="<?= $editMode ? 'update' : 'store' ?>">
      <?php if ($editMode): ?>
        <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
      <?php endif; ?>

      <!-- Grid untuk form dan gambar -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Form Kiri -->
        <div class="space-y-4">
          <?php
            $fields = [
              'nama_produk' => ['label' => 'Nama Produk', 'type' => 'text', 'pattern' => '[A-Za-z0-9\s\.\-\,]+'],
              'tanggal_expired' => ['label' => 'Tanggal Kadaluwarsa', 'type' => 'date'],
              'stok_produk' => ['label' => 'Stok', 'type' => 'number', 'min' => '0', 'max' => '100000'],
              'uang_modal_produk' => ['label' => 'Modal', 'type' => 'number', 'min' => '0', 'step' => '0.01'],
              'harga_jual_produk' => ['label' => 'Harga Jual', 'type' => 'number', 'min' => '0', 'step' => '0.01']
            ];

          foreach ($fields as $name => $info): 
            $id = $name;
            $value = $produk[$name] ?? '';
          ?>
          <div>
            <label for="<?= $id ?>" class="block text-sm font-medium text-gray-700 mb-1"><?= $info['label'] ?></label>
            <input type="<?= $info['type'] ?>" id="<?= $id ?>" name="<?= $id ?>" value="<?= htmlspecialchars($value) ?>"
              <?= isset($info['pattern']) ? "pattern=\"{$info['pattern']}\"" : '' ?>
              <?= isset($info['min']) ? "min=\"{$info['min']}\"" : '' ?>
              <?= isset($info['max']) ? "max=\"{$info['max']}\"" : '' ?>
              <?= isset($info['step']) ? "step=\"{$info['step']}\"" : '' ?>
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none shadow-sm">
          </div>
          <?php endforeach; ?>

          <!-- Kategori -->
          <div>
            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select id="kategori" name="kategori" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none shadow-sm">
              <option value="" disabled <?= $editMode ? '' : 'selected' ?>>Pilih Kategori</option>
              <?php foreach ($selectKategori as $kategori): ?>
              <option value="<?= $kategori['id_kategori'] ?>" <?= $editMode && $produk['fid_kategori'] == $kategori['id_kategori'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($kategori['nama_kategori']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Form Kanan -->
        <div class="space-y-4">
          <!-- Gambar Produk -->
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
            <?php if ($editMode && $produk['gambar_produk']): ?>
              <div class="mb-3 flex justify-center">
                <img src="/serbaada1/public/resources/img/gambarProduk/<?= htmlspecialchars($produk['gambar_produk']) ?>" 
                     alt="Preview Gambar Produk" 
                     class="w-40 h-40 object-contain rounded-md border">
              </div>
            <?php endif; ?>
            <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp"
              class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 file:bg-highlight file:text-white file:font-medium file:px-4 file:py-2 file:rounded-md hover:file:brightness-110">
          </div>

          <!-- Barcode -->
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-2">Barcode Produk</label>
            <?php if ($editMode && $produk['id_produk']): ?>
              <div class="flex justify-center">
                <img src="/serbaada1/public/resources/img/gambarBarcode/<?= htmlspecialchars($produk['id_produk']) ?>.png" 
                     alt="Barcode Produk" 
                     class="w-40 h-28">
              </div>
              <p class="text-xs text-center text-gray-500 mt-2">Barcode otomatis dari ID produk</p>
            <?php else: ?>
              <div class="text-center text-sm text-gray-400 bg-white border border-dashed rounded-md p-3">
                Barcode akan muncul setelah disimpan
              </div>
            <?php endif; ?>
          </div>

          <!-- Deskripsi -->
          <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" required minlength="5" maxlength="500"
              class="w-full px-4 py-2 border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-highlight focus:outline-none h-32 shadow-sm"
              placeholder="Tulis deskripsi produk..."><?= htmlspecialchars($produk['deskripsi_produk']) ?></textarea>
          </div>
        </div>
      </div>

      <!-- Tombol Submit -->
      <div class="pt-6">
        <button type="submit"
          class="w-full bg-highlight text-white py-3 rounded-md hover:brightness-110 transition font-semibold text-base tracking-wide shadow-md">
          <?= $editMode ? 'Update Produk' : 'Tambah Produk' ?>
        </button>
      </div>
    </form>
  </div>
</main>


</body>
</html>