function konfirmasiLogout() {
    const yakin = confirm('Apakah kamu yakin ingin keluar?');
    if (yakin) {
        // Redirect ke halaman logout atau lakukan aksi logout
        window.location.href = '/logout'; // Ganti sesuai kebutuhan
    } else {
        // Tidak melakukan apa-apa (user pilih 'Tidak')
        console.log('User membatalkan logout');
    }
}