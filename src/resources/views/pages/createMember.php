<?php
require '../../php/memberProses.php';
require '../../../database/connection.php';

$action = 'create';
$memberData = [
    'nama_member' => '',
    'no_telepon_member' => '',
    'point_member' => 0,
    'status_member' => 'aktif'
];

// Cek jika ini halaman edit
if (isset($_GET['id_member'])) {
    $id_member = $_GET['id_member'];
    $result = find($conn, $id_member);
    if ($result['success']) {
        $action = 'edit';
        $memberData = $result['data'];
    } else {
        header('Location: ../member.php?status=member tidak ditemukan');
        exit;
    }
}

require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/views/components/header.php';
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <div class="absolute top-6 left-6">
    <a href="member.php" class="flex items-center text-gray-700 hover:text-highlight transition">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      <span class="font-medium">Kembali</span>
    </a>
  </div>

  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-200">
    <h2 class="text-2xl font-semibold text-center text-highlight mb-6">
      <?= $action === 'edit' ? 'Edit Member' : 'Tambah Member' ?>
    </h2>

    <form action="" method="POST" class="space-y-5">
      <input type="hidden" name="action" value="<?= $action ?>">
      <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id_member" value="<?= htmlspecialchars($memberData['id_member']) ?>">
      <?php endif; ?>

      <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input type="text" id="nama" name="nama"
          value="<?= htmlspecialchars($memberData['nama_member']) ?>"
          minlength="2" maxlength="100"
          required
          pattern="[A-Za-z\s]{2,50}"
          title="Nama hanya boleh mengandung huruf dan spasi, panjang 2-100 karakter"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
      </div>

      <div class="flex items-end gap-2">
        <!-- Kode Negara (+62) -->
        <div class="flex-1"> 
          <label for="kodeNegara" class="block text-sm font-medium text-gray-700 mb-1">Kode Negara</label>
          <input type="text" id="kodeNegara" value="+62"
            readonly
            class="w-16 px-1 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none text-center">
        </div>

        <!-- Nomor Telepon - Fleksibel -->
        <div class="flex-1">
          <label for="nomorTelepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
          <input type="tel" id="nomorTelepon" name="nomorTelepon"
            value="<?= htmlspecialchars($memberData['no_telepon_member']) ?>"
            required
            pattern="^[0-9]{8,11}$"
            placeholder="8123456789"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>
      </div>


      <button type="submit" class="w-full bg-highlight text-white py-2 rounded-md hover:brightness-110 transition font-semibold">
        <?= $action === 'edit' ? 'Update Member' : 'Tambah Member' ?>
      </button>
    </form>
  </div>
</main>
</body>
</html>
