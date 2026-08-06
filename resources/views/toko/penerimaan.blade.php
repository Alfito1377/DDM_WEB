@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @php
    $toko = null;
    // Cek apakah user yang login punya store_id (seperti di database Anda)
    if (Auth::user()->store_id) {
        $toko = \App\Models\StoresModel::find(Auth::user()->store_id);
    }
@endphp

<!-- Pengecekan: Muncul HANYA jika data toko ditemukan DAN latitude-nya masih kosong (null) -->
@if($toko && is_null($toko->latitude))
<div id="modalSetLokasiToko" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden p-6 text-center border-t-8 border-yellow-500">
        
        <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        
        <h3 class="font-black text-gray-800 text-xl mb-2">Atur Titik Lokasi Toko</h3>
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
            Sistem mendeteksi titik koordinat toko Anda belum diatur. Titik ini akan digunakan oleh kurir sebagai lokasi <b>Checkpoint</b>. <br><br>
            <span class="text-red-600 font-bold">PERINGATAN:</span> Apakah Anda sedang berada di dalam bangunan fisik toko saat ini?
        </p>

        <div class="flex flex-col gap-3">
            <button id="btnSetLokasi" onclick="getLocation()" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                Ya, Kunci Lokasi Saat Ini
            </button>
            <button onclick="document.getElementById('modalSetLokasiToko').classList.add('hidden')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-xl transition">
                Tidak, Saya Sedang di Luar Toko (Lewati)
            </button>
        </div>
    </div>
</div>
@endif
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Pengiriman ke Toko</h1>
                <p class="text-sm text-gray-500 mt-1">Pilih pengiriman yang sedang Anda terima untuk mulai membongkar dan men-scan barang.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-medium text-sm border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($inTransit as $logistic)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col hover:border-green-300 transition-colors">
                    <div class="p-5 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                @if($logistic->status === 'out_of_transit')
                                    <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Tiba di Toko (Siap Bongkar)</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">Menuju Toko</span>
                                @endif
                                <h3 class="font-bold text-gray-800 mt-2 font-mono">{{ $logistic->id_logistic }}</h3>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                <span>Kurir: <span class="font-semibold">{{ $logistic->driverName }}</span></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Total Barang: <span class="font-bold text-gray-800">{{ $logistic->total_items }}</span> unit</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Sudah Discan: <span class="font-bold text-green-600">{{ $logistic->received_items }}</span> unit</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $percentage = $logistic->total_items > 0 ? ($logistic->received_items / $logistic->total_items) * 100 : 0;
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-2.5 mb-2">
                            <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-gray-50 bg-gray-50/50">
                        <a href="{{ url('/toko/penerimaan/mulai/' . $logistic->id_logistic) }}" class="w-full block text-center bg-gray-800 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-700 transition shadow-sm">
                            Bongkar & Scan Barang
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="text-lg font-bold text-gray-600 mb-1">Tidak ada pengiriman</h3>
                    <p class="text-sm text-gray-400 text-center max-w-sm">Saat ini tidak ada paket logistik yang sedang dalam perjalanan menuju toko Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
<script>
// --- LOGIKA UPDATE TITIK KOORDINAT TOKO ---
    function getLocation() {
        let btn = document.getElementById('btnSetLokasi');
        let originalText = btn.innerHTML;
        btn.innerHTML = "Mengecek Akurasi GPS...";
        btn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    // Deteksi Akurasi Anti-Curang
                    let akurasi = position.coords.accuracy;
                    if (akurasi > 200) {////nanti kalau sudah dideploy ubah menjadi 100
                        alert("Gagal! Akurasi GPS terlalu lemah (" + Math.round(akurasi) + " meter). Matikan Fake GPS atau cari sinyal yang lebih baik.");
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        return;
                    }

                    // Kirim ke server
                    try {
                        let response = await fetch("{{ route('toko.update.lokasi') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude
                            })
                        });
                        
                        let result = await response.json();
                        
                        if(result.success) {
                            alert("Berhasil! Titik lokasi toko Anda sudah dikunci.");
                            window.location.reload(); // Reload agar banner otomatis hilang
                        } else {
                            alert("Gagal menyimpan lokasi.");
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }
                    } catch(e) {
                        alert("Terjadi kesalahan jaringan.");
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                },
                function(error) {
                    alert("Akses Lokasi Ditolak. Harap izinkan akses lokasi (GPS) pada browser Anda agar bisa menggunakan fitur ini.");
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Browser HP/Laptop Anda tidak mendukung fitur lokasi (GPS).");
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    </script>
@endsection
