@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Knowledge Base AI</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola basis referensi untuk chatbot RAG Anda.</p>
    </div>

    <!-- Upload Section -->
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-green-100">
        <form id="formUploadData" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- Drag & Drop Area -->
            <label class="relative block border-2 border-dashed border-green-200 rounded-2xl p-10 text-center transition-all cursor-pointer">
                <input type="file" id="fileInput" name="kb_document" accept=".pdf,.csv,.xlsx,.xls,.docx,.txt" required class="absolute inset-0 opacity-0 cursor-pointer">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <p id="fileNameDisplay" class="text-sm font-bold text-gray-900">Pilih berkas atau tarik ke sini</p>
                <p class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider font-bold">PDF, CSV, XLSX, DOCX (Maks 10MB)</p>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori Dokumen</label>
                    <select name="category" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition bg-white font-medium text-sm text-gray-900">
                        <option value="regulasi">Regulasi & Kebijakan</option>
                        <option value="katalog">Katalog & Spesifikasi</option>
                        <option value="panduan">SOP Operasional</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi Singkat</label>
                    <input type="text" name="description" class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition text-sm text-gray-900" placeholder="Contoh: Dokumen Retur Mei 2026">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-100 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Proses & Indeks Dokumen
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
        <div class="p-6 border-b border-green-50">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Dokumen Terindeks</h2>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-100">
                @forelse ($documents as $doc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $doc->title }}</div>
                            <div class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $doc->description ?? 'Tanpa deskripsi' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-2 py-1 rounded-lg uppercase">{{ $doc->category }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-[11px] font-bold text-gray-500">{{ strtoupper($doc->file_type) }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="/storage/{{ $doc->file_path }}" target="_blank" class="text-green-600 font-bold hover:underline">Buka</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-10 text-center text-gray-400 text-xs italic">Belum ada dokumen yang terindeks.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih berkas dokumen atau tarik ke sini';
        const display = document.getElementById('fileNameDisplay');
        display.innerText = fileName;
        display.classList.add('text-green-600');
    });

    document.getElementById('formUploadData').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = 'Mengindeks dokumen...';
        btn.disabled = true;

        try {
            const res = await fetch('/unggah-data', {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await res.json();
            if (res.ok) window.location.reload();
            else alert(result.message || 'Gagal mengunggah');
        } catch {
            alert('Kesalahan koneksi.');
        } finally {
            btn.innerHTML = 'Proses & Indeks Dokumen';
            btn.disabled = false;
        }
    });
</script>
@endsection