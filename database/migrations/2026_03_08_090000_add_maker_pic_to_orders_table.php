<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('maker_id')->nullable()->after('created_by');
            $table->unsignedBigInteger('pic_id')->nullable()->after('maker_id');
            $table->foreign('maker_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('pic_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['maker_id']);
            $table->dropForeign(['pic_id']);
            $table->dropColumn(['maker_id', 'pic_id']);
        });
    }
};
