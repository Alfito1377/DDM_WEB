<aside
    class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between hidden md:flex h-screen flex-shrink-0 shadow-sm">
    <div>
        <div class="h-16 flex items-center px-6 border-b border-gray-100 bg-gray-50/50">
            <div class="text-green-600 font-extrabold text-lg flex items-center gap-3 truncate"
                title="PT.Sage Mashlahat Indonesia">
                <div
                    class="w-9 h-9 flex-shrink-0 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo"
                        class="w-full h-full object-contain p-1">
                </div>

                <span class="truncate">PT. Sage Mashlahat</span>
            </div>
        </div>

        <nav class="p-4 space-y-1.5 overflow-y-auto">
            @php
                $userRole = strtolower(Auth::user()->role->role_name ?? '');
                $prefix = $userRole === 'admin' ? '/admin' : ($userRole === 'manajer' ? '/manajer' : '/toko');
            @endphp

            {{-- MENU ADMIN --}}
            @if ($userRole === 'admin')
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-4">Menu Admin</p>
                <a href="{{ $prefix }}/daftar-toko"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Daftar Mitra Toko
                </a>
                <a href="{{ $prefix }}/produk"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Kelola Produk Benih
                </a>
            @endif

            {{-- MENU MANAJER --}}
            @if ($userRole === 'manajer')
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-4">Menu Manajer</p>
                <a href="{{ $prefix }}/dashboard"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Dashboard Analitik
                </a>
                <a href="{{ $prefix }}/retur"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    Daftar Retur
                </a>
            @endif

            {{-- MENU BERSAMA (ADMIN & MANAJER) --}}
            @if ($userRole === 'admin' || $userRole === 'manajer')
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-4">Sistem AI & Data
                </p>
                <a href="/unggah-data"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Unggah Data
                </a>
                <a href="/chatbot"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    Chatbot AI
                </a>
            @endif

            {{-- MENU TOKO --}}
            @if ($userRole === 'toko')
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-4">Menu Toko</p>
                {{-- <a href="{{ $prefix }}/retur" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                    Ajukan Retur
                </a> --}}
                <a href="{{ $prefix }}/riwayat"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Retur
                </a>

                <a href="{{ $prefix }}/panduan"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Panduan & Kebijakan
                </a>
                <a href="{{ $prefix }}/profil"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-all group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Profil & Keamanan
                </a>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-red-500 hover:bg-red-50 hover:text-red-600 w-full rounded-xl transition-all group">
                <svg class="w-5 h-5 text-red-400 group-hover:text-red-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Keluar Aplikasi
            </button>
        </form>
    </div>
</aside>
