<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Retur - {{ $retur->return_code ?? '#RET-'.$retur->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pengaturan khusus saat dicetak atau disave ke PDF */
        @media print {
            @page { margin: 0; size: A4; }
            body { margin: 1.5cm; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans" onload="window.print()">

    <!-- Tombol Kembali (Disembunyikan saat dicetak) -->
    <div class="max-w-4xl mx-auto mt-8 mb-4 no-print">
        <a href="/toko/riwayat" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-gray-700 transition">
            &larr; Kembali ke Riwayat
        </a>
        <span class="text-sm text-gray-500 ml-4">Jendela cetak/PDF akan terbuka otomatis. Jika tidak, tekan Ctrl+P.</span>
    </div>

    <!-- Kertas A4 -->
    <div class="max-w-4xl mx-auto bg-white p-12 shadow-lg border border-gray-200 min-h-[29.7cm]">
        
        <!-- KOP SURAT -->
        <div class="flex justify-between items-start border-b-4 border-gray-800 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-wider">SURAT JALAN RETUR</h1>
                <p class="text-sm font-bold text-gray-600 mt-1">PT. Sage Mashlahat Indonesia</p>
                <p class="text-xs text-gray-500 mt-1">Sistem Manajemen Pengembalian Benih Terpadu</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">KODE PENGIRIMAN</p>
                <p class="text-2xl font-black text-gray-800">{{ $retur->return_code ?? '#RET-'.$retur->id }}</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $retur->return_code ?? '#RET-'.$retur->id }}" alt="QR Code" class="ml-auto mt-2 w-16 h-16">
            </div>
        </div>

        <!-- INFO PENGIRIM & PENERIMA -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Dikirim Oleh (Mitra Toko):</p>
                <p class="font-bold text-lg text-gray-800">{{ $retur->store_name }}</p>
                <p class="text-gray-600 mt-1">Tanggal Pengajuan: {{ \Carbon\Carbon::parse($retur->created_at)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Tujuan Pengembalian:</p>
                <p class="font-bold text-lg text-gray-800">Gudang Pusat PT. Sage</p>
                <p class="text-gray-600 mt-1">Status Sistem: <span class="uppercase font-bold">{{ $retur->status }}</span></p>
            </div>
        </div>

        <!-- RINCIAN BARANG -->
        <div class="mb-12">
            <h3 class="font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 uppercase tracking-wider">Rincian Produk Retur</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border border-gray-300 w-12 text-center">No</th>
                        <th class="p-3 border border-gray-300">Deskripsi Produk Benih</th>
                        <th class="p-3 border border-gray-300 w-32 text-center">Jumlah (Pack)</th>
                        <th class="p-3 border border-gray-300 w-1/3">Alasan Retur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-gray-800">
                        <td class="p-3 border border-gray-300 text-center font-bold">1</td>
                        <td class="p-3 border border-gray-300 font-semibold">{{ $retur->product_name }}</td>
                        <td class="p-3 border border-gray-300 text-center font-bold text-lg">{{ $retur->quantity }}</td>
                        <td class="p-3 border border-gray-300 text-sm">{{ $retur->reason }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- AREA TANDA TANGAN -->
        <div class="grid grid-cols-3 gap-4 text-center mt-20 pt-8">
            <div>
                <p class="text-sm text-gray-600 mb-20">Pihak Toko (Pengirim)</p>
                <p class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4">{{ $retur->store_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-20">Kurir / Ekspedisi</p>
                <p class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4">( Nama Terang & TTD )</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-20">Penerima (Gudang)</p>
                <p class="font-bold text-gray-800 underline border-b border-gray-800 inline-block px-4">( Nama Terang & TTD )</p>
            </div>
        </div>

        <div class="mt-16 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-400">Dokumen ini dicetak otomatis dari Sistem Jual Benih App. Harap sertakan dokumen ini bersama fisik barang yang diretur.</p>
        </div>
    </div>

</body>
</html>