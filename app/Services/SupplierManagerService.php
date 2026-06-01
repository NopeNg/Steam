<?php

namespace App\Services;

use App\Models\SupplierProvider;
use App\Models\GameSupplierMapping;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupplierManagerService
{
    /**
     * Mua key cho game từ supplier phù hợp nhất
     * - Tìm supplier đang Active cho game đó
     * - Ưu tiên supplier có priority cao nhất
     * - Nếu supplier đầu fail, tự động fallback sang supplier khác
     */
    public function purchaseKeys(int $gameId, int $quantity = 1, ?string $customerEmail = null): array
    {
        // Lấy tất cả supplier mapping Active cho game này, sắp xếp priority giảm dần
        $mappings = GameSupplierMapping::where('game_id', $gameId)
            ->where('status', 'Active')
            ->whereHas('supplierProvider', fn($q) => $q->where('status', 'Active'))
            ->with('supplierProvider')
            ->orderByRaw('(SELECT priority FROM supplier_providers WHERE id = game_supplier_mappings.supplier_provider_id) DESC')
            ->get();

        if ($mappings->isEmpty()) {
            Log::warning('[SupplierManager] No active supplier for game #' . $gameId);
            return [
                'success' => false,
                'error' => 'No supplier configured for this game',
                'error_code' => 'NO_SUPPLIER',
            ];
        }

        $lastError = null;

        foreach ($mappings as $mapping) {
            $provider = $mapping->supplierProvider;

            Log::info('[SupplierManager] Trying supplier: ' . $provider->name . ' for game #' . $gameId);

            $result = $this->callSupplierApi($provider, $mapping->supplier_game_id ?? $gameId, $quantity, $customerEmail);

            if ($result['success']) {
                Log::info('[SupplierManager] Success with: ' . $provider->name);
                return [
                    'success' => true,
                    'keys' => $result['keys'],
                    'transaction_id' => $result['transaction_id'],
                    'supplier_name' => $provider->name,
                    'supplier_code' => $provider->code,
                ];
            }

            $lastError = $result['error'];
            Log::warning('[SupplierManager] ' . $provider->name . ' failed: ' . $result['error'] . ' - Trying next supplier...');
        }

        // Tất cả supplier đều fail
        Log::error('[SupplierManager] All suppliers failed for game #' . $gameId);
        return [
            'success' => false,
            'error' => $lastError ?? 'All suppliers failed',
            'error_code' => 'ALL_SUPPLIERS_FAILED',
        ];
    }

    /**
     * Gọi API của một supplier cụ thể
     */
    private function callSupplierApi(SupplierProvider $provider, $supplierGameId, int $quantity, ?string $customerEmail): array
    {
        try {
            $headers = [
                'Accept' => 'application/json',
                $provider->api_key_header => $provider->api_key ?? '',
            ];

            // Merge headers bổ sung từ DB (nếu có)
            if ($provider->headers && is_array($provider->headers)) {
                foreach ($provider->headers as $key => $value) {
                    $headers[$key] = $value;
                }
            }

            $response = Http::timeout($provider->timeout)
                ->withHeaders($headers)
                ->post(rtrim($provider->base_url, '/') . '/' . ltrim($provider->purchase_endpoint, '/'), [
                    'game_id' => $supplierGameId,
                    'quantity' => $quantity,
                    'customer_email' => $customerEmail,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                return [
                    'success' => true,
                    'keys' => $body['keys'] ?? [],
                    'transaction_id' => $body['transaction_id'] ?? null,
                ];
            }

            $body = $response->json();
            return [
                'success' => false,
                'error' => $body['error'] ?? 'Supplier returned status ' . $response->status(),
                'error_code' => $body['error_code'] ?? 'HTTP_' . $response->status(),
                'http_status' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'EXCEPTION',
            ];
        }
    }

    /**
     * Kiểm tra health của 1 supplier
     */
    public function healthCheck(SupplierProvider $provider): array
    {
        try {
            $response = Http::timeout(5)->get(rtrim($provider->base_url, '/') . '/api/health');
            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->successful() ? $response->json() : null,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Xác thực key với supplier đã cung cấp key đó
     */
    public function verifyKey(string $supplierCode, string $keyCode): array
    {
        $provider = SupplierProvider::where('code', $supplierCode)->first();
        if (!$provider) {
            return ['success' => false, 'error' => 'Supplier not found'];
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    $provider->api_key_header => $provider->api_key ?? '',
                    'Accept' => 'application/json',
                ])
                ->post(rtrim($provider->base_url, '/') . '/' . ltrim($provider->verify_endpoint, '/'), [
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