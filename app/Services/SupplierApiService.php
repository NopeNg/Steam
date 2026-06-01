<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupplierApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.supplier.base_url', 'http://127.0.0.1:4099');
        $this->apiKey = config('services.supplier.api_key', 'SUPPLIER_DEMO_KEY_2026');
    }

    /**
     * Mua key từ supplier
     *
     * @param int $gameId
     * @param int $quantity
     * @param string|null $customerEmail
     * @return array {success: bool, keys?: array, transaction_id?: string, error?: string, error_code?: string}
     */
    public function purchaseKeys(int $gameId, int $quantity = 1, ?string $customerEmail = null): array
    {
        Log::info('[SupplierAPI] Calling purchaseKeys', [
            'game_id' => $gameId,
            'quantity' => $quantity,
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/purchase', [
                    'game_id' => $gameId,
                    'quantity' => $quantity,
                    'customer_email' => $customerEmail,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                Log::info('[SupplierAPI] Purchase successful', [
                    'transaction_id' => $body['transaction_id'] ?? null,
                    'keys_count' => count($body['keys'] ?? []),
                ]);

                return [
                    'success' => true,
                    'keys' => $body['keys'] ?? [],
                    'transaction_id' => $body['transaction_id'] ?? null,
                    'raw' => $body,
                ];
            }

            // Lỗi từ supplier
            $body = $response->json();
            Log::warning('[SupplierAPI] Purchase failed', [
                'status' => $response->status(),
                'error' => $body['error'] ?? 'Unknown error',
                'error_code' => $body['error_code'] ?? null,
            ]);

            return [
                'success' => false,
                'error' => $body['error'] ?? 'Supplier API returned status ' . $response->status(),
                'error_code' => $body['error_code'] ?? 'UNKNOWN_ERROR',
                'http_status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('[SupplierAPI] Connection failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Cannot connect to supplier API: ' . $e->getMessage(),
                'error_code' => 'CONNECTION_FAILED',
            ];
        }
    }

    /**
     * Kiểm tra health của supplier
     */
    public function healthCheck(): array
    {
        try {
            $response = Http::timeout(5)->get($this->baseUrl . '/api/health');
            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }
            return ['success' => false, 'error' => 'Health check failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Xác thực key với supplier
     */
    public function verifyKey(string $keyCode): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post($this->baseUrl . '/api/verify-key', [
                    'key_code' => $keyCode,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }
            return ['success' => false, 'error' => 'Verification failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}