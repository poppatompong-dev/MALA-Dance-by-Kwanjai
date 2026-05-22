<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('sku');
            $table->index('name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('created_at');
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('pos_carts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('product_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->index('user_id');
            $table->index('date');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->index('purchase_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['sku']);
            $table->dropIndex(['name']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('pos_carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['date']);
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropIndex(['purchase_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
