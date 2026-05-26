<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color', 20)->default('#6c757d');
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_channel_id')->nullable()->after('user_id');
            $table->decimal('platform_fee', 10, 2)->default(0)->after('due');
            $table->string('platform_order_ref')->nullable()->after('platform_fee');

            $table->foreign('sales_channel_id')->references('id')->on('sales_channels')->nullOnDelete();
            $table->index('sales_channel_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['sales_channel_id']);
            $table->dropIndex(['sales_channel_id']);
            $table->dropColumn(['sales_channel_id', 'platform_fee', 'platform_order_ref']);
        });
        Schema::dropIfExists('sales_channels');
    }
};
