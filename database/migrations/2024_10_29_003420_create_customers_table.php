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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('alamat', 255);
            $table->string('whatsapp', 15);
            $table->string('kategori', 50);
            $table->string('pekerjaan', 50);
            $table->string('bank', 50);
            $table->string('sertifikat', 50);
            $table->decimal('nilai_transaksi', 15, 2);
            $table->decimal('harga_real', 15, 2);
            $table->decimal('harga_kesepakatan', 15, 2);
            $table->string('data_pajak_pembeli', 100);
            $table->string('data_pajak_penjual', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
