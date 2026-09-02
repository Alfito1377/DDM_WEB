@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-full max-w-full space-y-4 sm:space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-white to-indigo-50/50 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:flex-row justify-between lg:items-center gap-4">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
                Dashboard Operasional Logistik
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">
                Pantauan real-time Surat Jalan, Alokasi Pengiriman, dan Mobilitas Armada.
            </p>
        </div>

        <div class="w-full lg:w-auto text-left lg:text-right bg-white px-4 sm:px-5 py-3 rounded-xl border border-indigo-100 shadow-sm shrink-0 flex gap-4">
            <div>
                <p class="text-[10px] sm:text-xs text-indigo-500 font-bold uppercase tracking-wider mb-1">Total Armada</p>
                <p class="text-xl sm:text-2xl font-black text-indigo-600 leading-none">
                    {{ $fleetStats['vehicle_ready'] + $fleetStats['on_trip'] + $fleetStats['maintenance'] }} <span class="text-xs font-medium text-gray-500">Unit</span>
                </p>
            </div>
            <div class="border-l border-gray-200 pl-4">
                <p class="text-[10px] sm:text-xs text-indigo-500 font-bold uppercase tracking-wider mb-1">Total Sopir</p>
                <p class="text-xl sm:text-2xl font-black text-indigo-600 leading-none">
                    {{ $fleetStats['ready_driver'] + $fleetStats['on_trip'] }} <span class="text-xs font-medium text-gray-500">Orang</span>
                </p>
            </div>
        </div>
    </div>

   {{-- 4 STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        
        {{-- 1. PENGIRIMAN BARU HARI INI --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Pengiriman Baru Hari Ini</p>
                <p class="text-xl sm:text-2xl font-black text-gray-800">{{ $stats['delivery_receipts_today'] ?? 0 }}</p>
            </div>
        </div>

        {{-- 2. TOTAL AKTIVITAS SCAN --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Aktivitas Scan</p>
                <p class="text-xl sm:text-2xl font-black text-purple-600">{{ $stats['total_scans_all_time'] ?? 0 }}</p>
            </div>
        </div>

        {{-- 3. PENGIRIMAN AKTIF (TRANSIT) --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Pengiriman Aktif (Transit)</p>
                <p class="text-xl sm:text-2xl font-black text-amber-600">{{ $stats['active_allocations'] ?? 0 }}</p>
            </div>
        </div>

        {{-- 4. TINGKAT KEBERHASILAN --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Tingkat Keberhasilan</p>
                <p class="text-xl sm:text-2xl font-black text-green-600">{{ $stats['success_rate'] ?? '0%' }}</p>
            </div>
        </div>
    </div>
    {{-- CHART & ACTIVITY SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        
        {{-- TREND PENGIRIMAN --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col min-w-0 border-t-4 border-t-indigo-500">
            <h2 class="text-xs sm:text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider flex items-center gap-2">
                <span class="p-1.5 bg-indigo-50 rounded-lg text-indigo-500 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </span>
                Pengiriman Dalam (7 Hari)
            </h2>
            <div class="relative w-full h-[300px] sm:h-[350px] min-w-0">
                <canvas id="shipmentTrendChart"></canvas>
            </div>
        </div>

        {{-- REALTIME SCANS --}}
        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex flex-col min-w-0">
            <h2 class="text-xs sm:text-sm font-bold text-gray-800 mb-6 uppercase tracking-wider border-b border-gray-100 pb-3">
                Live Scan Logistik
            </h2>
            <div class="flow-root overflow-y-auto max-h-[300px] sm:max-h-[350px] pr-2">
                <ul role="list" class="-mb-8">
                    @forelse($recentScans as $scan)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-{{ $scan['color'] }}-100 flex items-center justify-center ring-8 ring-white">
                                        <div class="h-2.5 w-2.5 rounded-full bg-{{ $scan['color'] }}-500"></div>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $scan['resi'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $scan['status'] }}</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-gray-500 font-medium">
                                        <time>{{ $scan['time'] }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada scan hari ini.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- SURAT JALAN / DELIVERY RECEIPTS TABLE --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-sm overflow-hidden border-t-4 border-t-blue-500">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider"> Pengiriman Aktif</h2>
            <button class="text-xs text-blue-600 hover:text-blue-800 font-bold bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">Lihat Semua</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">No. Surat Jalan</th>
                        <th class="px-6 py-4 font-bold">Mitra / Toko</th>
                        <th class="px-6 py-4 font-bold">Sopir</th>
                        <th class="px-6 py-4 font-bold">No. Kendaraan</th>
                        <th class="px-6 py-4 font-bold">Waktu Update</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($activeShipments as $shipment)
                        <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $shipment->receipt_number ?? 'SJ-XXX' }}</td>
                            <td class="px-6 py-4 font-medium">{{ $shipment->store_name ?? 'Nama Toko' }}</td>
                            <td class="px-6 py-4">{{ $shipment->driver_name ?? 'Nama Sopir' }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 border border-gray-200 px-2 py-1 rounded text-xs font-mono font-bold">{{ $shipment->vehicle_no ?? 'Nopol' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($shipment->updated_at)->format('d M, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[11px] font-bold uppercase tracking-wider">
                                    In Transit
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Tidak ada pengiriman berjalan saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Grafik Tren Surat Jalan
        const ctxTrend = document.getElementById('shipmentTrendChart').getContext('2d');
        const trendLabels = @json($trendLabels ?? []);
        const trendData = @json($trendData ?? []);

        if (trendLabels.length > 0) {
            new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Surat Jalan Terkirim',
                        data: trendData,
                        backgroundColor: 'rgba(99, 102, 241, 0.15)', // Indigo-500 with opacity
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(99, 102, 241, 0.3)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4] },
                            ticks: { font: { size: window.innerWidth < 640 ? 10 : 12 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: window.innerWidth < 640 ? 10 : 12 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection