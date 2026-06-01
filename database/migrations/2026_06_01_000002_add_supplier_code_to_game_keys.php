<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            if (!Schema::hasColumn('game_keys', 'supplier_code')) {
                $table->string('supplier_code')->nullable()->after('supplier_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            $table->dropColumn('supplier_code');
        });
    }
};