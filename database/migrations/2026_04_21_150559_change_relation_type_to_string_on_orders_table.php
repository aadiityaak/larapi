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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('relation_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('relation_type', [
                'penjual',
                'pembeli',
                'debitur',
                'penjamin',
                'pemilik',
                'penyewa',
                'direktur',
                'komisaris',
                'pemberi_kuasa',
                'penerima_kuasa',
                'lainnya'
            ])->nullable()->change();
        });
    }
};
