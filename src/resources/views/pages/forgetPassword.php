<?php 
session_start(); 
require "../components/header.php"; 
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-300">
    <h2 class="text-2xl font-semibold text-center text-black mb-6">Lupa Password</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="text-red-600 text-sm text-center mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['step']) || $_SESSION['step'] == 1): ?>
      <!-- Step 1: Email -->
      <form action="../../php/forgetPassProses.php" method="POST" class="space-y-5">
        <div>
          <label for="email" class="block text-lg font-semibold text-black mb-2">Email</label>
          <input type="email" id="email" name="email"
              required placeholder="contoh@domain.com"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <div class="flex justify-end pt-2">
          <button type="submit" name="valemail"
              class="bg-highlight text-white px-4 py-2 rounded-md font-semibold hover:brightness-110 transition">
            Selanjutnya
          </button>
        </div>
      </form>

    <?php elseif ($_SESSION['step'] == 2): ?>
      <!-- Step 2: Update Password -->
      <form action="../../php/forgetPassProses.php" method="POST" class="space-y-5">
        <div>
          <label for="otp" class="block text-lg font-semibold text-black mb-2">Kode OTP</label>
          <input type="text" id="otp" name="otp"
              required placeholder="Masukkan kode OTP dari email"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <div>
          <label for="new_password" class="block text-lg font-semibold text-black mb-2">Password Baru</label>
          <input type="password" id="new_password" name="new_password"
              required minlength="8" maxlength="50" placeholder="Minimal 8 karakter"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <div>
          <label for="confirm_password" class="block text-lg font-semibold text-black mb-2">Konfirmasi Password</label>
          <input type="password" id="confirm_password" name="confirm_password"
              required minlength="8" maxlength="50" placeholder="Ulangi password baru"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <div class="flex justify-between pt-2">
          <button type="submit" name="cancel"
              class="bg-gray-300 text-black px-4 py-2 rounded-md font-semibold hover:brightness-95 transition">
            Batal
          </button>

          <button type="submit" name="update_password"
              class="bg-highlight text-white px-4 py-2 rounded-md font-semibold hover:brightness-110 transition">
            Perbarui Password
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
