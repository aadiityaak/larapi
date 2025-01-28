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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type', 191); // Ubah panjang kolom
            $table->uuid('notifiable_id'); // Pastikan ini sesuai dengan tipe ID yang Anda gunakan
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Tambahkan indeks jika diperlukan
            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_type_notifiable_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
