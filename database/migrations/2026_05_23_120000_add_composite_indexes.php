<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Composite indexes สำหรับ query pattern จริงที่ใช้บ่อยในระบบ POS:
     * - products(status, quantity)     → POS active+stocked filter ทุกครั้ง
     * - orders(created_at, status)     → Dashboard/Report filter date+status
     * - pos_carts(user_id, product_id) → Cart lookup เช็คว่ามีในตะกร้าหรือยัง
     */
    public function up(): void
    {
        // products: POS ใช้ WHERE status=1 AND quantity>=1 ทุก request → composite เร็วกว่า index แยก
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'quantity'], 'products_status_quantity_idx');
        });

        // orders: Dashboard/Report filter by created_at range + status ร่วมกันบ่อย
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['created_at', 'status'], 'orders_created_at_status_idx');
        });

        // pos_carts: WHERE user_id=? AND product_id=? AND spice_level=? ใน CartController::store
        Schema::table('pos_carts', function (Blueprint $table) {
            $table->index(['user_id', 'product_id'], 'pos_carts_user_product_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_status_quantity_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_created_at_status_idx');
        });

        Schema::table('pos_carts', function (Blueprint $table) {
            $table->dropIndex('pos_carts_user_product_idx');
        });
    }
};
