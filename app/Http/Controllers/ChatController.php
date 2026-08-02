<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // 1. Validasi input dari user
        $request->validate([
            'pertanyaan' => 'required|string'
        ]);

        try {
            // 2. Tembak data ke AI Service (Python FastAPI)
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000') . '/chat';
            
            $response = Http::timeout(60)->post($aiServiceUrl, [
                'pertanyaan' => $request->pertanyaan
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                Log::error('AI Service Error: ' . $response->body());
                return response()->json([
                    'jawaban' => 'Maaf, terjadi kendala saat memproses jawaban di mesin AI.',
                    'sumber' => 'Error'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Koneksi ke AI Service Gagal: ' . $e->getMessage());
            return response()->json([
                'jawaban' => 'Gagal terhubung ke server AI. Pastikan service Python (main.py) sudah berjalan.',
                'sumber' => 'Sistem'
            ], 500);
        }
    }
}