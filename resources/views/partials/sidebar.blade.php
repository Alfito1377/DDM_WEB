<aside class="w-64 bg-gray-50 border-r border-gray-200 flex flex-col justify-between hidden md:flex h-screen flex-shrink-0">
    <div>
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <div class="text-green-600 font-bold text-xl flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 10-1.414 1.414L12 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path></svg>
                Jual Benih App
            </div>
        </div>
        
        <nav class="p-4 space-y-1">
            @php 
                $userRole = strtolower(Auth::user()->role->role_name ?? ''); 
                $prefix = $userRole === 'admin' ? '/admin' : ($userRole === 'manajer' ? '/manajer' : '/toko');
            @endphp

            {{-- MENU ADMIN --}}
           {{-- MENU ADMIN --}}
            @if($userRole === 'admin')
                <a href="{{ $prefix }}/daftar-toko" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Daftar Mitra Toko</a>
                <a href="{{ $prefix }}/produk" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Kelola Produk Benih</a>
            @endif
            

            {{-- MENU MANAJER --}}
            @if($userRole === 'manajer')
                <a href="{{ $prefix }}/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Dashboard</a>
                <a href="{{ $prefix }}/retur" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Daftar Retur</a>
            @endif

            {{-- MENU BERSAMA (ADMIN & MANAJER) --}}
            @if($userRole === 'admin' || $userRole === 'manajer')
                <a href="/unggah-data" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
    Unggah Data
</a>

<a href="/chatbot" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
    Chatbot AI
</a>
            @endif

            {{-- MENU TOKO --}}
           @if($userRole === 'toko')
                <a href="{{ $prefix }}/retur" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Ajukan Retur</a>
                <a href="{{ $prefix }}/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 transition">Riwayat Retur</a>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-gray-200">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 w-full rounded-lg transition">
                Keluar
            </button>
        </form>
    </div>
</aside>