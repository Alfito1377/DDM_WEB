<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR - {{ $toko->store_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        @media print {
            @page { 
                margin: 0; 
                size: portrait;
            }
            body {
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                background-color: white !important;
            }
            .no-print { 
                display: none !important; 
            }
            .print-container {
                box-shadow: none !important;
                transform: scale(1.05); /* Sedikit diperbesar saat dicetak */
            }
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-6 font-sans">

    <button onclick="window.print()" class="no-print fixed top-6 right-6 bg-black text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-green-800 transition-all flex items-center gap-2 z-50 border-2 border-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak QR
    </button>

    <div class="print-container w-full max-w-[380px] bg-[#163c28] rounded-2xl shadow-2xl relative overflow-hidden flex flex-col border border-gray-300">
        
        <div class="bg-white px-6 py-4 flex items-center gap-4 z-10">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo PT Sage" class="w-12 h-12 object-contain">
            <div class="border-l-2 border-green-600 pl-3">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em] leading-none mb-1">DDM </p>
                <h2 class="text-[13px] font-black text-gray-900 leading-tight tracking-tight">
                    PT SAGE MASHLAHAT<br>INDONESIA
                </h2>
            </div>
        </div>

        <div class="h-1.5 w-full bg-gradient-to-r from-[#9DC08B] via-green-500 to-[#9DC08B]"></div>

        <div class="px-8 pt-8 pb-6 text-center flex flex-col items-center relative">
            
            <div class="bg-yellow-400 text-yellow-900 text-[11px] font-black px-4 py-1.5 rounded-full tracking-widest uppercase mb-3 shadow-md border border-yellow-300">
                {{ $title }}
            </div>

            <h1 class="text-2xl font-black text-white mb-8 uppercase tracking-wide leading-snug line-clamp-2">
                {{ $toko->store_name }}
            </h1>

            <div class="relative w-full max-w-[220px] aspect-square mx-auto mb-2">
                <div class="absolute -top-3 -left-3 w-8 h-8 border-t-[5px] border-l-[5px] border-yellow-400 rounded-tl-sm"></div>
                <div class="absolute -top-3 -right-3 w-8 h-8 border-t-[5px] border-r-[5px] border-yellow-400 rounded-tr-sm"></div>
                <div class="absolute -bottom-3 -left-3 w-8 h-8 border-b-[5px] border-l-[5px] border-yellow-400 rounded-bl-sm"></div>
                <div class="absolute -bottom-3 -right-3 w-8 h-8 border-b-[5px] border-r-[5px] border-yellow-400 rounded-br-sm"></div>
                
                <div class="w-full h-full bg-white p-3 rounded-xl shadow-inner relative z-10 flex items-center justify-center">
                    <img src="{{ $qrImage }}" alt="{{ $title }}" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        <!-- FOOTER KARTU: Instruksi -->
        <div class="bg-[#0f291b] p-5 mt-auto border-t border-white/10">
            <div class="flex items-start gap-3 text-left">
                <svg class="w-6 h-6 text-yellow-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-gray-300 text-[11px] leading-relaxed">
                    @if($title == 'QR Code Login')
                        Scan QR Code ini menggunakan aplikasi perangkat toko untuk <b>masuk (login) otomatis</b> ke dalam sistem.
                    @else
                        Wajib di-scan oleh kurir/sopir saat tiba di lokasi untuk <b>konfirmasi titik checkpoint</b> pengiriman.
                    @endif
                </p>
            </div>
        </div>
        
    </div>

</body>
</html>