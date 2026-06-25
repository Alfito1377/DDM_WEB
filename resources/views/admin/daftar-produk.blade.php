@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Data Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola katalog benih dan cetak barcode SKU untuk keperluan distribusi.</p>
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
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Kode Barcode / SKU</label>
                    <input type="text" name="barcode" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition font-mono tracking-widest" placeholder="899xxxxxxx">
                    <p class="text-[10px] text-gray-500 mt-1">Sistem akan otomatis men-generate gambar barcodenya.</p>
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
    function openProductModal() {
        document.getElementById('productModal').classList.remove('hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.getElementById('formProduct').reset();
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