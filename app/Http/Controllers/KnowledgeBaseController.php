<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class KnowledgeBaseController extends Controller
{
    /**
     * Menampilkan halaman utama Unggah Data (Knowledge Base)
     */
    public function index()
    {
        // Panggil role_name persis seperti di sidebar.blade.php
        $userRole = strtolower(Auth::user()->role->role_name ?? '');

        // Keamanan tambahan: Pastikan hanya admin atau manajer
        if (!in_array($userRole, ['superadmin', 'admin', 'manajer'])) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        $documents = KnowledgeBase::with('uploader')->orderBy('created_at', 'desc')->get();

        return view('knowledge_base.upload', compact('documents'));
    }

    /**
     * Memproses unggahan dokumen baru
     */
    public function store(Request $request)
    {
        // 1. Hapus 'category' dari validasi
        $request->validate([
            'kb_document' => 'required|file|mimes:pdf,txt,csv,xlsx,xls,docx|max:10240',
        ]);

        try {
            if ($request->hasFile('kb_document')) {
                $file = $request->file('kb_document');
                $filePath = $file->store('knowledge_base', 'public');
                $absolutePath = storage_path('app/public/' . $filePath);

                // 2. Simpan ke database MySQL Laravel (Kategori diisi 'umum' secara otomatis)
                KnowledgeBase::create([
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'category' => 'umum', // <--- Ubah menjadi teks statis agar tidak error di database
                    'file_size' => $file->getSize(),
                    'description' => $request->description ?? '',
                    'uploaded_by' => Auth::user()->id,
                ]);

                // Kirim lokasi file absolut ke FastAPI (Port 8001)
                $aiServiceUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8001') . '/webhook/document';

                $response = Http::timeout(10)->post($aiServiceUrl, [
                    'file_path' => $absolutePath
                ]);

                return back()->with('success', 'Dokumen berhasil diunggah! AI sedang memprosesnya di latar belakang.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah dokumen: ' . $e->getMessage());
        }
    }
    public function panduanToko()
    {
        $documents = KnowledgeBase::with('uploader')
            ->whereIn('category', ['regulasi', 'panduan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('toko.panduan', compact('documents'));
    }
}
