@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>

<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Analitik Terpadu</h1>
            <p class="text-sm text-gray-500 mt-1">Visualisasi data internal sistem dan data eksternal dinamis dari Knowledge Base.</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Data Center</p>
            <p class="text-2xl font-black text-blue-600">{{ $stats['total_dokumen'] }} <span class="text-sm font-medium text-gray-500">Berkas</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Retur Masuk</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['total_retur'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Menunggu Proses</p>
                <p class="text-2xl font-black text-amber-600">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Retur Disetujui</p>
                <p class="text-2xl font-black text-green-600">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Distribusi Alasan Retur</h2>
            <div class="relative h-64 w-full"><canvas id="reasonChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Top 5 Mitra Toko (Intensitas Retur)</h2>
            <div class="relative h-64 w-full"><canvas id="storeChart"></canvas></div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Visualisasi Data Dinamis (Live dari Berkas)
                </h2>
                <p class="text-xs text-gray-500 mt-1">Sistem secara otomatis membaca dan menerjemahkan file CSV terbaru yang Anda unggah.</p>
            </div>
            @if($latestCsv)
                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full border border-blue-200">
                    Membaca: {{ $latestCsv->title }}
                </span>
            @else
                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-3 py-1 rounded-full">
                    Belum ada data CSV
                </span>
            @endif
        </div>

        <div class="relative h-80 w-full flex items-center justify-center bg-gray-50/50 rounded-xl border border-dashed border-gray-200" id="dynamicChartContainer">
            @if($latestCsv)
                <canvas id="dynamicCsvChart"></canvas>
            @else
                <div class="text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm font-medium">Unggah file berformat .CSV di Knowledge Base untuk memunculkan grafik.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const colorPalette = ['#16a34a', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'];

    // 1. Grafik Internal (Sama seperti sebelumnya)
    const reasonData = @json($reasonStats);
    if(Object.keys(reasonData).length > 0) {
        new Chart(document.getElementById('reasonChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(reasonData),
                datasets: [{ data: Object.values(reasonData), backgroundColor: colorPalette, borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });
    }

    const storeData = @json($storeStats);
    if(Object.keys(storeData).length > 0) {
        new Chart(document.getElementById('storeChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(storeData),
                datasets: [{ label: 'Jumlah Pengajuan', data: Object.values(storeData), backgroundColor: '#dcfce7', borderColor: '#16a34a', borderWidth: 2, borderRadius: 6 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    // 2. KEAJAIBAN GRAFIK DINAMIS (Membaca CSV secara Live)
    const latestCsvUrl = @json($latestCsv ? asset('storage/' . $latestCsv->file_path) : null);

    if(latestCsvUrl) {
        Papa.parse(latestCsvUrl, {
            download: true,
            header: true,
            complete: function(results) {
                // Saring baris yang kosong
                const data = results.data.filter(row => Object.keys(row).length > 1); 
                
                if(data.length > 0) {
                    // Sistem menebak: Kolom pertama adalah Label (Sumbu X), Kolom kedua adalah Nilai (Sumbu Y)
                    const keys = Object.keys(data[0]);
                    const labelKey = keys[0]; 
                    const valueKey = keys[1]; 

                    const chartLabels = data.map(row => row[labelKey]);
                    const chartValues = data.map(row => parseFloat(row[valueKey]) || 0);

                    new Chart(document.getElementById('dynamicCsvChart'), {
                        type: 'line', // Menggunakan grafik garis yang cantik
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                label: valueKey,
                                data: chartValues,
                                backgroundColor: 'rgba(59, 130, 246, 0.1)', // blue-500 dengan transparansi
                                borderColor: '#3b82f6',
                                borderWidth: 3,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#3b82f6',
                                pointRadius: 4,
                                fill: true,
                                tension: 0.4 // Membuat garis melengkung halus
                            }]
                        },
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false,
                            plugins: { legend: { display: true, position: 'top' } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                }
            },
            error: function() {
                document.getElementById('dynamicChartContainer').innerHTML = '<p class="text-red-500 text-sm">Gagal membedah data CSV.</p>';
            }
        });
    }
</script>
@endsection