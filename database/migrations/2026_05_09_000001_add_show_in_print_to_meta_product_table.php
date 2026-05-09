<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meta_product', function (Blueprint $table) {
            $table->boolean('show_in_print')->default(false)->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('meta_product', function (Blueprint $table) {
            $table->dropColumn('show_in_print');
        });
    }
};

