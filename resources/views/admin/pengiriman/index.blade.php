@extends('layouts.app')

@section('content')
<!-- Memanggil Library HTML5 QR Code -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Data Pengiriman Aktif</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status, tujuan, sopir, dan armada yang sedang berjalan.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Form Filter Status -->
            <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5 outline-none font-semibold cursor-pointer">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Packed</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="out_of_transit" {{ request('status') == 'out_of_transit' ? 'selected' : '' }}>Out of Transit</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>

            <!-- Tombol Fetch -->
            <button onclick="openFetchModal()" class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Fetch Terbaru
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-y border-gray-200">
                <tr>
                    <th scope="col" class="px-4 py-4 font-bold text-center w-12">No.</th>
                    <th scope="col" class="px-6 py-4 font-bold">Tujuan & Waktu Berangkat</th>
                    <th scope="col" class="px-6 py-4 font-bold">Sopir & Kontak</th>
                    <th scope="col" class="px-6 py-4 font-bold">Armada Kendaraan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logistics as $index => $logistic)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Perhitungan Nomor Urut Pagination -->
                        <td class="px-4 py-4 font-bold text-gray-700 text-center">
                            {{ ($logistics->currentPage() - 1) * $logistics->perPage() + $loop->iteration }}
                        </td>
                        
                        <!-- 1. Kolom Tujuan & Waktu -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 text-base mb-1">{{ $logistic->destination ?? '-' }}</div>
                            <div class="flex items-center text-xs text-green-700 font-semibold bg-green-50 w-max px-2 py-1 rounded border border-green-100">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $logistic->departedAt }}
                            </div>
                        </td>

                        <!-- 2. Kolom Sopir & No HP -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ optional($logistic->driver)->name ?? 'Belum ada sopir' }}</div>
                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ optional($logistic->driver)->phone ?? '-' }}
                            </div>
                        </td>

                        <!-- 3. Kolom Kendaraan & Plat -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ optional($logistic->vehicle)->vehicleType ?? '-' }}</div>
                            <div class="mt-1">
                                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-300 font-mono text-xs font-bold rounded">
                                    {{ optional($logistic->vehicle)->plateNo ?? 'Plat Kosong' }}
                                </span>
                            </div>
                        </td>

                        <!-- 4. Kolom Status -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold leading-none {{ $logistic->status['color'] ?? 'bg-gray-100 text-gray-800' }} rounded-full">
                                {{ $logistic->status['label'] ?? 'Unknown' }}
                            </span>
                        </td>

                        <!-- 5. Kolom Aksi -->
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-xs px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition" onclick="openLogisticModal({{ $logistic->id }})">
                                Detail Info
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Belum ada data pengiriman yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Menampilkan Link Pagination Laravel -->
    <div class="mt-6">
        <!-- appends(request()->query()) berguna agar filter status tidak hilang saat ganti halaman -->
        {{ $logistics->appends(request()->query())->links() }}
    </div>
</div>

<!-- Modal Info Pengiriman (Tetap sama seperti sebelumnya) -->
<div id="logisticModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Detail Ekstra Pengiriman</h3>
            <button onclick="closeLogisticModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sisi Kiri: Info Waktu & Mitra -->
                <div>
                    <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi & Tujuan</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Saat Ini</label>
                            <input id="info_status" disabled type="text" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Waktu Berangkat & Sampai</label>
                            <div class="flex gap-2">
                                <input id="info_departedAt" disabled type="text" placeholder="Berangkat..." class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none" />
                                <input id="info_arrivedAt" disabled type="text" placeholder="Sampai..." class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Mitra (Tujuan)</label>
                            <div class="flex gap-2">
                                <input id="info_store_name" disabled type="text" class="w-2/3 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none" />
                                <input id="info_store_type" disabled type="text" class="w-1/3 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 text-center focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pemilik & Kontak Mitra</label>
                            <div class="flex gap-2">
                                <input id="info_store_owner" disabled type="text" class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none" />
                                <input id="info_store_phone" disabled type="text" class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <textarea id="info_store_address" disabled rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Info Driver -->
                <div>
                    <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Driver & Armada</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Driver</label>
                            <input id="info_driver_name" disabled type="text" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kontak Driver</label>
                            <input id="info_driver_phone" disabled type="text" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Armada / Kendaraan</label>
                            <div class="flex gap-2">
                                <input id="info_driver_type" disabled type="text" class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-semibold text-gray-800 focus:outline-none" />
                                <input id="info_driver_vehicle_number" disabled type="text" class="w-1/2 bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-mono font-bold text-center text-gray-800 focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Catatan Tambahan (Notes)</label>
                            <textarea id="info_driver_description" disabled rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm text-gray-800 focus:outline-none resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 text-right">
            <button onclick="closeLogisticModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openFetchModal() {
        Swal.fire({
            title: "Memperbarui Data",
            text: "Apakah anda ingin mengambil data pengiriman terbaru?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Perbarui!",
            cancelButtonText: "Batal",
            confirmButtonColor: "#16a34a",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            };
        });
    }

    function openLogisticModal(logisticId) {
        fetch(`/superadmin/pengiriman/${logisticId}/detail`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('info_status').value = data.data.status.label;
                document.getElementById('info_departedAt').value = data.data.departedAt;
                document.getElementById('info_arrivedAt').value = data.data.arrivedAt || '-';
                
                if(data.data.mitra) {
                    document.getElementById('info_store_name').value = data.data.mitra.store_name || '-';
                    document.getElementById('info_store_type').value = data.data.mitra.jenis_mitra || '-';
                    document.getElementById('info_store_owner').value = data.data.mitra.owner_name || '-';
                    document.getElementById('info_store_phone').value = data.data.mitra.phone_number || '-';
                    document.getElementById('info_store_address').value = data.data.mitra.address || '-';
                }

                if(data.data.driver) {
                    document.getElementById('info_driver_name').value = data.data.driver.name || '-';
                    document.getElementById('info_driver_phone').value = data.data.driver.phone || '-';
                    document.getElementById('info_driver_description').value = data.data.driver.notes || '-';
                }

                if(data.data.vehicle) {
                    document.getElementById('info_driver_vehicle_number').value = data.data.vehicle.plateNo || '-';
                    document.getElementById('info_driver_type').value = data.data.vehicle.vehicleType || '-';
                }
            })
            .catch(error => {
                console.error("Gagal memuat data pengiriman: ", error);
                alert("Gagal memuat data pengiriman.");
            });
            
        document.getElementById('logisticModal').classList.remove('hidden');
    }

    function closeLogisticModal() {
        document.getElementById('logisticModal').classList.add('hidden');
    }
</script>
@endsection