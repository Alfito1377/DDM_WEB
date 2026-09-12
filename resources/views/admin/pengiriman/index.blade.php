@extends('layouts.app')

@section('content')
    {{-- {{ dd($process_status['filter']['status']) }} --}}
    <!-- Memanggil Library HTML5 QR Code -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>

        /* Base Alert Class */
        .alert {
            position: relative;
            padding: 1rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem; /* Mirip dengan rounded Bootstrap */
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            box-sizing: border-box;
        }

        /* Success Alert (Warna Hijau) */
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        /* Warning Alert (Warna Kuning) */
        /* Saya sertakan juga .alert-waning untuk berjaga-jaga jika Anda tidak mengubah typo-nya */
        .alert-warning, .alert-waning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }

        /* (Opsional) Danger Alert (Warna Merah) jika nanti Anda butuhkan */
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        /* (Opsional) Info Alert (Warna Biru) jika nanti Anda butuhkan */
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
        /*
        |--------------------------------------------------------------------------
        | Responsive Table
        |--------------------------------------------------------------------------
        */

        .responsive-table-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .responsive-table {
            min-width: 950px;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 640px) {

            .responsive-table {
                min-width: 900px;
            }

            /*
            | SweetAlert agar nyaman di HP
            */
            .swal2-popup {
                width: calc(100% - 30px) !important;
                margin: 0 15px;
            }
        }
    </style>


    {{-- ============================================================= --}}
    {{-- MAIN CONTAINER --}}
    {{-- ============================================================= --}}

    <div class="w-full min-w-0">

        <div
            class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 relative w-full min-w-0">


            {{-- ========================================================= --}}
            {{-- HEADER --}}
            {{-- ========================================================= --}}

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8 gap-4">

                {{-- TITLE --}}

                <div class="min-w-0">

                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">
                        Master Data Pengiriman Aktif
                    </h1>

                    <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">
                        Pantau status, tujuan, sopir, dan armada yang sedang berjalan.
                    </p>

                </div>


                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

                    <!-- Form Pencarian & Filter Status (Disatukan dalam 1 Form) -->
                    <form method="GET" action="{{ url()->current() }}"
                        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">

                        {{-- Area Kotak Pencarian --}}
                        <div class="relative w-full sm:w-64">

                            {{-- Icon Search --}}
                            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>
                            </svg>

                            {{-- Input Search --}}
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari mitra, pemilik, alamat..."
                                class="w-full border border-gray-300 p-3 sm:py-2.5 sm:pr-10 pl-10 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-sm text-gray-700">

                            {{-- Tombol Submit Tersembunyi (Agar bisa submit saat tekan Enter) --}}
                            <button type="submit" class="hidden"></button>

                            {{-- Tombol Reset (Silang) --}}
                            @if (request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition"
                                    title="Hapus kata kunci">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12">
                                        </path>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        {{-- Dropdown Filter Status --}}
                        <select name="status" onchange="this.form.submit()"
                            class="w-full sm:w-auto bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5 outline-none font-semibold cursor-pointer">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Packed</option>
                            <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit
                            </option>
                            <option value="out_of_transit" {{ request('status') == 'out_of_transit' ? 'selected' : '' }}>Out
                                of Transit</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>

                    </form>

                    <!-- Tombol Fetch -->
                    <button onclick="openFetchModal()" type="button"
                        class="w-full sm:w-auto bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Fetch Terbaru
                    </button>

                    <form method="GET" action="{{ url()->current() }}" id="form-fetch-data">
                        <input type="hidden" name="fetch_data" id="fetch_data" value="false">
                    </form>

                </div>

            </div>

            @if ($process_status['filter']['status'])
                <div class="alert alert-success" role="alert">
                    {{ $process_status['filter']['msg'] }}
                </div>
            @elseif ($process_status['fetch_data']['status'])
                <div class="alert alert-warning" role="alert">
                    {{ $process_status['fetch_data']['msg'] }}
                </div>
                <script>
                    let params = new URLSearchParams(window.location.search);
                    params.delete('fetch_data'); 
                    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + window.location.hash;
                    window.history.replaceState(null, '', newUrl);
                </script>
            @endif


            {{-- ========================================================= --}}
            {{-- MOBILE TABLE INFO --}}
            {{-- ========================================================= --}}

            <div class="flex sm:hidden items-center gap-2 mb-3 text-xs text-gray-400">

                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h14">
                    </path>

                </svg>

                <span>
                    Geser tabel ke kiri atau kanan untuk melihat data lainnya.
                </span>

            </div>


            {{-- ========================================================= --}}
            {{-- TABLE --}}
            {{-- ========================================================= --}}

            <div class="responsive-table-wrapper border border-gray-100 rounded-xl">

                <table class="responsive-table w-full text-sm text-left text-gray-600">

                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-y border-gray-200">

                        <tr>

                            <th scope="col" class="px-4 py-4 font-bold text-center w-12 whitespace-nowrap">

                                No.

                            </th>


                            <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">

                                Nomor Dokumen & Waktu Berangkat

                            </th>


                            <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">

                                Sopir & Kontak

                            </th>


                            <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">

                                Armada Kendaraan

                            </th>


                            <th scope="col" class="px-6 py-4 font-bold text-center whitespace-nowrap">

                                Status

                            </th>


                            <th scope="col" class="px-6 py-4 font-bold text-center whitespace-nowrap">

                                Aksi

                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse ($logistics as $index => $logistic)
                            <tr class="hover:bg-gray-50 transition">


                                {{-- ================================================= --}}
                                {{-- NOMOR --}}
                                {{-- ================================================= --}}

                                <td class="px-4 py-4 font-bold text-gray-700 text-center whitespace-nowrap">

                                    {{ ($logistics->currentPage() - 1) * $logistics->perPage() + $loop->iteration }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- TUJUAN & WAKTU --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4">

                                    <div class="font-bold text-gray-800 text-base mb-1 whitespace-nowrap">

                                        {{ $logistic->doNumber ?? '-' }}

                                    </div>


                                    <div
                                        class="flex items-center text-xs text-green-700 font-semibold bg-green-50 w-max px-2 py-1 rounded border border-green-100 whitespace-nowrap">

                                        <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>

                                        </svg>

                                        {{ $logistic->departedAt }}

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- SOPIR --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4">

                                    <div class="font-bold text-gray-800 whitespace-nowrap">

                                        {{ optional($logistic->driver)->name ?? 'Belum ada sopir' }}

                                    </div>


                                    <div class="flex items-center text-xs text-gray-500 mt-1 whitespace-nowrap">

                                        <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>

                                        </svg>

                                        {{ optional($logistic->driver)->phone ?? '-' }}

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- KENDARAAN --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4">

                                    <div class="font-bold text-gray-800 whitespace-nowrap">

                                        {{ optional($logistic->vehicle)->vehicleType ?? '-' }}

                                    </div>


                                    <div class="mt-1">

                                        <span
                                            class="inline-block px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-300 font-mono text-xs font-bold rounded whitespace-nowrap">

                                            {{ optional($logistic->vehicle)->plateNo ?? 'Plat Kosong' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 text-center">

                                    <span
                                        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold leading-none {{ $logistic->status['color'] ?? 'bg-gray-100 text-gray-800' }} rounded-full whitespace-nowrap">

                                        {{ $logistic->status['label'] ?? 'Unknown' }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- AKSI --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 text-center">

                                    <button type="button"
                                        class="text-blue-600 hover:text-blue-800 font-bold text-xs px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition whitespace-nowrap"
                                        onclick="openLogisticModal({{ $logistic->id }})">

                                        Detail Info

                                    </button>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">

                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>

                                    </svg>

                                    Belum ada data pengiriman yang cocok.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ========================================================= --}}
            {{-- PAGINATION --}}
            {{-- ========================================================= --}}

            <div class="mt-6 overflow-x-auto">

                {{ $logistics->appends(request()->query())->links() }}

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- MODAL INFO PENGIRIMAN --}}
    {{-- ============================================================= --}}

    <div id="logisticModal"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-2 sm:p-4">

        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-xl w-full max-w-5xl transform transition-all overflow-hidden flex flex-col max-h-[95vh] sm:max-h-[90vh]">


            {{-- ========================================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ========================================================= --}}

            <div
                class="px-4 sm:px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 flex-shrink-0">

                <h3 class="font-bold text-gray-800 text-base sm:text-lg">

                    Detail Ekstra Pengiriman

                </h3>


                <button type="button" onclick="closeLogisticModal()"
                    class="text-gray-400 hover:text-red-500 transition flex-shrink-0 ml-3">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>

                    </svg>

                </button>

            </div>


            {{-- ========================================================= --}}
            {{-- MODAL BODY --}}
            {{-- ========================================================= --}}

            <div class="p-4 sm:p-6 overflow-y-auto">


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">


                    {{-- ================================================= --}}
                    {{-- SISI KIRI --}}
                    {{-- ================================================= --}}

                    <div>

                        <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">

                            Informasi & Tujuan

                        </h4>


                        <div class="space-y-4">


                            {{-- STATUS --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Status Saat Ini

                                </label>


                                <input id="info_status" disabled type="text"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none">

                            </div>


                            {{-- WAKTU --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Waktu Berangkat & Sampai

                                </label>


                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                    <input id="info_departedAt" disabled type="text" placeholder="Berangkat..."
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none">

                                    <input id="info_arrivedAt" disabled type="text" placeholder="Sampai..."
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none">

                                </div>

                            </div>


                            {{-- MITRA --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Nama Mitra (Tujuan)

                                </label>


                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">

                                    <input id="info_store_name" disabled type="text"
                                        class="w-full sm:col-span-2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none">

                                    <input id="info_store_type" disabled type="text"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 sm:text-center focus:outline-none">

                                </div>

                            </div>


                            {{-- PEMILIK --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Pemilik & Kontak Mitra

                                </label>


                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                    <input id="info_store_owner" disabled type="text"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none">

                                    <input id="info_store_phone" disabled type="text"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none">

                                </div>

                            </div>


                            {{-- ALAMAT --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Alamat Lengkap

                                </label>


                                <textarea id="info_store_address" disabled rows="3"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none resize-none"></textarea>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SISI KANAN --}}
                    {{-- ================================================= --}}

                    <div>

                        <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">

                            Informasi Driver & Armada

                        </h4>


                        <div class="space-y-4">


                            {{-- DRIVER --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Nama Driver

                                </label>


                                <input id="info_driver_name" disabled type="text"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none">

                            </div>


                            {{-- KONTAK --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Kontak Driver

                                </label>


                                <input id="info_driver_phone" disabled type="text"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none">

                            </div>


                            {{-- KENDARAAN --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Armada / Kendaraan

                                </label>


                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                    <input id="info_driver_type" disabled type="text"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none">

                                    <input id="info_driver_vehicle_number" disabled type="text"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-mono font-bold sm:text-center text-gray-800 focus:outline-none">

                                </div>

                            </div>


                            {{-- CATATAN --}}

                            <div>

                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">

                                    Catatan Tambahan (Notes)

                                </label>


                                <textarea id="info_driver_description" disabled rows="4"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none resize-none"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MODAL FOOTER --}}
            {{-- ========================================================= --}}

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end flex-shrink-0">

                <button type="button" onclick="closeLogisticModal()"
                    class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 px-6 rounded-lg transition">

                    Tutup

                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>
        function openFetchModal() {
            Swal.fire({
                title: "Memperbarui Data",
                text: "Apakah anda ingin mengambil data pengiriman terbaru?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Perbarui!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#16a34a",
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location.reload();
                    const targetForm = document.querySelector('#form-fetch-data');
                    const targetParam = document.getElementById('fetch_data');
                    targetParam.value = true;
                    targetForm.submit();
                };
            });
        }


        function openLogisticModal(logisticId) {

            fetch(`/superadmin/pengiriman/${logisticId}/detail`)

                .then(response => response.json())

                .then(data => {

                    document.getElementById('info_status').value =
                        data.data.status.label;

                    document.getElementById('info_departedAt').value =
                        data.data.departedAt;

                    document.getElementById('info_arrivedAt').value =
                        data.data.arrivedAt || '-';


                    if (data.data.mitra) {

                        document.getElementById('info_store_name').value =
                            data.data.mitra.store_name || '-';

                        document.getElementById('info_store_type').value =
                            data.data.mitra.jenis_mitra || '-';

                        document.getElementById('info_store_owner').value =
                            data.data.mitra.owner_name || '-';

                        document.getElementById('info_store_phone').value =
                            data.data.mitra.phone_number || '-';

                        document.getElementById('info_store_address').value =
                            data.data.mitra.address || '-';

                    }


                    if (data.data.driver) {

                        document.getElementById('info_driver_name').value =
                            data.data.driver.name || '-';

                        document.getElementById('info_driver_phone').value =
                            data.data.driver.phone || '-';

                        document.getElementById('info_driver_description').value =
                            data.data.driver.notes || '-';

                    }


                    if (data.data.vehicle) {

                        document.getElementById('info_driver_vehicle_number').value =
                            data.data.vehicle.plateNo || '-';

                        document.getElementById('info_driver_type').value =
                            data.data.vehicle.vehicleType || '-';

                    }

                })

                .catch(error => {

                    console.error(
                        "Gagal memuat data pengiriman: ",
                        error
                    );

                    alert(
                        "Gagal memuat data pengiriman."
                    );

                });


            document
                .getElementById('logisticModal')
                .classList.remove('hidden');

            document
                .getElementById('logisticModal')
                .classList.add('flex');

        }


        function closeLogisticModal() {

            const modal =
                document.getElementById('logisticModal');

            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }
    </script>
@endsection
