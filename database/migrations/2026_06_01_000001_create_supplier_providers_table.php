<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Tên nhà cung cấp (VD: SupplierA, SupplierB)
            $table->string('code')->unique();                // Mã code (VD: SUPPLIER_A, G2A, ENEBA)
            $table->string('base_url');                      // Endpoint gốc của API
            $table->string('api_key')->nullable();           // API Key để xác thực
            $table->string('api_key_header')->default('X-API-Key'); // Header chứa API Key
            $table->integer('timeout')->default(15);         // Timeout (giây)
            $table->integer('priority')->default(0);         // Ưu tiên (cao hơn = gọi trước)
            $table->json('headers')->nullable();             // Headers bổ sung
            $table->string('purchase_endpoint')->default('/api/purchase');  // Endpoint mua key
            $table->string('verify_endpoint')->default('/api/verify-key');  // Endpoint xác thực key
            $table->string('status')->default('Active');     // Active / Inactive
            $table->text('notes')->nullable();               // Ghi chú
            $table->timestamps();
        });

        Schema::create('game_supplier_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('game_id');
            $table->foreignId('supplier_provider_id')->constrained('supplier_providers')->onDelete('cascade');
            $table->string('supplier_game_id')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();

            $table->unique(['game_id', 'supplier_provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_supplier_mappings');
        Schema::dropIfExists('supplier_providers');
    }
};