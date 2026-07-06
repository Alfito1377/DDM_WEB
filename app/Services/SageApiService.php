<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SageApiService
{
    protected $baseUrl;
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = env('SAGE_API_URL');
        $this->email = env('SAGE_API_EMAIL'); 
        $this->password = env('SAGE_API_PASSWORD');
    }

    /**
     * Fungsi untuk melakukan Login dan mengambil Token
     */
    public function getToken()
    {
        // Cek apakah token masih ada di cache
        if (Cache::has('sage_api_token')) {
            return Cache::get('sage_api_token');
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->post("{$this->baseUrl}/login", [
                    'email'    => $this->email,
                    'password' => $this->password,
                ]);

            if ($response->successful()) {
                // PERBAIKAN: Mengambil token dari dalam objek 'data'
                $token = $response->json('data.token'); 
                
                if ($token) {
                    Cache::put('sage_api_token', $token, now()->addMinutes(120));
                    return $token;
                }
            }

            Log::error('API Sage merespons tapi token tidak ditemukan: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Error koneksi ke SAGE API: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Fungsi untuk mengambil data Produk menggunakan Token
     */
    /**
     * Fungsi untuk mengambil data Variety
     */
    public function getVarieties()
    {
        $token = $this->getToken();

        if (!$token) {
            return [];
        }

        try {
            // PERUBAHAN: Menggunakan POST dan penyesuaian URL endpoint
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->post("https://app.sage.biz.id/api/v1/mst/variety"); // Sesuai dokumentasi

            if ($response->successful()) {
                return $response->json('data'); 
            }

            // Tambahan log error agar jika gagal lagi, kita tahu alasannya
            \Illuminate\Support\Facades\Log::error('Gagal fetch variety API SAGE: ' . $response->body());
            return [];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saat fetch variety: ' . $e->getMessage());
            return [];
        }
    }
}