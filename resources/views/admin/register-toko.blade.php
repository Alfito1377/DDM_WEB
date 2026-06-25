@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Registrasi Mitra Toko Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Daftarkan toko reseller baru agar sistem dapat men-generate QR Code akses.</p>
        </div>
        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Panel Admin</span>
    </div>

    <form id="formRegisterToko" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Toko</label>
            <input type="text" id="store_name" name="store_name" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Contoh: Toko Tani Makmur">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Lengkap</label>
            <textarea id="address" name="address" rows="3" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Jalan, Kecamatan, Kota..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">ID Sales Penanggung Jawab</label>
            <input type="number" id="sales_id" name="sales_id" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Masukkan ID Sales (Contoh: 2)">
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                Simpan Data & Generate QR Code
            </button>
        </div>
    </form>

    <div id="resultArea" class="hidden mt-8 p-6 bg-green-50 border border-green-100 rounded-2xl text-center">
        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <p class="text-green-800 font-bold text-lg mb-1">Toko Berhasil Didaftarkan!</p>
        <p class="text-sm text-gray-600 mb-6">Silakan simpan dan berikan QR Code ini kepada pihak toko.</p>
        
        <div class="flex justify-center mb-6">
            <div class="w-48 h-48 bg-white p-2 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                <img id="qrImage" src="" alt="QR Code Login Toko" class="w-full h-full object-contain">
            </div>
        </div>
        
        <a id="btnDownloadQr" href="#" target="_blank" class="text-sm text-green-600 font-bold hover:underline">Buka / Unduh Gambar QR</a>
    </div>
</div>

<script>
    document.getElementById('formRegisterToko').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses...';
        btnSubmit.disabled = true;
        
        // Sembunyikan area hasil jika sebelumnya terbuka
        document.getElementById('resultArea').classList.add('hidden');

        let formData = new FormData(this);

        try {
            // Mengirim request ke AdminController
            const response = await fetch('/admin/register-toko', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Tampilkan gambar asli dari respon server
                document.getElementById('qrImage').src = result.qr_image;
                document.getElementById('btnDownloadQr').href = result.qr_image; // Link untuk buka gambar
                document.getElementById('resultArea').classList.remove('hidden');
                
                e.target.reset(); // Kosongkan form
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan saat menyambungkan ke server.');
        } finally {
            // Kembalikan tombol ke keadaan semula
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    });
</script>
@endsection