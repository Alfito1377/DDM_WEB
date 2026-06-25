<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        if (!in_array($userRole, ['admin', 'manajer'])) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        $documents = KnowledgeBase::with('uploader')->orderBy('created_at', 'desc')->get();
        
        return view('shared.upload', compact('documents'));
    }

    /**
     * Memproses unggahan dokumen baru
     */
    public function store(Request $request)
    {
        $userRole = strtolower(Auth::user()->role->role_name ?? '');

        if (!in_array($userRole, ['admin', 'manajer'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        // 1. Validasi disesuaikan dengan form baru Anda
        $request->validate([
            'category' => 'required|in:regulasi,katalog,panduan',
            'description' => 'nullable|string',
            // Mendukung DOCX dan menggunakan nama input 'kb_document'
            'kb_document' => 'required|file|mimes:pdf,txt,csv,xlsx,xls,docx|max:10240', 
        ]);

        try {
            if ($request->hasFile('kb_document')) {
                $file = $request->file('kb_document');
                $filePath = $file->store('knowledge_base', 'public');
                
                // Gunakan nama file asli sebagai title karena input title dihapus
                $originalName = $file->getClientOriginalName();
                
                KnowledgeBase::create([
                    'title' => $originalName, 
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'category' => $request->category,
                    'file_size' => $file->getSize(),
                    'description' => $request->description,
                    'uploaded_by' => Auth::user()->id, 
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diindeks ke dalam sistem!'
                ]);
            }
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}