<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Surat Jalan Retur - {{ $retur->return_code ?? '#RET-'.$retur->id }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* =========================================
           RESPONSIVE MOBILE
        ========================================= */

        body {
            overflow-x: hidden;
        }

        /* =========================================
           PRINT / PDF
        ========================================= */

        @media print {

            @page {
                margin: 0;
                size: A4;
            }

            html,
            body {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .a4-paper {
                width: 210mm !important;
                min-height: 297mm !important;
                max-width: none !important;

                margin: 0 !important;
                padding: 15mm !important;

                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 640px) {

            .a4-paper {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 1rem;
                border-radius: 0;
            }

            .print-header {
                flex-direction: column;
                gap: 1.25rem;
            }

            .print-header .header-code {
                width: 100%;
                text-align: left;
            }

            .print-header .header-code img {
                margin-left: 0;
            }

            .signature-section {
                grid-template-columns: 1fr !important;
                gap: 3rem !important;
                margin-top: 3rem !important;
            }

            .product-table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .product-table {
                min-width: 600px;
            }
        }
    </style>
</head>

<body
    class="bg-gray-100 text-gray-800 font-sans"
    onload="window.print()"
>

    <!-- =========================================
         TOMBOL KEMBALI
    ========================================== -->

<div class="max-w-4xl mx-auto mt-4 sm:mt-8 mb-4 px-3 sm:px-0 no-print">

    <div class="flex flex-col sm:flex-row sm:items-center gap-3">

        <!-- Tombol Kembali -->
        <a
            href="/toko/riwayat"
            class="inline-flex items-center justify-center gap-2 bg-gray-800 text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow hover:bg-gray-700 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Kembali ke Riwayat
        </a>


        <!-- Tombol Cetak -->
        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow hover:bg-green-700 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-10 0h8v4H8v-4z"
                />
            </svg>

            Cetak Surat Jalan
        </button>


        <!-- Informasi -->
        <span class="text-xs sm:text-sm text-gray-500 sm:ml-1">
            Klik tombol cetak untuk mencetak atau menyimpan sebagai PDF.
        </span>

    </div>

</div>


    <!-- =========================================
         KERTAS A4
    ========================================== -->

    <div
        class="a4-paper max-w-4xl mx-auto bg-white p-5 sm:p-8 md:p-12 shadow-lg border border-gray-200 min-h-[29.7cm]"
    >

        <!-- =====================================
             KOP SURAT
        ====================================== -->

        <div
            class="print-header flex flex-col sm:flex-row justify-between items-start gap-6 border-b-4 border-gray-800 pb-5 sm:pb-6 mb-6 sm:mb-8"
        >

            <!-- Informasi Perusahaan -->

            <div class="w-full sm:w-auto">

                <h1
                    class="text-2xl sm:text-3xl font-black text-gray-800 tracking-wider leading-tight"
                >
                    SURAT JALAN RETUR
                </h1>

                <p class="text-sm sm:text-base font-bold text-gray-600 mt-1">
                    PT. Sage Mashlahat Indonesia
                </p>

                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Sistem Manajemen Pengembalian Benih Terpadu
                </p>

            </div>


            <!-- Kode Pengiriman -->

            <div class="header-code w-full sm:w-auto text-left sm:text-right">

                <p
                    class="text-xs sm:text-sm font-bold text-gray-500 uppercase tracking-widest"
                >
                    KODE PENGIRIMAN
                </p>

                <p
                    class="text-xl sm:text-2xl font-black text-gray-800 break-all"
                >
                    {{ $retur->return_code ?? '#RET-'.$retur->id }}
                </p>

                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($retur->return_code ?? '#RET-'.$retur->id) }}"
                    alt="QR Code"
                    class="w-16 h-16 mt-2 ml-0 sm:ml-auto"
                >

            </div>

        </div>


        <!-- =====================================
             INFO PENGIRIM & PENERIMA
        ====================================== -->

        <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-6 sm:mb-8 text-sm"
        >

            <!-- Pengirim -->

            <div
                class="p-4 bg-gray-50 border border-gray-200 rounded-lg"
            >

                <p
                    class="text-xs font-bold text-gray-500 uppercase mb-2"
                >
                    Dikirim Oleh (Mitra Toko):
                </p>

                <p
                    class="font-bold text-base sm:text-lg text-gray-800 break-words"
                >
                    {{ $retur->store_name }}
                </p>

                <p class="text-gray-600 mt-1 text-xs sm:text-sm">
                    Tanggal Pengajuan:
                    {{ \Carbon\Carbon::parse($retur->created_at)->translatedFormat('d F Y') }}
                </p>

            </div>


            <!-- Penerima -->

            <div
                class="p-4 bg-gray-50 border border-gray-200 rounded-lg"
            >

                <p
                    class="text-xs font-bold text-gray-500 uppercase mb-2"
                >
                    Tujuan Pengembalian:
                </p>

                <p
                    class="font-bold text-base sm:text-lg text-gray-800"
                >
                    Gudang Pusat PT. Sage
                </p>

                <p class="text-gray-600 mt-1 text-xs sm:text-sm">
                    Status Sistem:

                    <span class="uppercase font-bold">
                        {{ $retur->status }}
                    </span>
                </p>

            </div>

        </div>


        <!-- =====================================
             RINCIAN BARANG
        ====================================== -->

        <div class="mb-8 sm:mb-12">

            <h3
                class="font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 uppercase tracking-wider text-sm sm:text-base"
            >
                Rincian Produk Retur
            </h3>


            <!-- Wrapper Scroll Mobile -->

            <div class="product-table-wrapper rounded-lg">

                <table
                    class="product-table w-full text-left border-collapse"
                >

                    <thead>

                        <tr class="bg-gray-100 text-gray-700 text-xs sm:text-sm">

                            <th
                                class="p-2 sm:p-3 border border-gray-300 w-12 text-center"
                            >
                                No
                            </th>

                            <th
                                class="p-2 sm:p-3 border border-gray-300"
                            >
                                Barcode
                            </th>

                            <th
                                class="p-2 sm:p-3 border border-gray-300 w-32 text-center"
                            >
                                Jumlah (Pack)
                            </th>

                            <th
                                class="p-2 sm:p-3 border border-gray-300 w-1/3"
                            >
                                Alasan Retur
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr class="text-gray-800 text-sm">

                            <td
                                class="p-2 sm:p-3 border border-gray-300 text-center font-bold"
                            >
                                1
                            </td>

                            <td
                                class="p-2 sm:p-3 border border-gray-300 font-semibold text-center"
                            >
                                {{ $retur->barcode }}
                            </td>

                            <td
                                class="p-2 sm:p-3 border border-gray-300 text-center font-bold text-base sm:text-lg"
                            >
                                {{ $retur->quantity }}
                            </td>

                            <td
                                class="p-2 sm:p-3 border border-gray-300 text-xs sm:text-sm break-words"
                            >
                                {{ $retur->reason }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- Petunjuk scroll hanya di mobile -->

            <p class="text-[10px] text-gray-400 mt-2 sm:hidden">
                ← Geser tabel ke samping untuk melihat seluruh data →
            </p>

        </div>


        <!-- =====================================
             AREA TANDA TANGAN
        ====================================== -->

        <div
            class="signature-section grid grid-cols-1 sm:grid-cols-3 gap-10 sm:gap-4 text-center mt-10 sm:mt-20 pt-6 sm:pt-8"
        >

            <!-- Toko -->

            <div>

                <p class="text-sm text-gray-600 mb-16 sm:mb-20">
                    Pihak Toko (Pengirim)
                </p>

                <p
                    class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4 max-w-full break-words"
                >
                    {{ $retur->store_name }}
                </p>

            </div>


            <!-- Kurir -->

            <div>

                <p class="text-sm text-gray-600 mb-16 sm:mb-20">
                    Kurir / Ekspedisi
                </p>

                <p
                    class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4"
                >
                    ( Nama Terang & TTD )
                </p>

            </div>


            <!-- Gudang -->

            <div>

                <p class="text-sm text-gray-600 mb-16 sm:mb-20">
                    Penerima (Gudang)
                </p>

                <p
                    class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4"
                >
                    ( Nama Terang & TTD )
                </p>

            </div>

        </div>


        <!-- =====================================
             FOOTER
        ====================================== -->

        <div
            class="mt-12 sm:mt-16 pt-4 border-t border-gray-200 text-center"
        >

            <p class="text-[10px] sm:text-xs text-gray-400 leading-relaxed">
                Dokumen ini dicetak otomatis dari Sistem Jual Benih App.
                Harap sertakan dokumen ini bersama fisik barang yang diretur.
            </p>

        </div>

    </div>

</body>
</html>