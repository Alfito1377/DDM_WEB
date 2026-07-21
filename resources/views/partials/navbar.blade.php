<header class="h-16 border-b border-gray-200 flex items-center justify-between px-4 md:px-8 bg-white flex-shrink-0">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-2 -ml-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="md:hidden text-green-600 font-bold text-lg">PT SAGE</div>
    </div>
    <div class="hidden md:block"></div>
    
    <div class="flex items-center gap-4 ml-auto">
        <div class="flex items-center gap-3 text-sm text-right">
            <div>
                <div class="font-bold text-gray-800">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="text-xs text-gray-500 capitalize">{{ Auth::user()->role->role_name ?? 'Role' }}</div>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden border border-gray-200">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=random" alt="Avatar">
            </div>
        </div>
    </div>
</header>