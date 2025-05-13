<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

let scannerStarted = false;

document.getElementById('reader').addEventListener('click', function () {
    if (scannerStarted) return;

    const readerDiv = document.getElementById('reader');
    readerDiv.innerHTML = ''; // Kosongkan isi agar kamera bisa tampil
    readerDiv.style.background = 'transparent'; // Hilangkan latar abu-abu

    const html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        (decodedText, decodedResult) => {
            console.log("Scanned:", decodedText);

            fetch('tambahPesanan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_produk: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ Produk ditambahkan: ' + data.produk.nama + ' (Qty: ' + data.produk.jumlah + ')');
                } else {
                    alert('❌ Gagal: ' + data.message);
                }

                html5QrCode.stop().then(() => {
                    scannerStarted = false;
                    readerDiv.innerHTML = 'Klik di sini untuk membuka kamera';
                    readerDiv.style.background = '#f3f3f3';
                });
            });
        },
        (errorMessage) => {
            // console.log("Scanning error", errorMessage);
        }
    ).then(() => {
        scannerStarted = true;
    }).catch(err => {
        alert("Tidak bisa mengakses kamera: " + err);
    });
});