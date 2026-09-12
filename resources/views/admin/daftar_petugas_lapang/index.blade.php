@extends('layouts.app')

@section('content')

    <style>
        .text-muted {
            color: rgb(158, 158, 158);
            font-size: .8rem;
            margin-bottom: 2%;
        }
    </style>
    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
        {{-- Alert Notifikasi (Success / Error) --}}
        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
                {{ session('success') }}
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    // Your code here (e.g., button click listeners)
                    Swal.fire({
                        title: "Berhasil!",
                        text: "{{ session('success') }}",
                        icon: "success"
                    });
                });
            </script>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                {{ session('error') }}
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    // Your code here (e.g., button click listeners)
                    Swal.fire({
                        title: "Error!",
                        text: "{{ session('error') }}",
                        icon: "error"
                    });
                });
            </script>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header Halaman --}}
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Petugas Lapang</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola data pengguna yang bertugas di lapangan.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                {{-- Form Pencarian --}}
                <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email..."
                        class="rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-2 w-full sm:w-64">
                    <button type="submit"
                        class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Cari
                    </button>
                </form>

                {{-- Tombol Tambah (Membuka Modal) --}}
                <button type="button" onclick="openModalTambah()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Petugas
                </button>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Petugas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email Akun</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Akses / Sistem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Terdaftar</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($petugasLapang as $index => $petugas)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $petugasLapang->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $petugas->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $petugas->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $petugas->store ? $petugas->store->store_name : 'Tanpa Gudang' }}
                                    </div>
                                    <span class="px-2.5 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-green-100 text-green-800 mt-1">
                                        {{ $petugas->store ? $petugas->store->address : 'Mobile / Virtual' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $petugas->created_at ? \Carbon\Carbon::parse($petugas->created_at)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2 items-center">
                                        {{-- Tombol Edit --}}
                                        <button type="button" 
                                            onclick="openModalEdit('{{ $petugas->id }}', '{{ addslashes($petugas->name) }}', '{{ $petugas->email }}')" 
                                            class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-100 transition">
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form id="formHapus" action="{{ url(Request::segment(1) . '/daftar-petugas-lapang/' . $petugas->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="deleteData('{{ $petugas->name }}')" 
                                                class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-lg text-xs font-bold hover:bg-red-100 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        Tidak ada data petugas lapang yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($petugasLapang->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $petugasLapang->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH PETUGAS --}}
    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Petugas Lapang</h3>
                <button type="button" onclick="closeModalTambah()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ url(Request::segment(1) . '/daftar-petugas-lapang/store') }}" method="POST" id="formTambah">
                @csrf
          <div class="p-6 space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" name="name" required placeholder="Contoh: Abdi Dalem" class="border w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-2">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required placeholder="Abdidalem@gmail.com" class="border w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-2">
    </div>
    
    {{-- Password dengan Fitur Toggle Lihat Password --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password (optional)</label>
        <p class="text-muted">Password default adalah <b>sage1234</b>, isikan kolom password jika ingin memberikan password kepada akun.</p>
        <div class="relative">
            <input type="password" name="password" id="passwordInput" minlength="8" placeholder="Minimal 8 karakter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm pl-4 pr-10 py-2 border">
            <button type="button" onclick="togglePassword('passwordInput', 'iconEyePassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                {{-- Icon Mata Terbuka (Default) --}}
                <svg id="iconEyePassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <div class="relative">
            <input type="password" name="password_confirmation" id="passwordConfirmationInput" minlength="8" placeholder="Ulangi password" class="border w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm pl-4 pr-10 py-2">
            <button type="button" onclick="togglePassword('passwordConfirmationInput', 'iconEyeConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                {{-- Icon Mata Terbuka (Default) --}}
                <svg id="iconEyeConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
    </div>

</div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="submitAddBtn()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Simpan Petugas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PETUGAS --}}
    {{-- Overlay & Modal Edit Petugas --}}
    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Edit Petugas Lapang</h3>
                <button type="button" onclick="closeModalEdit()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" required class="w-full rounded-lg border shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="editEmail" required class="w-full rounded-lg border shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-2">
                    </div>

                    {{-- Password Baru (Opsional) dengan ikon mata --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                        <div class="relative">
                            <input type="password" name="password" id="editPasswordInput" minlength="8" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-lg border shadow-sm focus:border-green-500 focus:ring-green-500 text-sm pl-4 pr-10 py-2">
                            <button type="button" onclick="togglePassword('editPasswordInput', 'iconEditPassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="iconEditPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password Baru dengan ikon mata --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="editPasswordConfirmInput" minlength="8" placeholder="Ulangi password baru" class="w-full rounded-lg border shadow-sm focus:border-green-500 focus:ring-green-500 text-sm pl-4 pr-10 py-2">
                            <button type="button" onclick="togglePassword('editPasswordConfirmInput', 'iconEditConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="iconEditConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Fitur Reset Device ID --}}
                    <div class="flex items-center mt-2">
                        <input type="checkbox" name="reset_device_id" id="resetDeviceId" value="1" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                        <label for="resetDeviceId" class="ml-2 text-sm font-medium text-gray-700">Reset Device ID (Izinkan login di perangkat baru)</label>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT MODAL TAMBAH & EDIT --}}
    <script>
        // swal success with return from controller
        function openModalTambah() {
            document.getElementById('modalTambah').classList.remove('hidden');
        }
        function closeModalTambah() {
            document.getElementById('modalTambah').classList.add('hidden');
        }

        function openModalEdit(id, name, email) {
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            
            const segment1 = '{{ Request::segment(1) }}';
            const actionUrl = `/${segment1}/daftar-petugas-lapang/${id}`;
            document.getElementById('formEdit').action = actionUrl;
            
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function closeModalEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function submitAddBtn() {
            const form = document.getElementById('formTambah');
            const passwordField = document.getElementById('passwordInput').value;
            const passwordConfirmField = document.getElementById('passwordConfirmationInput').value;
            if(passwordField != passwordConfirmField) {
                // swal error
                Swal.fire({
                    title: "Kesalahan!",
                    text: "Konfirmasi password tidak sama!",
                    icon: "error"
                });
            } else {
                // swal success
                Swal.fire({
                    title: "Peringatan",
                    text: "Apakah anda akan menambahkan data ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, tambahkan!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Proses...",
                            text: "Sistem sedang memproses data...",
                            icon: "warning",
                            showConfirmButton: false,
                        });
                        form.requestSubmit();
                    }
                });
            }
        }

        function deleteData(name) {
            const form = document.getElementById('formHapus');
            Swal.fire({
                title: "Peringatan",
                text: "Apakah anda akan menghapus data (" + name + ") ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#9d9d9d",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Proses...",
                        text: "Sistem sedang memproses data...",
                        icon: "warning",
                        showConfirmButton: false,
                    });
                    form.requestSubmit();    
                } 
            });
        }

        // Fungsi untuk menampilkan/menyembunyikan password
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                // Ubah ikon menjadi mata dicoret (sembunyikan)
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.03 10.03 0 014.24-5.385m3.76-1.535a9.953 9.953 0 013.542-.08m3.542 2.305A9.974 9.974 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7a9.969 9.969 0 01-4.042-.864M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                `;
            } else {
                input.type = 'password';
                // Kembalikan ikon menjadi mata terbuka
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
@endsection