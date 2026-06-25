@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Persetujuan Retur Benih</h1>
            <p class="text-sm text-gray-500 mt-1">Tinjau foto bukti dan ambil keputusan untuk pengajuan pengembalian produk.</p>
        </div>
        <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Menunggu Proses: {{ $returns->where('status', 'Pending')->count() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Kode / Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-bold">Nama Toko & Produk</th>
                    <th scope="col" class="px-6 py-4 font-bold">Alasan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Aksi Manajer</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($returns as $retur)
                    <tr class="hover:bg-gray-50 transition items-center">
                        <td class="px-6 py-4 text-gray-800">
                            <div class="font-bold">{{ $retur->return_code ?? '#RET-'.str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-green-700">{{ $retur->store_name }}</div>
                            <div class="text-xs text-gray-600 font-medium">{{ $retur->product_name }} ({{ $retur->quantity }} Pack)</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-[150px] truncate" title="{{ $retur->reason }}">{{ $retur->reason }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            @if($retur->status === 'Pending')
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Pending</span>
                            @elseif($retur->status === 'Approved')
                                <span class="bg-green-50 text-green-600 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Disetujui</span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Ditolak</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center flex justify-center gap-1.5">
                            <button onclick='openDetailModal(@json($retur))' class="bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 font-bold px-3 py-1.5 rounded-lg text-xs transition">
                                Lihat Bukti
                            </button>

                            @if($retur->status === 'Pending')
                                <button onclick="processReturn({{ $retur->id }}, 'Approved')" class="bg-green-100 hover:bg-green-200 text-green-700 font-bold px-3 py-1.5 rounded-lg text-xs transition">Setujui</button>
                                <button onclick="processReturn({{ $retur->id }}, 'Rejected')" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold px-3 py-1.5 rounded-lg text-xs transition">Tolak</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pengajuan retur dari toko.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Detail Pengajuan Retur</h3>
                <p id="modalReturnCode" class="text-xs text-green-600 font-mono font-bold mt-0.5">Kode Retur</p>
            </div>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Nama Toko Mitra</p>
                    <p id="modalStoreName" class="text-sm font-bold text-gray-800">Toko</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Produk & Jumlah</p>
                    <p id="modalProduct" class="text-sm font-bold text-gray-800">Produk</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 col-span-2">
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Alasan Retur</p>
                    <p id="modalReason" class="text-sm text-gray-700 font-medium leading-relaxed">Alasan</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-3">Galeri Foto Bukti</p>
                <div id="modalImageGallery" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- FUNGSI MODAL DETAIL ---
    function openDetailModal(returData) {
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
            let images = JSON.parse(returData.proof_image);
            if(Array.isArray(images) && images.length > 0) {
                images.forEach(imgPath => {
                    const imgUrl = `/storage/${imgPath}`;
                    const imgElement = `
                        <a href="${imgUrl}" target="_blank" class="block group relative overflow-hidden rounded-xl border border-gray-200">
                            <img src="${imgUrl}" class="w-full h-32 object-cover transition transform group-hover:scale-105" alt="Bukti Retur">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            </div>
                        </a>
                    `;
                    gallery.insertAdjacentHTML('beforeend', imgElement);
                });
            } else {
                gallery.innerHTML = '<p class="text-sm text-gray-400 italic">Tidak ada foto bukti.</p>';
            }
        } catch (e) {
            gallery.innerHTML = '<p class="text-sm text-red-400 italic">Gagal memuat foto bukti.</p>';
        }

        // Tampilkan Modal
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // --- FUNGSI AKSI MANAJER (Sama seperti sebelumnya) ---
    async function processReturn(returnId, decisionStatus) {
        const isConfirmed = confirm(`Apakah Anda yakin ingin ${decisionStatus === 'Approved' ? 'MENYETUJUI' : 'MENOLAK'} retur ini?`);
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
            alert('Terjadi kesalahan komunikasi dengan server.');
        }
    }
</script>
@endsection