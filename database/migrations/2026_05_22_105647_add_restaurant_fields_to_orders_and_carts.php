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
            $table->string('order_type')->default('dine_in')->comment('dine_in, takeaway, delivery');
            $table->text('notes')->nullable();
        });

        Schema::table('pos_carts', function (Blueprint $table) {
            $table->string('spice_level')->nullable();
            $table->string('toppings')->nullable();
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->string('spice_level')->nullable();
            $table->string('toppings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'notes']);
        });

        Schema::table('pos_carts', function (Blueprint $table) {
            $table->dropColumn(['spice_level', 'toppings']);
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['spice_level', 'toppings']);
        });
    }
};
