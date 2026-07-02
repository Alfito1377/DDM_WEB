@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>

<div class="space-y-6">
    <div class="bg-gradient-to-r from-white to-blue-50/50 p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard Analitik Terpadu</h1>
            <p class="text-sm text-gray-500 mt-1">Visualisasi data internal sistem dan data eksternal dinamis dari Knowledge Base.</p>
        </div>
        <div class="text-left md:text-right bg-white px-5 py-3 rounded-xl border border-blue-100 shadow-sm">
            <p class="text-xs text-blue-500 font-bold uppercase tracking-wider mb-1">Total Data Center</p>
            <p class="text-3xl font-black text-blue-600 leading-none">
                {{ $stats['total_dokumen'] }} <span class="text-sm font-medium text-gray-500">Berkas</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Retur Masuk</p>
                <p class="text-2xl font-black text-gray-800">{{ $stats['total_retur'] }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Menunggu Proses</p>
                <p class="text-2xl font-black text-amber-600">{{ $stats['pending'] }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Retur Disetujui</p>
                <p class="text-2xl font-black text-green-600">{{ $stats['approved'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider border-b pb-2">Distribusi Alasan Retur</h2>
            <div class="relative flex-grow min-h-[250px] w-full">
                <canvas id="reasonChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider border-b pb-2">Top 5 Mitra Toko (Intensitas Retur)</h2>
            <div class="relative flex-grow min-h-[250px] w-full">
                <canvas id="storeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-blue-500">
        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="p-1.5 bg-blue-50 rounded-lg text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </span>
                    Visualisasi Data Dinamis
                </h2>
                <p class="text-xs text-gray-500 mt-1">Sistem secara otomatis membaca dan menerjemahkan file CSV terbaru.</p>
            </div>
            
            <div>
                @if($latestCsv)
                    <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 px-4 py-2 rounded-full">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                        </span>
                        <span class="text-blue-700 text-xs font-bold truncate max-w-[200px]" title="{{ $latestCsv->title }}">
                            Membaca: {{ $latestCsv->title }}
                        </span>
                    </div>
                @else
                    <span class="bg-gray-50 text-gray-500 text-xs font-bold px-4 py-2 rounded-full border border-gray-200">
                        Belum ada data CSV
                    </span>
                @endif
            </div>
        </div>

        <div class="relative min-h-[350px] w-full flex items-center justify-center bg-slate-50/50 rounded-xl border border-dashed border-gray-200 p-4" id="dynamicChartContainer">
            @if($latestCsv)
                <canvas id="dynamicCsvChart"></canvas>
            @else
                <div class="text-center text-gray-400">
                    <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Unggah file berformat .CSV</p>
                    <p class="text-xs mt-1">Upload di Knowledge Base untuk memunculkan grafik.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Membungkus seluruh script agar berjalan setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        const colorPalette = ['#16a34a', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#14b8a6', '#f43f5e'];

        // 1. Inisialisasi Grafik Internal
        initInternalCharts();

        // 2. Inisialisasi Grafik CSV Dinamis
        initDynamicCsvChart();


        // --- FUNGSI-FUNGSI BANTUAN --- //

        function initInternalCharts() {
            // Chart Distribusi Alasan Retur
            const reasonData = @json($reasonStats);
            if (Object.keys(reasonData).length > 0) {
                new Chart(document.getElementById('reasonChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(reasonData),
                        datasets: [{ 
                            data: Object.values(reasonData), 
                            backgroundColor: colorPalette, 
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { 
                            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } } 
                        }, 
                        cutout: '65%' 
                    }
                });
            }

            // Chart Top 5 Mitra Toko
            const storeData = @json($storeStats);
            if (Object.keys(storeData).length > 0) {
                new Chart(document.getElementById('storeChart'), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(storeData),
                        datasets: [{ 
                            label: 'Jumlah Pengajuan', 
                            data: Object.values(storeData), 
                            backgroundColor: 'rgba(59, 130, 246, 0.1)', // Warna biru transparan
                            borderColor: '#3b82f6', 
                            borderWidth: 2, 
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(59, 130, 246, 0.2)'
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }

        function initDynamicCsvChart() {
            const latestCsvUrl = @json($latestCsv ? asset('storage/' . $latestCsv->file_path) : null);

            if (latestCsvUrl) {
                Papa.parse(latestCsvUrl, {
                    download: true,
                    header: true,
                    skipEmptyLines: true, // Otomatis melewati baris kosong
                    complete: function(results) {
                        const data = results.data.filter(row => Object.keys(row).length > 1); 
                        
                        if (data.length > 0) {
                            const keys = Object.keys(data[0]);
                            const labelKey = keys[0]; 
                            const valueKey = keys[1]; 

                            const chartLabels = data.map(row => row[labelKey]);
                            const chartValues = data.map(row => parseFloat(row[valueKey]) || 0);

                            new Chart(document.getElementById('dynamicCsvChart'), {
                                type: 'line',
                                data: {
                                    labels: chartLabels,
                                    datasets: [{
                                        label: valueKey,
                                        data: chartValues,
                                        backgroundColor: 'rgba(139, 92, 246, 0.1)', // Warna ungu
                                        borderColor: '#8b5cf6',
                                        borderWidth: 3,
                                        pointBackgroundColor: '#ffffff',
                                        pointBorderColor: '#8b5cf6',
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: { 
                                    responsive: true, 
                                    maintainAspectRatio: false,
                                    interaction: { mode: 'index', intersect: false },
                                    plugins: { 
                                        legend: { display: true, position: 'top', labels: { usePointStyle: true } },
                                        tooltip: { backgroundColor: 'rgba(17, 24, 39, 0.9)', padding: 12 }
                                    },
                                    scales: { 
                                        y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                                        x: { grid: { display: false } }
                                    }
                                }
                            });
                        } else {
                            showError("File CSV tidak memiliki data yang valid untuk dirender.");
                        }
                    },
                    error: function() {
                        showError("Gagal membaca atau membedah data file CSV.");
                    }
                });
            }
        }

        function showError(message) {
            const container = document.getElementById('dynamicChartContainer');
            if (container) {
                container.innerHTML = `
                    <div class="text-center text-red-500 bg-red-50 p-4 rounded-lg border border-red-100">
                        <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-semibold">${message}</p>
                    </div>`;
            }
        }
    });
</script>
@endsection