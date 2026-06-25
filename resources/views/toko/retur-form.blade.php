@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/html5-qrcode"></script>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Form Pengajuan Retur</h1>
            <p class="text-sm text-gray-500 mt-1">Ajukan pengembalian benih yang bermasalah atau kedaluwarsa dengan cepat.</p>
        </div>
        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Panel Toko</span>
    </div>

    <form id="formRetur" class="space-y-5">
        <input type="hidden" id="store_id" name="store_id" value="{{ Auth::user()->store_id ?? 1 }}"> 
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">ID Produk / Barcode SKU</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <input type="text" id="product_id" name="product_id" required class="w-full border border-gray-300 py-3 pl-12 pr-24 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Ketikan manual atau scan...">
                
                <div class="absolute inset-y-0 right-1 flex items-center">
                    <button type="button" onclick="openBarcodeScanner()" class="bg-green-100 text-green-700 hover:bg-green-200 font-bold text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Scan
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Jumlah Diretur (Pack)</label>
                <input type="number" id="quantity" name="quantity" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" min="1" placeholder="0">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Alasan Retur</label>
                <select id="reason" name="reason" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="" disabled selected>Pilih Alasan...</option>
                    <option value="Cacat">Kemasan Cacat</option>
                    <option value="Rusak">Benih Rusak / Berkutu</option>
                    <option value="Kedaluwarsa">Kedaluwarsa</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>

        <div>
    <label class="block text-sm font-bold text-gray-700 mb-1.5">Unggah Foto Bukti (Bisa Banyak Foto)</label>
    <input type="file" id="proof_images" name="proof_images[]" accept="image/png, image/jpeg" multiple required class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
    <p class="text-[10px] text-gray-500 mt-1.5">*Format yang didukung: JPG, PNG. Anda bisa memilih lebih dari 1 foto sekaligus. Maksimal 5MB per file.</p>
</div>

        <div class="pt-4 mt-6">
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>

<div id="barcodeModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm transform transition-all overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Scan Barcode Benih</h3>
            <button onclick="closeBarcodeScanner()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6">
            <div class="relative w-full bg-black rounded-xl overflow-hidden mb-4" style="aspect-ratio: 1/1;">
                <div id="barcode-reader" class="absolute inset-0 w-full h-full border-none"></div>
            </div>
            <p class="text-xs text-center text-gray-500 font-medium">Arahkan garis merah pemindai tepat ke barcode pada kemasan produk.</p>
        </div>
    </div>
</div>

<style>
    /* Styling khusus agar scanner menyesuaikan border radius Tailwind */
    #barcode-reader video { object-fit: cover; border-radius: 0.75rem; width: 100% !important; height: 100% !important; }
    #barcode-reader__dashboard_section_csr span { display: none !important; }
</style>

<script>
    // --- LOGIKA SCANNER BARCODE 1D ---
    let barcodeScanner;
    let isBarcodeScanning = false;

    function openBarcodeScanner() {
        document.getElementById('barcodeModal').classList.remove('hidden');
        
        // Buat instance scanner
        barcodeScanner = new Html5Qrcode("barcode-reader");
        
        // Konfigurasi: qrbox dibuat memanjang (persegi panjang) karena barcode 1D biasanya lebar
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 120 } 
        };

        barcodeScanner.start({ facingMode: "environment" }, config, 
            (decodedText, decodedResult) => {
                // Jika berhasil terbaca
                document.getElementById('product_id').value = decodedText;
                
                // Beri efek visual pada input untuk menandakan berhasil masuk
                const inputField = document.getElementById('product_id');
                inputField.classList.add('ring-2', 'ring-green-500', 'bg-green-50');
                setTimeout(() => inputField.classList.remove('ring-2', 'ring-green-500', 'bg-green-50'), 1500);

                // Tutup kamera otomatis
                closeBarcodeScanner();
            },
            (errorMessage) => {
                // Mengabaikan error pencarian per frame
            }
        ).then(() => {
            isBarcodeScanning = true;
        }).catch((err) => {
            alert("Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.");
            closeBarcodeScanner();
        });
    }

    function closeBarcodeScanner() {
        document.getElementById('barcodeModal').classList.add('hidden');
        if (barcodeScanner && isBarcodeScanning) {
            barcodeScanner.stop().then(() => {
                isBarcodeScanning = false;
            }).catch(err => console.log(err));
        }
    }

    // --- LOGIKA SUBMIT FORM ---
    document.getElementById('formRetur').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses...';
        btnSubmit.disabled = true;
        
        // Cari bagian pengiriman formData di dalam tag <script> Anda, lalu sesuaikan menjadi seperti ini:
let formData = new FormData();
formData.append('store_id', document.getElementById('store_id').value);
formData.append('product_id', document.getElementById('product_id').value);
formData.append('quantity', document.getElementById('quantity').value);
formData.append('reason', document.getElementById('reason').value);

// AMBIL SEMUA GAMBAR YANG DIPILIH OLEH USER
const imageInput = document.getElementById('proof_images');
for (let i = 0; i < imageInput.files.length; i++) {
    formData.append('proof_images[]', imageInput.files[i]);
}
        try {
            const response = await fetch('/api/v1/returns', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            const result = await response.json();
            if (response.ok) {
                alert('Berhasil: ' + result.message);
                document.getElementById('formRetur').reset(); 
            } else {
                alert('Gagal: ' + (result.message || 'Cek kembali data Anda.'));
            }
        } catch (error) {
            alert('Terjadi kesalahan koneksi server.');
        } finally {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    });
</script>
@endsection