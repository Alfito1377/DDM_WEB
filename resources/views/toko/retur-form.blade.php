@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/html5-qrcode"></script>

<div class="space-y-4 sm:space-y-6 max-w-3xl mx-auto">
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="mb-5 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Ajukan Retur Benih</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi data produk yang bermasalah untuk diajukan.</p>
        </div>

        <form id="formRetur" class="space-y-4 sm:space-y-5">
            @csrf
            
            {{-- PILIH TOKO JIKA PEKERJA LAPANG --}}
            @if(Auth::user()->role->role_name === 'pekerja_lapang')
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Pilih Toko Asal Retur</label>
                    <select id="store_id" name="store_id" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition bg-white font-medium">
                        <option value="" disabled selected>Pilih Toko...</option>
                        @if(isset($stores))
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @else
                <input type="hidden" id="store_id" name="store_id" value="{{ Auth::user()->store_id ?? 1 }}">
            @endif

            {{-- BARCODE --}}
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">ID Produk / Barcode SKU</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 001 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <input type="text" id="barcode" name="barcode" required class="w-full border border-gray-300 py-3 pl-11 sm:pl-12 pr-[78px] sm:pr-24 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-sm font-medium" placeholder="Ketikan ID atau Scan Barcode...">
                    <div class="absolute inset-y-0 right-1.5 flex items-center">
                        <button type="button" onclick="openBarcodeScanner()" class="bg-green-100 text-green-700 hover:bg-green-200 font-bold text-[10px] sm:text-xs px-2 sm:px-3 py-1.5 rounded-lg flex items-center gap-1 sm:gap-1.5 transition border border-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Scan</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- JUMLAH + ALASAN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Jumlah (Pack)</label>
                    <input type="number" id="quantity" name="quantity" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition font-medium" min="1" placeholder="Misal: 5">
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Alasan Retur</label>
                    <select id="reason" name="reason" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition bg-white font-medium">
                        <option value="" disabled selected>Pilih Alasan...</option>
                        <option value="Cacat">Kemasan Cacat</option>
                        <option value="Rusak">Benih Rusak / Berkutu</option>
                        <option value="Kedaluwarsa">Kedaluwarsa</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- CATATAN --}}
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Keterangan / Catatan Manual</label>
                <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition font-medium" placeholder="Tuliskan detail kerusakan atau catatan manual di sini..."></textarea>
            </div>

            {{-- FOTO --}}
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Foto Bukti Kerusakan</label>
                <input type="file" id="proof_images" name="proof_image[]" accept="image/png, image/jpeg" multiple required class="w-full border border-gray-300 p-2 rounded-xl text-xs sm:text-sm outline-none file:mr-2 sm:file:mr-3 file:py-1.5 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-bold hover:file:bg-green-100 cursor-pointer transition">
                <p class="text-[9px] sm:text-[10px] text-gray-400 mt-1.5">*Maksimal 5MB. Dapat memilih lebih dari satu foto sekaligus (JPG/PNG).</p>
            </div>

            {{-- SUBMIT --}}
            <div class="pt-3 sm:pt-4 border-t border-gray-100 mt-2">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 sm:py-3.5 px-6 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL SCANNER BARCODE --}}
<div id="barcodeModal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-2 sm:p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm transform transition-all overflow-hidden flex flex-col">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex justify-between items-center gap-3 bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-base sm:text-lg">Scan Barcode Benih</h3>
            <button onclick="closeBarcodeScanner()" class="shrink-0 text-gray-400 hover:text-red-500 transition bg-white rounded-full p-1 border border-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 sm:p-6">
            <div class="relative w-full bg-black rounded-xl overflow-hidden mb-4 border border-gray-200" style="aspect-ratio: 1/1;">
                <div id="barcode-reader" class="absolute inset-0 w-full h-full border-none"></div>
            </div>
            <p class="text-[11px] sm:text-xs text-center text-gray-500 font-medium leading-relaxed">Arahkan garis merah pemindai tepat ke barcode pada kemasan produk benih.</p>
        </div>
    </div>
</div>

<style>
    #barcode-reader video {
        object-fit: cover;
        border-radius: 0.75rem;
        width: 100% !important;
        height: 100% !important;
    }
    #barcode-reader__dashboard_section_csr span {
        display: none !important;
    }
    @media (max-width: 360px) {
        #barcode { padding-right: 72px !important; }
        #barcodeModal .p-4 { padding-left: 12px; padding-right: 12px; }
    }
</style>

<script>
    let barcodeScanner;
    let isBarcodeScanning = false;

    function openBarcodeScanner() {
        document.getElementById('barcodeModal').classList.remove('hidden');
        barcodeScanner = new Html5Qrcode("barcode-reader");
        const config = { fps: 10, qrbox: { width: 250, height: 120 } };

        barcodeScanner.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                const inputField = document.getElementById('barcode');
                inputField.value = decodedText;
                inputField.classList.add('ring-2', 'ring-green-500', 'bg-green-50');
                setTimeout(() => {
                    inputField.classList.remove('ring-2', 'ring-green-500', 'bg-green-50');
                }, 1500);
                closeBarcodeScanner();
            },
            (errorMessage) => {}
        ).then(() => {
            isBarcodeScanning = true;
        }).catch((err) => {
            alert("Gagal mengakses kamera. Pastikan izin browser telah diberikan.");
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

    document.getElementById('formRetur').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses Data...';
        btnSubmit.disabled = true;

        let formData = new FormData();
        formData.append('store_id', document.getElementById('store_id').value);
        formData.append('barcode', document.getElementById('barcode').value);
        formData.append('quantity', document.getElementById('quantity').value);
        formData.append('reason', document.getElementById('reason').value);

        const notesInput = document.getElementById('notes');
        if (notesInput) {
            formData.append('notes', notesInput.value);
        }

        const imageInput = document.getElementById('proof_images');
        for (let i = 0; i < imageInput.files.length; i++) {
            formData.append('proof_images[]', imageInput.files[i]);
        }

        try {
            // Pekerja lapang submits to /lapangan/retur (or /toko/retur), but wait, the endpoint is same for both actually: 
            // web.php shows POST /lapangan/retur for pekerja lapang and POST /toko/retur for toko.
            // Using window.location.pathname ensures it posts to the current URL.
            const url = window.location.pathname; 
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const result = await response.json();
            
            if (response.ok && result.success) {
                alert('Berhasil: ' + (result.message || 'Retur diajukan!'));
                window.location.reload();
            } else if (response.status === 422) {
                let errorString = 'Terdapat data yang tidak valid:\n';
                for (const [field, messages] of Object.entries(result.errors)) {
                    errorString += `- ${messages[0]}\n`;
                }
                alert(errorString);
            } else {
                alert('Gagal: ' + (result.message || 'Terjadi kesalahan pada sistem.'));
            }
        } catch (error) {
            console.error("Detail Error:", error);
            alert('Terjadi kesalahan gagal menghubungi server. Cek koneksi Anda.');
        } finally {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    });
</script>
@endsection
