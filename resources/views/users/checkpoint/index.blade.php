@extends('users.layouts.app')

@section('title', 'Verifikasi Checkpoint Kurir')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        
        <!-- Header / Banner -->
        <div class="bg-brand-600 p-6 text-center relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg mb-4">
                    <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Checkpoint Kurir</h2>
                <p class="text-brand-100 text-sm mt-1">Konfirmasi Kedatangan Pengiriman</p>
            </div>
        </div>

        <div class="p-6">
            @if(isset($store) && $store)
                <!-- Store Info Card -->
                <div class="bg-slate-50 rounded-2xl p-4 mb-4 border border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-brand-100 text-brand-600 rounded-xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">{{ $store->store_name }}</h3>
                            <p class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="line-clamp-2 leading-tight">{{ $store->address ?? 'Alamat tidak tersedia' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if(isset($shipments) && count($shipments) > 0)
                    <!-- Active Shipments Card -->
                    <div class="bg-amber-50 rounded-2xl p-4 mb-6 border border-amber-100">
                        <h4 class="font-bold text-amber-800 text-sm mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            Daftar Pengiriman Aktif
                        </h4>
                        <div class="space-y-2">
                            @foreach($shipments as $shipment)
                                <label class="bg-white rounded-xl p-3 shadow-sm border border-amber-100 flex items-center gap-3 cursor-pointer hover:bg-amber-50/50 transition-colors">
                                    <input type="checkbox" name="selected_shipments[]" value="{{ $shipment->id_logistic }}" class="w-5 h-5 text-brand-600 rounded focus:ring-brand-500 border-slate-300 shipment-checkbox" checked>
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-800 text-xs">Resi: {{ substr($shipment->shipmentId, 0, 8) }}...</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            Kurir: <strong>{{ $shipment->driverName ?? 'Tidak diketahui' }}</strong> &bull; 
                                            {{ $shipment->plateNo }} ({{ $shipment->vehicleType }})
                                        </p>
                                    </div>
                                    <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded-lg text-[10px] font-bold">In Transit</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- No Active Shipments -->
                    <div class="bg-slate-50 text-slate-500 p-4 rounded-2xl mb-6 border border-slate-200 text-center text-sm">
                        <p>Tidak ada pengiriman aktif (In Transit) untuk toko ini saat ini.</p>
                    </div>
                @endif
            @else
                <!-- Invalid Token Alert -->
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 border border-red-100 flex items-center gap-3">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h4 class="font-bold text-sm">Token Tidak Valid</h4>
                        <p class="text-xs mt-0.5">Kode QR checkpoint tidak valid atau kedaluwarsa.</p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form id="checkpoint-form" class="space-y-5">
                @csrf
                <input type="hidden" id="token" name="token" value="{{ $token ?? '' }}">
                
                <!-- Timestamp Clock (Live) -->
                <div class="flex items-center justify-between px-1 mb-2">
                    <span class="text-sm font-semibold text-slate-500">Waktu Kedatangan</span>
                    <span id="live-clock" class="text-sm font-bold text-brand-600 bg-brand-50 px-3 py-1.5 rounded-lg border border-brand-100">--:--:--</span>
                </div>

                <div>
                    <label for="nama-pengirim" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1">Nama Kurir / Pengirim <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" id="nama-pengirim" name="nama_pengirim" required placeholder="Masukkan nama lengkap" 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div>
                    <label for="nomor-handphone" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1">Nomor Handphone / WA <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="tel" id="nomor-handphone" name="nomor_handphone" required placeholder="Contoh: 08123456789" 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1">Nomor Plat Kendaraan (Opsional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 pt-3 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <input type="text" id="catatan" name="catatan" placeholder="B 1234 ABC" 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="submit-button" @if(!isset($store) || !$store) disabled @endif
                            class="w-full flex items-center justify-center py-3.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none group">
                        <span id="btn-text">Konfirmasi Kedatangan</span>
                        <svg id="btn-icon" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <svg id="btn-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Sistem Logistik PT. Sage Maslahat</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clockEl = document.getElementById('live-clock');
            if(clockEl) clockEl.textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Form Submit Handler
        const form = document.getElementById('checkpoint-form');
        const btnSubmit = document.getElementById('submit-button');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        const btnSpinner = document.getElementById('btn-spinner');

        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Collect selected shipments
                const selectedCheckboxes = document.querySelectorAll('.shipment-checkbox:checked');
                if(selectedCheckboxes.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan centang minimal 1 paket yang ingin dikonfirmasi.',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#f59e0b',
                        customClass: {
                            popup: 'rounded-2xl font-sans',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    });
                    return;
                }
                const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

                // Validasi Sederhana
                const nama = document.getElementById('nama-pengirim').value;
                const noHp = document.getElementById('nomor-handphone').value;
                const catatan = document.getElementById('catatan').value;
                const token = document.getElementById('token').value;
                const csrf = document.querySelector('input[name="_token"]').value;
                
                if(!nama || !noHp) return;

                // Loading state
                btnSubmit.disabled = true;
                btnText.textContent = 'Memproses...';
                btnIcon.classList.add('hidden');
                btnSpinner.classList.remove('hidden');

                const formData = new FormData();
                formData.append('nama_pengirim', nama);
                formData.append('nomor_handphone', noHp);
                formData.append('catatan', catatan);
                formData.append('token', token);
                formData.append('_token', csrf);
                formData.append('selected_shipments', JSON.stringify(selectedIds));

                // Fetch API Request
                fetch("{{ route('login.qr.checkpoint.post') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message + (data.updated_count > 0 ? ` (${data.updated_count} paket diselesaikan)` : ''),
                            confirmButtonText: 'Selesai',
                            confirmButtonColor: '#16a34a',
                            customClass: {
                                popup: 'rounded-2xl font-sans border-0 shadow-2xl',
                                title: 'text-xl font-bold text-slate-800',
                                confirmButton: 'rounded-xl px-8 py-3 font-bold shadow-lg shadow-green-500/30'
                            }
                        }).then(() => {
                            // Reload to see the empty active shipments list
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan sistem.');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-2xl font-sans',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    });
                })
                .finally(() => {
                    btnSubmit.disabled = false;
                    btnText.textContent = 'Konfirmasi Kedatangan';
                    btnIcon.classList.remove('hidden');
                    btnSpinner.classList.add('hidden');
                });
            });
        }
    });
</script>
@endpush