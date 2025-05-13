<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/adminProses.php';
require "../components/header.php";

$isEdit = isset($_GET['id_karyawan']);
$admin = $isEdit ? findAdminById($conn, $_GET['id_karyawan']) : null;

$loggedId = $_SESSION['logged']['id_karyawan'];
$hasAccess = !$isEdit || ($isEdit && $loggedId == $admin['id_karyawan']);

// var_dump($admin);
// var_dump($_SESSION['logged']);
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <!-- Tombol Kembali -->
  <div class="absolute top-6 left-6">
    <a href="admin.php" class="flex items-center text-gray-700 hover:text-highlight transition">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="font-medium">Kembali</span>
    </a>
  </div>

  <!-- Card Form -->
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6">
      <?= $isEdit ? 'Edit Admin' : 'Tambah Admin' ?>
    </h2>

    <form action="../../php/adminProses.php" method="POST" enctype="multipart/form-data" class="space-y-5">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id_karyawan" value="<?= $admin['id_karyawan'] ?>">
      <?php endif; ?>

      <!-- Nama -->
      <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" id="nama" name="nama"
          value="<?= $admin['username_karyawan'] ?? '' ?>"
          pattern="[A-Za-z\s]{2,50}"
          title="Hanya huruf dan spasi (2-50 karakter)"
          required
          <?= !$hasAccess ? 'readonly' : '' ?>
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email"
          value="<?= $admin['email_karyawan'] ?? '' ?>"
          required
          <?= !$hasAccess ? 'readonly' : '' ?>
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password"
          minlength="8" maxlength="255"
          <?= $isEdit ? '' : 'required' ?>
          placeholder="<?= $isEdit ? 'Kosongkan jika tidak diganti' : '' ?>"
          <?= !$hasAccess ? 'readonly' : '' ?>
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Konfirmasi Password -->
      <div>
        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword"
          minlength="8" maxlength="255"
          <?= $isEdit ? '' : 'required' ?>
          placeholder="<?= $isEdit ? 'Kosongkan jika tidak diganti' : '' ?>"
          <?= !$hasAccess ? 'readonly' : '' ?>
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Upload Gambar -->
      <div>
        <label for="gambarAdmin" class="block text-sm font-medium text-gray-700 mb-1">Foto Admin</label>
        <input type="file" id="gambarAdmin" name="gambarAdmin"
          accept="image/png, image/jpeg, image/jpg"
          <?= !$hasAccess ? 'disabled' : '' ?>
          class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-highlight file:text-white hover:file:brightness-110">
        
        <?php if ($isEdit && $admin['gambar_karyawan']): ?>
          <p class="text-xs text-gray-500 mt-1">Gambar sekarang: <?= $admin['gambar_karyawan'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Tombol -->
      <?php if ($hasAccess): ?>
        <button type="submit"
          class="w-full bg-highlight text-white py-2 rounded-md hover:brightness-110 transition font-semibold">
          <?= $isEdit ? 'Update Admin' : 'Tambah Admin' ?>
        </button>
      <?php elseif(!$hasAccess): ?>
        <button type="button" disabled
          class="w-full bg-highlight text-white py-2 rounded-md cursor-not-allowed font-semibold">
          Tanpa Akses!
        </button>
      <?php endif; ?>
    </form>
  </div>
</main>

</body>
</html>
