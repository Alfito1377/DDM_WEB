@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Unggah Dokumen Pengetahuan AI</h1>
                <p class="text-sm text-gray-500 mt-1">Tambahkan berkas PDF, DOCX, atau CSV sebagai basis dokumen referensi untuk mesin pintar Chatbot RAG Anda.</p>
            </div>
        </div>

        <form id="formUploadData" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center hover:border-green-500 bg-gray-50/50 transition group cursor-pointer relative">
                <input type="file" id="fileInput" name="kb_document" accept=".pdf,.csv,.xlsx,.xls,.docx,.txt" required class="absolute inset-0 opacity-0 cursor-pointer z-10">
                <div class="w-16 h-16 bg-white text-gray-400 group-hover:text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <p id="fileNameDisplay" class="text-sm font-bold text-gray-700">Pilih berkas dokumen atau tarik ke sini</p>
                <p class="text-xs text-gray-400 mt-1">PDF, CSV, XLSX, atau DOCX hingga Maksimal 10MB</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori Dokumen</label>
                    <select name="category" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                        <option value="regulasi">Regulasi & Kebijakan Retur</option>
                        <option value="katalog">Katalog Produk & Spesifikasi Benih</option>
                        <option value="panduan">SOP Operasional Toko Mitra</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi Singkat Berkas</label>
                    <input type="text" name="description" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Contoh: Kebijakan Retur Batch Mei 2026">
                </div>
            </div>

            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Proses & Indeks Dokumen
                </button>
            </div>
        </form>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Dokumen Terindeks</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-400 uppercase border-b border-gray-200">
                    <tr>
                        <th class="pb-3 font-bold">Nama File & Deskripsi</th>
                        <th class="pb-3 font-bold">Kategori</th>
                        <th class="pb-3 font-bold text-center">Format</th>
                        <th class="pb-3 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($documents as $doc)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3">
                                <div class="font-bold text-gray-800 max-w-[200px] truncate" title="{{ $doc->title }}">{{ $doc->title }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $doc->description ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded uppercase">{{ $doc->category }}</span>
                            </td>
                            <td class="py-3 text-center font-mono text-xs">{{ strtoupper($doc->file_type) }}</td>
                            <td class="py-3 text-center">
                                <a href="/storage/{{ $doc->file_path }}" target="_blank" class="text-green-600 hover:text-green-800 font-bold text-xs">Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada dokumen yang diunggah.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Menampilkan nama file saat dipilih
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih berkas dokumen atau tarik ke sini';
        document.getElementById('fileNameDisplay').innerText = fileName;
    });

    // Proses Submit AJAX
    document.getElementById('formUploadData').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Memproses...';
        btnSubmit.disabled = true;

        let formData = new FormData(this);

        try {
            const response = await fetch('/unggah-data', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.location.reload();
            } else {
                alert(result.message || 'Gagal menyimpan dokumen.');
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