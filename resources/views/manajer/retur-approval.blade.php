@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/60 relative">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Persetujuan Retur Benih</h1>
            <p class="text-sm text-slate-500 mt-1">Tinjau foto bukti dan ambil keputusan untuk pengajuan pengembalian produk.</p>
        </div>
        <div class="bg-amber-50 border border-amber-200/60 flex items-center gap-2.5 px-4 py-2.5 rounded-xl shadow-sm">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
            </span>
            <span class="text-amber-700 text-xs font-bold uppercase tracking-wider">
                Menunggu Proses: <span class="text-amber-800 bg-amber-200/50 px-1.5 py-0.5 rounded ml-1">{{ $returns->where('status', 'Pending')->count() }}</span>
            </span>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-hidden rounded-xl border border-slate-200/60 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200/60 whitespace-nowrap">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Kode & Tanggal</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Nama Toko & Produk</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Alasan Retur</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Keterangan Tambahan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Aksi Manajer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($returns as $retur)
                        <tr class="hover:bg-blue-50/20 transition-colors duration-200">
                            <!-- Kolom Kode & Tanggal -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $retur->return_code ?? '#RET-'.str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }}
                                </div>
                            </td>
                            
                            <!-- Kolom Toko & Produk -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $retur->store_name }}
                                </div>
                                <div class="text-[11px] text-slate-600 font-medium mt-1.5 bg-slate-100/80 border border-slate-200/60 inline-block px-2 py-0.5 rounded-md">
                                    {{ $retur->product_name }} &bull; <span class="font-bold text-slate-800">{{ $retur->quantity }} Pack</span>
                                </div>
                            </td>
                            
                            <!-- Kolom Alasan -->
                            <td class="px-6 py-4">
                                <p class="text-slate-600 max-w-[180px] line-clamp-2 text-xs leading-relaxed" title="{{ $retur->reason }}">
                                    {{ $retur->reason }}
                                </p>
                            </td>
                            
                            <!-- Kolom Keterangan -->
                            <td class="px-6 py-4">
                                @if($retur->notes)
                                    <div class="bg-slate-50 text-slate-600 px-3 py-2 rounded-lg border border-slate-100 text-[11px] max-w-[180px] max-h-16 overflow-y-auto shadow-inner line-clamp-2" title="{{ $retur->notes }}">
                                        {{ $retur->notes }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-xs">-</span>
                                @endif
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($retur->status === 'Pending')
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Pending
                                    </span>
                                @elseif($retur->status === 'Approved')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200/80 text-[10px] font-bold px-2.5 py-1.5 rounded-lg uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openDetailModal(@json($retur))' title="Lihat Detail Foto Bukti" class="group flex items-center gap-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-blue-600 font-semibold px-3 py-1.5 rounded-lg text-xs transition-all shadow-sm hover:shadow">
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </button>

                                    @if($retur->status === 'Pending')
                                        <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                        <button onclick="processReturn({{ $retur->id }}, 'Approved')" title="Setujui Retur" class="flex items-center justify-center w-8 h-8 bg-emerald-50 hover:bg-emerald-500 hover:text-white border border-emerald-200 text-emerald-700 rounded-lg transition-all shadow-sm hover:shadow group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button onclick="processReturn({{ $retur->id }}, 'Rejected')" title="Tolak Retur" class="flex items-center justify-center w-8 h-8 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-200 text-rose-700 rounded-lg transition-all shadow-sm hover:shadow group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-base font-medium text-slate-500">Belum ada pengajuan retur</p>
                                    <p class="text-xs mt-1">Pengajuan retur dari mitra toko akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail (Dilengkapi Transisi Halus) -->
<div id="detailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="modalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh] ring-1 ring-black/5">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 backdrop-blur">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Detail Pengajuan Retur</h3>
                <p id="modalReturnCode" class="text-[11px] text-blue-700 font-mono font-bold mt-1 bg-blue-50 inline-block px-2 py-0.5 rounded border border-blue-100 tracking-wide">Kode Retur</p>
            </div>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-all outline-none focus:ring-2 focus:ring-rose-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Nama Toko Mitra</p>
                    <p id="modalStoreName" class="text-sm font-bold text-slate-800">Toko</p>
                </div>
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> Produk & Jumlah</p>
                    <p id="modalProduct" class="text-sm font-bold text-slate-800">Produk</p>
                </div>
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 col-span-2">
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-1">Alasan Retur</p>
                    <p id="modalReason" class="text-sm text-slate-700 font-medium leading-relaxed bg-white p-3 rounded-lg border border-slate-100 mt-1 shadow-sm">Alasan</p>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Galeri Foto Bukti</p>
                </div>
                <div id="modalImageGallery" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <!-- Foto akan dimuat di sini -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- FUNGSI MODAL DETAIL ---
    function openDetailModal(returData) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        
        // Isi teks informasi
        document.getElementById('modalReturnCode').innerText = returData.return_code || ('#RET-' + returData.id);
        document.getElementById('modalStoreName').innerText = returData.store_name;
        document.getElementById('modalProduct').innerText = `${returData.product_name} (${returData.quantity} Pack)`;
        document.getElementById('modalReason').innerText = returData.reason;

        // Bersihkan galeri gambar sebelumnya
        const gallery = document.getElementById('modalImageGallery');
        gallery.innerHTML = '';

        // Parsing JSON gambar dari database
        try {
            let images = typeof returData.proof_image === 'string' ? JSON.parse(returData.proof_image) : returData.proof_image;
            if(Array.isArray(images) && images.length > 0) {
                images.forEach(imgPath => {
                    const imgUrl = `/storage/${imgPath}`;
                    const imgElement = `
                        <a href="${imgUrl}" target="_blank" class="block group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm hover:shadow-md transition-all">
                            <img src="${imgUrl}" class="w-full h-32 object-cover transition duration-300 transform group-hover:scale-110" alt="Bukti Retur">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-white mb-1 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                <span class="text-white text-[10px] font-bold tracking-wider drop-shadow-md">PERBESAR</span>
                            </div>
                        </a>
                    `;
                    gallery.insertAdjacentHTML('beforeend', imgElement);
                });
            } else {
                gallery.innerHTML = `
                    <div class="col-span-full py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-slate-400 font-medium">Tidak ada lampiran foto bukti.</p>
                    </div>`;
            }
        } catch (e) {
            gallery.innerHTML = '<p class="col-span-full py-4 text-center text-sm text-rose-500 bg-rose-50 rounded-xl border border-rose-100 font-medium">⚠️ Gagal memuat data foto.</p>';
        }

        // Tampilkan Modal dengan animasi Fade-in & Scale-up
        modal.classList.remove('pointer-events-none');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        
        // Sembunyikan Modal dengan animasi Fade-out & Scale-down
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 300); // Sesuaikan dengan durasi transisi (duration-300)
    }

    // --- FUNGSI AKSI MANAJER ---
    async function processReturn(returnId, decisionStatus) {
        const actionText = decisionStatus === 'Approved' ? 'MENYETUJUI' : 'MENOLAK';
        const isConfirmed = confirm(`Apakah Anda yakin ingin ${actionText} pengajuan retur ini?`);
        
        if (!isConfirmed) return;

        const formData = new FormData();
        formData.append('manager_id', '{{ Auth::user()->id ?? 1 }}'); 
        formData.append('status', decisionStatus);

        try {
            const response = await fetch(`/manajer/retur/${returnId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Gagal memproses: ' + (result.message || result.error));
            }
        } catch (error) {
            alert('Terjadi kesalahan komunikasi dengan server saat memproses data.');
        }
    }
</script>

<style>
    /* Styling tambahan untuk scrollbar agar terlihat lebih bersih di dalam modal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc; 
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
@endsection