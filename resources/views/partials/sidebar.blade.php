<!-- Overlay khusus mobile -->
<div id="sidebar-overlay"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300 md:hidden"
    onclick="closeSidebar()">
</div>

<!-- Sidebar -->
<aside id="mobile-sidebar"
    class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between
           h-screen flex-shrink-0 shadow-sm
           fixed md:relative inset-y-0 left-0 z-50
           transform -translate-x-full md:translate-x-0
           transition-transform duration-300 ease-in-out">

    <div class="flex flex-col min-h-0 h-full">

        <div class="min-h-16 flex items-center px-4 sm:px-5 border-b border-gray-100 bg-gray-50/50">

            <div class="flex items-center gap-2.5 w-full min-w-0">

                {{-- LOGO --}}
                <div
                    class="w-9 h-9 flex-shrink-0 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo PT Sage Mashlahat"
                        class="w-full h-full object-contain p-1">
                </div>

                {{-- NAMA PERUSAHAAN --}}
                <div class="flex-1 min-w-0">
                    <span class="block text-green-600 font-extrabold text-sm leading-tight">
                        PT. Sage Mashlahat Indonesia
                    </span>
                </div>

                {{-- CLOSE MOBILE --}}
                <button type="button" onclick="closeSidebar()"
                    class="md:hidden flex-shrink-0 p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>

                    </svg>

                </button>

            </div>
        </div>

        <!-- Daftar Menu -->
        <nav class="p-3 sm:p-4 space-y-1.5 overflow-y-auto flex-1 min-h-0">

            @php
                $userRole = strtolower(Auth::user()->role->role_name ?? '');

                $prefix =
                    $userRole === 'superadmin'
                        ? '/superadmin'
                        : ($userRole === 'admin'
                            ? '/admin'
                            : ($userRole === 'toko'
                                ? '/toko'
                                : ''));
            @endphp


            {{-- ================= MENU MANAJEMEN ================= --}}
            @if ($userRole === 'superadmin' || $userRole === 'admin')
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2 sm:mt-4">
                    Menu Manajemen
                </p>

                <!-- Dashboard Logistik -->
                <a href="{{ url($prefix . '/dashboard-logistik') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10M21 16V10a2 2 0 00-2-2h-3" />

                    </svg>

                    <span class="truncate">
                        Dashboard Logistik
                    </span>

                </a>


                <!-- Dashboard Analitik -->
                <a href="{{ url($prefix . '/dashboard-analitik') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />

                    </svg>

                    <span class="truncate">
                        Dashboard Analitik
                    </span>

                </a>


                <!-- Daftar Mitra -->
                <a href="{{ url($prefix . '/daftar-customer') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>

                    </svg>

                    <span class="truncate">
                        Daftar Mitra
                    </span>

                </a>
                <!-- Permintaan Reset Lokasi -->
                <a href="{{ url($prefix . '/permintaan-reset-lokasi') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>

                    </svg>

                    <span class="truncate">
                        Permintaan Reset GPS
                    </span>

                </a>


                <!-- Kelola Pengiriman -->
                <a href="{{ url($prefix . '/pengiriman') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                        </path>

                    </svg>

                    <span class="truncate">
                        Kelola Pengiriman
                    </span>

                </a>


                <!-- Daftar Retur -->
                <a href="{{ url($prefix . '/retur') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>

                    </svg>

                    <span class="truncate">
                        Daftar Retur
                    </span>

                </a>


                <!-- Chatbot -->
                <a href="{{ url($prefix . '/chatbot') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />

                    </svg>

                    <span class="truncate">
                        Chatbot AI
                    </span>

                </a>


                <!-- Upload Data -->
                <a href="{{ url($prefix . '/unggah-data') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />

                    </svg>

                    <span class="truncate">
                        Unggah Data
                    </span>

                </a>
            @endif


            {{-- ================= MENU TOKO ================= --}}
            @if ($userRole === 'toko')
            @php
                    $toko = \App\Models\StoresModel::find(Auth::user()->store_id);
                @endphp
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2 sm:mt-4">
                    Menu Toko
                </p>

                <!-- Penerimaan Barang -->
                <a href="{{ url('/toko/penerimaan') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                        </path>
                    </svg>
                    <span class="truncate">
                        Penerimaan Barang
                    </span>
                </a>

                <!-- Retur Barang -->
                <a href="{{ url('/toko/riwayat') }}" onclick="closeSidebarOnMobile()"
                    class="flex items-center gap-3 px-3 sm:px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-green-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="truncate">
                        Retur Barang
                    </span>
                </a>

                @if ($toko && !is_null($toko->latitude))
                    <div class="px-3 sm:px-4 mt-4">
                        <div class="h-px bg-gray-100 w-full mb-4"></div>

                        @if ($toko->request_reset_lokasi)
                            {{-- State: Menunggu Persetujuan --}}
                            <div class="flex items-start gap-2.5 p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                                <svg class="w-4 h-4 text-yellow-500 animate-spin flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-[11px] leading-tight font-bold text-yellow-700">
                                    Menunggu persetujuan Admin.
                                </span>
                            </div>
                        @else
                            {{-- Hitung Sisa Hari --}}
                            @php
                                $bisaReset = true;
                                $sisaHari = 0;
                                if ($toko->last_location_set_at) {
                                    $tanggalBisaReset = \Carbon\Carbon::parse($toko->last_location_set_at)->addDays(30);
                                    if (now()->lessThan($tanggalBisaReset)) {
                                        $bisaReset = false;
                                        $sisaHari = now()->diffInDays($tanggalBisaReset) ?: 1;
                                    }
                                }
                            @endphp

                            @if($bisaReset)
                                {{-- State: Tombol Aktif (Bisa Diklik) --}}
                                <button onclick="ajukanResetLokasi()"
                                    class="w-full flex items-center justify-center gap-2 p-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all group">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-gray-600 group-hover:text-blue-600 transition-colors">
                                        Ajukan Reset Lokasi
                                    </span>
                                </button>
                            @else
                                {{-- State: Tombol Terkunci (Disabled) --}}
                                <button disabled
                                    class="w-full flex items-center justify-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-not-allowed">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span class="text-[11px] font-bold text-gray-400">
                                        Terkunci (Sisa {{ $sisaHari }} Hari)
                                    </span>
                                </button>
                            @endif

                        @endif
                    </div>
                @endif

            @endif

        </nav>


        <!-- Bagian Bawah: Logout -->
        <div class="p-3 sm:p-4 border-t border-gray-100 bg-gray-50/50 flex-shrink-0">

            <form action="{{ url('/logout') }}" method="POST">
                @csrf

                <button type="submit"
                    class="flex items-center gap-3 px-3 sm:px-4 py-2.5 text-sm font-semibold text-red-500 hover:bg-red-50 hover:text-red-600 w-full rounded-xl transition-all group">

                    <svg class="w-5 h-5 flex-shrink-0 text-red-400 group-hover:text-red-500 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>

                    </svg>

                    <span>
                        Keluar Aplikasi
                    </span>

                </button>

            </form>

        </div>

    </div>
