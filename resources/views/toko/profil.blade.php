@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 h-fit">
        <div class="text-center pb-6 border-b border-gray-100">
            <div class="w-20 h-20 bg-green-50 text-green-600 font-black text-2xl rounded-full flex items-center justify-center mx-auto mb-3 border border-green-200">
                {{ strtoupper(substr($store->store_name ?? 'M', 0, 1)) }}
            </div>
            <h2 class="font-bold text-gray-800 text-base">{{ $store->store_name ?? 'Nama Toko' }}</h2>
            <p class="text-xs bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full inline-block font-bold uppercase mt-1.5 tracking-wider">Mitra Resmi</p>
        </div>
        
        <div class="pt-6 space-y-4 text-xs text-gray-600">
            <div>
                <p class="font-bold text-gray-400 uppercase tracking-wider mb-0.5">ID Akun Pengguna</p>
                <p class="font-mono text-gray-800 font-semibold bg-gray-50 p-2 rounded-lg border">{{ $user->username }}</p>
            </div>
            <div>
                <p class="font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Lokasi</p>
                <p class="text-gray-800 font-medium leading-relaxed">{{ $store->address ?? 'Alamat belum diatur.' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Pengaturan Keamanan Akun</h2>
        <p class="text-xs text-gray-400 mb-6">Perbarui kata sandi berkala Anda untuk menjaga privasi akses data retur toko Anda.</p>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 p-3.5 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="/toko/profil/ganti-password" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition">
                @error('current_password') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Kata Sandi Baru</label>
                    <input type="password" name="new_password" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition" placeholder="Minimal 6 karakter">
                    @error('new_password') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="new_password_confirmation" required class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 text-sm outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-sm">
                    Simpan Perubahan Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection