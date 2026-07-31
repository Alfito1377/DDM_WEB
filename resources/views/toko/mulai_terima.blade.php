@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/html5-qrcode"></script>

<div class="space-y-6">
    <!-- Tombol Kembali -->
    <a href="{{ url('/toko/penerimaan') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Daftar Pengiriman
    </a>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Scan Barang Pengiriman</h1>
                <p class="text-sm text-gray-500 mt-1">ID Logistik: <span class="font-mono font-bold text-gray-800">{{ $logistic->id_logistic }}</span></p>
            </div>
            
            @php
                $total = count($scans);
                $received = $scans->whereNotNull('received_at')->count();
                $isCompleted = $total > 0 && $total == $received;
            @endphp
            
            <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">Progress Bongkar Muat</div>
                <div class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    {{ $received }} <span class="text-gray-400 font-normal text-sm">dari</span> {{ $total }} <span class="text-gray-400 font-normal text-sm">barang</span>
                    @if($isCompleted)
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
            </div>
        </div>

        @if($isCompleted)
            <div class="bg-green-50 text-green-700 p-6 rounded-2xl mb-6 flex flex-col items-center justify-center text-center border border-green-200">
                <svg class="w-16 h-16 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xl font-bold mb-2">Semua Barang Telah Diterima!</h3>
                <p class="text-sm opacity-90 max-w-md">Pengiriman ini sudah dibongkar sepenuhnya. Status logistik telah otomatis diperbarui menjadi selesai.</p>
                <a href="{{ url('/toko/penerimaan') }}" class="mt-6 bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm">
                    Kembali ke Daftar
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Kolom Kiri: Scanner -->
                <div class="flex flex-col items-center justify-start space-y-4">
                    <div class="w-full max-w-sm rounded-2xl overflow-hidden bg-black/5 border-4 border-gray-100 relative shadow-inner aspect-square flex items-center justify-center">
                        <div id="qr-reader" class="w-full h-full"></div>
                        <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 pointer-events-none bg-white">
                            <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            <span class="text-sm font-medium">Memuat Kamera...</span>
                        </div>
                    </div>

                    <div class="w-full max-w-sm">
                        <div class="flex items-center my-4">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="px-3 text-xs font-semibold text-gray-400 uppercase">ATAU</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>
                        
                        <form id="manual-scan-form" class="flex gap-2">
                            <input type="text" id="manual-resi" placeholder="Masukkan Barcode Produk" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium placeholder-gray-400">
                            <button type="submit" class="bg-gray-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-700 transition shadow-sm whitespace-nowrap">Scan</button>
                        </form>

                        <!-- DEBUG LOG -->
                        <div class="mt-4 p-3 bg-gray-900 rounded-xl border border-gray-700 shadow-inner">
                            <div class="text-[10px] font-bold text-gray-400 uppercase mb-1 flex justify-between">
                                <span>Scanner Debug Log</span>
                                <button type="button" onclick="document.getElementById('debug-log').innerHTML=''" class="text-gray-500 hover:text-white">Clear</button>
                            </div>
                            <div id="debug-log" class="text-[11px] font-mono text-green-400 h-24 overflow-y-auto whitespace-pre-wrap flex flex-col gap-1">
                                <span class="text-gray-500">Menunggu scan...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Checklist -->
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5 overflow-hidden h-full">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center justify-between mb-4">
                            <span>Manifes Pengiriman (Checklist)</span>
                        </h3>
                        
                        <div class="space-y-2 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($scans as $item)
                                @if($item->received_at != null)
                                    <!-- Sudah Diterima -->
                                    <div class="bg-white p-3 rounded-xl border border-green-200 shadow-sm flex items-center justify-between opacity-75">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-800 font-bold mb-0.5 line-through decoration-gray-400">{{ $item->barcode }}</p>
                                                <p class="text-[10px] text-gray-500">Diterima: {{ \Carbon\Carbon::parse($item->received_at)->format('H:i:s') }}</p>
                                            </div>
                                        </div>
                                        @if($item->sack_id)
                                            <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-mono">{{ $item->sack_id }}</span>
                                        @endif
                                    </div>
                                @else
                                    <!-- Belum Diterima -->
                                    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-800 font-bold mb-0.5">{{ $item->barcode }}</p>
                                                <p class="text-[10px] text-amber-600 font-semibold">Menunggu Scan...</p>
                                            </div>
                                        </div>
                                        @if($item->sack_id)
                                            <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-mono">{{ $item->sack_id }}</span>
                                        @endif
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
/* Sembunyikan pesan html5-qrcode bawaan agar UI lebih bersih */
#qr-reader {
    border: none !important;
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
</style>

@if(!$isCompleted)
<script>
document.addEventListener('DOMContentLoaded', function () {
    let isScanning = false;
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10, 
            // Kotak persegi panjang khusus untuk membaca Barcode
            qrbox: { width: 300, height: 120 }, 
            // Hanya deteksi format Barcode (matikan QR Code agar lebih cepat)
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.ITF
            ]
        },
        /* verbose= */ false);

    function logDebug(message, type = 'info') {
        const logBox = document.getElementById('debug-log');
        if (!logBox) return;
        
        // Buang pesan default
        if (logBox.innerHTML.includes('Menunggu scan...')) {
            logBox.innerHTML = '';
        }

        const time = new Date().toLocaleTimeString('id-ID', { hour12: false });
        let colorClass = 'text-green-400';
        if (type === 'error') colorClass = 'text-red-400';
        if (type === 'warn') colorClass = 'text-yellow-400';

        const span = document.createElement('div');
        span.className = colorClass;
        span.textContent = `[${time}] ${message}`;
        logBox.appendChild(span);
        logBox.scrollTop = logBox.scrollHeight;
    }

    function processScan(scannedCode) {
        logDebug(`Processing code: ${scannedCode}`);
        if (isScanning) {
            logDebug(`Skipped, still processing previous scan.`, 'warn');
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
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                barcode: scannedCode,
                logistic_id: '{{ $logistic->id_logistic }}'
            })
        })
        .then(response => response.json().then(data => ({status: response.status, body: data})))
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
                }).then(() => { html5QrcodeScanner.resume(); });
            } else {
                logDebug(`Server Response: Error - ${result.body.message}`, 'error');
                Swal.fire({
                    icon: 'error',
                    title: 'Ditolak',
                    text: result.body.message
                }).then(() => { html5QrcodeScanner.resume(); });
            }
        })
        .catch(error => {
            isScanning = false;
            logDebug(`Fetch Error: ${error.message}`, 'error');
            Swal.fire({
                icon: 'error',
                title: 'Error Jaringan',
                text: 'Terjadi kesalahan saat menghubungi server.'
            }).then(() => { html5QrcodeScanner.resume(); });
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        logDebug(`Scan Success! Captured: ${decodedText}`);
        html5QrcodeScanner.pause();
        processScan(decodedText);
    }

    // Limit log error to prevent spamming log box too fast
    let lastErrorTime = 0;
    function onScanFailure(error) {
        const now = Date.now();
        if (now - lastErrorTime > 2000) { // log error max once per 2 seconds
            logDebug(`Scan failed/searching...`, 'warn');
            lastErrorTime = now;
        }
    }

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    setTimeout(() => {
        const placeholder = document.getElementById('scanner-placeholder');
        if (placeholder) placeholder.style.display = 'none';
    }, 1000);

    const manualForm = document.getElementById('manual-scan-form');
    manualForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('manual-resi').value.trim();
        if (input !== '') {
            processScan(input);
            document.getElementById('manual-resi').value = ''; 
        }
    });
});
</script>
@endif
@endsection