</aside>


<!-- ========================================================= -->
<!-- JAVASCRIPT SIDEBAR MOBILE -->
<!-- ========================================================= -->

<script>
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');


    // Membuka sidebar
    function openSidebar() {

        if (!sidebar || !overlay) return;

        sidebar.classList.remove('-translate-x-full');

        overlay.classList.remove('hidden');

        // Tunggu browser agar transition berjalan
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
        });

        // Mencegah body di-scroll ketika sidebar terbuka
        document.body.classList.add('overflow-hidden');
    }


    // Menutup sidebar
    function closeSidebar() {

        if (!sidebar || !overlay) return;

        sidebar.classList.add('-translate-x-full');

        overlay.classList.add('opacity-0');

        setTimeout(() => {

            // Jangan hidden jika sudah desktop
            if (window.innerWidth < 768) {
                overlay.classList.add('hidden');
            }

        }, 300);

        document.body.classList.remove('overflow-hidden');
    }


    // Dipanggil ketika menu diklik
    function closeSidebarOnMobile() {

        if (window.innerWidth < 768) {
            closeSidebar();
        }

    }


    // ESC untuk menutup sidebar
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {
            closeSidebar();
        }

    });


    // Ketika ukuran layar berubah
    window.addEventListener('resize', function() {

        if (window.innerWidth >= 768) {

            sidebar.classList.remove('-translate-x-full');

            overlay.classList.add('hidden');
            overlay.classList.add('opacity-0');

            document.body.classList.remove('overflow-hidden');

        }

    });
</script>
