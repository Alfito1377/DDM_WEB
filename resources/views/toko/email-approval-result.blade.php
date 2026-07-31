<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Persetujuan Retur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        @if($status === 'success')
            <div class="bg-emerald-500 p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-inner mb-4">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-1">Berhasil Diproses!</h2>
                <p class="text-emerald-50 font-medium">{{ $message }}</p>
            </div>
        @else
            <div class="bg-rose-500 p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-inner mb-4">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-1">Gagal Diproses</h2>
                <p class="text-rose-50 font-medium">{{ $message }}</p>
            </div>
        @endif

        <div class="p-8 text-center">
            <p class="text-slate-500 mb-6 text-sm">Status retur untuk <strong class="text-slate-800">{{ $retur->return_code ?? ('#RET-' . $retur->id) }}</strong> telah berhasil diperbarui di sistem. Anda dapat menutup halaman ini sekarang.</p>
            
            <a href="/" class="inline-flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                Ke Beranda Utama
            </a>
        </div>
    </div>

</body>
</html>
