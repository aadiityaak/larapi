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
            $table->unsignedBigInteger('related_order_id')->nullable()->after('customer_id');
            $table->enum('relation_type', ['penjual', 'pembeli', 'lainnya'])->nullable()->after('related_order_id');

            $table->foreign('related_order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['related_order_id']);
            $table->dropColumn(['related_order_id', 'relation_type']);
        });
    }
};
