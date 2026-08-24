@extends('layouts.app')

@section('content')
    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-200/60 relative">

        <div class="mb-6 sm:mb-8 space-y-4">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 sm:w-11 sm:h-11
                            rounded-xl bg-blue-50 border border-blue-100
                            flex items-center justify-center shrink-0">

                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>
                        </div>

                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                                Persetujuan Retur Benih
                            </h1>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                                Tinjau bukti pengembalian dan proses pengajuan retur pelanggan.
                            </p>
                        </div>

                    </div>
                </div>


<!-- ================= RETURN STATISTICS ================= -->
<div class="flex flex-wrap items-center gap-4 mb-6">

    <!-- ================= PENDING ================= -->
    <div class="flex items-center justify-between w-full sm:w-auto min-w-[240px] px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-2xl shadow-sm hover:shadow transition-all">
        <div class="flex items-center gap-3">
            <!-- Dot Indicator -->
            <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
            <!-- Label -->
            <span class="text-sm font-medium text-amber-700">
                Menunggu Persetujuan
            </span>
        </div>
        <!-- Number Badge -->
        <div class="px-3 py-1 bg-amber-200 text-amber-900 text-sm font-bold rounded-xl ml-4">
            {{ $pendingCount ?? 0 }}
        </div>
    </div>

    <!-- ================= APPROVED ================= -->
    <div class="flex items-center justify-between w-full sm:w-auto min-w-[240px] px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm hover:shadow transition-all">
        <div class="flex items-center gap-3">
            <!-- Dot Indicator -->
            <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
            <!-- Label -->
            <span class="text-sm font-medium text-emerald-700">
                Retur Disetujui
            </span>
        </div>
        <!-- Number Badge -->
        <div class="px-3 py-1 bg-emerald-200 text-emerald-900 text-sm font-bold rounded-xl ml-4">
            {{ $approvedCount ?? 0 }}
        </div>
    </div>

    <!-- ================= REJECTED ================= -->
    <div class="flex items-center justify-between w-full sm:w-auto min-w-[240px] px-4 py-2.5 bg-rose-50 border border-rose-200 rounded-2xl shadow-sm hover:shadow transition-all">
        <div class="flex items-center gap-3">
            <!-- Dot Indicator -->
            <span class="w-3 h-3 bg-rose-500 rounded-full"></span>
            <!-- Label -->
            <span class="text-sm font-medium text-rose-700">
                Retur Ditolak
            </span>
        </div>
        <!-- Number Badge -->
        <div class="px-3 py-1 bg-rose-200 text-rose-900 text-sm font-bold rounded-xl ml-4">
            {{ $rejectedCount ?? 0 }}
        </div>
    </div>

