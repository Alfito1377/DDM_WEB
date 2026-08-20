<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - PT. Sage Maslahat Indonesia</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- HTML5 QR Code -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        /* Scanner animation */
        @keyframes scan {
            0% {
                top: 10%;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                top: 90%;
                opacity: 0;
            }
        }

        .scanner-line {
            position: absolute;
            left: 10%;
            right: 10%;
            height: 2px;
            background-color: #22c55e;
            box-shadow: 0 0 8px 2px rgba(34, 197, 94, 0.5);
            animation: scan 2.5s infinite linear;
            z-index: 20;
        }

        .scanner-corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border-color: #22c55e;
            border-width: 3px;
            z-index: 20;
        }

        /* QR Reader */
        #reader {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            overflow: hidden;
        }

        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 1rem;
        }

        #reader__scan_region {
            width: 100% !important;
            height: 100% !important;
        }

        #reader__dashboard {
            display: none !important;
        }

        /* Hide scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Fade animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans min-h-screen">

    <!-- Main Container -->
    <main class="min-h-screen flex items-center justify-center p-3 sm:p-5 md:p-8">

        <div
            class="bg-white w-full max-w-6xl rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px] md:min-h-[680px]">

            <!-- ===================================================== -->
            <!-- LEFT SIDE - DESKTOP -->
            <!-- ===================================================== -->

            <section
                class="hidden md:flex md:w-1/2 lg:w-[52%] bg-sage-600 p-8 lg:p-12 flex-col justify-between relative overflow-hidden text-white">

                <!-- Background Decoration -->
                <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-sage-500 rounded-full opacity-50 blur-3xl">
                </div>

                <div class="absolute -top-20 -right-20 w-72 h-72 bg-sage-400 rounded-full opacity-30 blur-3xl">
                </div>

                <div class="absolute bottom-0 right-0 w-64 h-64 bg-white rounded-full opacity-5 blur-3xl">
                </div>

                <!-- Content -->
                <div class="relative z-10">

                    <!-- Logo + Company -->
                    <div class="flex items-center gap-3 mb-12">

                        <!-- LOGO PT SAGE -->
                        <div
                            class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden flex-shrink-0">

                            <img
                                src="{{ asset('assets/images/Logo.png') }}"
                                alt="Logo PT. Sage Maslahat Indonesia"
                                class="w-full h-full object-contain p-1.5"
                                onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-sage-600 font-bold text-xl\'>S</span>';"
                            >

                        </div>

                        <div class="flex flex-col">
                            <span class="text-xl lg:text-2xl font-bold tracking-tight">
                                PT. Sage Maslahat Indonesia
                            </span>
                        </div>
                    </div>

                    <!-- Hero Text -->
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight mb-5">
                        Efisiensi Distribusi
                        <br>
                        di Ujung Jari.
                    </h1>

                    <p class="text-green-100 text-base lg:text-lg leading-relaxed max-w-lg">
                        Platform manajemen alokasi dan retur benih terpadu
                        untuk meningkatkan efisiensi operasional.
                    </p>

                </div>

                <!-- Bottom Info -->
                <div class="relative z-10">

                    <div class="flex items-center gap-2 text-green-100 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z">
                            </path>
                        </svg>

                        Sistem Terintegrasi PT. Sage Maslahat
                    </div>

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- RIGHT SIDE -->
            <!-- ===================================================== -->

            <section
                class="w-full md:w-1/2 lg:w-[48%] bg-white flex flex-col relative overflow-y-auto no-scrollbar">

                <!-- ================================================= -->
                <!-- MOBILE HEADER -->
                <!-- ================================================= -->

                <div class="md:hidden px-5 pt-6 pb-3">

                    <div class="flex items-center gap-3">

                        <!-- LOGO -->
                        <div
                            class="w-11 h-11 bg-green-50 border border-green-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">

                            <img
                                src="{{ asset('assets/images/Logo.png') }}"
                                alt="Logo PT. Sage Maslahat Indonesia"
                                class="w-full h-full object-contain p-1"
                                onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-green-600 font-bold text-lg\'>S</span>';"
                            >

                        </div>

                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-gray-800">
                                PT. Sage Maslahat Indonesia
                            </span>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- FORM CONTENT -->
                <!-- ================================================= -->

                <div class="flex-1 flex flex-col justify-center px-5 py-6 sm:px-8 md:px-10 lg:px-12">

                    <div class="w-full max-w-md mx-auto">

                        <!-- TAB SWITCHER -->

                        <div class="flex bg-gray-100 p-1 rounded-xl mb-7 w-full">

                            <button
                                type="button"
                                onclick="switchTab('toko')"
                                id="btnToko"
                                class="flex-1 py-2.5 px-3 text-sm font-bold bg-white text-gray-800 shadow-sm rounded-lg transition-all duration-200">

                                Toko (QR)

                            </button>

                            <button
                                type="button"
                                onclick="switchTab('manajemen')"
                                id="btnManajemen"
                                class="flex-1 py-2.5 px-3 text-sm font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all duration-200">

                                Manajemen

                            </button>

                        </div>


                        <!-- ================================================= -->
                        <!-- TAB TOKO -->
                        <!-- ================================================= -->

                        <div id="tabToko" class="w-full flex flex-col items-center animate-fade-in">

                            <div class="text-center mb-6">

                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                                    Selamat Datang di Toko
                                </h2>

                                <p class="text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
                                    Arahkan kamera ke QR Code toko yang telah dibagikan untuk masuk ke sistem.
                                </p>

                            </div>


                            <!-- QR SCANNER -->

                            <div
                                class="relative w-full max-w-[280px] aspect-square bg-gray-900 rounded-2xl shadow-inner mb-6 overflow-hidden flex items-center justify-center">

                                <!-- Camera -->
                                <div
                                    id="reader"
                                    class="absolute inset-0 rounded-2xl overflow-hidden">
                                </div>


                                <!-- Scanner Corners -->

                                <div
                                    id="scanAnim1"
                                    class="scanner-corner border-t-0 border-r-0 top-4 left-4 rounded-tl-lg hidden">
                                </div>

                                <div
                                    id="scanAnim2"
                                    class="scanner-corner border-t-0 border-l-0 top-4 right-4 rounded-tr-lg hidden">
                                </div>

                                <div
                                    id="scanAnim3"
                                    class="scanner-corner border-b-0 border-r-0 bottom-4 left-4 rounded-bl-lg hidden">
                                </div>

                                <div
                                    id="scanAnim4"
                                    class="scanner-corner border-b-0 border-l-0 bottom-4 right-4 rounded-br-lg hidden">
                                </div>


                                <!-- Scanner Line -->

                                <div
                                    id="scanAnim5"
                                    class="scanner-line hidden">
                                </div>


                                <!-- Placeholder -->

                                <div
                                    id="cameraPlaceholder"
                                    class="relative z-10 text-xs sm:text-sm text-gray-400 text-center px-6">

                                    <svg
                                        class="w-10 h-10 mx-auto mb-3 text-gray-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>

                                    </svg>

                                    Kamera belum aktif.
                                    <br>
                                    Tekan tombol di bawah.

                                </div>

                            </div>


                            <!-- BUTTONS -->

                            <div class="w-full space-y-4">

                                <!-- Start Camera -->

                                <button
                                    type="button"
                                    id="btnStartScan"
                                    class="w-full bg-green-500 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-green-600 active:scale-[0.98] transition-all shadow-md flex justify-center items-center gap-2">

                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>

                                    </svg>

                                    Aktifkan Kamera Scan

                                </button>

                            </div>

                        </div>


                        <!-- ================================================= -->
                        <!-- TAB MANAJEMEN -->
                        <!-- ================================================= -->

                        <div
                            id="tabManajemen"
                            class="w-full flex flex-col animate-fade-in hidden">

                            <div class="mb-7">

                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                                    Login Manajemen
                                </h2>

                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Masuk ke sistem menggunakan email yang terdaftar.
                                </p>

                            </div>


                            <!-- LOGIN FORM -->

                            <form
                                action="{{ url('/login') }}"
                                method="POST"
                                class="space-y-5">

                                @csrf


                                <!-- Error -->

                                @error('email')

                                <div
                                    class="bg-red-50 text-red-600 text-xs p-3 rounded-lg border border-red-200">
                                    {{ $message }}
                                </div>

                                @enderror


                                <!-- Email -->

                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-semibold text-gray-700 mb-2">

                                        Alamat Email

                                    </label>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        class="w-full border border-gray-300 px-4 py-3.5 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                        placeholder="admin@jualbenih.co.id">

                                </div>


                                <!-- Password -->

                                <div>

                                    <div class="flex justify-between items-center mb-2">

                                        <label
                                            for="password"
                                            class="block text-sm font-semibold text-gray-700">

                                            Kata Sandi

                                        </label>

                                        <button
                                            type="button"
                                            onclick="forgotPassword()"
                                            class="text-xs font-semibold text-green-600 hover:text-green-700 hover:underline">

                                            Lupa sandi?

                                        </button>

                                    </div>

                                    <div class="relative">

                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                            class="w-full border border-gray-300 px-4 py-3.5 pr-12 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                            placeholder="••••••••">

                                        <button
                                            type="button"
                                            onclick="togglePassword()"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">

                                            <svg
                                                id="eyeIcon"
                                                class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                </path>

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>

                                            </svg>

                                        </button>

                                    </div>

                                </div>


                                <!-- Submit -->

                                <button
                                    type="submit"
                                    class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 active:scale-[0.98] transition-all shadow-md mt-3">

                                    Masuk ke Dashboard

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- FOOTER -->
                <!-- ================================================= -->

                <div class="px-5 pb-5 pt-2 text-center">

                    <p class="text-xs text-gray-400 flex items-center justify-center gap-1.5">

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>

                        </svg>

                        Butuh bantuan akses?

                    </p>

                </div>

            </section>

        </div>

    </main>


    <script>

        // ============================================================
        // QR SCANNER
        // ============================================================

        let html5QrCode = null;
        let isScanning = false;


        function startScanner() {

            if (isScanning) return;

            const reader = document.getElementById('reader');
            const placeholder = document.getElementById('cameraPlaceholder');
            const button = document.getElementById('btnStartScan');

            placeholder.classList.add('hidden');

            // Tampilkan animasi
            for (let i = 1; i <= 5; i++) {
                document
                    .getElementById('scanAnim' + i)
                    .classList.remove('hidden');
            }

            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                qrbox: function(viewfinderWidth, viewfinderHeight) {

                    const minSize = Math.min(
                        viewfinderWidth,
                        viewfinderHeight
                    );

                    const size = Math.min(
                        Math.floor(minSize * 0.7),
                        220
                    );

                    return {
                        width: size,
                        height: size
                    };
                }
            };


            // ========================================================
            // SUCCESS
            // ========================================================

            const onSuccess = async (decodedText) => {

                if (!html5QrCode || !isScanning) return;

                isScanning = false;

                try {
                    await html5QrCode.stop();
                } catch (error) {
                    console.warn(error);
                }

                stopScannerUI();

                const button = document.getElementById('btnStartScan');

                button.innerHTML = `
                    <svg class="w-5 h-5 animate-spin"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83">
                        </path>

                    </svg>

                    Memproses Login...
                `;

                button.disabled = true;
                button.classList.add(
                    'opacity-70',
                    'cursor-not-allowed'
                );


                // Redirect ke URL dari QR
                if (
                    decodedText.startsWith('http://') ||
                    decodedText.startsWith('https://')
                ) {

                    window.location.href = decodedText;

                } else {

                    // Jika QR hanya berisi kode toko
                    window.location.href =
                        `/toko/login?code=${encodeURIComponent(decodedText)}`;

                }

            };


            // ========================================================
            // ERROR SCAN
            // ========================================================

            const onError = () => {
                // Abaikan error ketika QR belum ditemukan
            };


            // ========================================================
            // CAMERA STARTED
            // ========================================================

            const onCameraStarted = () => {

                isScanning = true;

                button.innerHTML = `
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>

                    </svg>

                    Kamera Aktif. Arahkan ke QR!
                `;

                button.classList.remove(
                    'bg-green-500',
                    'hover:bg-green-600'
                );

                button.classList.add(
                    'bg-gray-800',
                    'hover:bg-gray-900'
                );
            };


            // ========================================================
            // CAMERA ERROR
            // ========================================================

            const onCameraFailed = (error) => {

                console.error(
                    'Camera error:',
                    error
                );

                stopScannerUI();

                alert(
                    "Gagal membuka kamera.\n\n" +
                    "Pastikan browser memiliki izin kamera " +
                    "dan website menggunakan HTTPS."
                );
            };


            // ========================================================
            // START CAMERA
            // ========================================================

            html5QrCode
                .start(
                    {
                        facingMode: {
                            exact: "environment"
                        }
                    },
                    config,
                    onSuccess,
                    onError
                )
                .then(onCameraStarted)
                .catch(() => {

                    console.warn(
                        "Environment camera gagal. Mencari kamera..."
                    );

                    Html5Qrcode
                        .getCameras()
                        .then(devices => {

                            if (!devices || devices.length === 0) {

                                onCameraFailed(
                                    "Tidak ada kamera."
                                );

                                return;
                            }

                            // Cari kamera belakang
                            let camera =
                                devices.find(device =>
                                    /back|rear|environment/i
                                        .test(device.label)
                                );

                            // Jika tidak ditemukan,
                            // gunakan kamera terakhir
                            if (!camera) {
                                camera =
                                    devices[devices.length - 1];
                            }

                            html5QrCode
                                .start(
                                    camera.id,
                                    config,
                                    onSuccess,
                                    onError
                                )
                                .then(onCameraStarted)
                                .catch(onCameraFailed);

                        })
                        .catch(onCameraFailed);
                });
        }


        // ============================================================
        // STOP SCANNER
        // ============================================================

        function stopScanner() {

            if (!html5QrCode || !isScanning) {
                stopScannerUI();
                return;
            }

            html5QrCode
                .stop()
                .then(() => {
                    stopScannerUI();
                })
                .catch(() => {
                    stopScannerUI();
                });
        }


        // ============================================================
        // RESET SCANNER UI
        // ============================================================

        function stopScannerUI() {

            isScanning = false;

            for (let i = 1; i <= 5; i++) {

                document
                    .getElementById('scanAnim' + i)
                    .classList.add('hidden');
            }

            document
                .getElementById('cameraPlaceholder')
                .classList.remove('hidden');


            const button =
                document.getElementById('btnStartScan');


            button.disabled = false;

            button.classList.remove(
                'opacity-70',
                'cursor-not-allowed',
                'bg-gray-800',
                'hover:bg-gray-900'
            );

            button.classList.add(
                'bg-green-500',
                'hover:bg-green-600'
            );


            button.innerHTML = `
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                    </path>

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                    </path>

                </svg>

                Aktifkan Kamera Scan
            `;
        }


        // ============================================================
        // START BUTTON
        // ============================================================

        document
            .getElementById('btnStartScan')
            .addEventListener(
                'click',
                startScanner
            );


        // ============================================================
        // TAB SWITCH
        // ============================================================

        function switchTab(tab) {

            const btnToko =
                document.getElementById('btnToko');

            const btnManajemen =
                document.getElementById('btnManajemen');

            const tabToko =
                document.getElementById('tabToko');

            const tabManajemen =
                document.getElementById('tabManajemen');


            if (tab === 'toko') {

                stopScanner();


                // Toko active
                btnToko.className =
                    'flex-1 py-2.5 px-3 text-sm font-bold bg-white text-gray-800 shadow-sm rounded-lg transition-all duration-200';

                // Manajemen inactive
                btnManajemen.className =
                    'flex-1 py-2.5 px-3 text-sm font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all duration-200';


                tabToko.classList.remove('hidden');
                tabManajemen.classList.add('hidden');

            } else {

                stopScanner();


                // Manajemen active
                btnManajemen.className =
                    'flex-1 py-2.5 px-3 text-sm font-bold bg-white text-gray-800 shadow-sm rounded-lg transition-all duration-200';

                // Toko inactive
                btnToko.className =
                    'flex-1 py-2.5 px-3 text-sm font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all duration-200';


                tabManajemen.classList.remove('hidden');
                tabToko.classList.add('hidden');
            }
        }


        // ============================================================
        // MANUAL CODE MODAL
        // ============================================================

        function openManualCode() {

            const modal =
                document.getElementById('manualCodeModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {

                document
                    .getElementById('manualCodeInput')
                    .focus();

            }, 100);
        }


        function closeManualCode() {

            const modal =
                document.getElementById('manualCodeModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        function submitManualCode() {

            const input =
                document.getElementById('manualCodeInput');

            const code =
                input.value.trim();


            if (!code) {

                alert(
                    'Silakan masukkan kode toko terlebih dahulu.'
                );

                input.focus();

                return;
            }


            window.location.href =
                `/toko/login?code=${encodeURIComponent(code)}`;
        }


        // ============================================================
        // ENTER MANUAL CODE
        // ============================================================

        document
            .getElementById('manualCodeInput')
            .addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'Enter') {
                        submitManualCode();
                    }

                }
            );


        // ============================================================
        // PASSWORD TOGGLE
        // ============================================================

        function togglePassword() {

            const password =
                document.getElementById('password');

            if (password.type === 'password') {

                password.type = 'text';

            } else {

                password.type = 'password';

            }
        }


        // ============================================================
        // FORGOT PASSWORD
        // ============================================================

        function forgotPassword() {

            alert(
                'Silakan hubungi administrator IT PT. Sage Maslahat untuk mereset kata sandi Anda.'
            );
        }


        // ============================================================
        // CLOSE MODAL WHEN CLICK OUTSIDE
        // ============================================================

        document
            .getElementById('manualCodeModal')
            .addEventListener(
                'click',
                function(event) {

                    if (event.target === this) {
                        closeManualCode();
                    }

                }
            );


        // ============================================================
        // STOP CAMERA WHEN LEAVING PAGE
        // ============================================================

        window.addEventListener(
            'beforeunload',
            function() {

                if (html5QrCode && isScanning) {

                    html5QrCode
                        .stop()
                        .catch(() => {});

                }

            }
        );

    </script>

</body>

</html>

