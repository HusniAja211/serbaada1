<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/presensiProses.php';

require "../components/header.php";

?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <!-- Tombol Kembali -->
  <div class="absolute top-6 left-6">
    <a href="jualan.php" class="flex items-center text-gray-700 hover:text-highlight transition">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="font-medium">Kembali</span>
    </a>
  </div>

  <!-- Card Form -->
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6">Presensi Sekarang</h2>

    <form action="../../php/presensiProses.php" method="POST" class="space-y-5">

      <input type="number" id="id_karyawan" name="id_karyawan" hidden value="<?= $_SESSION['logged']['id_karyawan'] ?>" />

      <!-- Nama -->
      <div>
        <label for="nama_karyawan" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" readonly required
        id="nama_karyawan" name="nama_karyawan" value="<?= $_SESSION['logged']['nama_karyawan'] ?>"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Hari -->
      <div>
        <label for="hari_presensi" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
        <input type="date" required
        id="hari_presensi" name="hari_presensi" value="<?= date('Y-m-d') ?>"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <!-- Status -->
      <div>
        <label for="status_presensi" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select id="status_presensi" name="status_presensi" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
          <option value="" disabled selected>Pilih status</option>
          <option value="Hadir">Hadir</option>
          <option value="Izin">Izin</option>
          <option value="Sakit">Sakit</option>
          <option value="Tidak Hadir">Tidak Hadir</option>
        </select>
      </div>

      <!-- Button -->
      <button type="submit" name="submit" class="w-full bg-highlight text-white py-2 rounded-md hover:brightness-110 transition font-semibold">
        Kirim Data Presensi
      </button>

    </form>
  </div>
</main>

</body>
</html>
