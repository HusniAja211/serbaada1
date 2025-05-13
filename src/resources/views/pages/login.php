<?php 
require "../components/header.php";
require $_SERVER['DOCUMENT_ROOT'] . '/serbaada1/src/resources/php/loginProses.php';
?>

<main class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4">
  <!-- Card Form -->
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8 border border-gray-300">
    <h2 class="text-2xl font-semibold text-center text-black mb-6">Masuk</h2>

    <form action="../../php/loginProses.php" method="POST" class="space-y-5">
        <!-- Email -->
        <div>
          <label for="email" class="block text-lg font-semibold text-black mb-2">Email</label>
          <input type="email" id="email" name="email"
              required
              placeholder="contoh@domain.com"
              pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
              title="Masukkan alamat email yang valid"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-lg font-semibold text-black mb-2">Password</label>
          <input type="password" id="password" name="password" 
              required
              minlength="6" maxlength="50"
              placeholder="Tulis password Anda"
              class="w-full px-4 py-3 border border-highlight rounded-md focus:ring-2 focus:ring-highlight focus:outline-none">
        </div>

        <!-- Remember me + Lupa password + Login -->
        <div class="flex items-center justify-between pt-2">
          <!-- Checkbox -->
          <label class="inline-flex items-center">
            <input type="checkbox" class="form-checkbox w-5 h-5 text-black border-highlight rounded-md">
            <span class="ml-2 text-sm text-black">Ingat Saya</span>
          </label>

          <!-- Link -->
          <a href="forgetPassword.php" class="text-sm text-purple-600 hover:underline">Lupa Password?</a>

          <!-- Button -->
          <button type="submit" class="bg-highlight text-white px-4 py-2 rounded-md font-semibold hover:brightness-110 transition">
            Masuk
          </button>
    </form>
  </div>
</main>
</body>
</html>
