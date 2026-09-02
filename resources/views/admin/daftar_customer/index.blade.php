@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow-sm border border-gray-100 relative w-full">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">

            {{-- Judul --}}
            <div class="shrink-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
                    Daftar Mitra
                </h1>

                <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">
                    Kelola data mitra dan tambahkan mitra baru secara langsung.
                </p>
            </div>

            {{-- Search + Tombol --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">

                {{-- Form Pencarian --}}
                <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-64 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari mitra, pemilik, alamat..."
                        class="w-full border border-gray-300 p-3 sm:py-2.5 sm:pr-10 sm:pl-11 rounded-lg
                       focus:ring-2 focus:ring-green-500 focus:border-green-500
                       outline-none transition text-sm text-gray-700">

                    {{-- Icon Search --}}
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                        </path>
                    </svg>

                    {{-- Tombol Reset --}}
                    @if (request('search'))
                        <a href="{{ url()->current() }}"
                            class="absolute right-3 top-1/2 -translate-y-1/2
                           text-gray-400 hover:text-red-500 transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </a>
                    @endif
                </form>

                {{-- Tombol Tambah --}}
                <button onclick="openChooseMethodModal()"
                    class="w-full sm:w-auto shrink-0 bg-green-600 text-white text-sm font-bold
                   px-4 py-3 sm:py-2.5 rounded-lg hover:bg-green-700 transition
                   shadow-sm flex items-center justify-center gap-2">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>

                    Tambah Mitra Baru
                </button>

            </div>
        </div>
        {{-- ========================================================= --}}
        {{-- MODAL PILIH METODE TAMBAH --}}
        {{-- ========================================================= --}}

        <div id="chooseMethodModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg">
                        Pilih Metode Input
                    </h3>
                    <button onclick="closeChooseMethodModal()" class="text-gray-400 hover:text-red-500 transition p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6 flex flex-col gap-3">
                    {{-- Opsi Manual --}}
                    <button onclick="closeChooseMethodModal(); openRegisterModal();"
                        class="w-full flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition text-left group">
                        <div class="bg-green-100 p-3 rounded-lg group-hover:bg-green-200 transition text-green-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Input Manual</div>
                            <div class="text-xs text-gray-500 mt-0.5">Isi form data mitra satu per satu</div>
                        </div>
                    </button>

                    {{-- Opsi Excel --}}
                    <button onclick="closeChooseMethodModal(); openImportExcelModal();"
                        class="w-full flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left group">
                        <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition text-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Import Excel</div>
                            <div class="text-xs text-gray-500 mt-0.5">Upload file .xlsx untuk banyak data</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- MODAL IMPORT EXCEL --}}
        {{-- ========================================================= --}}

        <div id="importExcelModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[92vh] overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 flex-shrink-0">
                    <h3 class="font-bold text-gray-800 text-base sm:text-lg">
                        Import Data dari Excel
                    </h3>
                    <button onclick="closeImportExcelModal()" class="text-gray-400 hover:text-red-500 transition p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-6 overflow-y-auto">
                    {{-- Sesuaikan URL action dengan route Laravel Anda nanti --}}
                    <form id="formImportExcel" action="/superadmin/register-customer-excel" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        {{-- Area Drop/Pilih File --}}
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition cursor-pointer bg-gray-50 hover:bg-blue-50"
                             onclick="document.getElementById('fileExcel').click()">
                             
                            <input type="file" id="fileExcel" name="file" accept=".xlsx, .xls" class="hidden" required onchange="updateFileName(this)">
                            
                            <div class="flex flex-col items-center pointer-events-none">
                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span class="font-bold text-gray-700 text-sm sm:text-base" id="fileNameDisplay">Klik untuk memilih file Excel</span>
                                <span class="text-xs text-gray-500 mt-1">Format yang didukung: .xlsx, .xls</span>
                            </div>
                        </div>

                        {{-- Link Download Template --}}
                        <div class="flex justify-start items-center">
                            {{-- Sesuaikan URL download template dengan route Laravel Anda --}}
                            <a href="/superadmin/download-template" class="text-blue-600 hover:text-blue-700 hover:underline font-bold text-xs sm:text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template Excel
                            </a>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-md">
                                Upload & Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- INFO SCROLL MOBILE --}}
        {{-- ========================================================= --}}

        <div class="flex items-center gap-2 mb-2 sm:hidden text-xs text-gray-400">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h14">
                </path>
            </svg>

            <span>
                Geser tabel ke kiri atau kanan untuk melihat data lainnya
            </span>
        </div>


        {{-- ========================================================= --}}
        {{-- TABLE --}}
        {{-- ========================================================= --}}

        <div class="w-full overflow-x-auto rounded-xl border border-gray-100">

            <table class="w-full min-w-[1050px] text-sm text-left text-gray-600">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">

                    <tr>
                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            No
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            Nama Mitra
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            Jenis Mitra
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            Pemilik
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            Kontak (WA)
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold">
                            Alamat
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold text-center">
                            Tanggal Daftar
                        </th>

                        <th scope="col" class="px-5 sm:px-6 py-4 font-bold text-center">
                            Aksi & QR Code
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($stores as $toko)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 sm:px-6 py-4 text-center text-gray-600 font-medium">
                                {{ $stores->firstItem() + $loop->index }}
                            </td>
                            <td class="px-5 sm:px-6 py-4 font-bold text-gray-800">
                                {{ $toko->store_name }}
                            </td>

                            <td class="px-5 sm:px-6 py-4 font-bold text-gray-800">
                                {{ $toko->jenis_mitra }}
                            </td>

                            <td class="px-5 sm:px-6 py-4 text-gray-800">
                                {{ $toko->owner_name ?? '-' }}
                            </td>

                            <td class="px-5 sm:px-6 py-4 text-gray-600">
                                {{ $toko->phone_number ?? '-' }}
                            </td>

                            <td class="px-5 sm:px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $toko->address }}">

                                {{ $toko->address }}

                            </td>

                            <td class="px-5 sm:px-6 py-4 text-center whitespace-nowrap">

                                {{ \Carbon\Carbon::parse($toko->created_at)->format('d M Y') }}

                            </td>

                            <td class="px-5 sm:px-6 py-4 text-center">

                                @php

                                    $loginUrl = urlencode(url('/login/qr?token=' . $toko->qr_token_login));

                                    $qrImageLogin =
                                        'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $loginUrl;

                                    $checkpointUrl = urlencode(
                                        url('/login/qr/checkpoint?token=' . $toko->qr_token_checkpoint),
                                    );

                                    $qrImageCheckpoint =
                                        'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
                                        $checkpointUrl;

                                @endphp


                                <div class="flex flex-wrap items-center justify-center gap-2 min-w-[230px]">

                                    <button
                                        onclick="showQR({
                                            id: {{ $toko->id }},
                                            storeName: '{{ addslashes($toko->store_name) }}',
                                            qrLoginUrl: '{{ $qrImageLogin }}',
                                            qrCheckpointUrl: '{{ $qrImageCheckpoint }}'
                                        })"
                                        class="px-3 py-1.5 border border-green-200 bg-green-50 text-green-700 rounded-lg text-xs font-bold hover:bg-green-100 transition">

                                        QR Code

                                    </button>


                                    <button onclick="openEditModal({{ $toko->id }})"
                                        class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-100 transition">

                                        Edit

                                    </button>


                                    <button
                                        onclick="deleteStore(
                                            {{ $toko->id }},
                                            '{{ addslashes($toko->store_name) }}'
                                        )"
                                        class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-lg text-xs font-bold hover:bg-red-100 transition">

                                        Hapus

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                @if (request('search'))
                                    Data mitra dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                                @else
                                    Belum ada toko yang terdaftar.
                                @endif
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
        <div class="mt-6">
            {{ $stores->links() }}
        </div>

        {{-- ========================================================= --}}
        {{-- MODAL REGISTER --}}
        {{-- ========================================================= --}}

        <div id="registerModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[92vh] overflow-hidden flex flex-col">

                {{-- Header --}}

                <div
                    class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 flex-shrink-0">

                    <h3 class="font-bold text-gray-800 text-base sm:text-lg">
                        Registrasi Toko Baru
                    </h3>

                    <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-red-500 transition p-1">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>

                        </svg>

                    </button>

                </div>


                {{-- Content --}}

                <div class="p-4 sm:p-6 overflow-y-auto">

                    <form id="formRegisterCustomer" class="space-y-4">

                        @csrf


                        {{-- Nama Toko --}}

                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Nama Toko
                            </label>

                            <input type="text" name="store_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Contoh: Toko Tani Makmur">

                        </div>


                        {{-- Pemilik --}}

                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Nama Pemilik Toko
                            </label>

                            <input type="text" name="owner_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Masukkan nama lengkap pemilik">

                        </div>


                        {{-- Telepon --}}

                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                No. WhatsApp / Telepon
                            </label>

                            <input type="tel" name="phone_number" required inputmode="numeric"
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Contoh: 081234567890">

                        </div>


                        {{-- Alamat --}}

                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Alamat Lengkap
                            </label>

                            <textarea name="address" rows="3" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition resize-none"
                                placeholder="Jalan, Kecamatan, Kota..."></textarea>

                        </div>


                        {{-- Jenis Mitra --}}

                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Jenis Mitra
                            </label>

                            <select name="jenis_mitra_id" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">

                                <option value="" disabled selected>
                                    -- Pilih Jenis Mitra --
                                </option>

                                @foreach ($jenisMitraList as $jenisMitra)
                                    <option value="{{ $jenisMitra->id }}">
                                        {{ $jenisMitra->nama_jenis_mitra }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- Submit --}}

                        <div class="pt-2 sm:pt-4">

                            <button type="submit"
                                class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">

                                Simpan & Generate QR Code

                            </button>

                        </div>

                    </form>


                    {{-- ================================================= --}}
                    {{-- REGISTER SUCCESS --}}
                    {{-- ================================================= --}}

                    <div id="registerSuccessArea" class="hidden text-center">

                        <div
                            class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>

                            </svg>

                        </div>


                        <p class="text-green-800 font-bold text-lg mb-1">
                            Toko Berhasil Didaftarkan!
                        </p>

                        <p class="text-sm text-gray-600 mb-6">
                            Berikut adalah QR Code akses untuk toko ini.
                        </p>


                        {{-- QR RESPONSIVE --}}

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-6">

                            {{-- QR Login --}}

                            <div class="flex flex-col items-center">

                                <span class="text-xs font-bold text-gray-500 mb-2">
                                    QR Login
                                </span>

                                <div
                                    class="w-36 h-36 sm:w-40 sm:h-40 bg-white p-2 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center">

                                    <img id="newQrImageLogin" src="" alt="QR Code Login"
                                        class="w-full h-full object-contain">

                                </div>

                            </div>


                            {{-- QR Checkpoint --}}

                            <div class="flex flex-col items-center">

                                <span class="text-xs font-bold text-gray-500 mb-2">
                                    QR Checkpoint
                                </span>

                                <div
                                    class="w-36 h-36 sm:w-40 sm:h-40 bg-white p-2 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center">

                                    <img id="newQrImageCheckpoint" src="" alt="QR Code Checkpoint"
                                        class="w-full h-full object-contain">

                                </div>

                            </div>

                        </div>


                        <button onclick="reloadPage()"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition">

                            Tutup & Perbarui Tabel

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MODAL EDIT --}}
        {{-- ========================================================= --}}

        <div id="editModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[92vh] overflow-hidden flex flex-col">

                {{-- Header --}}

                <div
                    class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 flex-shrink-0">

                    <h3 class="font-bold text-gray-800 text-base sm:text-lg">
                        Edit Data Toko
                    </h3>

                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition p-1">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>

                        </svg>

                    </button>

                </div>


                {{-- Form --}}

                <div class="p-4 sm:p-6 overflow-y-auto">

                    <form id="formEditCustomer" class="space-y-4">

                        @csrf
                        @method('PUT')

                        <input type="hidden" id="edit_store_id" name="id">


                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Nama Toko
                            </label>

                            <input type="text" id="edit_store_name" name="store_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">

                        </div>


                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Nama Pemilik Toko
                            </label>

                            <input type="text" id="edit_owner_name" name="owner_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">

                        </div>


                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                No. WhatsApp / Telepon
                            </label>

                            <input type="tel" id="edit_phone_number" name="phone_number" required
                                inputmode="numeric"
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">

                        </div>


                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Alamat Lengkap
                            </label>

                            <textarea id="edit_address" name="address" rows="3" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>

                        </div>


                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                                Jenis Mitra
                            </label>

                            <select id="edit_jenis_mitra_id" name="jenis_mitra_id" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">

                                <option value="" disabled>
                                    -- Pilih Jenis Mitra --
                                </option>

                                @foreach ($jenisMitraList as $jenisMitra)
                                    <option value="{{ $jenisMitra->id }}">
                                        {{ $jenisMitra->nama_jenis_mitra }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="pt-2 sm:pt-4">

                            <button type="submit"
                                class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MODAL QR CODE --}}
        {{-- ========================================================= --}}

        <div id="qrModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-3 sm:p-4">

            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-xl w-full max-w-xl max-h-[92vh] overflow-y-auto">

                {{-- Header --}}

                <div class="flex justify-between items-center mb-5">

                    <h3 class="font-bold text-gray-800 text-base sm:text-lg pr-3" id="modalStoreName">

                        Nama Toko

                    </h3>

                    <button onclick="closeQR()" class="text-gray-400 hover:text-red-500 transition p-1 flex-shrink-0">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>

                        </svg>

                    </button>

                </div>


                {{-- QR CONTAINER --}}

                <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-6">

                    {{-- QR LOGIN --}}

                    <div class="flex flex-col items-center w-full sm:w-auto">

                        <h1 class="font-bold text-base sm:text-lg text-gray-800 mb-2">
                            QR Code Login
                        </h1>

                        <a id="modalImageLinkLogin" href="#" target="_blank"
                            class="cursor-pointer transition-all hover:scale-105 hover:shadow-lg rounded-xl">

                            <img id="modalQrImageLogin" src="" alt="QR Code Login"
                                class="w-40 h-40 sm:w-48 sm:h-48 object-contain border-2 border-transparent hover:border-green-400 rounded-xl p-2 transition-colors">

                        </a>

                        <p class="text-[10px] text-green-600 mt-2 font-bold animate-pulse">
                            👆 Klik gambar untuk cetak
                        </p>

                    </div>


                    {{-- QR CHECKPOINT --}}

                    <div class="flex flex-col items-center w-full sm:w-auto">

                        <h1 class="font-bold text-base sm:text-lg text-gray-800 mb-2">
                            QR Code Checkpoint
                        </h1>

                        <a id="modalImageLinkCheckpoint" href="#" target="_blank"
                            class="cursor-pointer transition-all hover:scale-105 hover:shadow-lg rounded-xl">

                            <img id="modalQrImageCheckpoint" src="" alt="QR Code Checkpoint"
                                class="w-40 h-40 sm:w-48 sm:h-48 object-contain border-2 border-transparent hover:border-green-400 rounded-xl p-2 transition-colors">

                        </a>

                        <p class="text-[10px] text-green-600 mt-2 font-bold animate-pulse">
                            👆 Klik gambar untuk cetak
                        </p>

                    </div>

                </div>


                {{-- Description --}}

                <p class="text-xs text-center text-gray-500 mb-6 leading-relaxed">

                    Scan QR ini menggunakan kamera perangkat toko untuk masuk
                    otomatis dan checkpoint sopir untuk konfirmasi.

                </p>


                {{-- BUTTONS --}}

                <div class="space-y-2">

                    <a id="modalDownloadLoginBtn" href="#" target="_blank"
                        class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition">

                        Buka Gambar QR Login di Tab Baru

                    </a>


                    <a id="modalDownloadCheckpointBtn" href="#" target="_blank"
                        class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition">

                        Buka Gambar QR Checkpoint di Tab Baru

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        // ============================================================
        // CHOOSE METHOD & IMPORT EXCEL MODAL
        // ============================================================

        // Tambahkan di dalam blok CLOSE MODAL CLICK OUTSIDE
        document.getElementById('chooseMethodModal').addEventListener('click', function(e) {
            if (e.target === this) closeChooseMethodModal();
        });

        document.getElementById('importExcelModal').addEventListener('click', function(e) {
            if (e.target === this) closeImportExcelModal();
        });

        // ============================================================
        // ESCAPE TO CLOSE MODAL
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRegisterModal();
                closeEditModal();
                closeQR();
                closeChooseMethodModal(); // <-- Tambahkan ini
                closeImportExcelModal();  // <-- Tambahkan ini
            }
        });

        function openChooseMethodModal() {
            const modal = document.getElementById('chooseMethodModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeChooseMethodModal() {
            const modal = document.getElementById('chooseMethodModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openImportExcelModal() {
            const modal = document.getElementById('importExcelModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Reset input file saat modal dibuka
            document.getElementById('formImportExcel').reset();
            document.getElementById('fileNameDisplay').innerText = 'Klik untuk memilih file Excel';
        }

        function closeImportExcelModal() {
            const modal = document.getElementById('importExcelModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Fungsi untuk mengupdate teks saat file dipilih
        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            if (input.files && input.files.length > 0) {
                display.innerText = input.files[0].name;
                display.classList.add('text-blue-600');
            } else {
                display.innerText = 'Klik untuk memilih file Excel';
                display.classList.remove('text-blue-600');
            }
        }
        // ============================================================
        // REGISTER MODAL
        // ============================================================

        function openRegisterModal() {

            const modal =
                document.getElementById('registerModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document
                .getElementById('formRegisterCustomer')
                .classList.remove('hidden');

            document
                .getElementById('registerSuccessArea')
                .classList.add('hidden');
        }


        function closeRegisterModal() {

            const modal =
                document.getElementById('registerModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document
                .getElementById('formRegisterCustomer')
                .reset();
        }


        function reloadPage() {

            window.location.reload();

        }


        // ============================================================
        // REGISTER AJAX
        // ============================================================

        document
            .getElementById('formRegisterCustomer')
            .addEventListener('submit', async function(e) {

                e.preventDefault();

                const btnSubmit =
                    e.target.querySelector('button[type="submit"]');

                const originalText =
                    btnSubmit.innerHTML;

                btnSubmit.innerHTML = 'Memproses...';

                btnSubmit.disabled = true;


                const formData =
                    new FormData(this);


                try {

                    const response =
                        await fetch(
                            '/superadmin/register-customer', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );


                    const result =
                        await response.json();


                    if (result.success) {

                        document
                            .getElementById('formRegisterCustomer')
                            .classList.add('hidden');


                        document
                            .getElementById('newQrImageLogin')
                            .src = result.qr_image_login;


                        document
                            .getElementById('newQrImageCheckpoint')
                            .src = result.qr_checkpoint_image;


                        document
                            .getElementById('registerSuccessArea')
                            .classList.remove('hidden');


                        e.target.reset();

                    } else {

                        alert(
                            result.message ||
                            'Gagal mendaftarkan toko.'
                        );

                    }

                } catch (error) {

                    console.error(error);

                    alert(
                        'Terjadi kesalahan koneksi server.'
                    );

                } finally {

                    btnSubmit.innerHTML =
                        originalText;

                    btnSubmit.disabled =
                        false;
                }

            });


        // ============================================================
        // EDIT MODAL
        // ============================================================

        async function openEditModal(id) {

            try {

                const response =
                    await fetch(
                        `/superadmin/edit-customer/${id}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    document
                        .getElementById('edit_store_id')
                        .value = result.data.id;


                    document
                        .getElementById('edit_store_name')
                        .value = result.data.store_name;


                    document
                        .getElementById('edit_owner_name')
                        .value = result.data.owner_name;


                    document
                        .getElementById('edit_phone_number')
                        .value = result.data.phone_number;


                    document
                        .getElementById('edit_address')
                        .value = result.data.address;


                    document
                        .getElementById('edit_jenis_mitra_id')
                        .value = result.data.jenis_mitra_id;


                    const modal =
                        document.getElementById('editModal');

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                } else {

                    alert(
                        result.message ||
                        'Gagal mengambil data customer.'
                    );

                }

            } catch (error) {

                console.error(error);

                alert(
                    'Terjadi kesalahan saat mengambil data dari server.'
                );

            }

        }


        function closeEditModal() {

            const modal =
                document.getElementById('editModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document
                .getElementById('formEditCustomer')
                .reset();
        }


        // ============================================================
        // EDIT AJAX
        // ============================================================

        document
            .getElementById('formEditCustomer')
            .addEventListener('submit', async function(e) {

                e.preventDefault();

                const id =
                    document.getElementById('edit_store_id').value;


                const btnSubmit =
                    e.target.querySelector('button[type="submit"]');


                const originalText =
                    btnSubmit.innerHTML;


                btnSubmit.innerHTML =
                    'Menyimpan...';

                btnSubmit.disabled =
                    true;


                const formData =
                    new FormData(this);


                try {

                    const response =
                        await fetch(
                            `/superadmin/update-customer/${id}`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );


                    const result =
                        await response.json();


                    if (result.success) {

                        alert(
                            'Data mitra berhasil diperbarui!'
                        );

                        reloadPage();

                    } else {

                        alert(
                            result.message ||
                            'Gagal memperbarui data mitra.'
                        );

                    }

                } catch (error) {

                    console.error(error);

                    alert(
                        'Terjadi kesalahan koneksi server.'
                    );

                } finally {

                    btnSubmit.innerHTML =
                        originalText;

                    btnSubmit.disabled =
                        false;

                }

            });


        // ============================================================
        // DELETE
        // ============================================================

        async function deleteStore(id, storeName) {

            const confirmation =
                confirm(
                    `Apakah Anda yakin ingin menghapus mitra "${storeName}"? Tindakan ini tidak dapat dibatalkan.`
                );


            if (!confirmation) {
                return;
            }


            try {

                const response =
                    await fetch(
                        `/superadmin/delete-customer/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    );


                const result =
                    await response.json();


                if (result.success) {

                    alert(
                        'Mitra berhasil dihapus.'
                    );

                    reloadPage();

                } else {

                    alert(
                        result.message ||
                        'Gagal menghapus mitra.'
                    );

                }

            } catch (error) {

                console.error(error);

                alert(
                    'Terjadi kesalahan koneksi server.'
                );

            }

        }


        // ============================================================
        // QR MODAL
        // ============================================================

        function showQR({
            id,
            storeName,
            qrLoginUrl,
            qrCheckpointUrl
        }) {

            document
                .getElementById('modalStoreName')
                .innerText = storeName;


            document
                .getElementById('modalQrImageLogin')
                .src = qrLoginUrl;


            document
                .getElementById('modalQrImageCheckpoint')
                .src = qrCheckpointUrl;


            // Link cetak QR Login

            document
                .getElementById('modalDownloadLoginBtn')
                .href =
                `/superadmin/mitra/${id}/print-qr?type=login`;


            document
                .getElementById('modalDownloadLoginBtn')
                .innerText =
                'Cetak QR Login';


            // Link cetak QR Checkpoint

            document
                .getElementById('modalDownloadCheckpointBtn')
                .href =
                `/superadmin/mitra/${id}/print-qr?type=checkpoint`;


            document
                .getElementById('modalDownloadCheckpointBtn')
                .innerText =
                'Cetak QR Checkpoint';


            // Link gambar Login

            document
                .getElementById('modalImageLinkLogin')
                .href =
                `/superadmin/mitra/${id}/print-qr?type=login`;


            // Link gambar Checkpoint

            document
                .getElementById('modalImageLinkCheckpoint')
                .href =
                `/superadmin/mitra/${id}/print-qr?type=checkpoint`;


            // Buka modal

            const modal =
                document.getElementById('qrModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }


        function closeQR() {

            const modal =
                document.getElementById('qrModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');


            document
                .getElementById('modalQrImageLogin')
                .src = '';


            document
                .getElementById('modalQrImageCheckpoint')
                .src = '';

        }


        // ============================================================
        // CLOSE MODAL CLICK OUTSIDE
        // ============================================================

        document
            .getElementById('registerModal')
            .addEventListener('click', function(e) {

                if (e.target === this) {

                    closeRegisterModal();

                }

            });


        document
            .getElementById('editModal')
            .addEventListener('click', function(e) {

                if (e.target === this) {

                    closeEditModal();

                }

            });


        document
            .getElementById('qrModal')
            .addEventListener('click', function(e) {

                if (e.target === this) {

                    closeQR();

                }

            });


        // ============================================================
        // ESCAPE TO CLOSE MODAL
        // ============================================================

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                closeRegisterModal();
                closeEditModal();
                closeQR();

            }

        });
    </script>
@endsection
