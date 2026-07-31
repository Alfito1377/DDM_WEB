@extends('layouts.app')

@section('content')
<div class="space-y-6">
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
@endsection
