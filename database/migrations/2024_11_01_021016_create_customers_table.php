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
            $table->string('phone', 20);
            $table->string('alamat', 255)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->text('pekerjaan')->nullable();
            $table->string('bank', 100)->nullable();
            $table->string('sertifikat', 50)->nullable();
            $table->integer('nilai_transaksi')->nullable();
            $table->integer('harga_real')->nullable();
            $table->integer('harga_kesepakatan')->nullable();
            $table->integer('data_pajak_pembeli')->nullable();
            $table->integer('data_pajak_penjual')->nullable();
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
