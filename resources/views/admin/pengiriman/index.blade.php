@extends('layouts.app')

@section('content')
<!-- Memanggil Library HTML5 QR Code (Diletakkan di atas agar siap dipakai) -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Data Pengiriman</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pengiriman barang.</p>
        </div>
        <button onclick="openProductModal()" class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Produk Benih</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Visual Barcode (Code 128)</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $produk)
                    <tr class="hover:bg-gray-50 transition items-center">
                        <td class="px-6 py-4 font-bold text-green-700 text-base">{{ $produk->product_name }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center justify-center p-2 bg-white border border-gray-200 rounded-lg inline-block">
                                <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $produk->barcode }}&includetext=true&scale=2&height=10" 
                                     alt="Barcode {{ $produk->barcode }}" 
                                     class="h-12 object-contain mix-blend-multiply">
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded-lg transition">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada produk yang terdaftar di sistem.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div id="productModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all overflow-hidden flex flex-col">
        
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Tambah Produk Benih</h3>
            <button onclick="closeProductModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6">
            <form id="formProduct" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Produk Benih</label>
                    <input type="text" name="product_name" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Contoh: Padi Inpari 32">
                </div>
                
                <!-- 👇 PERUBAHAN: Input Barcode + Tombol Kamera -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Kode Barcode / SKU</label>
                    <div class="flex gap-2">
                        <input type="text" id="barcodeInput" name="barcode" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition font-mono tracking-widest" placeholder="Scan atau Ketik Manual">
                        
                        <button type="button" onclick="startScanner()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl transition flex items-center gap-2 shadow-sm whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Scan
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Sistem akan otomatis men-generate gambar barcodenya.</p>

                    <!-- Layar Kamera (Tersembunyi secara default) -->
                    <div id="reader-container" class="hidden mt-3 p-2 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                        <div id="reader" width="100%"></div>
                        <button type="button" onclick="stopScanner()" class="mt-2 w-full bg-red-100 text-red-600 hover:bg-red-200 font-bold py-2 rounded-lg transition text-sm">
                            Tutup Kamera
                        </button>
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                        Simpan & Buat Barcode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // --- LOGIKA SCANNER KAMERA ---
    let html5QrcodeScanner = null;

    function startScanner() {
        document.getElementById('reader-container').classList.remove('hidden');

        if (!html5QrcodeScanner) {
            // Inisialisasi scanner
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { fps: 10, qrbox: {width: 250, height: 100} }, 
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                html5QrcodeScanner = null;
                document.getElementById('reader-container').classList.add('hidden');
            }).catch(error => {
                console.error("Gagal mematikan scanner: ", error);
            });
        } else {
            document.getElementById('reader-container').classList.add('hidden');
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // 1. Masukkan hasil scan ke kolom input
        document.getElementById('barcodeInput').value = decodedText;
        
        // 2. Bunyikan efek suara
        playBeepSound();

        // 3. Matikan kamera
        stopScanner();
    }

    function onScanFailure(error) {
        // Biarkan kosong, scanner akan terus mencari sampai berhasil
    }

    function playBeepSound() {
        // Efek Suara Beep Sintetis
        const context = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = context.createOscillator();
        const gainNode = context.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(context.destination);
        oscillator.type = 'sine';
        oscillator.frequency.value = 800; // Nada
        gainNode.gain.setValueAtTime(1, context.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, context.currentTime + 0.1);
        oscillator.start(context.currentTime);
        oscillator.stop(context.currentTime + 0.1);
    }

    // --- LOGIKA MODAL & FORM ---
    function openProductModal() {
        Swal.fire({
            title: "Apakah anda ingin memperbarui data pengiriman?",
            showCancelButton: true,
            confirmButtonText: "Iya!",
        }).then((result) => {
            if (result.isConfirmed) {
                // cek pengiriman aktif
            };
        });
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.getElementById('formProduct').reset();
        stopScanner(); // Pastikan kamera mati jika modal ditutup paksa
    }

    document.getElementById('formProduct').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Menyimpan...';
        btnSubmit.disabled = true;

        let formData = new FormData(this);

        try {
            const response = await fetch('/admin/produk', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.location.reload();
            } else {
                alert(result.message || 'Gagal menyimpan data.');
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