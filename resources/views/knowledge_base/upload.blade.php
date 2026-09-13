@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-6">
    <!-- Card Form Unggah Dokumen -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Unggah Basis Pengetahuan AI</h2>
        <p class="text-gray-500 text-sm mb-6">Unggah dokumen SOP, katalog, atau aturan (PDF, DOCX, XLSX) agar AI dapat mempelajarinya.</p>

        <!-- Pesan Sukses/Error dari Controller -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pesan Error Validasi -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Upload (Sudah dibersihkan dari duplikasi tag) -->
        <form action="/superadmin/unggah-data" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                    <span>Pilih file dokumen</span>
                    <input id="file-upload" name="kb_document" type="file" class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                </label>
                <p class="text-xs text-gray-500 mt-2">Mendukung PDF, Word, dan Excel (Maks. 10MB)</p>
            </div>
            
            <p id="file-name" class="text-sm text-gray-600 italic hidden"></p>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md">
                Unggah & Latih AI
            </button>
        </form>
    </div>

  <!-- Card Daftar Dokumen yang Telah Diunggah -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Dokumen Tersimpan di Sistem</h3>
        
        @if(isset($documents) && $documents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diunggah Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Unggah</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($documents as $index => $doc)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $doc->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 uppercase">
                                        {{ $doc->file_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $doc->uploader->name ?? 'Administrator' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $doc->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <!-- Tombol Hapus dengan Konfirmasi -->
                                    <form action="/superadmin/unggah-data/{{ $doc->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini dari sistem?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg transition text-xs font-bold shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm italic">Belum ada dokumen yang diunggah ke dalam sistem saat ini.</p>
        @endif
    </div>
    </div>
</div>

<script>
    // Script interaktif untuk menampilkan nama file yang dipilih sebelum submit
    document.getElementById('file-upload').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            var fileName = e.target.files[0].name;
            var display = document.getElementById('file-name');
            display.textContent = "File terpilih: " + fileName;
            display.classList.remove('hidden');
        }
    });
</script>
@endsection