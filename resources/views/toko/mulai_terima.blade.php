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
          $hasScannedAtLeastOne = $received > 0;
        @endphp

        <!-- Progress -->
        <div class="w-full lg:w-auto bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
          <div class="text-[10px] sm:text-xs text-gray-500 mb-1">
            Progress Bongkar Muat
          </div>
          <div class="font-bold text-gray-800 text-base sm:text-lg flex items-center gap-1.5 flex-wrap">
            <span id="received-counter">{{ $received }}</span>
            <span class="text-gray-400 font-normal text-xs sm:text-sm">dari</span>
            <span id="total-counter">{{ $total }}</span>
            <span class="text-gray-400 font-normal text-xs sm:text-sm">barang</span>
            
            <svg id="completed-badge-icon" class="w-5 h-5 text-green-500 ml-1 flex-shrink-0 {{ $isCompleted ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>

          <!-- Progress Bar -->
          @if($total > 0)
            <div class="w-full lg:w-48 h-1.5 bg-gray-200 rounded-full mt-2 overflow-hidden">
              <div id="progress-bar-fill" class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ ($received / $total) * 100 }}%"></div>
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
              <div class="flex items-center justify-between gap-2 mb-4">
                <div class="flex items-center gap-2">
                  <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0"></path>
                    </svg>
                    <span>Manifes Pengiriman</span>
                  </h3>
                  <span id="checklist-badge" class="text-[10px] sm:text-xs font-bold bg-white border border-gray-200 px-2 py-1 rounded-lg text-gray-500">
                    {{ $received }}/{{ $total }}
                  </span>
                </div>

                @if(!$isCompleted)
                  <button type="button" id="btn-terima-semua-top" onclick="konfirmasiTerimaSemuaLangsung()" class="{{ $hasScannedAtLeastOne ? '' : 'hidden' }} bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 px-2.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="hidden sm:inline">Terima Semua Sekaligus</span>
                    <span class="sm:hidden">Terima Semua</span>
                  </button>
                @endif
              </div>

              <!-- Notifikasi Wajib Scan Minimal 1 Barcode -->
              <div id="scan-required-notice" class="{{ $hasScannedAtLeastOne ? 'hidden' : '' }} mb-3 p-3 bg-amber-50/90 border border-amber-200/80 rounded-xl flex items-center gap-2.5 text-amber-800 text-xs">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Scan minimal 1 barcode produk fisik untuk memverifikasi dan membuka opsi centang manifes.</span>
              </div>

              <!-- Checklist -->
              <div id="manifest-items-list" class="space-y-2 max-h-[420px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2 custom-scrollbar">
                @foreach($scans as $item)
                  @if($item->received_at != null)
                    <!-- SUDAH DITERIMA -->
                    <div id="item-row-{{ $item->id }}" data-barcode="{{ $item->barcode }}" class="manifest-item is-received bg-white p-3 rounded-xl border border-green-200 shadow-sm flex items-center justify-between gap-3 opacity-80 transition-all duration-300">
                      <div class="flex items-center gap-3 min-w-0">
                        <div class="status-icon w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                        <div class="min-w-0">
                          <p class="text-xs sm:text-sm text-gray-800 font-bold mb-0.5 truncate">
                            {{ $item->barcode }}
                          </p>
                          @if($item->itemName)
                            <span class="bg-gray-100 text-gray-600 text-[9px] sm:text-[10px] px-2 py-0.5 rounded font-mono mr-1">
                              {{ $item->itemName }}
                            </span>
                          @endif
                          <p class="status-text text-[9px] sm:text-[10px] text-gray-500">
                            Diterima: {{ \Carbon\Carbon::parse($item->received_at)->format('H:i:s') }}
                          </p>
                        </div>
                      </div>
                      <span class="text-green-600 text-[10px] font-bold px-2 py-1 bg-green-50 rounded-lg border border-green-200 flex-shrink-0">
                        ✓ Terverifikasi
                      </span>
                    </div>
                  @else
                    <!-- BELUM DITERIMA -->
                    <div id="item-row-{{ $item->id }}" data-barcode="{{ $item->barcode }}" class="manifest-item bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between gap-3 transition-all duration-300">
                      <div class="flex items-center gap-3 min-w-0">
                        <div class="status-icon w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                        </div>
                        <div class="min-w-0">
                          <p class="text-xs sm:text-sm text-gray-800 font-bold truncate">
                            {{ $item->barcode }}
                          </p>
                          @if($item->itemName)
                            <span class="bg-gray-100 text-gray-600 text-[9px] sm:text-[10px] px-2 py-0.5 rounded font-mono mr-1">
                              {{ $item->itemName }}
                            </span>
                          @endif
                          <p class="status-text text-[9px] sm:text-[10px] text-amber-600 font-semibold py-0.5">
                            Menunggu Scan...
                          </p>
                        </div>
                      </div>
                      <button type="button" onclick="centangManual('{{ $item->barcode }}')" class="btn-centang-manual {{ $hasScannedAtLeastOne ? '' : 'hidden' }} bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-700 px-2.5 py-1.5 rounded-lg transition text-xs font-semibold flex items-center gap-1 border border-gray-200 hover:border-green-300 flex-shrink-0" title="Centang Manual">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[10px] font-bold">Centang</span>
                      </button>
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
       HTML5 QR SCANNER — BASE
       ========================================= */
    #qr-reader {
      border: none !important;
      width: 100% !important;
      font-family: inherit !important;
    }

    /* Sembunyikan header bawaan library */
    #qr-reader > img,
    #qr-reader__header_message {
      display: none !important;
    }

    /* Area scan region */
    #qr-reader__scan_region {
      background: #111827 !important;
      border-radius: 0 !important;
    }

    /* Sembunyikan frame/overlay putih bawaan library */
    #qr-reader__scan_region > img,
    #qr-reader__scan_region > div[style*="border"],
    #qr-shaded-region,
    #qr-reader__scan_region canvas {
      display: none !important;
      opacity: 0 !important;
      pointer-events: none !important;
    }

    /* Video selalu full container */
    #qr-reader video {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
    }

    /* =========================================
       DASHBOARD (area bawah kamera)
       ========================================= */
    #qr-reader__dashboard {
      background: transparent !important;
      padding: 0 !important;
      border: none !important;
    }

    #qr-reader__dashboard_section {
      padding: 0 !important;
    }

    /* =========================================
       TOMBOL IZIN KAMERA (Request Camera Permission)
       ========================================= */
    #qr-reader__camera_permission_button {
      display: block !important;
      width: 100% !important;
      max-width: 100% !important;
      margin: 16px 0 8px 0 !important;
      padding: 14px 20px !important;
      background: linear-gradient(135deg, #10b981, #059669) !important;
      color: #ffffff !important;
      border: none !important;
      border-radius: 14px !important;
      font-size: 15px !important;
      font-weight: 700 !important;
      font-family: inherit !important;
      cursor: pointer !important;
      letter-spacing: 0.01em !important;
      box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
      transition: all 0.2s ease !important;
      text-align: center !important;
    }
    #qr-reader__camera_permission_button:hover {
      background: linear-gradient(135deg, #059669, #047857) !important;
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45) !important;
      transform: translateY(-1px) !important;
    }
    #qr-reader__camera_permission_button:active {
      transform: translateY(0) !important;
    }

    /* =========================================
       DROPDOWN SELECT KAMERA
       ========================================= */
    #qr-reader__camera_selection,
    #qr-reader select,
    #qr-reader__dashboard select {
      display: block !important;
      width: 100% !important;
      min-width: 0 !important;
      box-sizing: border-box !important;
      margin: 12px 0 !important;
      padding: 14px 16px !important;
      background: #f8fafc !important;
      color: #1e293b !important;
      border: 2px solid #e2e8f0 !important;
      border-radius: 12px !important;
      font-size: 15px !important;
      font-weight: 600 !important;
      font-family: inherit !important;
      cursor: pointer !important;
      appearance: auto !important;
      -webkit-appearance: menulist !important;
      box-shadow: 0 1px 4px rgba(0,0,0,0.06) !important;
      transition: border-color 0.2s !important;
      height: auto !important;
      line-height: 1.4 !important;
    }
    #qr-reader__camera_selection:focus,
    #qr-reader select:focus {
      outline: none !important;
      border-color: #10b981 !important;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }

    /* Label "Select Camera" di atas select */
    #qr-reader__dashboard_section_csr span,
    #qr-reader__dashboard_section_csr label {
      display: block !important;
      font-size: 11px !important;
      font-weight: 600 !important;
      color: #64748b !important;
      text-transform: uppercase !important;
      letter-spacing: 0.06em !important;
      margin-bottom: 6px !important;
    }

    /* =========================================
       TOMBOL START / STOP SCANNING
       ========================================= */
    #qr-reader__dashboard_section_csr button,
    #qr-reader__dashboard_section_swaplink {
      display: block !important;
      width: 100% !important;
      margin: 10px 0 !important;
      padding: 13px 20px !important;
      background: linear-gradient(135deg, #1e293b, #334155) !important;
      color: #ffffff !important;
      border: none !important;
      border-radius: 12px !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      font-family: inherit !important;
      cursor: pointer !important;
      text-align: center !important;
      box-shadow: 0 3px 10px rgba(30, 41, 59, 0.25) !important;
      transition: all 0.2s ease !important;
      text-decoration: none !important;
    }
    #qr-reader__dashboard_section_csr button:hover,
    #qr-reader__dashboard_section_swaplink:hover {
      background: linear-gradient(135deg, #0f172a, #1e293b) !important;
      box-shadow: 0 5px 16px rgba(30, 41, 59, 0.35) !important;
      transform: translateY(-1px) !important;
    }

    /* Tombol Stop berwarna merah */
    #qr-reader__dashboard_section_csr button[id*="stop"],
    #qr-reader__stop_button {
      background: linear-gradient(135deg, #ef4444, #dc2626) !important;
      box-shadow: 0 3px 10px rgba(239, 68, 68, 0.25) !important;
    }
    #qr-reader__dashboard_section_csr button[id*="stop"]:hover {
      background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    }

    /* Sembunyikan link "Or enter image URL" atau sejenisnya */
    #qr-reader__dashboard_section_fsr,
    #qr-reader__filescan_input {
      display: none !important;
    }

    /* =========================================
       STATUS TEXT KAMERA
       ========================================= */
    #qr-reader__status_span {
      display: block !important;
      font-size: 11px !important;
      color: #94a3b8 !important;
      font-weight: 500 !important;
      text-align: center !important;
      margin: 4px 0 8px 0 !important;
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
      #qr-reader__scan_region {
        min-height: 220px;
      }
      #qr-reader__camera_permission_button {
        font-size: 14px !important;
        padding: 12px 16px !important;
      }
      #qr-reader__dashboard_section_csr button {
        font-size: 13px !important;
        padding: 12px 16px !important;
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
      // =========================================
      // AUDIO SYNTHESIS FEEDBACK (Web Audio API)
      // =========================================
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      let audioCtx = null;

      function getAudioContext() {
        if (!audioCtx && AudioContext) {
          audioCtx = new AudioContext();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
          audioCtx.resume();
        }
        return audioCtx;
      }

      function playAudioSuccess() {
        try {
          const ctx = getAudioContext();
          if (!ctx) return;
          const osc = ctx.createOscillator();
          const gain = ctx.createGain();
          osc.type = 'sine';
          osc.frequency.setValueAtTime(880, ctx.currentTime);
          osc.frequency.exponentialRampToValueAtTime(1320, ctx.currentTime + 0.12);
          gain.gain.setValueAtTime(0.3, ctx.currentTime);
          gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.start();
          osc.stop(ctx.currentTime + 0.15);
        } catch (e) {
          console.warn("Audio success error:", e);
        }
      }

      function playAudioWarning() {
        try {
          const ctx = getAudioContext();
          if (!ctx) return;
          const osc = ctx.createOscillator();
          const gain = ctx.createGain();
          osc.type = 'sawtooth';
          osc.frequency.setValueAtTime(320, ctx.currentTime);
          osc.frequency.setValueAtTime(240, ctx.currentTime + 0.1);
          gain.gain.setValueAtTime(0.2, ctx.currentTime);
          gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.start();
          osc.stop(ctx.currentTime + 0.25);
        } catch (e) {
          console.warn("Audio warning error:", e);
        }
      }

      // =========================================
      // SWEETALERT TOAST
      // =========================================
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });

      let processScan = null;
      let prosesTerimaSatu = null;
      let prosesTerimaSemua = null;
      let html5QrcodeScanner = null;

      function getUnreceivedCount() {
        return document.querySelectorAll('.manifest-item:not(.is-received)').length;
      }

      function unlockChecklistActions() {
        const topBtn = document.getElementById('btn-terima-semua-top');
        if (topBtn) topBtn.classList.remove('hidden');

        document.querySelectorAll('.btn-centang-manual').forEach(btn => {
          btn.classList.remove('hidden');
        });

        const notice = document.getElementById('scan-required-notice');
        if (notice) notice.classList.add('hidden');
      }

      // =========================================
      // CENTANG MANUAL (GLOBAL PER ITEM)
      // =========================================
      window.centangManual = function(barcode) {
        Swal.fire({
          title: 'Centang Manual',
          text: 'Tandai barang "' + barcode + '" sebagai diterima?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#10b981',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Ya, Tandai Diterima',
          cancelButtonText: 'Batal'
        }).then((res) => {
          if (res.isConfirmed && typeof prosesTerimaSatu === 'function') {
            prosesTerimaSatu(barcode);
          }
        });
      };

      // =========================================
      // TERIMA SEMUA LANGSUNG (TOMBOL HEADER)
      // =========================================
      window.konfirmasiTerimaSemuaLangsung = function() {
        const unreceivedCount = getUnreceivedCount();
        if (unreceivedCount === 0) {
          Toast.fire({ icon: 'info', title: 'Semua barang sudah diterima.' });
          return;
        }

        Swal.fire({
          icon: 'question',
          title: 'Terima Semua Barang Sekaligus?',
          html: `Terdapat <b>${unreceivedCount}</b> barang yang belum diterima.<br>Apakah Anda yakin ingin menandai seluruh barang sebagai diterima sekarang?`,
          showCancelButton: true,
          confirmButtonText: 'Ya, Terima Semua',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#10b981',
          cancelButtonColor: '#6b7280'
        }).then((res) => {
          if (res.isConfirmed && typeof prosesTerimaSemua === 'function') {
            prosesTerimaSemua('');
          }
        });
      };

      document.addEventListener('DOMContentLoaded', function () {
        let lastScannedCode = null;
        let lastScanTime = 0;
        const SCAN_COOLDOWN_MS = 1500;
        let isProcessingAjax = false;

        html5QrcodeScanner = new Html5QrcodeScanner(
          "qr-reader",
          {
            fps: 10,
            rememberLastUsedCamera: false,

            formatsToSupport: [
              Html5QrcodeSupportedFormats.QR_CODE,
              Html5QrcodeSupportedFormats.CODE_128,
              Html5QrcodeSupportedFormats.CODE_39,
              Html5QrcodeSupportedFormats.EAN_13,
              Html5QrcodeSupportedFormats.EAN_8,
              Html5QrcodeSupportedFormats.UPC_A,
              Html5QrcodeSupportedFormats.UPC_E,
              Html5QrcodeSupportedFormats.ITF
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
           UPDATE DOM CHECKLIST & PROGRESS
           ========================================= */
        function updateItemInChecklist(data) {
          const barcode = data.barcode;
          const scanId = data.scan_id;
          const scannedAt = data.scanned_at || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
          const receivedCount = data.received_count;
          const totalCount = data.total_count;
          const isCompleted = data.is_completed;

          // Cari baris item berdasarkan ID scan terlebih dahulu, atau barcode yang belum diterima
          let row = document.getElementById('item-row-' + scanId);
          if (!row) {
            const rows = document.querySelectorAll(`.manifest-item[data-barcode="${barcode}"]`);
            for (let r of rows) {
              if (!r.classList.contains('is-received')) {
                row = r;
                break;
              }
            }
          }

          if (row) {
            row.classList.add('is-received', 'opacity-80', 'border-green-200');
            row.classList.remove('border-gray-200');

            // Update icon status
            const iconContainer = row.querySelector('.status-icon');
            if (iconContainer) {
              iconContainer.className = 'status-icon w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0';
              iconContainer.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            }

            // Update teks status
            const statusText = row.querySelector('.status-text');
            if (statusText) {
              statusText.className = 'status-text text-[9px] sm:text-[10px] text-gray-500';
              statusText.textContent = 'Diterima: ' + scannedAt;
            }

            // Ganti tombol centang manual menjadi badge terverifikasi
            const manualBtn = row.querySelector('.btn-centang-manual');
            if (manualBtn) {
              const badge = document.createElement('span');
              badge.className = 'text-green-600 text-[10px] font-bold px-2 py-1 bg-green-50 rounded-lg border border-green-200 flex-shrink-0';
              badge.textContent = '✓ Terverifikasi';
              manualBtn.replaceWith(badge);
            }

            // Efek highlight baris
            row.classList.add('bg-green-50');
            setTimeout(() => row.classList.remove('bg-green-50'), 1500);

            // Pindahkan baris yang sudah diterima ke atas daftar agar rapi
            const listContainer = document.getElementById('manifest-items-list');
            if (listContainer && listContainer.firstChild) {
              listContainer.insertBefore(row, listContainer.firstChild);
            }
          }

          // Update Counter & Progress Bar
          if (receivedCount !== undefined && totalCount !== undefined) {
            const receivedEl = document.getElementById('received-counter');
            const totalEl = document.getElementById('total-counter');
            const badgeEl = document.getElementById('checklist-badge');
            const progressFill = document.getElementById('progress-bar-fill');

            if (receivedEl) receivedEl.textContent = receivedCount;
            if (totalEl) totalEl.textContent = totalCount;
            if (badgeEl) badgeEl.textContent = `${receivedCount}/${totalCount}`;
            if (progressFill && totalCount > 0) {
              progressFill.style.width = ((receivedCount / totalCount) * 100) + '%';
            }
          }

          // Buka aksi centang karena minimal 1 scan telah berhasil
          unlockChecklistActions();

          // Jika semua barang telah diterima
          if (isCompleted) {
            handleAllCompleted();
          }
        }

        /* =========================================
           HANDLE ALL COMPLETED
           ========================================= */
        function handleAllCompleted() {
          const badgeIcon = document.getElementById('completed-badge-icon');
          if (badgeIcon) badgeIcon.classList.remove('hidden');

          const topBtn = document.getElementById('btn-terima-semua-top');
          if (topBtn) topBtn.style.display = 'none';

          if (html5QrcodeScanner) {
            try {
              html5QrcodeScanner.clear();
            } catch (e) {}
          }

          Swal.fire({
            icon: 'success',
            title: 'Semua Barang Telah Diterima!',
            text: 'Seluruh barang dalam pengiriman ini telah lengkap diverifikasi.',
            confirmButtonText: 'Kembali ke Daftar Pengiriman',
            confirmButtonColor: '#10b981',
            allowOutsideClick: false
          }).then(() => {
            window.location.href = "{{ url('/toko/penerimaan') }}";
          });
        }

        /* =========================================
           PROSES TERIMA SEMUA BARANG (BATCH)
           ========================================= */
        prosesTerimaSemua = function(sampleBarcode) {
          isProcessingAjax = true;

          Swal.fire({
            title: 'Menerima Semua Barang...',
            text: 'Sedang memproses seluruh manifes dan stok toko...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          fetch('{{ url('/toko/penerimaan/terima-semua') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              barcode: sampleBarcode || '',
              logistic_id: '{{ $logistic->id_logistic }}'
            })
          })
          .then(res => res.json().then(data => ({ status: res.status, body: data })))
          .then(result => {
            isProcessingAjax = false;
            Swal.close();

            if (result.status === 200 && result.body.status === 'success') {
              playAudioSuccess();

              // Ubah SEMUA item checklist menjadi centang hijau
              const unreceivedRows = document.querySelectorAll('.manifest-item:not(.is-received)');
              const scannedAt = result.body.data.scanned_at;
              unreceivedRows.forEach(row => {
                row.classList.add('is-received', 'opacity-80', 'border-green-200');
                row.classList.remove('border-gray-200');

                const icon = row.querySelector('.status-icon');
                if (icon) {
                  icon.className = 'status-icon w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0';
                  icon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                }

                const statusText = row.querySelector('.status-text');
                if (statusText) {
                  statusText.className = 'status-text text-[9px] sm:text-[10px] text-gray-500';
                  statusText.textContent = 'Diterima: ' + scannedAt;
                }

                const manualBtn = row.querySelector('.btn-centang-manual');
                if (manualBtn) {
                  const badge = document.createElement('span');
                  badge.className = 'text-green-600 text-[10px] font-bold px-2 py-1 bg-green-50 rounded-lg border border-green-200 flex-shrink-0';
                  badge.textContent = '✓ Terverifikasi';
                  manualBtn.replaceWith(badge);
                }
              });

              // Update counter & progress bar ke 100%
              const total = result.body.data.total_count;
              const receivedEl = document.getElementById('received-counter');
              const totalEl = document.getElementById('total-counter');
              const badgeEl = document.getElementById('checklist-badge');
              const progressFill = document.getElementById('progress-bar-fill');

              if (receivedEl) receivedEl.textContent = total;
              if (totalEl) totalEl.textContent = total;
              if (badgeEl) badgeEl.textContent = `${total}/${total}`;
              if (progressFill) progressFill.style.width = '100%';

              handleAllCompleted();
            } else {
              playAudioWarning();
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: result.body.message || 'Terjadi kesalahan sistem.'
              });
            }
          })
          .catch(err => {
            isProcessingAjax = false;
            console.error("Terima semua error:", err);
            Swal.fire({
              icon: 'error',
              title: 'Error Jaringan',
              text: 'Terjadi kesalahan saat menghubungi server.'
            });
          });
        };

        /* =========================================
           PROSES TERIMA 1 BARANG (SINGLE)
           ========================================= */
        prosesTerimaSatu = function(scannedCode) {
          isProcessingAjax = true;

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
            isProcessingAjax = false;

            if (result.status === 200 || result.status === 201 || result.body.status === 'success') {
              playAudioSuccess();
              Toast.fire({
                icon: 'success',
                title: 'Diterima: ' + scannedCode
              });

              updateItemInChecklist(result.body.data);

            } else if (result.status === 400 || result.body.status === 'warning') {
              playAudioWarning();
              Toast.fire({
                icon: 'warning',
                title: result.body.message || 'Sudah Diterima'
              });
            } else {
              playAudioWarning();
              Toast.fire({
                icon: 'error',
                title: result.body.message || 'Ditolak: Tidak ada di manifes'
              });
            }
          })
          .catch(error => {
            isProcessingAjax = false;
            playAudioWarning();
            logDebug(`Fetch Error: ${error.message}`, 'error');
            Toast.fire({
              icon: 'error',
              title: 'Kesalahan jaringan saat verifikasi'
            });
          });
        };

        /* =========================================
           SCAN HANDLER UTAMA (OPSI A: KONFIRMASI)
           ========================================= */
        processScan = function(scannedCode, isManual = false) {
          scannedCode = String(scannedCode).trim();
          if (!scannedCode) return;

          const now = Date.now();
          if (!isManual && scannedCode === lastScannedCode && (now - lastScanTime < SCAN_COOLDOWN_MS)) {
            return;
          }

          if (isProcessingAjax) {
            logDebug('Sedang memproses scan sebelumnya...', 'warn');
            return;
          }

          lastScannedCode = scannedCode;
          lastScanTime = now;

          // Efek visual scanner
          const scanContainer = document.getElementById('qr-reader');
          if (scanContainer) {
            scanContainer.classList.add('ring-4', 'ring-emerald-400');
            setTimeout(() => scanContainer.classList.remove('ring-4', 'ring-emerald-400'), 350);
          }

          // Cek keberadaan di manifes
          const matchingUnreceived = document.querySelector(`.manifest-item:not(.is-received)[data-barcode="${scannedCode}"]`);
          const matchingReceived = document.querySelector(`.manifest-item.is-received[data-barcode="${scannedCode}"]`);
          const unreceivedCount = getUnreceivedCount();

          if (!matchingUnreceived) {
            if (matchingReceived) {
              playAudioWarning();
              Toast.fire({ icon: 'warning', title: 'Semua barang dengan barcode ini sudah diterima!' });
            } else {
              playAudioWarning();
              Toast.fire({ icon: 'error', title: 'Barcode ' + scannedCode + ' tidak ada di manifes ini!' });
            }
            return;
          }

          // Jika ada lebih dari 1 barang yang belum diterima -> Munculkan Dialog Opsi A
          if (unreceivedCount > 1) {
            isProcessingAjax = true;
            playAudioSuccess();

            Swal.fire({
              icon: 'question',
              title: 'Konfirmasi Penerimaan Barang',
              html: `Barcode <b>"${scannedCode}"</b> cocok dengan pengiriman ini!<br>Terdapat <b>${unreceivedCount}</b> barang yang belum diterima.<br><br>Terima semua barang dalam pengiriman ini sekaligus?`,
              showCancelButton: true,
              showDenyButton: true,
              confirmButtonText: 'Ya, Terima Semua Barang',
              denyButtonText: 'Tidak, lakukan cek secara manual',
              cancelButtonText: 'Batal',
              confirmButtonColor: '#10b981',
              denyButtonColor: '#4b5563',
              cancelButtonColor: '#9ca3af',
              allowOutsideClick: false
            }).then((result) => {
              isProcessingAjax = false;

              if (result.isConfirmed) {
                prosesTerimaSemua(scannedCode);
              } else if (result.isDenied) {
                // Proses 1 barcode sampel ini saja, sisa barang tetap belum diterima
                prosesTerimaSatu(scannedCode);
                unlockChecklistActions();
                Toast.fire({
                  icon: 'info',
                  title: '1 barang diterima. Opsi centang checklist telah dibuka.'
                });
              }
            });
          } else {
            // Hanya ada 1 barang tersisa, langsung proses terima
            prosesTerimaSatu(scannedCode);
          }
        };

        /* =========================================
           SCAN SUCCESS HANDLER
           ========================================= */
        function onScanSuccess(decodedText, decodedResult) {
          logDebug(`Scan captured: ${decodedText}`);
          processScan(decodedText, false);
        }

        /* =========================================
           SCAN FAILURE HANDLER
           ========================================= */
        function onScanFailure(error) {
          // Diamkan logging failure frame scanner
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
           MUTATION OBSERVER — PAKSA STYLE ELEMEN LIBRARY
           ========================================= */
        function applyForcedScannerStyles() {
          // Paksa style semua <select> dalam qr-reader
          document.querySelectorAll('#qr-reader select, #qr-reader__camera_selection').forEach(el => {
            el.style.cssText = [
              'display: block',
              'width: 100%',
              'min-width: 0',
              'box-sizing: border-box',
              'margin: 12px 0',
              'padding: 14px 16px',
              'background: #f8fafc',
              'color: #1e293b',
              'border: 2px solid #e2e8f0',
              'border-radius: 12px',
              'font-size: 15px',
              'font-weight: 600',
              'font-family: inherit',
              'cursor: pointer',
              'height: auto',
              'line-height: 1.4',
              'box-shadow: 0 1px 4px rgba(0,0,0,0.06)',
              'transition: border-color 0.2s'
            ].join(' !important; ') + ' !important';
          });

          // Paksa style camera permission button
          const permBtn = document.getElementById('qr-reader__camera_permission_button');
          if (permBtn) {
            permBtn.style.cssText = [
              'display: block',
              'width: 100%',
              'box-sizing: border-box',
              'margin: 16px 0 8px 0',
              'padding: 14px 20px',
              'background: linear-gradient(135deg, #10b981, #059669)',
              'color: #ffffff',
              'border: none',
              'border-radius: 14px',
              'font-size: 15px',
              'font-weight: 700',
              'font-family: inherit',
              'cursor: pointer',
              'text-align: center',
              'box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35)'
            ].join(' !important; ') + ' !important';
          }

          // Paksa style semua button dalam dashboard (kecuali yg sudah di-handle)
          document.querySelectorAll('#qr-reader__dashboard button').forEach(btn => {
            if (btn.id === 'qr-reader__camera_permission_button') return;
            const isStop = btn.textContent.toLowerCase().includes('stop');
            const bg = isStop
              ? 'linear-gradient(135deg, #ef4444, #dc2626)'
              : 'linear-gradient(135deg, #1e293b, #334155)';
            btn.style.cssText = [
              'display: block',
              'width: 100%',
              'box-sizing: border-box',
              'margin: 10px 0',
              'padding: 13px 20px',
              `background: ${bg}`,
              'color: #ffffff',
              'border: none',
              'border-radius: 12px',
              'font-size: 14px',
              'font-weight: 700',
              'font-family: inherit',
              'cursor: pointer',
              'text-align: center',
              'box-shadow: 0 3px 10px rgba(30, 41, 59, 0.25)'
            ].join(' !important; ') + ' !important';
          });
        }

        // Jalankan observer untuk mendeteksi perubahan DOM library
        const qrReaderEl = document.getElementById('qr-reader');
        if (qrReaderEl) {
          const observer = new MutationObserver(() => {
            applyForcedScannerStyles();
          });
          observer.observe(qrReaderEl, { childList: true, subtree: true });
          // Jalankan sekali langsung
          applyForcedScannerStyles();
          // Dan 500ms setelah render selesai
          setTimeout(applyForcedScannerStyles, 500);
          setTimeout(applyForcedScannerStyles, 1500);
        }

        /* =========================================
           MANUAL INPUT FORM
           ========================================= */
        const manualForm = document.getElementById('manual-scan-form');
        const manualInput = document.getElementById('manual-resi');

        if (manualForm) {
          manualForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const input = manualInput.value.trim();
            if (input !== '') {
              processScan(input, true);
              manualInput.value = '';
            }
          });
        }
      });
    </script>
  @endif

@endsection