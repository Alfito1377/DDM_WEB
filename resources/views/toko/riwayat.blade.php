@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Retur Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan pengembalian benih Anda yang diproses oleh manajemen.</p>
        </div>
        <a href="/toko/retur" class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Ajukan Retur Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Kode / Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-bold">Produk Benih</th>
                    <th scope="col" class="px-6 py-4 font-bold">Alasan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Dokumen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($returns as $retur)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-800">
                            <div class="font-bold">{{ $retur->return_code ?? '#RET-'.str_pad($retur->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($retur->created_at)->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $retur->product_name }}</div>
                            <div class="text-xs text-green-600 font-bold mt-0.5">{{ $retur->quantity }} Pack</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $retur->reason }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            @if($retur->status === 'Pending')
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Pending</span>
                            @elseif($retur->status === 'Approved')
                                <span class="bg-green-50 text-green-600 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Disetujui</span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase">Ditolak</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button onclick='openPhotoModal(@json($retur))' class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-1.5 rounded-lg transition">
                                Lihat Foto Bukti
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Anda belum pernah melakukan pengajuan retur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Berkas Foto Bukti Retur</h3>
                <p id="modalCode" class="text-xs text-green-600 font-mono font-bold mt-0.5">Kode</p>
            </div>
            <button onclick="closePhotoModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div id="modalGallery" class="grid grid-cols-2 gap-3">
                </div>
        </div>
    </div>
</div>

<script>
    function openPhotoModal(retur) {
        document.getElementById('modalCode').innerText = retur.return_code || ('#RET-' + retur.id);
        const gallery = document.getElementById('modalGallery');
        gallery.innerHTML = '';

        try {
            // Ambil data array text JSON gambar dari kolom proof_image
            let images = JSON.parse(retur.proof_image);
            if(Array.isArray(images) && images.length > 0) {
                images.forEach(path => {
                    const url = `/storage/${path}`;
                    const imgHtml = `
                        <a href="${url}" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                            <img src="${url}" class="w-full h-28 object-cover hover:scale-105 transition duration-200">
                        </a>
                    `;
                    gallery.insertAdjacentHTML('beforeend', imgHtml);
                });
            } else {
                gallery.innerHTML = '<p class="text-sm text-gray-400 italic col-span-2">Tidak ada foto.</p>';
            }
        } catch (e) {
            gallery.innerHTML = '<p class="text-sm text-red-400 italic col-span-2">Gagal memuat gambar berkas.</p>';
        }

        document.getElementById('photoModal').classList.remove('hidden');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
    }
</script>
@endsection