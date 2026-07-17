@extends('layouts.app')

@section('content')
<!-- Memanggil Library HTML5 QR Code (Diletakkan di atas agar siap dipakai) -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Data Pengiriman Aktif</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pengiriman barang yang sedang berjalan.</p>
        </div>
        <button onclick="openFetchModal()" class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Fetch Pengiriman Terbaru
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">No.</th>
                    <th scope="col" class="px-6 py-4 font-bold">Waktu Dikirim</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                    <th scope="col" class="px-6 py-4 font-bold">Tujuan</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $i = 1;
                @endphp
                @forelse ($logistics as $logistic)
                    <tr class="hover:bg-gray-50 transition items-center">
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $i++ }}</td>
                        <td class="px-6 py-4 font-bold text-green-700 text-base">{{ $logistic->departedAt }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold leading-none {{ $logistic->status['color'] }}">
                                {{ $logistic->status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $logistic->destination }}</td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-500 hover:text-blue-700 font-semibold text-xs px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg transition" onclick="openLogisticModal({{ $logistic->id }})">Info</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pengiriman yang aktif di sistem.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Info Pengiriman -->
<div id="logisticModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-7xl transform transition-all overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-lg">Detail Pengiriman</h3>
            <button onclick="closeLogisticModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-12">
                <div class="col-span-4 px-2">
                    <p class="text-lg font-bold">Informasi Pengiriman</p>
                    <div class="col-span-full py-2">
                        <label for="info_status" class="block text-sm/6 font-light text-gray-600">Status Pengiriman</label>
                        <input id="info_status" disabled type="text" name="info_status" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_destination" class="block text-sm/6 font-light text-gray-600">Alamat Pengiriman</label>
                        <input id="info_destination" disabled type="text" name="info_destination" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_departedAt" class="block text-sm/6 font-light text-gray-600">Dikirim Pada</label>
                        <input id="info_departedAt" disabled type="text" name="info_departedAt" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_arrivedAt" class="block text-sm/6 font-light text-gray-600">Sampai Pada</label>
                        <input id="info_arrivedAt" disabled type="text" name="info_arrivedAt" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                </div>
                <div class="col-span-4 px-2">
                    <p class="text-lg font-bold">Informasi Mitra</p>
                    <div class="col-span-full py-2">
                        <label for="info_store_name" class="block text-sm/6 font-light text-gray-600">Nama Mitra</label>
                        <input id="info_store_name" disabled type="text" name="info_store_name" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_store_type" class="block text-sm/6 font-light text-gray-600">Jenis Mitra</label>
                        <input id="info_store_type" disabled type="text" name="info_store_type" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_store_owner" class="block text-sm/6 font-light text-gray-600">Pemilik</label>
                        <input id="info_store_owner" disabled type="text" name="info_store_owner" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_store_phone" class="block text-sm/6 font-light text-gray-600">No. Handphone</label>
                        <input id="info_store_phone" disabled type="text" name="info_store_phone" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_store_address" class="block text-sm/6 font-light text-gray-600">Alamat Mitra</label>
                        <input id="info_store_address" disabled type="text" name="info_store_address" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                </div>
                <div class="col-span-4 px-2">
                    <p class="text-lg font-bold">Informasi Driver Pengantar</p>
                    <div class="col-span-full py-2">
                        <label for="info_driver_name" class="block text-sm/6 font-light text-gray-600">Nama Driver</label>
                        <input id="info_driver_name" disabled type="text" name="info_driver_name" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_driver_phone" class="block text-sm/6 font-light text-gray-600">No. Handphone Driver</label>
                        <input id="info_driver_phone" disabled type="text" name="info_driver_phone" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_driver_description" class="block text-sm/6 font-light text-gray-600">Deskripsi</label>
                        <input id="info_driver_description" disabled type="text" name="info_driver_description" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_driver_vehicle_number" class="block text-sm/6 font-light text-gray-600">Nomor Kendaraan</label>
                        <input id="info_driver_vehicle_number" disabled type="text" name="info_driver_vehicle_number" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                    <div class="col-span-full py-2">
                        <label for="info_driver_type" class="block text-sm/6 font-light text-gray-600">Tipe Kendaraan</label>
                        <input id="info_driver_type" disabled type="text" name="info_driver_type" class="block min-w-0 w-full grow bg-white border border-gray-300 rounded-md py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- LOGIKA MODAL & FORM ---
    function openFetchModal() {
        Swal.fire({
            title: "Apakah anda ingin memperbarui data pengiriman?",
            showCancelButton: true,
            confirmButtonText: "Iya!",
        }).then((result) => {
            if (result.isConfirmed) {
                // cek pengiriman aktif
            };
        });
    }

    function openLogisticModal(logisticId) {
        // Ambil data pengiriman berdasarkan ID
        // document.querySelector('#logisticModal .p-6').innerHTML = '<p class="text-gray-500 text-center">Memuat data...</p>';
        fetch(`/superadmin/pengiriman/${logisticId}/detail`)
            .then(response => response.json())
            .then(data => {
                // Isi data ke dalam modal
                document.getElementById('info_status').value = data.data.status.label;
                document.getElementById('info_destination').value = data.data.destination;
                document.getElementById('info_departedAt').value = data.data.departedAt;
                document.getElementById('info_arrivedAt').value = data.data.arrivedAt || '-';
                document.getElementById('info_store_name').value = data.data.mitra.store_name;
                document.getElementById('info_store_type').value = data.data.mitra.jenis_mitra;
                document.getElementById('info_store_owner').value = data.data.mitra.owner_name;
                document.getElementById('info_store_phone').value = data.data.mitra.phone_number;
                document.getElementById('info_store_address').value = data.data.mitra.address;
                document.getElementById('info_driver_name').value = data.data.driver.name;
                document.getElementById('info_driver_phone').value = data.data.driver.phone;
                document.getElementById('info_driver_description').value = data.data.driver.notes || '-';
                document.getElementById('info_driver_vehicle_number').value = data.data.vehicle.plateNo || '-';
                document.getElementById('info_driver_type').value = data.data.vehicle.vehicleType || '-';
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