</div>
            </div>


            <!-- ================= FILTER TOOLBAR ================= -->
            <div
                class="bg-slate-50/70
                border border-slate-200/70
                rounded-2xl
                p-3 sm:p-4
                shadow-sm">

                <form method="GET" action="{{ url()->current() }}"
                    class="flex flex-col lg:flex-row lg:items-center gap-3">

                    <!-- ================= SEARCH ================= -->
                    <div class="relative flex-1">

                        <!-- Search Icon -->
                        <svg class="w-4 h-4 text-slate-400
                           absolute left-3.5 top-1/2 -translate-y-1/2
                           pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>


                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode retur, toko, atau barcode..."
                            class="w-full
                           bg-white
                           border border-slate-300
                           text-slate-700 text-sm
                           rounded-xl
                           pl-10 pr-10 py-2.5
                           outline-none
                           transition
                           shadow-sm
                           focus:border-blue-500
                           focus:ring-2
                           focus:ring-blue-500/10">


                        <!-- Reset Search -->
                        @if (request('search'))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                               w-6 h-6
                               flex items-center justify-center
                               rounded-md
                               text-slate-400
                               hover:text-rose-500
                               hover:bg-rose-50
                               transition"
                                title="Hapus pencarian">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>

                            </a>
                        @endif

                    </div>


                    <!-- ================= STATUS FILTER ================= -->
                    <div class="relative w-full lg:w-56">

                        <svg class="w-4 h-4 text-slate-400
                           absolute left-3 top-1/2 -translate-y-1/2
                           pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V17a1 1 0 01-.553.894l-4 2A1 1 0 019 19v-6.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>


                        <select name="status" onchange="this.form.submit()"
                            class="w-full
                           bg-white
                           border border-slate-300
                           text-slate-700 text-sm font-medium
                           rounded-xl
                           pl-10 pr-4 py-2.5
                           outline-none
                           cursor-pointer
                           shadow-sm
                           transition
                           focus:border-blue-500
                           focus:ring-2
                           focus:ring-blue-500/10">

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>
                                Disetujui
                            </option>

                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                                Ditolak
                            </option>

                        </select>

                    </div>


                    <!-- ================= RESET FILTER ================= -->
                    @if (request('search') || request('status'))
                        <a href="{{ url()->current() }}"
                            class="w-full lg:w-auto
                           flex items-center justify-center gap-2
                           px-4 py-2.5
                           rounded-xl
                           border border-slate-200
                           bg-white
                           text-sm font-medium text-slate-600
                           hover:bg-slate-50
                           hover:text-rose-600
                           transition
                           shadow-sm">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M4.582 9H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2M19.419 15H15" />
                            </svg>

                            Reset

                        </a>
                    @endif


                    <!-- ================= REFRESH ================= -->
                    <a href="{{ url()->current() }}"
                        class="w-full lg:w-auto
                       flex items-center justify-center
                       px-3 py-2.5
                       rounded-xl
                       border border-slate-200
                       bg-white
                       text-slate-500
                       hover:text-blue-600
                       hover:bg-blue-50
                       transition
                       shadow-sm"
                        title="Muat ulang data">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M4.582 9H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2M19.419 15H15" />
                        </svg>

                    </a>

                </form>

            </div>


            <!-- ================= ACTIVE FILTER / DATA INFO ================= -->
            <div
                class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-2 px-1">

                <!-- Info Data -->
                {{-- <p class="text-xs sm:text-sm text-slate-500">

            Menampilkan data pengajuan retur benih --}}

                {{-- @if (request('search'))
                <span class="text-slate-700 font-medium">
                    untuk pencarian "{{ request('search') }}"
                </span>
            @endif

        </p> --}}


                <!-- Active Status -->
                @if (request('status'))
                    <div class="flex items-center gap-2">

                        <span class="text-xs text-slate-400">
                            Filter aktif:
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5
                             px-2.5 py-1
                             rounded-lg
                             bg-blue-50
                             border border-blue-100
                             text-xs font-semibold text-blue-700">

                            {{ request('status') }}

                        </span>

                    </div>
                @endif

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- DESKTOP TABLE -->
        <!-- ===================================================== -->

        <div class="hidden md:block overflow-hidden rounded-xl border border-slate-200/60 shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-sm text-left text-slate-600">

                    <thead
                        class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200/60 whitespace-nowrap">

                        <tr>
<th class="px-6 py-4 font-bold tracking-wider text-center w-16">
                        No.
                    </th>
                            <th class="px-6 py-4 font-bold tracking-wider">
                                Kode & Tanggal
                            </th>

                            <th class="px-6 py-4 font-bold tracking-wider">
                                Nama Toko & Barcode
                            </th>

                            <th class="px-6 py-4 font-bold tracking-wider">
                                Alasan Retur
                            </th>

                            <th class="px-6 py-4 font-bold tracking-wider">
                                Keterangan Tambahan
                            </th>

                            <th class="px-6 py-4 font-bold tracking-wider text-center">
                                Status
                            </th>

                            <th class="px-6 py-4 font-bold tracking-wider text-center">
                                Aksi Manajer
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse ($returns as $retur)
                            <tr class="hover:bg-blue-50/20 transition-colors duration-200">
