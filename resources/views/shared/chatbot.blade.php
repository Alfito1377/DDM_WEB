@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
        <div class="p-2 bg-green-500 text-white rounded-xl shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-gray-800">Asisten Pintar Jual Benih App</h2>
            <p class="text-[10px] text-green-600 flex items-center gap-1 font-medium">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                RAG Engine Terhubung (Model Optimasi Grid Search)
            </p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/30" id="chatContainer">
        <div class="flex items-start gap-3 max-w-lg">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">AI</div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm">
                <p class="text-sm text-gray-800 leading-relaxed">Halo {{ Auth::user()->name }}! Saya asisten AI yang telah mempelajari seluruh dokumen regulasi, data distribusi, dan katalog benih. Ada yang bisa saya bantu analisis hari ini?</p>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-gray-100 bg-white">
        <form id="chatForm" class="flex gap-3">
            <input type="text" id="userInput" required class="flex-1 border border-gray-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-sm" placeholder="Tanyakan tren retur toko, kualitas batch benih, atau analisis data alokasi...">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-5 rounded-xl transition shadow-md flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('userInput');
        const container = document.getElementById('chatContainer');
        if(!input.value.trim()) return;

        // Tampilkan pesan user
        const userMsg = `
            <div class="flex items-start gap-3 max-w-lg ml-auto justify-end">
                <div class="bg-green-600 p-4 rounded-2xl rounded-tr-none text-white shadow-sm">
                    <p class="text-sm leading-relaxed">${input.value}</p>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', userMsg);
        input.value = '';
        container.scrollTop = container.scrollHeight;
    });
</script>
@endsection