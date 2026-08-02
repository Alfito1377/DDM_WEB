@extends('layouts.app') 

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Logistik</h1>
        <p class="text-gray-500 mt-1">Ringkasan aktivitas pengiriman dan armada terpadu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pengiriman Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['activeShipments'] }}</p>
            </div>
        </div>

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

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Rata-rata Waktu (Jam)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['avgDeliveryTime'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-8">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tren Pengiriman (7 Hari Terakhir)</h2>
            <div class="relative h-64 w-full">
                <canvas id="shipmentChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Armada Saat Ini</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 font-medium">Tersedia (Standby)</span>
                            <span class="text-gray-900 font-bold">{{ $vehicleStats['available'] }} Unit</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 font-medium">Dalam Perjalanan</span>
                            <span class="text-gray-900 font-bold">{{ $vehicleStats['on_trip'] }} Unit</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 font-medium">Perawatan (Maintenance)</span>
                            <span class="text-gray-900 font-bold">{{ $vehicleStats['maintenance'] }} Unit</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua Kendaraan &rarr;</a>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Status Paket</h2>
            <div class="relative h-56 w-full">
                <canvas id="statusDoughnutChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($recentActivities as $index => $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center ring-8 ring-white">
                                        <div class="h-2.5 w-2.5 rounded-full bg-{{ $activity['color'] }}-500"></div>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-700">{{ $activity['message'] }}</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                        <time>{{ $activity['time'] }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

        const ctxDoughnut = document.getElementById('statusDoughnutChart').getContext('2d');
        const statusData = @json($statusDistribution);

        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Packed', 'In Transit', 'Completed'],
                datasets: [{
                    data: [statusData.pending, statusData.packed, statusData.in_transit, statusData.completed],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)', 
                        'rgba(139, 92, 246, 0.8)', 
                        'rgba(59, 130, 246, 0.8)', 
                        'rgba(16, 185, 129, 0.8)'  
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%', 
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    }
                }
            }
        });
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('shipmentChart').getContext('2d');
        
        const labels = @json($chartLabels);
        const dataPoints = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pengiriman Selesai',
                    data: dataPoints,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)', 
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection