<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // orders: status ใช้ filter paid/unpaid บ่อยมาก, deleted_at ต้องตรวจทุก query (soft delete)
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('deleted_at');
        });

        // order_transactions: ใช้ใน Dashboard chart, daily/monthly report ทุกครั้ง
        Schema::table('order_transactions', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('order_id');
            $table->index('customer_id');
        });

        // reward_usages: ใช้ค้นหาใน void order + POS invoice
        Schema::table('reward_usages', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('customer_id');
        });

        // audit_logs: ใช้ filter by user_id และ sort by created_at
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        // customers: ใช้ sort last_visit_at ใน reward/loyalty
        Schema::table('customers', function (Blueprint $table) {
            $table->index('last_visit_at');
        });

        // stock_movements: ใช้ดู history ของ product, sort by created_at
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('created_at');
        });

        // products: status ใช้ filter active products เสมอ
        Schema::table('products', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('order_transactions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['customer_id']);
        });

        Schema::table('reward_usages', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['customer_id']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['last_visit_at']);
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