<td class="px-6 py-4 whitespace-nowrap text-center font-medium text-slate-500">
                            {{ $loop->iteration }}
                        </td>
                                <!-- KODE -->
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="font-bold text-slate-800">
                                        {{ $retur->return_code ?? '#RET-' . str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>

                                    <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">

                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                        </svg>

                                        {{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }}

                                    </div>

                                </td>


                                <!-- TOKO -->
                                <td class="px-6 py-4">

                                    <div class="font-bold text-slate-800 flex items-center gap-1.5">

                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />

                                        </svg>

                                        <span class="truncate max-w-[180px]">
                                            {{ $retur->store_name }}
                                        </span>

                                    </div>

                                    <div
                                        class="text-[11px] text-slate-600 font-medium mt-1.5 bg-slate-100/80 border border-slate-200/60 inline-block px-2 py-0.5 rounded-md">

                                        Barcode:
                                        {{ $retur->barcode }}

                                        &bull;

                                        <span class="font-bold text-slate-800">
                                            {{ $retur->quantity }} Pack
                                        </span>

                                    </div>

                                </td>


                                <!-- ALASAN -->
                                <td class="px-6 py-4">

                                    <p class="text-slate-600 max-w-[180px] line-clamp-2 text-xs leading-relaxed"
                                        title="{{ $retur->reason }}">

                                        {{ $retur->reason }}

                                    </p>

                                </td>


                                <!-- KETERANGAN -->
                                <td class="px-6 py-4">

                                    @if ($retur->notes)
                                        <div class="bg-slate-50 text-slate-600 px-3 py-2 rounded-lg border border-slate-100 text-[11px] max-w-[180px] max-h-16 overflow-y-auto shadow-inner line-clamp-2"
                                            title="{{ $retur->notes }}">

                                            {{ $retur->notes }}

                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">
                                            -
                                        </span>
                                    @endif

                                </td>


                                <!-- STATUS -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">

                                    @if ($retur->status === 'Pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">

                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>

                                            Pending

                                        </span>
                                    @elseif($retur->status === 'Approved')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">

                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>

                                            Disetujui

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">

                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>

                                            Ditolak

                                        </span>
                                    @endif

                                </td>


                                <!-- AKSI -->
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="flex items-center justify-center gap-2">

                                        <button onclick='openDetailModal(@json($retur))'
                                            title="Lihat Detail Foto Bukti"
                                            class="group flex items-center gap-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-blue-600 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all shadow-sm hover:shadow">

                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                            </svg>

                                            Detail

                                        </button>


                                        @if ($retur->status === 'Pending')
                                            <div class="w-px h-5 bg-slate-200 mx-1"></div>

                                            <button onclick="processReturn({{ $retur->id }}, 'Approved')"
                                                title="Setujui Retur"
                                                class="flex items-center justify-center w-8 h-8 bg-emerald-50 hover:bg-emerald-500 hover:text-white border border-emerald-200 text-emerald-700 rounded-lg transition-all shadow-sm">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />

                                                </svg>

                                            </button>


                                            <button onclick="processReturn({{ $retur->id }}, 'Rejected')"
                                                title="Tolak Retur"
                                                class="flex items-center justify-center w-8 h-8 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200 text-rose-700 rounded-lg transition-all shadow-sm">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />

                                                </svg>

                                            </button>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-6 py-16 text-center">

                                    <div class="flex flex-col items-center justify-center text-slate-400">

                                        <div
                                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">

                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />

                                            </svg>

                                        </div>

                                        <p class="text-base font-medium text-slate-500">
                                            Belum ada pengajuan retur
                                        </p>

                                        <p class="text-xs mt-1">
                                            Pengajuan retur dari mitra toko akan muncul di sini.
                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- MOBILE CARD -->
        <!-- ===================================================== -->

        <div class="md:hidden space-y-4">

            @forelse ($returns as $retur)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

                    <!-- Card Header -->
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100">

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">

                                <p class="text-sm font-bold text-slate-800 truncate">
                                    {{ $retur->return_code ?? '#RET-' . str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}
                                </p>

                                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">

                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>

                                    {{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }}

                                </div>

                            </div>


                            <!-- Status -->

                            @if ($retur->status === 'Pending')
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-bold px-2 py-1 rounded-lg uppercase">

                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>

                                    Pending

                                </span>
                            @elseif($retur->status === 'Approved')
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold px-2 py-1 rounded-lg uppercase">

                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>

                                    Disetujui

                                </span>
                            @else
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-bold px-2 py-1 rounded-lg uppercase">

                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>

                                    Ditolak

                                </span>
                            @endif

                        </div>

                    </div>


                    <!-- Card Body -->

                    <div class="p-4 space-y-4">

                        <!-- Toko -->

                        <div>

                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">
                                Nama Toko
                            </p>

                            <div class="flex items-center gap-2">

                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />

                                </svg>

                                <span class="font-bold text-sm text-slate-800 break-words">
                                    {{ $retur->store_name }}
                                </span>

                            </div>

                        </div>


                        <!-- Barcode -->

                        <div class="flex flex-wrap gap-2">

                            <span
                                class="text-[11px] text-slate-600 font-medium bg-slate-100 border border-slate-200 px-2 py-1 rounded-md">

                                Barcode:
                                <span class="font-mono font-bold">
                                    {{ $retur->barcode }}
                                </span>

                            </span>

                            <span
                                class="text-[11px] text-slate-700 font-bold bg-blue-50 border border-blue-100 px-2 py-1 rounded-md">

                                {{ $retur->quantity }} Pack

                            </span>

                        </div>


                        <!-- Alasan -->

                        <div>

                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">
                                Alasan Retur
                            </p>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $retur->reason }}
                            </p>

                        </div>


                        <!-- Keterangan -->

                        <div>

                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">
                                Keterangan Tambahan
                            </p>

                            @if ($retur->notes)
                                <div
                                    class="bg-slate-50 text-slate-600 px-3 py-2 rounded-lg border border-slate-100 text-xs leading-relaxed">

                                    {{ $retur->notes }}

                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">
                                    Tidak ada keterangan
                                </span>
                            @endif

                        </div>

                    </div>


                    <!-- Card Action -->

                    <div class="p-4 bg-slate-50 border-t border-slate-100">

                        <div class="flex gap-2">

                            <!-- Detail -->

                            <button onclick='openDetailModal(@json($retur))'
                                class="flex-1 flex items-center justify-center gap-2 bg-white hover:bg-blue-50 border border-slate-200 text-slate-700 hover:text-blue-600 font-semibold px-3 py-2.5 rounded-lg text-xs transition-all shadow-sm">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                </svg>

                                Detail

                            </button>


                            @if ($retur->status === 'Pending')
                                <!-- Approve -->

                                <button onclick="processReturn({{ $retur->id }}, 'Approved')"
                                    class="w-11 h-11 flex items-center justify-center bg-emerald-50 hover:bg-emerald-500 hover:text-white border border-emerald-200 text-emerald-700 rounded-lg transition-all shadow-sm">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </button>


                                <!-- Reject -->

                                <button onclick="processReturn({{ $retur->id }}, 'Rejected')"
                                    class="w-11 h-11 flex items-center justify-center bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200 text-rose-700 rounded-lg transition-all shadow-sm">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />

                                    </svg>

                                </button>
                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="py-12 text-center">

                    <div class="flex flex-col items-center justify-center text-slate-400">

                        <div
                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">

                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 01-2-2H6a2 2 0 01-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />

                            </svg>

                        </div>

                        <p class="text-base font-medium text-slate-500">
                            Belum ada pengajuan retur
                        </p>

                        <p class="text-xs mt-1">
                            Pengajuan retur dari mitra toko akan muncul di sini.
                        </p>

                    </div>

                </div>
            @endforelse

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- MODAL DETAIL -->
    <!-- ===================================================== -->

    <div id="detailModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-opacity duration-300">

        <div id="modalContent"
            class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden flex flex-col max-h-[94vh] sm:max-h-[90vh] ring-1 ring-black/5">

            <!-- Modal Header -->

            <div
                class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 backdrop-blur shrink-0">

                <div class="min-w-0 pr-3">

                    <h3 class="font-bold text-slate-800 text-base sm:text-lg">
                        Detail Pengajuan Retur
                    </h3>

                    <p id="modalReturnCode"
                        class="text-[10px] sm:text-[11px] text-blue-700 font-mono font-bold mt-1 bg-blue-50 inline-block px-2 py-0.5 rounded border border-blue-100 tracking-wide">

                        Kode Retur

                    </p>

                </div>


                <button onclick="closeDetailModal()"
                    class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-all shrink-0">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>


            <!-- Modal Body -->

            <div class="p-4 sm:p-6 overflow-y-auto custom-scrollbar">

                <!-- Informasi -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-6">

                    <!-- Toko -->

                    <div class="bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-100">

                        <p class="text-[9px] sm:text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1">

                            Nama Toko Mitra

                        </p>

                        <p id="modalStoreName" class="text-sm font-bold text-slate-800 break-words">

                            Toko

                        </p>

                    </div>


                    <!-- Barcode -->

                    <div class="bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-100">

                        <p class="text-[9px] sm:text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1">

                            Barcode & Jumlah

                        </p>

                        <p id="modalProduct" class="text-sm font-bold text-slate-800 break-words">

                            Barcode

                        </p>

                    </div>


                    <!-- Alasan -->

                    <div class="bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-100 sm:col-span-2">

                        <p class="text-[9px] sm:text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1">

                            Alasan Retur

                        </p>

                        <p id="modalReason"
                            class="text-sm text-slate-700 font-medium leading-relaxed bg-white p-3 rounded-lg border border-slate-100 mt-1 shadow-sm break-words">

                            Alasan

                        </p>

                    </div>

                </div>


                <!-- Galeri -->

                <div>

                    <div class="flex items-center gap-2 mb-3">

                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                        </svg>

                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">

                            Galeri Foto Bukti

                        </p>

                    </div>


                    <div id="modalImageGallery" class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">

                    </div>

                </div>

            </div>

        </div>


    </div>
    <div class="mt-6 overflow-x-auto">
        {{ $returns->appends(request()->query())->links() }}
    </div>


    <!-- ===================================================== -->
    <!-- JAVASCRIPT -->
    <!-- ===================================================== -->

    <script>
        const storageBaseUrl = "{{ asset('storage') }}";


        // =====================================================
        // MODAL DETAIL
        // =====================================================

        function openDetailModal(returData) {

            const modal = document.getElementById('detailModal');
            const modalContent = document.getElementById('modalContent');


            // Isi informasi

            document.getElementById('modalReturnCode').innerText =
                returData.return_code || ('#RET-' + returData.id);

            document.getElementById('modalStoreName').innerText =
                returData.store_name || '-';

            document.getElementById('modalProduct').innerText =
                `${returData.barcode || '-'} (${returData.quantity || 0} Pack)`;

            document.getElementById('modalReason').innerText =
                returData.reason || '-';


            // Bersihkan galeri

            const gallery = document.getElementById('modalImageGallery');

            gallery.innerHTML = '';


            // Parsing gambar

            try {

                let images =
                    typeof returData.proof_image === 'string' ?
                    JSON.parse(returData.proof_image) :
                    returData.proof_image;


                if (Array.isArray(images) && images.length > 0) {

                    images.forEach(imgPath => {

                        const imgUrl =
                            `${storageBaseUrl}/${imgPath}`;


                        const imgElement = `

                        <a href="${imgUrl}"
                           target="_blank"
                           class="block group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">

                            <img
                                src="${imgUrl}"
                                class="w-full h-28 sm:h-32 object-cover transition duration-300 transform group-hover:scale-110"
                                alt="Bukti Retur">

                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">

                                <svg class="w-7 sm:w-8 h-7 sm:h-8 text-white mb-1"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>

                                </svg>

                                <span class="text-white text-[9px] sm:text-[10px] font-bold">
                                    PERBESAR
                                </span>

                            </div>

                        </a>

                    `;


                        gallery.insertAdjacentHTML(
                            'beforeend',
                            imgElement
                        );

                    });


                } else {

                    gallery.innerHTML = `

                    <div class="col-span-full py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">

                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                        </svg>

                        <p class="text-sm text-slate-400 font-medium">
                            Tidak ada lampiran foto bukti.
                        </p>

                    </div>

                `;

                }


            } catch (e) {

                gallery.innerHTML = `

                <p class="col-span-full py-4 text-center text-sm text-rose-500 bg-rose-50 rounded-xl border border-rose-100 font-medium">

                    ⚠️ Gagal memuat data foto.

                </p>

            `;

            }


            // Tampilkan modal

            modal.classList.remove('pointer-events-none');

            requestAnimationFrame(() => {

                modal.classList.remove('opacity-0');

                modalContent.classList.remove(
                    'scale-95',
                    'opacity-0'
                );

                modalContent.classList.add(
                    'scale-100',
                    'opacity-100'
                );

            });

        }


        // =====================================================
        // CLOSE MODAL
        // =====================================================

        function closeDetailModal() {

            const modal =
                document.getElementById('detailModal');

            const modalContent =
                document.getElementById('modalContent');


            modal.classList.add('opacity-0');

            modalContent.classList.remove(
                'scale-100',
                'opacity-100'
            );

            modalContent.classList.add(
                'scale-95',
                'opacity-0'
            );


            setTimeout(() => {

                modal.classList.add(
                    'pointer-events-none'
                );

            }, 300);

        }


        // =====================================================
        // PROCESS RETURN
        // =====================================================

        async function processReturn(
            returnId,
            decisionStatus
        ) {

            const actionText =
                decisionStatus === 'Approved' ?
                'MENYETUJUI' :
                'MENOLAK';


            const isConfirmed = confirm(
                `Apakah Anda yakin ingin ${actionText} pengajuan retur ini?`
            );


            if (!isConfirmed) return;


            const formData = new FormData();

            formData.append(
                'manager_id',
                '{{ Auth::user()->id ?? 1 }}'
            );

            formData.append(
                'status',
                decisionStatus
            );


            try {

                const response = await fetch(
                    `/superadmin/retur/${returnId}/approve`, {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',

                            'Accept': 'application/json'
                        },

                        body: formData
                    }
                );


                const result =
                    await response.json();


                if (response.ok) {

                    window.location.reload();

                } else {

                    alert(
                        'Gagal memproses: ' +
                        (result.message || result.error)
                    );

                }


            } catch (error) {

                alert(
                    'Terjadi kesalahan komunikasi dengan server saat memproses data.'
                );

            }

        }


        // =====================================================
        // CLOSE MODAL KETIKA KLIK BACKDROP
        // =====================================================

        document
            .getElementById('detailModal')
            .addEventListener('click', function(event) {

                if (event.target === this) {

                    closeDetailModal();

                }

            });


        // =====================================================
        // ESC UNTUK MENUTUP MODAL
        // =====================================================

        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    closeDetailModal();

                }

            }
        );
    </script>


    <style>
        /* Scrollbar modal */

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }


        /* Mencegah body bergeser ketika modal terbuka */

        body {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
@endsection
