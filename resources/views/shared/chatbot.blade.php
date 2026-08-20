@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-7rem)] min-h-[500px] w-full flex flex-col bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm">

    {{-- HEADER CHAT --}}
    <div class="p-3 sm:p-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2.5 sm:gap-3 flex-shrink-0">

        <div class="p-2 sm:p-2.5 bg-green-500 text-white rounded-lg sm:rounded-xl shadow-sm flex-shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
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


    {{-- AREA CHAT --}}
    <div
        class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden
               px-3 py-4
               sm:px-4 sm:py-5
               md:px-6 md:py-6
               space-y-4 bg-gray-50/30"
        id="chatContainer"
    >

        {{-- PESAN AWAL AI --}}
        <div class="flex items-start gap-2 sm:gap-3 w-full max-w-3xl">

            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-full bg-green-500 text-white
                        flex items-center justify-center font-bold text-xs
                        flex-shrink-0 shadow-sm">
                AI
            </div>

            {{-- Bubble --}}
            <div class="bg-white
                        px-3 py-3
                        sm:px-4 sm:py-4
                        rounded-2xl rounded-tl-none
                        border border-gray-100
                        shadow-sm
                        max-w-[calc(100%-2.5rem)]
                        sm:max-w-[85%]
                        md:max-w-2xl
                        break-words">

                <p class="text-xs sm:text-sm text-gray-800 leading-relaxed break-words">
                    Halo {{ Auth::user()->name }}! Saya asisten AI yang telah mempelajari seluruh dokumen regulasi, data distribusi, dan katalog benih. Ada yang bisa saya bantu analisis hari ini?
                </p>
            </div>
        </div>

    </div>


    {{-- INPUT CHAT --}}
    <div class="p-2.5 sm:p-3 md:p-4 border-t border-gray-100 bg-white flex-shrink-0">

        <form id="chatForm"
              class="flex items-center gap-2 sm:gap-3 w-full">

            <input
                type="text"
                id="userInput"
                required
                autocomplete="off"
                class="flex-1 min-w-0
                       border border-gray-300
                       px-3 py-2.5
                       sm:px-4 sm:py-3
                       rounded-xl
                       focus:ring-2 focus:ring-green-500
                       focus:border-green-500
                       outline-none transition
                       text-xs sm:text-sm
                       placeholder:text-gray-400"
                placeholder="Tanyakan sesuatu..."
            >

            <button
                type="submit"
                class="flex-shrink-0
                       w-10 h-10
                       sm:w-auto sm:h-11
                       sm:px-5
                       bg-green-600
                       hover:bg-green-700
                       active:bg-green-800
                       text-white
                       font-bold
                       rounded-xl
                       transition
                       shadow-md
                       flex items-center justify-center"
                aria-label="Kirim pesan"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>

                {{-- Tulisan hanya muncul di tablet/desktop --}}
                <span class="hidden sm:inline ml-2">
                    Kirim
                </span>
            </button>

        </form>
    </div>

</div>


