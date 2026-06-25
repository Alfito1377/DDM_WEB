<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Retur - Jual Benih App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 500: '#22c55e', 600: '#16a34a', 700: '#15803d' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-50 border-r border-gray-200 flex flex-col justify-between hidden md:flex">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="text-green-600 font-bold text-xl flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 10-1.414 1.414L12 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path></svg>
                    Jual Benih App
                </div>
            </div>
            <nav class="p-4 space-y-1">
                <a href="/manajer/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="/manajer/retur" class="flex items-center gap-3 px-4 py-3 bg-white rounded-lg text-sm font-semibold text-gray-800 shadow-sm border border-gray-100">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Daftar Retur
                </a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col bg-white overflow-hidden relative">
        
        <header class="h-16 border-b border-gray-200 flex items-center justify-end px-8 bg-white">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm text-right">
                    <div>
                        <div class="font-bold text-gray-800">Budi Manajer</div>
                        <div class="text-xs text-gray-500">Manajer / Direktur</div>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden border border-gray-200">
                        <img src="https://ui-avatars.com/api/?name=Budi+Manajer&background=random" alt="Avatar">
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            
            <div class="mb-6 border-b border-gray-100 pb-6">
                <a href="/manajer/retur" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 font-medium mb-4 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Retur
                </a>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <h1 class="text-3xl font-bold text-gray-800">RET-2024-0812-004</h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Menunggu Persetujuan
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Diajukan oleh: <span class="font-bold text-gray-800">Toko Berkah Tani - Subang</span>
                        </p>
                    </div>
                    <button class="flex items-center gap-2 border border-gray-200 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Bukti
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Informasi Produk</h2>
                                <p class="text-xs text-gray-500">Data produk yang diajukan untuk pengembalian</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Produk</p>
                                <p class="font-bold text-gray-800 text-sm">Benih Padi Unggul Inpari 32</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nomor Batch / SKU</p>
                                <p class="font-bold text-gray-800 text-sm">B2405-A2 / SKU-PADI-I32-001</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Retur</p>
                                <p class="font-bold text-gray-800 text-sm">50 Sak <span class="text-gray-500 font-normal">(5kg/sak)</span></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Pengajuan</p>
                                <p class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    12 Agustus 2026, 14:30 WIB
                                </p>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                            <div class="flex items-center gap-2 text-red-600 mb-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span class="text-xs font-bold uppercase tracking-wider">Alasan Retur</span>
                            </div>
                            <p class="text-sm text-gray-700 italic">"Kemasan bocor dan terdapat kutu di dalam paket pengiriman batch Mei."</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Dokumentasi Bukti</h2>
                                <p class="text-xs text-gray-500">Foto asli dari pihak toko saat barang diterima</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 group bg-gray-100 aspect-[4/3]">
                                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Barcode+Scan" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" alt="Hasil Scan">
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Hasil Scan Barcode</div>
                            </div>
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 group bg-gray-100 aspect-[4/3]">
                                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Kondisi+Kemasan" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" alt="Kemasan">
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Kondisi Kemasan</div>
                            </div>
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 group bg-gray-100 aspect-[4/3]">
                                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Bukti+Isi+(Kutu)" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" alt="Isi">
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Bukti Isi (Kutu)</div>
                            </div>
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 group bg-gray-100 aspect-[4/3]">
                                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Label+Pengiriman" class="w-full h-full object-cover transition duration-300 group-hover:scale-105" alt="Label">
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Label Pengiriman</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-green-50/50 p-6 rounded-xl border border-green-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Panel Keputusan</h3>
                        <p class="text-xs text-gray-600 mb-5">Berikan persetujuan atau penolakan pengajuan ini</p>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-2">Komentar / Alasan Keputusan</label>
                            <textarea rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-green-500 focus:border-green-500 outline-none resize-none bg-white" placeholder="Masukkan alasan keputusan Anda di sini..."></textarea>
                            <p class="text-[10px] text-gray-500 mt-1">*Wajib diisi jika Anda menolak pengajuan retur.</p>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button class="flex-1 border-2 border-red-500 text-red-600 font-bold py-2.5 rounded-lg hover:bg-red-50 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tolak
                            </button>
                            <button class="flex-1 bg-green-600 text-white font-bold py-2.5 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                        </div>
                        
                        <div class="mt-4 flex items-start gap-2 text-[10px] text-gray-500">
                            <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tindakan ini tidak dapat dibatalkan setelah dikirim.
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <h3 class="text-base font-bold text-gray-800">Riwayat Komentar</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 border border-gray-100 rounded-lg bg-gray-50/50">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">AA</div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">Ani Admin</div>
                                            <div class="text-[10px] font-semibold text-gray-500">Admin Operasional</div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-gray-400">1 jam yang lalu</span>
                                </div>
                                <p class="text-xs text-gray-700 leading-relaxed">Sudah diverifikasi dengan data pengiriman batch Mei. Memang ada kendala suhu di gudang transit saat itu.</p>
                            </div>

                            <div class="p-4 border border-gray-100 rounded-lg bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-800 overflow-hidden">
                                            <img src="https://ui-avatars.com/api/?name=Toko+Berkah&background=random" alt="Avatar">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">Toko Berkah Tani</div>
                                            <div class="text-[10px] font-semibold text-gray-500">Store Owner</div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-gray-400">3 jam yang lalu</span>
                                </div>
                                <p class="text-xs text-gray-700 leading-relaxed">Mohon segera diproses pak, pelanggan kami sudah komplain karena benihnya tidak bisa dipakai.</p>
                            </div>

                            <div class="flex items-center justify-between text-[10px] text-gray-400 px-2 py-2 border-t border-gray-100 mt-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                    <span class="font-bold text-gray-500">System <span class="font-normal opacity-70">Automated</span></span>
                                </div>
                                <span>12 Agu, 14:30</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <footer class="mt-8 pt-6 border-t border-gray-200 flex justify-between text-xs text-gray-500">
                <div>&copy; 2026 Jual Benih App. Hak Cipta Dilindungi.</div>
            </footer>
        </div>

        <button class="fixed bottom-8 right-8 w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105 z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </button>

    </main>
</body>
</html>