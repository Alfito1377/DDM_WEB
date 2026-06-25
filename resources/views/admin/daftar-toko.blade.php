@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Mitra Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data toko dan tambahkan mitra baru secara langsung.</p>
        </div>
        <button onclick="openRegisterModal()" class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Toko Baru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Toko</th>
                    <th scope="col" class="px-6 py-4 font-bold">Alamat</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Tanggal Daftar</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Aksi & QR Code</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($stores as $toko)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $toko->store_name }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $toko->address }}">{{ $toko->address }}</td>
                        <td class="px-6 py-4 text-center">{{ \Carbon\Carbon::parse($toko->created_at)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $loginUrl = urlencode(url('/login/qr/' . $toko->qr_token));
                                $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $loginUrl;
                            @endphp
                            <button onclick="showQR('{{ $toko->store_name }}', '{{ $qrImage }}')" class="px-4 py-1.5 border border-green-200 bg-green-50 text-green-700 rounded-lg text-xs font-bold hover:bg-green-100 transition">
                                Lihat QR Code
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada toko yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="registerModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Registrasi Toko Baru</h3>
            <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form id="formRegisterToko" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Toko</label>
                    <input type="text" name="store_name" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Contoh: Toko Tani Makmur">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="address" rows="3" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition resize-none" placeholder="Jalan, Kecamatan, Kota..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">ID Sales Penanggung Jawab</label>
                    <input type="number" name="sales_id" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Masukkan ID Sales (Contoh: 2)">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                        Simpan & Generate QR Code
                    </button>
                </div>
            </form>

            <div id="registerSuccessArea" class="hidden text-center">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-green-800 font-bold text-lg mb-1">Toko Berhasil Didaftarkan!</p>
                <p class="text-sm text-gray-600 mb-6">Berikut adalah QR Code akses untuk toko ini.</p>
                
                <div class="flex justify-center mb-6">
                    <div class="w-48 h-48 bg-white p-2 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center">
                        <img id="newQrImage" src="" alt="QR Code Baru" class="w-full h-full object-contain">
                    </div>
                </div>
                
                <button onclick="reloadPage()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition">
                    Tutup & Perbarui Tabel
                </button>
            </div>
        </div>
    </div>
</div>

<div id="qrModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-sm w-full mx-4 transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 text-lg" id="modalStoreName">Nama Toko</h3>
            <button onclick="closeQR()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="flex justify-center mb-4">
            <img id="modalQrImage" src="" alt="QR Code" class="w-48 h-48 object-contain">
        </div>
        <p class="text-xs text-center text-gray-500 mb-6">Scan QR ini menggunakan kamera perangkat toko untuk masuk otomatis.</p>
        <a id="modalDownloadBtn" href="#" target="_blank" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2.5 rounded-xl transition">
            Buka Gambar di Tab Baru
        </a>
    </div>
</div>

<script>
    // --- LOGIKA MODAL TAMBAH TOKO ---
    function openRegisterModal() {
        document.getElementById('registerModal').classList.remove('hidden');
        document.getElementById('formRegisterToko').classList.remove('hidden');
        document.getElementById('registerSuccessArea').classList.add('hidden');
    }

    function closeRegisterModal() {
        document.getElementById('registerModal').classList.add('hidden');
        document.getElementById('formRegisterToko').reset();
    }

    function reloadPage() {
        window.location.reload(); // Refresh halaman agar toko baru muncul di tabel
    }

    // --- LOGIKA SUBMIT FORM VIA AJAX ---
    document.getElementById('formRegisterToko').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses...';
        btnSubmit.disabled = true;

        let formData = new FormData(this);

        try {
            const response = await fetch('/admin/register-toko', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (result.success) {
                // Sembunyikan form, tampilkan area sukses
                document.getElementById('formRegisterToko').classList.add('hidden');
                document.getElementById('newQrImage').src = result.qr_image;
                document.getElementById('registerSuccessArea').classList.remove('hidden');
                
                e.target.reset(); // Kosongkan form
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan koneksi server.');
        } finally {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    });

    // --- LOGIKA MODAL LIHAT QR LAMA ---
    function showQR(storeName, qrUrl) {
        document.getElementById('modalStoreName').innerText = storeName;
        document.getElementById('modalQrImage').src = qrUrl;
        document.getElementById('modalDownloadBtn').href = qrUrl;
        document.getElementById('qrModal').classList.remove('hidden');
    }

    function closeQR() {
        document.getElementById('qrModal').classList.add('hidden');
        document.getElementById('modalQrImage').src = ''; 
    }
</script>
@endsection