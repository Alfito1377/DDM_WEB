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
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-6 font-sans">

    <!-- LOGIKA WARNA TEMA KALEM (ELEGAN & PROFESIONAL) -->
    @php
        if(isset($theme) && $theme == 'amber') {
            // Tema Checkpoint (Deep Warm Slate / Coklat Keabuan Gelap)
            $mainBg = 'bg-[#362D28]'; 
            $headerBorder = 'border-[#D97706]';
            $gradLine = 'from-[#D4A373] via-[#D97706] to-[#D4A373]';
            $footerBg = 'bg-[#261F1C]';
        } else {
            // Tema Login Toko (Deep Muted Green / Hijau Hutan Kalem)
            $mainBg = 'bg-[#1E3027]';
            $headerBorder = 'border-[#5C836B]';
            $gradLine = 'from-[#86A793] via-[#5C836B] to-[#86A793]';
            $footerBg = 'bg-[#14221B]';
        }

        // Warna aksen kuning kalem (Gold) yang dipakai bersamaan untuk kedua QR
        $accentGold = 'border-[#E5C07B]';
        $badgeGold = 'bg-[#E5C07B] text-[#3E321E] border-[#D4A373]';
    @endphp

    <button onclick="window.print()" class="no-print fixed top-6 right-6 bg-white text-gray-800 px-5 py-2.5 rounded-xl font-bold shadow-md hover:bg-gray-50 border border-gray-200 transition-all flex items-center gap-2 z-50">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak QR
    </button>

    <div class="print-container w-full max-w-[380px] {{ $mainBg }} rounded-3xl shadow-2xl relative overflow-hidden flex flex-col border border-gray-400/20">
        
        <!-- HEADER KARTU -->
        <div class="bg-white px-6 py-4 flex items-center gap-4 z-10">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo PT Sage" class="w-12 h-12 object-contain">
            <div class="border-l-2 {{ $headerBorder }} pl-3 py-0.5">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] leading-none mb-1">DDM</p>
                <h2 class="text-[13px] font-black text-gray-800 leading-tight tracking-tight">
                    PT SAGE MASHLAHAT<br>INDONESIA
                </h2>
            </div>
        </div>

        <!-- GARIS TRANSISI KALEM -->
        <div class="h-1.5 w-full bg-gradient-to-r {{ $gradLine }}"></div>

        <!-- AREA QR & NAMA TOKO -->
        <div class="px-8 pt-8 pb-7 text-center flex flex-col items-center relative">
            
            <div class="{{ $badgeGold }} text-[10px] font-black px-4 py-1.5 rounded-full tracking-widest uppercase mb-4 shadow-sm border">
                {{ $title }}
            </div>

            <h1 class="text-2xl font-black text-white mb-8 uppercase tracking-wider leading-snug line-clamp-2">
                {{ $toko->store_name }}
            </h1>

            <div class="relative w-full max-w-[210px] aspect-square mx-auto mb-1">
                <!-- SIKU WARNA KUNING EMAS (GOLD) -->
                <div class="absolute -top-2.5 -left-2.5 w-7 h-7 border-t-[4px] border-l-[4px] {{ $accentGold }} rounded-tl-sm"></div>
                <div class="absolute -top-2.5 -right-2.5 w-7 h-7 border-t-[4px] border-r-[4px] {{ $accentGold }} rounded-tr-sm"></div>
                <div class="absolute -bottom-2.5 -left-2.5 w-7 h-7 border-b-[4px] border-l-[4px] {{ $accentGold }} rounded-bl-sm"></div>
                <div class="absolute -bottom-2.5 -right-2.5 w-7 h-7 border-b-[4px] border-r-[4px] {{ $accentGold }} rounded-br-sm"></div>
                
                <div class="w-full h-full bg-white p-3 rounded-2xl shadow-inner relative z-10 flex items-center justify-center">
                    <!-- Titik QR hitam pekat dari Controller -->
                    <img src="{{ $qrImage }}" alt="{{ $title }}" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        <!-- FOOTER KARTU: Panduan Penggunaan -->
        <div class="{{ $footerBg }} p-6 mt-auto border-t border-white/5">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-[#E5C07B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[#E5C07B] text-[11px] font-bold uppercase tracking-widest">Panduan Singkat</span>
            </div>
            
            <div class="text-[10.5px] text-gray-300 leading-relaxed text-left space-y-2">
                @if(isset($theme) && $theme == 'amber')
                    <!-- PANDUAN CHECKPOINT -->
                    <div class="flex items-start gap-2">
                        <span class="text-[#E5C07B] mt-0.5">●</span>
                        <p><b>Fungsi:</b> Memvalidasi kedatangan kurir dan serah terima barang.</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-[#E5C07B] mt-0.5">●</span>
                        <p><b>Cara:</b> Buka kamera HP, lalu arahkan kamera ke kode QR ini</p>
                    </div>
                    <!-- Kotak Peringatan Checkpoint -->
                    <div class="flex items-start gap-2 mt-3 bg-[#4A2525] p-2.5 rounded-lg border border-[#7A3E3E]">
                        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-red-200 font-bold leading-snug">Peringatan: Sistem menolak scan di luar radius 100 meter dari lokasi.</p>
                    </div>
                @else
                    <!-- PANDUAN LOGIN TOKO -->
                    <div class="flex items-start gap-2">
                        <span class="text-[#E5C07B] mt-0.5">●</span>
                        <p><b>Fungsi:</b> Kunci masuk instan ke dalam Panel Sistem Toko.</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-[#E5C07B] mt-0.5">●</span>
                        <p><b>Cara:</b> Arahkan kamera HP Anda ke kode ini untuk mengakses.</p>
                    </div>
                    <!-- Kotak Peringatan Login -->
                    <div class="flex items-start gap-2 mt-3 bg-[#4A3F25] p-2.5 rounded-lg border border-[#7A6A3E]">
                        <svg class="w-4 h-4 text-yellow-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-yellow-200 font-bold leading-snug">Rahasia! Jangan difoto atau diberikan kepada orang luar.</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>

</body>
</html>