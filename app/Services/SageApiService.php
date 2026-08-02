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
        $this->baseUrl = config('services.sage.url', 'https://app.sage.biz.id/api');
        $this->email = config('services.sage.email');
        $this->password = config('services.sage.password');
    }

    /**
     * 1. FUNGSI LOGIN & TOKEN
     */
    public function getToken()
    {
        if (Cache::has('sage_api_token')) {
            return Cache::get('sage_api_token');
        }

        try {
            $response = Http::withoutVerifying()
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$this->baseUrl}/login", [
                    'email'    => $this->email,
                    'password' => $this->password,
                ]);

            if ($response->successful() && $token = $response->json('data.token')) {
                Cache::put('sage_api_token', $token, now()->addMinutes(120));
                return $token;
            }

            Log::error('API Sage Login Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('API Sage Connection Error: ' . $e->getMessage());
            return null;
        }
    }


    private function postRequest($endpoint, $payload = [])
    {
        $token = $this->getToken();
        if (!$token) return [];

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->post("{$this->baseUrl}/{$endpoint}", $payload);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            Log::error("Gagal fetch {$endpoint}: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("Error fetch {$endpoint}: " . $e->getMessage());
            return [];
        }
    }

    public function getVarieties($page = 1, $limit = 100)
    {
        return $this->postRequest('v1/mst/variety', ['page' => $page, 'limit' => $limit]);
    }

    /** 3. Master Product */
    public function getProducts($page = 1, $limit = 100)
    {
        return $this->postRequest('v1/mst/product', ['page' => $page, 'limit' => $limit]);
    }

    /** 4. Master Batch */
    public function getBatches($page = 1, $limit = 100, $variety_id = null)
    {
        $payload = ['page' => $page, 'limit' => $limit];
        if ($variety_id) $payload['variety_id'] = $variety_id;
        return $this->postRequest('v1/mst/batch', $payload);
    }

    /** 5. Master Lot No */
    public function getLotNumbers($page = 1, $limit = 100)
    {
        return $this->postRequest('v1/mst/lotno', ['page' => $page, 'limit' => $limit]);
    }

    /** 6. Master Location */
    public function getLocations($page = 1, $limit = 100)
    {
        return $this->postRequest('v1/mst/location', ['page' => $page, 'limit' => $limit]);
    }

    /** 7. Transaksi Turn Over (TOS) Header */
    public function getTurnovers($page = 1, $limit = 100, $location_id = null)
    {
        $payload = ['page' => $page, 'limit' => $limit];
        if ($location_id) $payload['location_id'] = $location_id;
        return $this->postRequest('v1/whs/tos', $payload);
    }

    /** 8. Transaksi Turn Over (TOS) Detail */
    public function getTurnoverDetail($tos_id)
    {
        // Wajib mengirimkan tos_id sesuai dokumentasi
        return $this->postRequest('v1/whs/tos/detail', ['tos_id' => $tos_id]);
    }

    /** 9. Transaksi Delivery Header */
    public function getDeliveries($page = 1, $limit = 100)
    {
        // Spasi pada "whs/ delivery" sudah diperbaiki di sini
        return $this->postRequest('v1/whs/delivery', ['page' => $page, 'limit' => $limit]);
    }

    /** 10. Transaksi Delivery Detail */
    public function getDeliveryDetail($delivery_id)
    {
        // Wajib mengirimkan delivery_id sesuai dokumentasi
        return $this->postRequest('v1/whs/delivery/detail', ['delivery_id' => $delivery_id]);
    }
}