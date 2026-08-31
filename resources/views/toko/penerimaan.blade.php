@extends('layouts.app')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        @php
            $toko = null;

            if (Auth::user()->store_id) {
                $toko = \App\Models\StoresModel::find(Auth::user()->store_id);
            }
        @endphp

        {{-- =========================================================
        MODAL SET LOKASI TOKO
    ========================================================== --}}
@if ($toko && empty($toko->latitude))
            <div id="modalSetLokasiToko"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-3 sm:p-4">

                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-md
                        transform transition-all overflow-hidden
                        border-t-4 sm:border-t-8 border-yellow-500">

                    <div class="p-5 sm:p-6 text-center">

                        {{-- Icon --}}
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16
                                bg-yellow-100 text-yellow-600
                                rounded-full flex items-center justify-center
                                mx-auto mb-4">

                            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="font-black text-gray-800 text-lg sm:text-xl mb-2">
                            Atur Titik Lokasi Toko
                        </h3>

                        <p class="text-xs sm:text-sm text-gray-600 mb-5 sm:mb-6 leading-relaxed">
                            Sistem mendeteksi titik koordinat toko Anda belum diatur.
                            Titik ini akan digunakan oleh kurir sebagai lokasi
                            <b>Checkpoint</b>.
                            <br><br>

                            <span class="text-red-600 font-bold">
                                PERINGATAN:
                            </span>

                            Apakah Anda sedang berada di dalam bangunan fisik toko saat ini?
                        </p>

                        <div class="flex flex-col gap-2.5 sm:gap-3">

                            <button id="btnSetLokasi" onclick="getLocation()"
                                class="w-full bg-green-600 text-white font-bold
                                       py-3 sm:py-3.5 px-4 rounded-xl
                                       hover:bg-green-700 active:bg-green-800
                                       transition shadow-md text-sm sm:text-base">

                                Ya, Kunci Lokasi Saat Ini
                            </button>

                            <button onclick="document.getElementById('modalSetLokasiToko').classList.add('hidden')"
                                class="w-full bg-gray-100 hover:bg-gray-200
                                       text-gray-700 font-bold
                                       py-3 sm:py-3.5 px-4 rounded-xl
                                       transition text-sm sm:text-base">

                                Tidak, Saya Sedang di Luar Toko (Lewati)
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
        <div
            class="bg-white p-4 sm:p-5 md:p-6
                rounded-xl sm:rounded-2xl
                shadow-sm border border-gray-100">

            {{-- HEADER --}}
            <div class="flex flex-col gap-2 sm:gap-3 mb-5 sm:mb-6">

                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">
                        Daftar Pengiriman ke Toko
                    </h1>

                    <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Pilih pengiriman yang sedang Anda terima untuk mulai
                        membongkar dan men-scan barang.
                    </p>
                </div>

            </div>


            {{-- ERROR --}}
            @if (session('error'))
                <div
                    class="bg-red-50 text-red-700
                        p-3 sm:p-4
                        rounded-xl mb-5 sm:mb-6
                        font-medium text-xs sm:text-sm
                        border border-red-100">

                    {{ session('error') }}

                </div>
            @endif


            {{-- =====================================================
            CARD PENGIRIMAN
        ====================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3
                    gap-4 sm:gap-5 lg:gap-6">

                @forelse($inTransit as $logistic)
                    <div
                        class="bg-white border border-gray-200
                            rounded-xl sm:rounded-2xl
                            shadow-sm overflow-hidden
                            flex flex-col
                            hover:border-green-300
                            hover:shadow-md
                            transition-all duration-200">

                        {{-- CARD CONTENT --}}
                        <div class="p-4 sm:p-5 flex-grow">

                            {{-- Status + ID --}}
                            <div class="flex items-start justify-between gap-3 mb-4">

                                <div class="min-w-0">

                                    @if ($logistic->status === 'out_of_transit')
                                        <span
                                            class="inline-flex
                                                 bg-blue-100 text-blue-800
                                                 text-[9px] sm:text-[10px]
                                                 px-2 sm:px-2.5 py-1
                                                 rounded-full
                                                 font-bold uppercase
                                                 tracking-wider">

                                            Tiba di Toko
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex
                                                 bg-amber-100 text-amber-800
                                                 text-[9px] sm:text-[10px]
                                                 px-2 sm:px-2.5 py-1
                                                 rounded-full
                                                 font-bold uppercase
                                                 tracking-wider">

                                            Menuju Toko
                                        </span>
                                    @endif

                                    <h3
                                        class="font-bold text-gray-800
                                           mt-2 font-mono
                                           text-sm sm:text-base
                                           break-all">

                                        {{ $logistic->id_logistic }}

                                    </h3>

                                </div>

                            </div>


                            {{-- DETAIL --}}
                            <div class="space-y-2.5 mb-4">

                                {{-- Kurir --}}
                                <div
                                    class="flex items-start text-xs sm:text-sm
                                        text-gray-600 gap-2">

                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4">
                                        </path>

                                    </svg>

                                    <span class="min-w-0">
                                        Kurir:

                                        <span class="font-semibold text-gray-800 break-words">
                                            {{ $logistic->driverName }}
                                        </span>
                                    </span>

                                </div>


                                {{-- Total --}}
                                <div
                                    class="flex items-start text-xs sm:text-sm
                                        text-gray-600 gap-2">

                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>

                                    </svg>

                                    <span>
                                        Total Barang:

                                        <span class="font-bold text-gray-800">
                                            {{ $logistic->total_items }}
                                        </span>

                                        unit
                                    </span>

                                </div>


                                {{-- Scanned --}}
                                <div
                                    class="flex items-start text-xs sm:text-sm
                                        text-gray-600 gap-2">

                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>

                                    </svg>

                                    <span>
                                        Sudah Discan:

                                        <span class="font-bold text-green-600">
                                            {{ $logistic->received_items }}
                                        </span>

                                        unit
                                    </span>

                                </div>

                            </div>


                            {{-- PROGRESS --}}
                            @php
                                $percentage =
                                    $logistic->total_items > 0
                                        ? ($logistic->received_items / $logistic->total_items) * 100
                                        : 0;
                            @endphp

                            <div
                                class="flex justify-between items-center
                                    text-[10px] sm:text-xs
                                    text-gray-500 mb-1.5">

                                <span>Progress</span>

                                <span class="font-bold text-green-600">
                                    {{ number_format($percentage, 0) }}%
                                </span>

                            </div>

                            <div class="w-full bg-gray-100
                                    rounded-full h-2 sm:h-2.5">

                                <div class="bg-green-500
                                        h-2 sm:h-2.5
                                        rounded-full
                                        transition-all duration-500"
                                    style="width: {{ $percentage }}%">
                                </div>

                            </div>

                        </div>


                        {{-- CARD FOOTER --}}
                        <div
                            class="p-3 sm:p-4
                                border-t border-gray-50
                                bg-gray-50/50">

                            <a href="{{ url('/toko/penerimaan/mulai/' . $logistic->id_logistic) }}"
                                class="w-full block text-center
                                  bg-gray-800 text-white
                                  px-4 py-3
                                  rounded-xl
                                  text-xs sm:text-sm
                                  font-bold
                                  hover:bg-gray-700
                                  active:bg-gray-900
                                  transition shadow-sm">

                                Bongkar & Scan Barang

                            </a>

                        </div>

                    </div>

                @empty

                    {{-- EMPTY STATE --}}
                    <div
                        class="col-span-full
                            py-10 sm:py-12
                            px-4
                            flex flex-col items-center justify-center
                            bg-gray-50
                            rounded-xl sm:rounded-2xl
                            border border-dashed border-gray-300">

                        <svg class="w-12 h-12 sm:w-16 sm:h-16
                                text-gray-300 mb-4"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                            </path>

                        </svg>

                        <h3
                            class="text-base sm:text-lg
                               font-bold text-gray-600
                               mb-1 text-center">

                            Tidak ada pengiriman

                        </h3>

                        <p
                            class="text-xs sm:text-sm
                              text-gray-400
                              text-center
                              max-w-sm
                              leading-relaxed">

                            Saat ini tidak ada paket logistik yang sedang
                            dalam perjalanan menuju toko Anda.

                        </p>

                    </div>
                @endforelse

            </div>

        </div>

    </div>


  {{-- =========================================================
    JAVASCRIPT GPS
========================================================== --}}
<script>
    function getLocation() {
        let btn = document.getElementById('btnSetLokasi');

        if (!btn || btn.disabled) return; // Mencegah double-click

        let originalText = btn.innerHTML;

        // Ubah teks tombol menjadi loading
        btn.innerHTML = `
            <span class="inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengecek Sinyal GPS...
            </span>
        `;
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');

        // Pengecekan Dukungan Browser
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                
                // 1. JIKA BERHASIL MENDAPATKAN LOKASI
                async function(position) {
                    let akurasi = position.coords.accuracy;
                    console.log("Koordinat didapat. Akurasi:", akurasi, "meter");

                    if (akurasi > 150) { 
                        alert(
                            "Gagal! Sinyal GPS Anda terlalu lemah (" + Math.round(akurasi) + " meter). " + 
                            "Harap matikan Fake GPS, nyalakan WiFi, atau melangkah keluar ruangan sejenak agar sinyal kuat."
                        );
                        resetTombol(btn, originalText);
                        return;
                    }

                    try {
                        let response = await fetch("{{ route('toko.update.lokasi') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            })
                        });

                        let result = await response.json();

                        if (result.success) {
                            alert("Berhasil! Titik lokasi toko Anda sudah dikunci.");
                            window.location.reload();
                        } else {
                            alert("Gagal menyimpan ke database: " + (result.message || 'Error tidak diketahui'));
                            resetTombol(btn, originalText);
                        }

                    } catch (e) {
                        console.error("Fetch Error:", e);
                        alert("Terjadi kesalahan jaringan internet.");
                        resetTombol(btn, originalText);
                    }
                },
                
               // 2. JIKA GAGAL MENDAPATKAN LOKASI
                function(error) {
                    // Memunculkan pesan error mentah dari browser secara langsung
                    alert("Browser Error Code: " + error.code + "\nPesan Asli: " + error.message);
                    resetTombol(btn, originalText);
                },
                
                // 3. PENGATURAN SENSOR GPS
                {
                    enableHighAccuracy: true,
                    timeout: 20000, // Waktu tunggu maksimal diperpanjang jadi 20 detik
                    maximumAge: 0
                }
            );
        } else {
            alert("Browser atau perangkat Anda tidak mendukung fitur sensor lokasi (GPS).");
            resetTombol(btn, originalText);
        }
    }

    // Fungsi untuk mengembalikan tombol ke keadaan semula jika gagal
    function resetTombol(btn, originalText) {
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-not-allowed');
    }

    // Fungsi Ajukan Reset (Tetap sama, tidak ada yang berubah)
    async function ajukanResetLokasi() {
        if (!confirm('Apakah Anda yakin ingin mengajukan perubahan titik lokasi toko? Proses ini butuh persetujuan Admin.')) return;

        try {
            let response = await fetch("{{ route('toko.ajukan.reset') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Gagal mengajukan reset: ' + result.message);
            }
        } catch (e) {
            alert('Terjadi kesalahan jaringan saat mengajukan reset.');
        }
    }
    </script>
@endsection
