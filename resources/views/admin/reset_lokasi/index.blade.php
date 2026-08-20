@extends('layouts.app')

@section('content')
@php
        $userRole = strtolower(Auth::user()->role->role_name ?? '');
        $prefix = $userRole === 'superadmin' ? '/superadmin' : '/admin';
    @endphp
    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow-sm border border-gray-100 relative w-full">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Permintaan Reset Lokasi
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                    Daftar mitra toko yang meminta izin untuk mengatur ulang titik koordinat GPS mereka.
                </p>
            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- ALERT SUCCESS --}}
        {{-- ========================================================= --}}
        @if (session('success'))
            <div
                class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-medium text-sm border border-green-100 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- INFO SCROLL MOBILE --}}
        {{-- ========================================================= --}}
        @if ($stores->count() > 0)
            <div class="flex items-center gap-2 mb-2 sm:hidden text-xs text-gray-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h14"></path>
                </svg>
                <span>Geser tabel ke kiri atau kanan untuk melihat data</span>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- TABLE --}}
        {{-- ========================================================= --}}
        <div class="w-full overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full min-w-[800px] text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-4 font-bold text-center w-16">No</th>
                        <th scope="col" class="px-5 py-4 font-bold">Nama Toko</th>
                        <th scope="col" class="px-5 py-4 font-bold">Pemilik / Kontak</th>
                        <th scope="col" class="px-5 py-4 font-bold">Alamat</th>
                        <th scope="col" class="px-5 py-4 font-bold text-center">Status</th>
                        <th scope="col" class="px-5 py-4 font-bold text-center w-32">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($stores as $toko)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Nomor (Menggunakan loop iteration karena datanya pakai get(), bukan paginate) --}}
                            <td class="px-5 py-4 text-center font-medium text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Nama Toko --}}
                            <td class="px-5 py-4 font-bold text-gray-800">
                                {{ $toko->store_name }}
                            </td>

                            {{-- Pemilik & Kontak --}}
                            <td class="px-5 py-4">
                                <div class="text-gray-800 font-medium">{{ $toko->owner_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $toko->phone_number }}</div>
                            </td>

                            {{-- Alamat --}}
                            <td class="px-5 py-4 max-w-xs truncate" title="{{ $toko->address }}">
                                {{ $toko->address }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                    Menunggu
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-center">
                                {{-- Pastikan URL action form sesuai dengan prefix route Anda (admin/superadmin) --}}
                                <form action="{{ url($prefix . '/setujui-reset-lokasi/' . $toko->id) }}" method="POST" ...>
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition group">
                                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <div
                                    class="py-12 px-4 flex flex-col items-center justify-center bg-gray-50 border-t border-dashed border-gray-200">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                    <h3 class="text-base font-bold text-gray-600 mb-1">Aman & Terkendali</h3>
                                    <p class="text-sm text-gray-400 text-center max-w-sm">
                                        Saat ini tidak ada mitra toko yang mengajukan permohonan reset titik lokasi GPS.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