<script>
document.getElementById('chatForm').addEventListener('submit', async function(e) {

    e.preventDefault();

    const input = document.getElementById('userInput');
    const container = document.getElementById('chatContainer');
    const pertanyaan = input.value.trim();

    if (!pertanyaan) return;


    // =========================================================
    // 1. PESAN USER
    // =========================================================

    const userMsg = `
        <div class="flex items-start gap-2 sm:gap-3 w-full justify-end mb-4">

            <div class="
                bg-green-600
                px-3 py-3
                sm:px-4 sm:py-3
                rounded-2xl rounded-tr-none
                text-white
                shadow-sm
                max-w-[85%]
                sm:max-w-[75%]
                md:max-w-2xl
                break-words
            ">
                <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line break-words">
                    ${pertanyaan}
                </p>
            </div>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', userMsg);

    input.value = '';

    container.scrollTop = container.scrollHeight;


    // =========================================================
    // 2. LOADING AI
    // =========================================================

    const loadingId = 'loading-' + Date.now();

    const loadingMsg = `
        <div id="${loadingId}"
             class="flex items-start gap-2 sm:gap-3 w-full mb-4">

            <div class="
                w-8 h-8
                rounded-full
                bg-green-500
                text-white
                flex items-center
                justify-center
                font-bold
                text-xs
                flex-shrink-0
                shadow-sm
            ">
                AI
            </div>

            <div class="
                bg-white
                px-3 py-3
                sm:px-4 sm:py-4
                rounded-2xl rounded-tl-none
                border border-gray-100
                shadow-sm
                flex items-center gap-2
            ">

                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>

                <span
                    class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                    style="animation-delay: 0.2s">
                </span>

                <span
                    class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                    style="animation-delay: 0.4s">
                </span>

            </div>

        </div>
    `;

    container.insertAdjacentHTML('beforeend', loadingMsg);

    container.scrollTop = container.scrollHeight;


    // =========================================================
    // 3. REQUEST KE LARAVEL
    // =========================================================

    try {

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

        if (!csrfMeta) {
            throw new Error('CSRF token tidak ditemukan.');
        }

        const csrfToken = csrfMeta.getAttribute('content');


        const response = await fetch(
            '{{ url(Request::route()->getPrefix() . "/chat/send") }}',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },

                body: JSON.stringify({
                    pertanyaan: pertanyaan
                })
            }
        );


        const data = await response.json();


        // =====================================================
        // 4. HAPUS LOADING
        // =====================================================

        const loadingElement = document.getElementById(loadingId);

        if (loadingElement) {
            loadingElement.remove();
        }


        // =====================================================
        // 5. JAWABAN AI
        // =====================================================

        const jawaban = data.jawaban || 'Terjadi kesalahan sistem.';
        const sumber = data.sumber || '-';


        const aiMsg = `
            <div class="
                flex items-start
                gap-2 sm:gap-3
                w-full
                mb-4
            ">

                <div class="
                    w-8 h-8
                    rounded-full
                    bg-green-500
                    text-white
                    flex items-center
                    justify-center
                    font-bold
                    text-xs
                    flex-shrink-0
                    shadow-sm
                ">
                    AI
                </div>


                <div class="
                    bg-white
                    px-3 py-3
                    sm:px-4 sm:py-4
                    rounded-2xl rounded-tl-none
                    border border-gray-100
                    shadow-sm
                    max-w-[calc(100%-2.5rem)]
                    sm:max-w-[85%]
                    md:max-w-2xl
                    break-words
                ">

                    <p class="
                        text-xs sm:text-sm
                        text-gray-800
                        leading-relaxed
                        whitespace-pre-line
                        break-words
                    ">
                        ${jawaban}
                    </p>


                    <span class="
                        text-[9px] sm:text-[10px]
                        font-bold
                        text-green-600
                        mt-2
                        block
                        break-words
                    ">
                        Sumber Data: ${sumber}
                    </span>

                </div>

            </div>
        `;


        container.insertAdjacentHTML('beforeend', aiMsg);

        container.scrollTop = container.scrollHeight;


    } catch (error) {

        // =====================================================
        // ERROR
        // =====================================================

        const loadingElement = document.getElementById(loadingId);

        if (loadingElement) {
            loadingElement.remove();
        }

        console.error(error);

        const errorMsg = `
            <div class="
                flex items-start
                gap-2 sm:gap-3
                w-full
                mb-4
            ">

                <div class="
                    w-8 h-8
                    rounded-full
                    bg-red-500
                    text-white
                    flex items-center
                    justify-center
                    font-bold
                    text-xs
                    flex-shrink-0
                ">
                    AI
                </div>

                <div class="
                    bg-red-50
                    border border-red-100
                    text-red-600
                    px-3 py-3
                    sm:px-4 sm:py-4
                    rounded-2xl rounded-tl-none
                    max-w-[85%]
                ">

                    <p class="text-xs sm:text-sm leading-relaxed">
                        Gagal mengirim pesan. Pastikan server dan koneksi berjalan dengan baik.
                    </p>

                </div>

            </div>
        `;

        container.insertAdjacentHTML('beforeend', errorMsg);

        container.scrollTop = container.scrollHeight;
    }
});
</script>

@endsection