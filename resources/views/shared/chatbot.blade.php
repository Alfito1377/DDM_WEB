@extends('layouts.app')

@section('content')
<style>
    .chat-markdown { font-size: 0.875rem; line-height: 1.5; }
    .chat-markdown p { margin-bottom: 0.5rem; }
    .chat-markdown p:last-child { margin-bottom: 0; }
    .chat-markdown strong { font-weight: 700; color: #1f2937; }
    .chat-markdown em { font-style: italic; }
    .chat-markdown ul { list-style-type: disc; padding-left: 1.25rem; margin-bottom: 0.5rem; }
    .chat-markdown ol { list-style-type: decimal; padding-left: 1.25rem; margin-bottom: 0.5rem; }
    .chat-markdown li { margin-bottom: 0.25rem; }
    .chat-markdown table { width: 100%; margin-bottom: 0.5rem; border-collapse: collapse; }
    .chat-markdown th, .chat-markdown td { border: 1px solid #e5e7eb; padding: 0.375rem 0.5rem; }
    .chat-markdown th { background-color: #f9fafb; font-weight: 600; }
    .chat-markdown code { background-color: #f3f4f6; padding: 0.125rem 0.25rem; border-radius: 0.25rem; color: #ef4444; font-family: monospace; }
    .chat-markdown pre { background-color: #1f2937; color: #f9fafb; padding: 0.75rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 0.5rem; }
    .chat-markdown pre code { background-color: transparent; color: inherit; padding: 0; }
</style>

<div class="h-[calc(100vh-7rem)] min-h-[500px] w-full flex flex-col bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm">

    <div class="p-3 sm:p-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between gap-3 flex-shrink-0">
        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
            <div class="p-2 sm:p-2.5 bg-green-500 text-white rounded-lg sm:rounded-xl shadow-sm flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>

            <div class="min-w-0">
                <h2 class="text-sm sm:text-base font-bold text-gray-800 truncate">
                    Asisten Pintar DDM
                </h2>
                <p class="text-[9px] sm:text-[10px] text-green-600 flex items-center gap-1 font-medium truncate">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse flex-shrink-0"></span>
                    <span class="truncate">
                        RAG Engine Terhubung (Model Optimasi Grid Search)
                    </span>
                </p>
            </div>
        </div>

        <button 
            type="button" 
            id="resetBtn"
            class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-red-600 bg-white hover:bg-red-50 border border-gray-200 hover:border-red-200 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl transition shadow-sm flex-shrink-0"
            title="Mulai percakapan dari awal"
        >
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span class="hidden sm:inline font-medium">Mulai Ulang</span>
        </button>
    </div>


    <div 
        class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-3 py-4 sm:px-4 sm:py-5 md:px-6 md:py-6 space-y-4 bg-gray-50/30 scroll-smooth" 
        id="chatContainer"
    >
        <div class="flex items-start gap-2 sm:gap-3 w-full max-w-3xl welcome-msg">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">
                AI
            </div>
            <div class="bg-white px-3 py-3 sm:px-4 sm:py-4 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm max-w-[calc(100%-2.5rem)] sm:max-w-[85%] md:max-w-2xl break-words">
                <p class="text-xs sm:text-sm text-gray-800 leading-relaxed break-words">
                    Halo {{ Auth::user()->name ?? 'Pengguna' }}! Saya asisten AI yang telah mempelajari seluruh regulasi, data distribusi, retur,dan logistic. Ada yang bisa saya bantu hari ini?
                </p>
            </div>
        </div>
    </div>


    {{-- INPUT CHAT --}}
    <div class="p-2.5 sm:p-3 md:p-4 border-t border-gray-100 bg-white flex-shrink-0">
        <form id="chatForm" class="flex items-center gap-2 sm:gap-3 w-full">
            <input
                type="text"
                id="userInput"
                required
                autocomplete="off"
                class="flex-1 min-w-0 border border-gray-300 px-3 py-2.5 sm:px-4 sm:py-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition text-xs sm:text-sm placeholder:text-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                placeholder="Tanyakan sesuatu..."
            >
            <button
                type="submit"
                id="submitBtn"
                class="flex-shrink-0 w-10 h-10 sm:w-auto sm:h-11 sm:px-5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold rounded-xl transition shadow-md flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                aria-label="Kirim pesan"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
                <span class="hidden sm:inline ml-2">Kirim</span>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('chatForm');
    const input = document.getElementById('userInput');
    const container = document.getElementById('chatContainer');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');

    // Simpan pesan awal/welcome HTML
    const initialWelcomeHTML = container.querySelector('.welcome-msg').outerHTML;

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    function scrollToBottom() {
        container.scrollTo({
            top: container.scrollHeight,
            behavior: 'smooth'
        });
    }

    function setFormState(isProcessing) {
        input.disabled = isProcessing;
        submitBtn.disabled = isProcessing;
        if (!isProcessing) {
            input.focus();
        }
    }

    // TOGGLE RESET PERCAKAPAN
    resetBtn.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin memulai ulang percakapan?')) {
            container.innerHTML = initialWelcomeHTML;
            input.value = '';
            input.focus();
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const pertanyaanRaw = input.value.trim();
        if (!pertanyaanRaw) return;

        const pertanyaanAman = escapeHtml(pertanyaanRaw);
        setFormState(true);

        // RENDER PESAN USER (Spasi & newline pada template string disatukan agar tidak membuat space kosong)
        const userMsg = `<div class="flex items-start gap-2 sm:gap-3 w-full justify-end mb-4"><div class="bg-green-600 px-3 py-2.5 sm:px-4 sm:py-3 rounded-2xl rounded-tr-none text-white shadow-sm max-w-[85%] sm:max-w-[75%] md:max-w-2xl break-words"><p class="text-xs sm:text-sm leading-relaxed break-words">${pertanyaanAman}</p></div></div>`;
        
        container.insertAdjacentHTML('beforeend', userMsg);
        input.value = '';
        scrollToBottom();

        // RENDER LOADING AI
        const loadingId = 'loading-' + Date.now();
        const loadingMsg = `<div id="${loadingId}" class="flex items-start gap-2 sm:gap-3 w-full mb-4"><div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">AI</div><div class="bg-white px-3 py-3 sm:px-4 sm:py-4 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm flex items-center gap-2"><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span></div></div>`;
        
        container.insertAdjacentHTML('beforeend', loadingMsg);
        scrollToBottom();

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) throw new Error('CSRF token tidak ditemukan.');
            
            const response = await fetch('{{ url(Request::route()->getPrefix() . "/chat/send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pertanyaan: pertanyaanRaw })
            });

            const data = await response.json();
            document.getElementById(loadingId)?.remove();

            // RENDER JAWABAN AI
            const rawJawaban = data.jawaban || 'Maaf, saya tidak bisa merespons saat ini.';
            
            // Konversi Markdown ke HTML aman jika Marked & DOMPurify tersedia
            let jawabanAman;
            if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                jawabanAman = DOMPurify.sanitize(marked.parse(rawJawaban));
            } else {
                jawabanAman = escapeHtml(rawJawaban);
            }

            const sumberAman = escapeHtml(data.sumber || '-');

            const aiMsg = `<div class="flex items-start gap-2 sm:gap-3 w-full mb-4"><div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">AI</div><div class="bg-white px-3 py-3 sm:px-4 sm:py-4 rounded-2xl rounded-tl-none border border-gray-100 shadow-sm max-w-[calc(100%-2.5rem)] sm:max-w-[85%] md:max-w-2xl break-words"><div class="chat-markdown text-gray-800 break-words">${jawabanAman}</div>${data.sumber ? `<span class="text-[9px] sm:text-[10px] font-bold text-green-600 mt-2 block break-words border-t border-gray-100 pt-2">Sumber Data: ${sumberAman}</span>` : ''}</div></div>`;
            
            container.insertAdjacentHTML('beforeend', aiMsg);
            
        } catch (error) {
            console.error(error);
            document.getElementById(loadingId)?.remove();

            const errorMsg = `<div class="flex items-start gap-2 sm:gap-3 w-full mb-4"><div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">!</div><div class="bg-red-50 border border-red-100 text-red-600 px-3 py-3 sm:px-4 sm:py-4 rounded-2xl rounded-tl-none max-w-[85%]"><p class="text-xs sm:text-sm leading-relaxed">Gagal terhubung ke server. Periksa koneksi Anda dan coba lagi.</p></div></div>`;
            container.insertAdjacentHTML('beforeend', errorMsg);
        } finally {
            setFormState(false);
            scrollToBottom();
        }
    });
});
</script>
@endsection