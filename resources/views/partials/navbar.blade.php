<header class="h-16 border-b border-gray-200 flex items-center justify-between px-8 bg-white">
    <div class="md:hidden text-green-600 font-bold">PT SAGE</div>
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