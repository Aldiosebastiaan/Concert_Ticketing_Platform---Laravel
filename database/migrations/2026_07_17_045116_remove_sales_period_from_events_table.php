<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai_penjualan', 'tanggal_selesai_penjualan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('tanggal_mulai_penjualan')->nullable();
            $table->dateTime('tanggal_selesai_penjualan')->nullable();
        });
    }
};
