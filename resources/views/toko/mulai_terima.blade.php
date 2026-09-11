@extends('layouts.app')

@section('content')

  <script src="https://unpkg.com/html5-qrcode"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <div class="w-full space-y-4 sm:space-y-6">
    <!-- Tombol Kembali -->
    <a href="{{ url('/toko/penerimaan') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors py-1">
      <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
      Kembali ke Daftar Pengiriman
    </a>

    <!-- Container Utama -->
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
      <!-- HEADER -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 sm:gap-5 mb-5 sm:mb-6">
        <div class="min-w-0">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
            Scan Barang Pengiriman
          </h1>
          <p class="text-xs sm:text-sm text-gray-500 mt-1">
            ID Logistik:
            <span class="font-mono font-bold text-gray-800 break-all">
              {{ $logistic->id_logistic }}
            </span>
          </p>
        </div>

        @php
          $total = count($scans);
          $received = $scans->whereNotNull('received_at')->count();
          $isCompleted = $total > 0 && $total == $received;
        @endphp

        <!-- Progress -->
        <div class="w-full lg:w-auto bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
          <div class="text-[10px] sm:text-xs text-gray-500 mb-1">
            Progress Bongkar Muat
          </div>
          <div class="font-bold text-gray-800 text-base sm:text-lg flex items-center gap-1.5 flex-wrap">
            <span>{{ $received }}</span>
            <span class="text-gray-400 font-normal text-xs sm:text-sm">dari</span>
            <span>{{ $total }}</span>
            <span class="text-gray-400 font-normal text-xs sm:text-sm">barang</span>
            
            @if($isCompleted)
              <svg class="w-5 h-5 text-green-500 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            @endif
          </div>

          <!-- Progress Bar -->
          @if($total > 0)
            <div class="w-full lg:w-48 h-1.5 bg-gray-200 rounded-full mt-2 overflow-hidden">
              <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ ($received / $total) * 100 }}%"></div>
            </div>
          @endif
        </div>
      </div>

      <!-- JIKA SUDAH SELESAI -->
      @if($isCompleted)
        <div class="bg-green-50 text-green-700 p-5 sm:p-6 rounded-2xl flex flex-col items-center justify-center text-center border border-green-200">
          <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-500 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <h3 class="text-lg sm:text-xl font-bold mb-2">
            Semua Barang Telah Diterima!
          </h3>
          <p class="text-xs sm:text-sm opacity-90 max-w-md leading-relaxed">
            Pengiriman ini sudah dibongkar sepenuhnya. Status logistik telah otomatis diperbarui menjadi selesai.
          </p>
          <a href="{{ url('/toko/penerimaan') }}" class="mt-5 sm:mt-6 bg-green-600 text-white px-5 sm:px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold hover:bg-green-700 transition shadow-sm">
            Kembali ke Daftar
          </a>
        </div>
      @else
        <!-- CONTENT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-8">
          
          <!-- KOLOM SCANNER -->
          <div class="flex flex-col items-center justify-start w-full">
            <div class="w-full max-w-md">
              <!-- Scanner -->
              <div class="relative w-full aspect-square sm:aspect-[4/3] lg:aspect-square rounded-2xl overflow-hidden bg-gray-100 border-4 border-gray-100 shadow-inner">
                <div id="qr-reader" class="w-full h-full"></div>
                
                <!-- Placeholder -->
                <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-white">
                  <svg class="w-12 h-12 sm:w-16 sm:h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                  </svg>
                  <span class="text-xs sm:text-sm font-medium">
                    Memuat Kamera...
                  </span>
                </div>
              </div>

              <!-- ATAU -->
              <div class="flex items-center my-4 sm:my-5">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="px-3 text-[10px] sm:text-xs font-semibold text-gray-400 uppercase">ATAU</span>
                <div class="flex-grow border-t border-gray-200"></div>
              </div>

              <!-- MANUAL SCAN -->
              <form id="manual-scan-form" class="flex flex-col sm:flex-row gap-2">
                <input type="text" id="manual-resi" placeholder="Masukkan Barcode Produk" autocomplete="off" class="w-full min-w-0 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium placeholder-gray-400">
                <button type="submit" class="w-full sm:w-auto bg-gray-800 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-700 active:bg-gray-900 transition shadow-sm whitespace-nowrap">
                  Scan
                </button>
              </form>
              <p class="text-[10px] sm:text-xs text-gray-400 text-center mt-3">
                Arahkan kamera ke barcode produk atau masukkan kode secara manual.
              </p>
            </div>
          </div>

          <!-- KOLOM CHECKLIST -->
          <div class="w-full">
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 sm:p-5">
              <!-- Header Checklist -->
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                  <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0"></path>
                  </svg>
                  <span>Manifes Pengiriman</span>
                </h3>
                <span class="text-[10px] sm:text-xs font-bold bg-white border border-gray-200 px-2 py-1 rounded-lg text-gray-500">
                  {{ $received }}/{{ $total }}
                </span>
              </div>

              <!-- Checklist -->
              <div class="space-y-2 max-h-[420px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2 custom-scrollbar">
                @foreach($scans as $item)
                  @if($item->received_at != null)
                    <!-- SUDAH DITERIMA -->
                    <div class="bg-white p-3 rounded-xl border border-green-200 shadow-sm flex items-center justify-between gap-3 opacity-75">
                      <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        <div class="min-w-0">
                          <p class="text-xs sm:text-sm text-gray-800 font-bold mb-0.5 truncate">
                            {{ $item->barcode }}
                          </p>
                          <p class="text-[9px] sm:text-[10px] text-gray-500">
                            Diterima: {{ \Carbon\Carbon::parse($item->received_at)->format('H:i:s') }}
                          </p>
                        </div>
                      </div>
                      @if($item->barcode)
                        <span class="bg-gray-100 text-gray-600 text-[9px] sm:text-[10px] px-2 py-1 rounded font-mono flex-shrink-0">
                          {{ $item->barcode }}
                        </span>
                      @endif
                    </div>
                  @else
                    <!-- BELUM DITERIMA -->
                    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between gap-3">
                      <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center flex-shrink-0">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                        </div>
                        <div class="min-w-0">
                          <p class="text-xs sm:text-sm text-gray-800 font-bold truncate">
                            {{ $item->barcode }}
                          </p>
                          @if($item->itemName)
                            <span class="bg-gray-100 text-gray-600 text-[9px] sm:text-[10px] px-2 py-1 rounded font-mono flex-shrink-0">
                              {{ $item->itemName }}
                            </span>
                          @endif
                          <p class="text-[9px] sm:text-[10px] text-amber-600 font-semibold py-1">
                            Menunggu Scan...
                          </p>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>

        </div>
      @endif
    </div>
  </div>

  <style>
    /* =========================================
       HTML5 QR SCANNER
       ========================================= */
    #qr-reader {
      border: none !important;
      width: 100% !important;
    }
    #qr-reader__dashboard_section_csr span {
      font-size: 12px;
    }
    #qr-reader__dashboard_section_csr button {
      background: #10b981;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }
    #qr-reader__scan_region {
      background: #f9fafb;
    }
    /* Agar video tidak keluar container */
    #qr-reader video {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
    }

    /* =========================================
       SCROLLBAR
       ========================================= */
    .custom-scrollbar::-webkit-scrollbar {
      width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* =========================================
       MOBILE
       ========================================= */
    @media (max-width: 640px) {
      #qr-reader__dashboard {
        padding: 6px !important;
      }
      #qr-reader__dashboard_section_csr {
        margin-top: 5px !important;
      }
      #qr-reader__scan_region {
        min-height: 220px;
      }
    }

    /* =========================================
       TABLET
       ========================================= */
    @media (min-width: 641px) and (max-width: 1023px) {
      #qr-reader__scan_region {
        min-height: 280px;
      }
    }
  </style>

  @if(!$isCompleted)
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        let isScanning = false;

        const html5QrcodeScanner = new Html5QrcodeScanner(
          "qr-reader",
          {
            fps: 10,
            rememberLastUsedCamera: false,
            qrbox: function (viewfinderWidth, viewfinderHeight) {
              /*
               * Ukuran kotak scanner menyesuaikan layar.
               * HP -> lebih kecil
               * Tablet/Desktop -> lebih besar
               */
              let width = Math.min(viewfinderWidth * 0.80, 300);
              let height = Math.min(viewfinderHeight * 0.35, 150);
              return {
                width: Math.floor(width),
                height: Math.floor(height)
              };
            },
            formatsToSupport: [
              Html5QrcodeSupportedFormats.QR_CODE,
              // Html5QrcodeSupportedFormats.CODE_128,
              // Html5QrcodeSupportedFormats.CODE_39,
              // Html5QrcodeSupportedFormats.EAN_13,
              // Html5QrcodeSupportedFormats.EAN_8,
              // Html5QrcodeSupportedFormats.UPC_A,
              // Html5QrcodeSupportedFormats.UPC_E,
              // Html5QrcodeSupportedFormats.ITF,
            ]
          },
          false
        );

        /* =========================================
           DEBUG LOGGER
           ========================================= */
        function logDebug(message, type = 'info') {
          const time = new Date().toLocaleTimeString('id-ID', { hour12: false });
          const logMessage = `[${time}] Scanner: ${message}`;
          
          if (type === 'error') {
            console.error(logMessage);
          } else if (type === 'warn') {
            console.warn(logMessage);
          } else {
            console.log(logMessage);
          }
        }

        /* =========================================
           PROCESS SCAN
           ========================================= */
        function processScan(scannedCode) {
          scannedCode = String(scannedCode).trim();
          logDebug(`Processing code: ${scannedCode}`);

          if (isScanning) {
            logDebug('Skipped, still processing previous scan.', 'warn');
            return;
          }

          if (!scannedCode) {
            return;
          }

          isScanning = true;

          Swal.fire({
            title: 'Mencocokkan...',
            text: 'Memverifikasi barcode: ' + scannedCode,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          fetch('{{ url('/toko/penerimaan/scan') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              barcode: scannedCode,
              logistic_id: '{{ $logistic->id_logistic }}'
            })
          })
          .then(response => response.json().then(data => ({
            status: response.status,
            body: data
          })))
          .then(result => {
            isScanning = false;

            if (result.status === 200 || result.status === 201 || result.body.status === 'success') {
              logDebug(`Server Response: Success - ${result.body.message}`);
              Swal.fire({
                icon: 'success',
                title: 'Diterima!',
                text: result.body.message,
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                window.location.reload();
              });
            } else if (result.status === 400 || result.body.status === 'warning') {
              logDebug(`Server Response: Warning - ${result.body.message}`, 'warn');
              Swal.fire({
                icon: 'warning',
                title: 'Sudah Diterima',
                text: result.body.message
              });
            } else {
              logDebug(`Server Response: Error - ${result.body.message}`, 'error');
              Swal.fire({
                icon: 'error',
                title: 'Ditolak',
                text: result.body.message
              });
            }
          })
          .catch(error => {
            isScanning = false;
            logDebug(`Fetch Error: ${error.message}`, 'error');
            Swal.fire({
              icon: 'error',
              title: 'Error Jaringan',
              text: 'Terjadi kesalahan saat menghubungi server.'
            });
          });
        }

        /* =========================================
           SCAN SUCCESS
           ========================================= */
        function onScanSuccess(decodedText, decodedResult) {
          logDebug(`Scan Success! Captured: ${decodedText}`);
          processScan(decodedText);
        }

        /* =========================================
           SCAN FAILURE
           ========================================= */
        function onScanFailure(error) {
          // Jangan console.log karena fungsi ini
          // dipanggil berkali-kali ketika kamera mencari barcode.
        }

        /* =========================================
           START SCANNER
           ========================================= */
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        /* =========================================
           HIDE PLACEHOLDER
           ========================================= */
        setTimeout(() => {
          const placeholder = document.getElementById('scanner-placeholder');
          if (placeholder) {
            placeholder.style.display = 'none';
          }
        }, 1000);

        /* =========================================
           MANUAL SCAN
           ========================================= */
        const manualForm = document.getElementById('manual-scan-form');
        const manualInput = document.getElementById('manual-resi');

        manualForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const input = manualInput.value.trim();
          
          if (input !== '') {
            processScan(input);
            manualInput.value = '';
          }
        });
      });
    </script>
  @endif

@endsection