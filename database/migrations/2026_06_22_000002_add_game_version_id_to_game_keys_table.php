<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('game_version_id')->nullable()->after('order_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('game_keys', function (Blueprint $table) {
            $table->dropColumn('game_version_id');
        });
    }
};