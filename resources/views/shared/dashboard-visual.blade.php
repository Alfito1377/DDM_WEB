@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Logistik</h1>
        <p class="text-gray-500 mt-1">Ringkasan aktivitas pengiriman dan armada terpadu.</p>
    </div>

    <!-- Section 1: Statistik Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card: Active Shipments -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pengiriman Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['activeShipments'] }}</p>
            </div>
        </div>

        <!-- Card: Completed Today -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Selesai Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completedToday'] }}</p>
            </div>
        </div>

        <!-- Card: Avg Delivery Time -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Rata-rata Waktu (Jam)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['avgDeliveryTime'] }}</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Tabel Pengiriman Aktif (Transit) -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Pengiriman Aktif (Transit)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-200 text-sm text-gray-600">
                        <th class="px-6 py-4 font-medium">ID Pengiriman</th>
                        <th class="px-6 py-4 font-medium">Tujuan</th>
                        <th class="px-6 py-4 font-medium">Sopir</th>
                        <th class="px-6 py-4 font-medium">Kendaraan</th>
                        <th class="px-6 py-4 font-medium">Waktu Berangkat</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($activeShipments as $shipment)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-blue-600">{{ $shipment->shipment_id }}</td>
                            <td class="px-6 py-4">{{ $shipment->destination }}</td>
                            <td class="px-6 py-4">{{ $shipment->driver_name }}</td>
                            <td class="px-6 py-4">{{ $shipment->vehicle_no }}</td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($shipment->departed_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    Transit
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Lacak</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada pengiriman aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection