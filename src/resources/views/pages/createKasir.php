<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/kasirProses.php';
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/database/connection.php';
require "../components/header.php";

// Cek apakah mode edit
$id_karyawan = $_GET['id_karyawan'] ?? null;
$editData = $id_karyawan ? find($conn, $id_karyawan) : null;
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <!-- Tombol Kembali -->
  <div class="absolute top-6 left-6">
    <a href="kasir.php" class="flex items-center text-gray-700 hover:text-highlight transition">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="font-medium">Kembali</span>
    </a>
  </div>

  <!-- Card Form -->
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6">
      <?= $id_karyawan ? "Edit Kasir" : "Tambah Kasir" ?>
    </h2>

    <form action="../../php/kasirProses.php" method="POST" enctype="multipart/form-data" class="space-y-5">
      <?php if ($id_karyawan): ?>
        <input type="hidden" name="id_kasir" value="<?= htmlspecialchars($editData['id_karyawan']) ?>">
      <?php endif; ?>

      <!-- Nama -->
      <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" id="nama" name="nama"
          required
          pattern="[A-Za-z\s]{2,50}"
          title="Hanya huruf dan spasi, 2-50 karakter"
          value="<?= $id_karyawan ? htmlspecialchars($editData['username_karyawan']) : '' ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email"
          required
          value="<?= $id_karyawan ? htmlspecialchars($editData['email_karyawan']) : '' ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password"
          <?= $id_karyawan ? '' : 'required' ?>
          minlength="6" maxlength="32"
          placeholder="<?= $id_karyawan ? 'Kosongkan jika tidak ingin mengubah' : '' ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword"
          <?= $id_karyawan ? '' : 'required' ?>
          minlength="6" maxlength="32"
          placeholder="<?= $id_karyawan ? 'Kosongkan jika tidak ingin mengubah' : '' ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Gambar Kasir -->
      <div>
        <label for="gambarKasir" class="block text-sm font-medium text-gray-700 mb-1">Foto Kasir</label>
        <input type="file" id="gambarKasir" name="gambarKasir"
          <?= $id_karyawan ? '' : 'required' ?>
          accept="image/jpeg, image/png"
          class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-highlight file:text-white hover:file:brightness-110">
        <?php if ($id_karyawan && $editData['gambar_karyawan']): ?>
          <img src="/serbaada1/public/uploads/<?= htmlspecialchars($editData['gambar_karyawan']) ?>" alt="Foto Kasir" class="mt-2 h-20 rounded-md">
        <?php endif; ?>
      </div>

      <!-- Button -->
      <?php if($_SESSION['logged']['level_karyawan'] == 'admin') :?>
      <button type="submit" class="w-full bg-highlight text-white py-2 rounded-md hover:brightness-110 transition font-semibold">
        <?= $id_karyawan ? "Update Kasir" : "Tambah Kasir" ?>
      </button>
      <?php else: ?>
      <?php endif; ?>
    </form>
  </div>
</main>
</body>
</html>