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
        // Lấy tất cả supplier mapping Active cho game này
        $mappings = GameSupplierMapping::where('game_supplier_mappings.game_id', $gameId)
            ->where('game_supplier_mappings.status', 'Active')
            ->whereHas('supplierProvider', fn($q) => $q->where('status', 'Active'))
            ->with('supplierProvider')
            ->get();

        if ($mappings->isEmpty()) {
            Log::warning('[SupplierManager] No active supplier for game #' . $gameId);
            return [
                'success' => false,
                'error' => 'No supplier configured for this game',
                'error_code' => 'NO_SUPPLIER',
            ];
        }

        // Sắp xếp bằng PHP: supplier có priority CAO hơn (số lớn hơn) được ưu tiên trước
        $mappings = $mappings->sortByDesc(fn($m) => $m->supplierProvider?->priority ?? 0)->values();

        $lastError = null;
        $failedSuppliers = [];
        $fallbackChain = [];

        foreach ($mappings as $index => $mapping) {
            $provider = $mapping->supplierProvider;

            if (!$provider) {
                Log::warning('[SupplierManager] Mapping has no provider, skipping (mapping_id=' . $mapping->id . ')');
                continue;
            }

            Log::info('[SupplierManager] Trying supplier: ' . $provider->name . ' (code=' . $provider->code . ', priority=' . $provider->priority . ') for game #' . $gameId);

            $result = $this->callSupplierApi($provider, $mapping->supplier_game_id ?? $gameId, $quantity, $customerEmail);

            if ($result['success']) {
                Log::info('[SupplierManager] Success with: ' . $provider->name . ' (priority=' . $provider->priority . ')');
                return [
                    'success' => true,
                    'keys' => $result['keys'],
                    'transaction_id' => $result['transaction_id'],
                    'supplier_name' => $provider->name,
                    'supplier_code' => $provider->code,
                    'fallback_chain' => $fallbackChain,
                ];
            }

            // Ghi lỗi chi tiết cho supplier hiện tại
            $lastError = $result['error'];
            $failedInfo = [
                'supplier' => $provider->name,
                'priority' => $provider->priority,
                'error' => $result['error'],
                'error_code' => $result['error_code'] ?? 'N/A',
            ];
            $failedSuppliers[] = $failedInfo;
            $fallbackChain[] = $provider->name . ' (priority=' . $provider->priority . ') - FAILED: ' . $result['error'];

            Log::warning('[SupplierManager] Supplier "' . $provider->name . '" (priority=' . $provider->priority . ') FAILED for game #' . $gameId . ': ' . $result['error'] . ' (error_code: ' . ($result['error_code'] ?? 'N/A') . ')');

            // Xác định supplier tiếp theo để ghi log fallback
            if (isset($mappings[$index + 1])) {
                $nextProvider = $mappings[$index + 1]->supplierProvider;
                if ($nextProvider) {
                    Log::warning('[SupplierManager] Falling back from "' . $provider->name . '" (priority=' . $provider->priority . ') to "' . $nextProvider->name . '" (priority=' . $nextProvider->priority . ') for game #' . $gameId);
                }
            } else {
                Log::error('[SupplierManager] No more suppliers to fallback for game #' . $gameId);
            }
        }

        // Tất cả supplier đều fail
        Log::error('[SupplierManager] ALL ' . count($failedSuppliers) . ' suppliers failed for game #' . $gameId . '. Chain: ' . implode(' -> ', array_column($failedSuppliers, 'supplier')));
        return [
            'success' => false,
            'error' => $lastError ?? 'All suppliers failed',
            'error_code' => 'ALL_SUPPLIERS_FAILED',
            'failed_suppliers' => array_column($failedSuppliers, 'supplier'),
            'fallback_chain' => $fallbackChain,
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