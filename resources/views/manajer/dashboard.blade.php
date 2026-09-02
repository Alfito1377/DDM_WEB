@extends('layouts.app')

@section('content')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>

  <div class="w-full max-w-full space-y-4 sm:space-y-6">

    {{-- HEADER --}}
    <div
      class="bg-gradient-to-r from-white to-blue-50/50 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex flex-col lg:flex-row justify-between lg:items-center gap-4">
      <div class="min-w-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Dashboard Analitik Terpadu</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">Visualisasi data internal sistem dan data
          eksternal dinamis dari Knowledge Base.</p>
      </div>
      <div
        class="w-full lg:w-auto text-left lg:text-right bg-white px-4 sm:px-5 py-3 rounded-xl border border-blue-100 shadow-sm shrink-0">
        <p class="text-[10px] sm:text-xs text-blue-500 font-bold uppercase tracking-wider mb-1">Total Data Center</p>
        <p class="text-2xl sm:text-3xl font-black text-blue-600 leading-none">{{ $stats['total_dokumen'] }} <span
            class="text-xs sm:text-sm font-medium text-gray-500">Berkas</span></p>
      </div>
    </div>

    {{-- STATISTIC CARD --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
      <div
        class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div
          class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
        </div>
        <div class="min-w-0">
          <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Retur Masuk</p>
          <p class="text-xl sm:text-2xl font-black text-gray-800">{{ $stats['total_retur'] }}</p>
        </div>
      </div>

      <div
        class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div
          class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="min-w-0">
          <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Menunggu Proses</p>
          <p class="text-xl sm:text-2xl font-black text-amber-600">{{ $stats['pending'] }}</p>
        </div>
      </div>

      <div
        class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div
          class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="min-w-0">
          <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Retur Disetujui</p>
          <p class="text-xl sm:text-2xl font-black text-green-600">{{ $stats['approved'] }}</p>
        </div>
      </div>
    </div>

    {{-- CHART INTERNAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
      <div
        class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col min-w-0">
        <h2
          class="text-xs sm:text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider border-b border-gray-200 pb-2">
          Distribusi Alasan Retur</h2>
        <div class="relative w-full h-[500px] sm:h-[540px] min-w-0"><canvas id="reasonChart"></canvas></div>
      </div>
      <div
        class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col min-w-0">
        <h2
          class="text-xs sm:text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider border-b border-gray-200 pb-2">
          Top 5 Mitra Toko (Intensitas Retur)</h2>
        <div class="relative w-full h-[320px] lg:flex-1 lg:min-h-0 min-w-0"><canvas id="storeChart"></canvas></div>
      </div>
    </div>

    {{-- TURNOVER CHART (KEMBALI KE ASLI) --}}
    <div
      class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-emerald-500 min-w-0">
      <h2
        class="text-xs sm:text-sm font-bold text-gray-800 uppercase tracking-wider flex items-start sm:items-center gap-2 mb-4">
        <span class="p-1.5 bg-emerald-50 rounded-lg text-emerald-500 shrink-0"><svg class="w-4 h-4 sm:w-5 sm:h-5"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
            </path>
          </svg></span>
        <span>Tren Turn Over (Barang Masuk) Gudang Pusat SAGE</span>
      </h2>
      <div class="relative w-full h-[300px] sm:h-[350px] lg:h-[400px] min-w-0">
        <canvas id="turnoverChart"></canvas>
      </div>
    </div>

    {{-- FORECAST CHART BARU (DARI API PYTHON) --}}
    <div
      class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-fuchsia-500 min-w-0">
      <h2
        class="text-xs sm:text-sm font-bold text-gray-800 uppercase tracking-wider flex items-start sm:items-center gap-2 mb-4">
        <span class="p-1.5 bg-fuchsia-50 rounded-lg text-fuchsia-500 shrink-0"><svg class="w-4 h-4 sm:w-5 sm:h-5"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
          </svg></span>
        <span>Prediksi Tren (Dari API Python)</span>
      </h2>
      <div class="relative w-full h-[300px] sm:h-[350px] lg:h-[400px] min-w-0">
        <canvas id="pythonForecastChart"></canvas>
      </div>
    </div>

    {{-- CLUSTERING CHART --}}
    <div
      class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-indigo-500 min-w-0 mt-4 sm:mt-6">
      <h2
        class="text-xs sm:text-sm font-bold text-gray-800 uppercase tracking-wider flex items-start sm:items-center gap-2 mb-4">
        <span class="p-1.5 bg-indigo-50 rounded-lg text-indigo-500 shrink-0">
          <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5">
            </path>
          </svg>
        </span>
        <span>Pemetaan Klaster Toko (PCA 2D)</span>
      </h2>
      <div class="relative w-full h-[350px] lg:h-[450px] min-w-0">
        <canvas id="clusterChart"></canvas>
      </div>
    </div>

    {{-- DYNAMIC CSV --}}
    <div
      class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-blue-500 min-w-0">
      <div class="flex flex-col xl:flex-row justify-between xl:items-center mb-6 gap-4">
        <div class="min-w-0">
          <h2
            class="text-xs sm:text-sm font-bold text-gray-800 uppercase tracking-wider flex items-start sm:items-center gap-2">
            <span class="p-1.5 bg-blue-50 rounded-lg text-blue-500 shrink-0"><svg class="w-4 h-4 sm:w-5 sm:h-5"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
              </svg></span>
            <span>Visualisasi Data Dinamis</span>
          </h2>
          <p class="text-[11px] sm:text-xs text-gray-500 mt-1 leading-relaxed">Sistem secara otomatis membaca dan
            menerjemahkan file CSV terbaru.</p>
        </div>
        <div class="w-full xl:w-auto">
          @if ($latestCsv)
            <div
              class="flex items-center gap-2 bg-blue-50 border border-blue-100 px-3 sm:px-4 py-2 rounded-xl sm:rounded-full max-w-full">
              <span class="relative flex h-2.5 w-2.5 shrink-0"><span
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span
                  class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span></span>
              <span class="text-blue-700 text-[11px] sm:text-xs font-bold truncate" title="{{ $latestCsv->title }}">Membaca:
                {{ $latestCsv->title }}</span>
            </div>
          @else
            <span
              class="inline-block bg-gray-50 text-gray-500 text-[11px] sm:text-xs font-bold px-3 sm:px-4 py-2 rounded-xl sm:rounded-full border border-gray-200">Belum
              ada data CSV</span>
          @endif
        </div>
      </div>

      <div
        class="relative w-full h-[300px] sm:h-[350px] lg:h-[400px] flex items-center justify-center bg-slate-50/50 rounded-xl border border-dashed border-gray-200 p-2 sm:p-4 min-w-0"
        id="dynamicChartContainer">
        @if ($latestCsv)
          <canvas id="dynamicCsvChart"></canvas>
        @else
          <div class="text-center text-gray-400 px-4">
            <div
              class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3">
              <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
              </svg>
            </div>
            <p class="text-sm font-medium text-gray-600">Unggah file berformat .CSV</p>
            <p class="text-xs mt-1">Upload di Knowledge Base untuk memunculkan grafik.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const colorPalette = ['#16a34a', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6', '#14b8a6', '#f43f5e'];

      // Inisialisasi Fungsi Chart
      initInternalCharts();
      initTurnoverChart();
      initPythonForecastChart(); // <-- Inisialisasi chart baru otomatis via API
      initClusterChart();
      initDynamicCsvChart();

      function initInternalCharts() {
        const reasonData = @json($reasonStats);
        if (Object.keys(reasonData).length > 0) {
          new Chart(document.getElementById('reasonChart'), {
            type: 'doughnut',
            data: { labels: Object.keys(reasonData), datasets: [{ data: Object.values(reasonData), backgroundColor: colorPalette, borderWidth: 2, borderColor: '#ffffff', hoverOffset: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, boxWidth: 8 } } } }
          });
        }

        const storeData = @json($storeStats);
        if (Object.keys(storeData).length > 0) {
          new Chart(document.getElementById('storeChart'), {
            type: 'bar',
            data: { labels: Object.keys(storeData), datasets: [{ label: 'Jumlah Pengajuan', data: Object.values(storeData), backgroundColor: 'rgba(59, 130, 246, 0.1)', borderColor: '#3b82f6', borderWidth: 2, borderRadius: 6, hoverBackgroundColor: 'rgba(59, 130, 246, 0.2)' }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } }
          });
        }
      }

      function initTurnoverChart() {
        const mergedLabels = @json($mergedLabels ?? []);
        const dataHistorical = @json($finalHistorical ?? []);
        const dataForecast = @json($finalForecast ?? []);

        if (mergedLabels.length > 0) {
          new Chart(document.getElementById('turnoverChart'), {
            type: 'line',
            data: {
              labels: mergedLabels,
              datasets: [
                { label: 'Data Riwayat Nyata (Kg)', data: dataHistorical, backgroundColor: 'rgba(16, 185, 129, 0.1)', borderColor: '#10b981', borderWidth: 3, pointBackgroundColor: '#ffffff', pointBorderColor: '#10b981', fill: true, tension: 0.3 },
                { label: 'Prediksi Tren Prophet (Kg)', data: dataForecast, backgroundColor: 'transparent', borderColor: '#f59e0b', borderWidth: 3, borderDash: [6, 6], pointBackgroundColor: '#ffffff', pointBorderColor: '#f59e0b', fill: false, tension: 0.3 }
              ]
            },
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { display: true, position: 'top' } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } }
          });
        }
      }

      // FUNGSI BARU: Tarik Data API Python via Laravel Proxy & Render Chart Forecast
      async function initPythonForecastChart() {
        try {
          const response = await fetch('{{ route("dashboard.analytics.forecast") }}', {
            method: 'GET',
            headers: { 
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          const result = await response.json();

          if (result.forecast && result.forecast.length > 0 && result.history) {
            let labels_history = result.history.map(item => item.date);
            let dataValues_history = result.history.map(item => item.actual);

            let labels_forecast = result.forecast.map(item => item.date);
            let dataValues_forecast = result.forecast.map(item => item.prediction);

            // 1. Gabungkan semua tanggal untuk sumbu X
            let combinedLabels = [...labels_history, ...labels_forecast];

            // 2. Ganjal array forecast dengan null agar mulainya pas di tanggal prediksi
            let paddedForecast = Array(labels_history.length).fill(null).concat(dataValues_forecast);

            new Chart(document.getElementById('pythonForecastChart'), {
              type: 'line',
              data: {
                labels: combinedLabels,
                // 3. Perbaikan struktur datasets (tidak di-nest)
                datasets: [
                  {
                    label: 'Data Riwayat Nyata (Kg)',
                    data: dataValues_history,
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderColor: '#8b5cf6',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                  },
                  {
                    label: 'Prediksi Output API (Kg)',
                    data: paddedForecast, // Gunakan data yang sudah diganjal null
                    backgroundColor: 'rgba(217, 70, 239, 0.1)',
                    borderColor: '#d946ef',
                    borderWidth: 3,
                    fill: false, // Disarankan false agar transparan dan lebih beda
                    borderDash: [6, 6], // Tambahan opsional: Garis putus-putus untuk prediksi
                    tension: 0.4
                  }
                ]
              },
              options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { display: true, position: 'top' } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4] } }, x: { grid: { display: false } } } }
            });
          }
        } catch (error) {
          console.error("Gagal menarik data dari API Python:", error);
        }
      }

      async function initClusterChart() {
        try {
          const response = await fetch('{{ route("dashboard.analytics.clustering") }}', { 
            method: 'GET', 
            headers: { 
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            } 
          });
          const result = await response.json();

          if (result.data && result.data.length > 0) {
            const clusterGroups = {};
            const colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6']; // Palet warna klaster

            // 1. Kelompokkan data berdasarkan ID cluster
            result.data.forEach(item => {
              if (!clusterGroups[item.cluster]) clusterGroups[item.cluster] = [];
              clusterGroups[item.cluster].push({
                x: item.pca1,
                y: item.pca2,
                storeName: item.store_name // Simpan nama toko untuk tooltip
              });
            });

            // 2. Format menjadi struktur dataset Chart.js
            const datasets = Object.keys(clusterGroups).map((clusterId, index) => {
              return {
                label: `Cluster ${clusterId}`,
                data: clusterGroups[clusterId],
                backgroundColor: colors[index % colors.length],
                borderColor: '#ffffff',
                borderWidth: 1,
                pointRadius: 7,
                pointHoverRadius: 9
              };
            });

            // 3. Render Scatter Chart
            new Chart(document.getElementById('clusterChart'), {
              type: 'scatter',
              data: { datasets: datasets },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  legend: { position: 'top' },
                  tooltip: {
                    callbacks: {
                      // Modifikasi label agar menampilkan nama toko
                      label: function (context) {
                        const point = context.raw;
                        return `${point.storeName} (PCA1: ${point.x.toFixed(2)}, PCA2: ${point.y.toFixed(2)})`;
                      }
                    }
                  }
                },
                scales: {
                  x: {
                    title: { display: true, text: `PCA 1 (${(result.explained_variance.pca1 * 100).toFixed(1)}% Variance)` },
                    grid: { borderDash: [4, 4] }
                  },
                  y: {
                    title: { display: true, text: `PCA 2 (${(result.explained_variance.pca2 * 100).toFixed(1)}% Variance)` },
                    grid: { borderDash: [4, 4] }
                  }
                }
              }
            });
          }
        } catch (error) {
          console.error("Gagal menarik data clustering:", error);
        }
      }

      function initDynamicCsvChart() {
        const latestCsvUrl = @json($latestCsv ? asset('storage/' . $latestCsv->file_path) : null);
        if (latestCsvUrl) {
          Papa.parse(latestCsvUrl, {
            download: true, header: true, skipEmptyLines: true,
            complete: function (results) {
              const data = results.data.filter(row => Object.keys(row).length > 1);
              if (data.length > 0) {
                const keys = Object.keys(data[0]);
                const chartLabels = data.map(row => row[keys[0]]);
                const chartValues = data.map(row => parseFloat(row[keys[1]]) || 0);
                new Chart(document.getElementById('dynamicCsvChart'), {
                  type: 'line',
                  data: { labels: chartLabels, datasets: [{ label: keys[1], data: chartValues, backgroundColor: 'rgba(139, 92, 246, 0.1)', borderColor: '#8b5cf6', borderWidth: 3, fill: true, tension: 0.4 }] },
                  options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false } }
                });
              } else { showError("File CSV tidak memiliki data yang valid untuk dirender."); }
            },
            error: function () { showError("Gagal membaca atau membedah data file CSV."); }
          });
        }
      }

      function showError(message) {
        const container = document.getElementById('dynamicChartContainer');
        if (container) {
          container.innerHTML = `<div class="text-center text-red-500 bg-red-50 p-4 rounded-lg border border-red-100 mx-2"><svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><p class="text-sm font-semibold">${message}</p></div>`;
        }
      }
    });
  </script>
@endsection