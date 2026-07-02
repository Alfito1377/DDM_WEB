@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/html5-qrcode"></script>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Retur Toko</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan pengembalian benih Anda secara real-time.</p>
            </div>
            <button onclick="openReturFormModal()" class="bg-green-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajukan Retur Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 border border-gray-100 rounded-xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-gray-500 shadow-sm border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pengajuan</p>
                    <p class="text-xl font-black text-gray-800">{{ $stats['total'] }} <span class="text-xs font-semibold text-gray-500">Berkas</span></p>
                </div>
            </div>
            
            <div class="bg-amber-50 p-4 border border-amber-100 rounded-xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Sedang Diproses</p>
                    <p class="text-xl font-black text-amber-700">{{ $stats['pending'] }} <span class="text-xs font-semibold text-amber-600/70">Berkas</span></p>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 border border-green-100 rounded-xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Retur Disetujui</p>
                    <p class="text-xl font-black text-green-700">{{ $stats['approved'] }} <span class="text-xs font-semibold text-green-600/70">Sukses</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Kode / Tanggal</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Produk Benih</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Alasan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Catatan Toko</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center tracking-wider">Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($returns as $retur)
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $retur->return_code ?? '#RET-' . str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-[11px] text-gray-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $retur->product_name }}</div>
                                <div class="text-xs text-green-600 font-bold mt-0.5">{{ $retur->quantity }} Pack</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $retur->reason }}</td>
                            
                            <td class="px-6 py-4 text-gray-500 italic text-xs font-medium">
                                {{ $retur->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($retur->status === 'Pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Pending</span>
                                @elseif($retur->status === 'Approved')
                                    <span class="bg-green-50 text-green-600 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Disetujui</span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-2">
                                <button onclick='openPhotoModal(@json($retur))' class="text-[11px] bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-2 rounded-lg transition border border-gray-200">
                                    Lihat Foto
                                </button>
                                @if ($retur->status === 'Approved')
                                    <a href="/toko/retur/{{ $retur->id }}/cetak" target="_blank" class="text-[11px] bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold px-3 py-2 rounded-lg transition border border-blue-200 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Cetak Bukti
                                    </a>
                                @elseif($retur->status === 'Pending')
                                    <button onclick="cancelReturn({{ $retur->id }})" class="text-[11px] bg-red-50 hover:bg-red-100 text-red-600 font-bold px-3 py-2 rounded-lg transition border border-red-200 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Batalkan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada pengajuan retur dari toko Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="returFormModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Ajukan Retur Benih</h3>
                <p class="text-xs text-gray-500 mt-0.5">Lengkapi data produk yang bermasalah.</p>
            </div>
            <button onclick="closeReturFormModal()" class="text-gray-400 hover:text-red-500 transition bg-white rounded-full p-1 border border-gray-200 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form id="formRetur" class="space-y-5">
                @csrf
                <input type="hidden" id="store_id" name="store_id" value="{{ Auth::user()->store_id ?? 1 }}"> 
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">ID Produk / Barcode SKU</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <input type="text" id="product_id" name="product_id" required class="w-full border border-gray-300 py-3 pl-12 pr-24 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-sm font-medium" placeholder="Ketikan ID atau Scan Barcode...">
                        
                        <div class="absolute inset-y-0 right-1.5 flex items-center">
                            <button type="button" onclick="openBarcodeScanner()" class="bg-green-100 text-green-700 hover:bg-green-200 font-bold text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition border border-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Scan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Jumlah (Pack)</label>
                        <input type="number" id="quantity" name="quantity" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition font-medium" min="1" placeholder="Misal: 5">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Alasan Retur</label>
                        <select id="reason" name="reason" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition bg-white font-medium">
                            <option value="" disabled selected>Pilih Alasan...</option>
                            <option value="Cacat">Kemasan Cacat</option>
                            <option value="Rusak">Benih Rusak / Berkutu</option>
                            <option value="Kedaluwarsa">Kedaluwarsa</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    </div> <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Keterangan / Catatan Manual</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition font-medium" placeholder="Tuliskan detail kerusakan atau catatan manual di sini..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Foto Bukti Kerusakan</label>
                    <input type="file" id="proof_images" name="proof_image[]" accept="image/png, image/jpeg" multiple required class="w-full border border-gray-300 p-2.5 rounded-xl text-sm outline-none file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-bold hover:file:bg-green-100 cursor-pointer transition">
                    <p class="text-[10px] text-gray-400 mt-1.5">*Maksimal 5MB. Dapat memilih lebih dari satu foto sekaligus (JPG/PNG).</p>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-2">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 px-6 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="barcodeModal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm transform transition-all overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Scan Barcode Benih</h3>
            <button onclick="closeBarcodeScanner()" class="text-gray-400 hover:text-red-500 transition bg-white rounded-full p-1 border border-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="relative w-full bg-black rounded-xl overflow-hidden mb-4 border border-gray-200" style="aspect-ratio: 1/1;">
                <div id="barcode-reader" class="absolute inset-0 w-full h-full border-none"></div>
            </div>
            <p class="text-xs text-center text-gray-500 font-medium leading-relaxed">Arahkan garis merah pemindai tepat ke barcode pada kemasan produk benih.</p>
        </div>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Berkas Foto Bukti Retur</h3>
                <p id="modalCode" class="text-xs text-green-600 font-mono font-bold mt-0.5">Kode</p>
            </div>
            <button onclick="closePhotoModal()" class="text-gray-400 hover:text-red-500 transition bg-white rounded-full p-1 border border-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div id="modalGallery" class="grid grid-cols-2 gap-3"></div>
        </div>
    </div>
</div>

<style>
    #barcode-reader video { object-fit: cover; border-radius: 0.75rem; width: 100% !important; height: 100% !important; }
    #barcode-reader__dashboard_section_csr span { display: none !important; }
</style>

<script>
    // --- MANAJEMEN MODAL FORM PENGAJUAN ---
    function openReturFormModal() { document.getElementById('returFormModal').classList.remove('hidden'); }
    function closeReturFormModal() { document.getElementById('returFormModal').classList.add('hidden'); }

    // --- MANAJEMEN MODAL SCANNER BARCODE ---
    let barcodeScanner;
    let isBarcodeScanning = false;

    function openBarcodeScanner() {
        document.getElementById('barcodeModal').classList.remove('hidden');
        barcodeScanner = new Html5Qrcode("barcode-reader");
        const config = { fps: 10, qrbox: { width: 250, height: 120 } };

        barcodeScanner.start({ facingMode: "environment" }, config, 
            (decodedText, decodedResult) => {
                const inputField = document.getElementById('product_id');
                inputField.value = decodedText;
                
                inputField.classList.add('ring-2', 'ring-green-500', 'bg-green-50');
                setTimeout(() => inputField.classList.remove('ring-2', 'ring-green-500', 'bg-green-50'), 1500);

                closeBarcodeScanner();
            },
            (errorMessage) => { /* Abaikan error per frame */ }
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

    // --- LOGIKA SUBMIT FORM RETUR ---
    document.getElementById('formRetur').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses Data...';
        btnSubmit.disabled = true;
        
        let formData = new FormData();
        formData.append('store_id', document.getElementById('store_id').value);
        formData.append('product_id', document.getElementById('product_id').value);
        formData.append('quantity', document.getElementById('quantity').value);
        formData.append('reason', document.getElementById('reason').value);

        // Ambil catatan manual jika ada
        const notesInput = document.getElementById('notes');
        if (notesInput) {
            formData.append('notes', notesInput.value);
        }

        const imageInput = document.getElementById('proof_images');
        for (let i = 0; i < imageInput.files.length; i++) {
            formData.append('proof_images[]', imageInput.files[i]);
        }

        try {
            const response = await fetch('/toko/retur', {
                method: 'POST',
                body: formData,
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // 👈 KUNCI UTAMA AGAR LARAVEL MERESPON JSON
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Parse response sebagai JSON
            const result = await response.json();

            if (response.ok && result.success) {
                // JIKA BERHASIL (Status 200 OK)
                alert('Berhasil: ' + (result.message || 'Retur diajukan!'));
                window.location.reload(); 
            } else if (response.status === 422) {
                // JIKA GAGAL VALIDASI (Status 422 Unprocessable Entity)
                let errorString = 'Terdapat data yang tidak valid:\n';
                for (const [field, messages] of Object.entries(result.errors)) {
                    errorString += `- ${messages[0]}\n`;
                }
                alert(errorString);
            } else {
                // JIKA ERROR LAINNYA
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
    // --- MANAJEMEN MODAL LIHAT FOTO ---
    function openPhotoModal(retur) {
        document.getElementById('modalCode').innerText = retur.return_code || ('#RET-' + retur.id);
        const gallery = document.getElementById('modalGallery');
        gallery.innerHTML = '';

        try {
            let images = JSON.parse(retur.proof_image);
            if(Array.isArray(images) && images.length > 0) {
                images.forEach(path => {
                    const url = `/storage/${path}`;
                    gallery.insertAdjacentHTML('beforeend', `<a href="${url}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 bg-gray-50"><img src="${url}" class="w-full h-28 object-cover hover:scale-105 transition duration-200"></a>`);
                });
            } else {
                gallery.innerHTML = '<p class="text-sm text-gray-400 italic col-span-2 text-center py-4">Tidak ada foto yang dilampirkan.</p>';
            }
        } catch (e) {
            gallery.innerHTML = '<p class="text-sm text-red-400 italic col-span-2 text-center py-4">Gagal memuat gambar dari server.</p>';
        }

        document.getElementById('photoModal').classList.remove('hidden');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
    }

    // --- LOGIKA BATALKAN RETUR ---
    async function cancelReturn(id) {
        if (!confirm('Apakah Anda yakin ingin membatalkan dan menghapus pengajuan retur ini?')) return;

        try {
            const response = await fetch(`/toko/retur/${id}/batal`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert(result.message);
                window.location.reload(); 
            } else {
                alert(result.message || 'Gagal membatalkan retur.');
            }
        } catch (error) {
            alert('Terjadi kesalahan komunikasi dengan server.');
        }
    }
</script>
@endsection