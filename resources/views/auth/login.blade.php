<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT.Sage Maslahat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { green: { 50: '#f0fdf4', 500: '#22c55e', 600: '#16a34a' } } } }
        }
    </script>
    <style>
        @keyframes scan {
            0% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }
        .scanner-line {
            position: absolute;
            left: 10%; right: 10%; height: 2px;
            background-color: #22c55e;
            box-shadow: 0 0 8px 2px rgba(34, 197, 94, 0.5);
            animation: scan 2.5s infinite linear;
            z-index: 20;
        }
        .scanner-corner {
            position: absolute; width: 30px; height: 30px; border-color: #22c55e; border-width: 3px; z-index: 20;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Penyesuaian khusus untuk membungkus video kamera agar rapi di Tailwind */
        #reader { width: 100%; height: 100%; border: none !important; }
        #reader video { object-fit: cover; border-radius: 1rem; width: 100% !important; height: 100% !important; }
        #reader__dashboard_section_csr span { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl flex flex-col md:flex-row overflow-hidden max-h-[95vh]">
        
        <div class="hidden md:flex w-1/2 bg-green-600 p-12 flex-col justify-center relative overflow-hidden text-white min-h-[600px]">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-10">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 10-1.414 1.414L12 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path></svg>
                    <span class="text-2xl font-bold tracking-tight">PT.Sage Mashlahat Indonesia </span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">Efisiensi Distribusi<br>di Ujung Jari.</h1>
                <p class="text-green-100 text-lg">Platform manajemen alokasi dan retur benih terpadu untuk efisiensi operasional.</p>
            </div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-500 rounded-full opacity-50 blur-3xl"></div>
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-green-400 rounded-full opacity-30 blur-2xl"></div>
        </div>

        <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col items-center relative bg-white overflow-y-auto no-scrollbar">
            
            <div class="md:hidden flex items-center gap-2 text-green-600 font-bold text-2xl mb-6 mt-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 10-1.414 1.414L12 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path></svg>
                Jual Benih App
            </div>

            <div class="w-full max-w-sm my-auto flex flex-col items-center pb-8">
                
                <div class="flex bg-gray-100 p-1 rounded-xl mb-6 w-full">
                    <button onclick="switchTab('toko')" id="btnToko" class="flex-1 py-2 text-sm font-bold bg-white text-gray-800 shadow-sm rounded-lg transition">Toko (QR)</button>
                    <button onclick="switchTab('manajemen')" id="btnManajemen" class="flex-1 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 rounded-lg transition">Manajemen</button>
                </div>

                <div id="tabToko" class="w-full flex flex-col items-center animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang di Toko</h2>
                    <p class="text-sm text-gray-500 text-center mb-6">Arahkan kamera ke QR Code yang tersedia di dinding toko untuk mulai mengelola retur.</p>

                    <div class="relative w-56 h-56 bg-gray-900 rounded-2xl shadow-inner mb-6 flex-shrink-0 flex items-center justify-center">
                        <div id="reader" class="absolute inset-0 rounded-2xl overflow-hidden"></div>
                        
                        <div class="scanner-corner border-t-0 border-r-0 top-4 left-4 rounded-tl-lg hidden" id="scanAnim1"></div>
                        <div class="scanner-corner border-t-0 border-l-0 top-4 right-4 rounded-tr-lg hidden" id="scanAnim2"></div>
                        <div class="scanner-corner border-b-0 border-r-0 bottom-4 left-4 rounded-bl-lg hidden" id="scanAnim3"></div>
                        <div class="scanner-corner border-b-0 border-l-0 bottom-4 right-4 rounded-br-lg hidden" id="scanAnim4"></div>
                        <div class="scanner-line hidden" id="scanAnim5"></div>

                        <span id="cameraPlaceholder" class="text-xs text-gray-400 z-10 relative text-center px-4">Kamera belum aktif.<br>Tekan tombol di bawah.</span>
                    </div>

                    <div class="w-full space-y-4">
                        <button type="button" id="btnStartScan" class="w-full bg-green-500 text-white font-bold py-3 rounded-xl hover:bg-green-600 transition shadow-md flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Aktifkan Kamera Scan
                        </button>
                        
                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink-0 mx-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Atau</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <button class="w-full bg-white border border-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-50 transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Masukkan Kode Manual
                        </button>
                    </div>
                </div>

                <div id="tabManajemen" class="w-full flex flex-col animate-fade-in hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Manajemen</h2>
                    <p class="text-sm text-gray-500 mb-8">Masuk ke sistem menggunakan email korporat Anda.</p>

                    <form action="{{ url('/login') }}" method="POST" class="space-y-4">
                        @csrf
                        @error('email')
                            <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg border border-red-200">
                                {{ $message }}
                            </div>
                        @enderror
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="admin@jualbenih.co.id">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                                <a href="javascript:void(0)" onclick="alert('Silakan hubungi administrator IT PT Sage Mashlahat untuk mereset kata sandi Anda.')" class="text-xs font-semibold text-green-600 hover:underline">Lupa sandi?</a>
                            </div>
                            <input type="password" name="password" required class="w-full border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="••••••••">
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md mt-6">
                            Masuk ke Dashboard
                        </button>
                    </form>
                </div>
                
            </div>

            <div class="mt-auto pt-4 pb-2 text-center w-full">
                <p class="text-xs text-gray-400 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Butuh bantuan akses?
                </p>
            </div>

        </div>
    </div>

    <script>
        // --- LOGIKA SCANNER QR CODE ---
        let html5QrCode;
        let isScanning = false;

        function startScanner() {
            if (isScanning) return; // Mencegah double klik
            
            document.getElementById('cameraPlaceholder').classList.add('hidden');
            
            // Tampilkan animasi scan
            for(let i=1; i<=5; i++) {
                document.getElementById('scanAnim'+i).classList.remove('hidden');
            }

            html5QrCode = new Html5Qrcode("reader");
            
            // Konfigurasi kamera: gunakan kamera belakang jika di HP
            const config = { fps: 10, qrbox: { width: 200, height: 200 } };

            html5QrCode.start({ facingMode: "environment" }, config, 
                // Jika QR Code berhasil dibaca
                (decodedText, decodedResult) => {
                    html5QrCode.stop().then(() => {
                        isScanning = false;
                        // Ubah tombol jadi status loading
                        const btn = document.getElementById('btnStartScan');
                        btn.innerHTML = 'Memproses Login...';
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                        
                        // Langsung arahkan browser ke URL yang didapat dari QR Code
                        window.location.href = decodedText;
                    });
                },
                // Jika masih mencari QR (berjalan terus setiap frame)
                (errorMessage) => {
                    // Abaikan error saat proses scan berlangsung (wajar jika blur/tidak ada QR)
                }
            ).then(() => {
                isScanning = true;
                const btn = document.getElementById('btnStartScan');
                btn.innerHTML = 'Kamera Aktif. Arahkan ke QR!';
                btn.classList.replace('bg-green-500', 'bg-gray-800');
                btn.classList.replace('hover:bg-green-600', 'hover:bg-gray-900');
            }).catch((err) => {
                alert("Tidak dapat mengakses kamera. Pastikan browser Anda memiliki izin untuk menggunakan kamera.");
                document.getElementById('cameraPlaceholder').classList.remove('hidden');
            });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    for(let i=1; i<=5; i++) {
                        document.getElementById('scanAnim'+i).classList.add('hidden');
                    }
                    document.getElementById('cameraPlaceholder').classList.remove('hidden');
                    const btn = document.getElementById('btnStartScan');
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Aktifkan Kamera Scan';
                    btn.classList.replace('bg-gray-800', 'bg-green-500');
                    btn.classList.replace('hover:bg-gray-900', 'hover:bg-green-600');
                });
            }
        }

        // Jalankan fungsi saat tombol ditekan
        document.getElementById('btnStartScan').addEventListener('click', startScanner);

        // --- LOGIKA PERGANTIAN TAB ---
        function switchTab(tab) {
            const btnToko = document.getElementById('btnToko');
            const btnManajemen = document.getElementById('btnManajemen');
            const tabToko = document.getElementById('tabToko');
            const tabManajemen = document.getElementById('tabManajemen');

            if (tab === 'toko') {
                btnToko.classList.replace('text-gray-500', 'text-gray-800');
                btnToko.classList.replace('hover:text-gray-700', 'bg-white');
                btnToko.classList.add('shadow-sm');
                
                btnManajemen.classList.replace('text-gray-800', 'text-gray-500');
                btnManajemen.classList.replace('bg-white', 'hover:text-gray-700');
                btnManajemen.classList.remove('shadow-sm');

                tabToko.classList.remove('hidden');
                tabManajemen.classList.add('hidden');
            } else {
                // Matikan kamera jika user berpindah ke tab Manajemen
                stopScanner();

                btnManajemen.classList.replace('text-gray-500', 'text-gray-800');
                btnManajemen.classList.replace('hover:text-gray-700', 'bg-white');
                btnManajemen.classList.add('shadow-sm');
                
                btnToko.classList.replace('text-gray-800', 'text-gray-500');
                btnToko.classList.replace('bg-white', 'hover:text-gray-700');
                btnToko.classList.remove('shadow-sm');

                tabManajemen.classList.remove('hidden');
                tabToko.classList.add('hidden');
            }
        }
    </script>
</body>
</html>